<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\ServiceStatus;
use App\Models\SparePart; // Tambahkan ini
use App\Models\TransactionSparePart; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Tampilkan daftar pekerjaan SAYA SAJA (Active Jobs).
     * Digunakan jika kamu mengakses route /mechanic/jobs
     */
    public function index()
    {
        $jobs = Transaction::with([
                'customer' => function($q) { $q->withTrashed(); }, 
                'vehicle' => function($q) { $q->withTrashed(); },
                'serviceStatus'
            ])
            ->where('mechanic_id', Auth::id())
            ->where('payment_status_id', '!=', 1) 
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pakai paginate biar halaman tidak panjang ke bawah

        // PERBAIKAN: Arahkan ke view 'mechanic.jobs.index', BUKAN 'mechanic.dashboard'
        return view('mechanic.jobs.index', compact('jobs'));
    }

    /**
     * Tampilkan detail satu pekerjaan (Saat klik tombol "Kerjakan").
     */
   public function show($id)
    {
        $transaction = Transaction::with([
                'services', 
                'spareParts', 
                'customer' => function($q) { $q->withTrashed(); }, 
                'vehicle' => function($q) { $q->withTrashed(); },
                'serviceStatus'
            ])
            ->where('id', $id)
            ->where('mechanic_id', Auth::id())
            ->firstOrFail();

        // --- [LOGIC BARU: BATASI STATUS MEKANIK] ---
        // Mekanik cuma butuh status: 
        // 3 (Sedang Dikerjakan) dan 4 (Selesai)
        // Kita juga sertakan status saat ini agar tidak error di dropdown
        
        $allowedStatusIds = [3, 4]; 
        // Tambahkan status saat ini ke list (misal saat ini statusnya 'Menunggu' (2), dia harus tetap muncul)
        if (!in_array($transaction->service_status_id, $allowedStatusIds)) {
            $allowedStatusIds[] = $transaction->service_status_id;
        }

        $statuses = ServiceStatus::whereIn('id', $allowedStatusIds)->get();
        
        $availableSpareParts = SparePart::where('stock', '>', 0)->where('is_active', true)->get();

        return view('mechanic.jobs.show', compact('transaction', 'statuses', 'availableSpareParts'));
    }

    /**
     * Update status pengerjaan (Misal: Menunggu -> Sedang Dikerjakan -> Selesai).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'service_status_id' => 'required|exists:service_statuses,id',
        ]);

        $transaction = Transaction::where('id', $id)
            ->where('mechanic_id', Auth::id())
            ->firstOrFail();

        $transaction->update([
            'service_status_id' => $request->service_status_id
        ]);

        return back()->with('success', 'Status pekerjaan berhasil diperbarui!');
    }

    /**
     * Halaman Riwayat (Pekerjaan yang sudah selesai/lunas).
     */
    public function history()
    {
        // PERBAIKAN: Nama variabel disesuaikan dengan View ($historyJobs)
        $historyJobs = Transaction::with([
                'customer' => function($q) { $q->withTrashed(); }, 
                'vehicle' => function($q) { $q->withTrashed(); },
                'serviceStatus'
            ])
            ->where('mechanic_id', Auth::id())
            // Asumsi: Job masuk history kalau status servis 'Selesai' (ID 4) atau Bayar Lunas (ID 1)
            // Sesuaikan logika ini dengan maumu. Biasanya patokannya Status Service = Selesai.
            ->where('service_status_id', 4) 
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pakai paginate biar halaman history rapi

        // Kirim dengan nama 'historyJobs' agar cocok dengan blade
        return view('mechanic.jobs.history', compact('historyJobs'));
    }

   /**
     * FITUR BARU: Tambah Sparepart ke Transaksi (Safe Stock Version)
     */
    public function storeSparePart(Request $request, $transactionId)
    {
        $request->validate([
            'spare_part_id' => 'required|exists:spare_parts,id',
            'qty' => 'required|integer|min:1',
        ]);
        
        $transaction = Transaction::where('id', $transactionId)
            ->where('mechanic_id', Auth::id())
            ->firstOrFail();

        if ($transaction->payment_status_id == 1) {
            return back()->with('error', 'Transaksi sudah LUNAS! Tidak bisa menambah item.');
        }

        DB::beginTransaction();
        try {
            // LOCK FOR UPDATE: Kunci baris stok agar tidak ada yang bisa ambil bersamaan
            $part = SparePart::where('id', $request->spare_part_id)->lockForUpdate()->firstOrFail();

            if($part->stock < $request->qty) {
                return back()->with('error', 'Stok tidak cukup! Sisa: ' . $part->stock);
            }

            TransactionSparePart::create([
                'transaction_id' => $transactionId,
                'spare_part_id'  => $part->id,
                'qty'            => $request->qty,
                'price_at_time'  => $part->sell_price ?? $part->price, 
            ]);

            $part->decrement('stock', $request->qty);
            
            $this->updateTransactionTotal($transactionId);

            DB::commit();
            return back()->with('success', 'Sparepart berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroySparePart($transactionId, $sparePartId)
    {
        $transaction = Transaction::where('id', $transactionId)
            ->where('mechanic_id', Auth::id())
            ->firstOrFail();
            
        if ($transaction->payment_status_id == 1) {
            return back()->with('error', 'Transaksi sudah LUNAS! Tidak bisa menghapus item.');
        }

        $item = TransactionSparePart::where('id', $sparePartId)
                ->where('transaction_id', $transactionId)
                ->firstOrFail();

        DB::beginTransaction();
        try {
            $part = SparePart::where('id', $item->spare_part_id)->lockForUpdate()->first();
            if($part) {
                $part->increment('stock', $item->qty);
            }
    
            $item->delete();
            $this->updateTransactionTotal($transactionId);
    
            DB::commit();
            return back()->with('success', 'Sparepart dibatalkan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
    
    private function updateTransactionTotal($transactionId)
    {
        $totalService = DB::table('transaction_services')
            ->where('transaction_id', $transactionId)
            ->select(DB::raw('SUM(price_at_time * qty) as total'))
            ->value('total');

        $totalParts = DB::table('transaction_spare_parts')
            ->where('transaction_id', $transactionId)
            ->select(DB::raw('SUM(price_at_time * qty) as total'))
            ->value('total');

        DB::table('transactions')
            ->where('id', $transactionId)
            ->update(['total_amount' => ($totalService + $totalParts)]);
    }
}
