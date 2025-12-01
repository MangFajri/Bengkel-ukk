<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\SparePart; // Pastikan import Model
use App\Models\TransactionSparePart; // Pastikan import Model Pivot
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionSparePartController extends Controller
{
    /**
     * Simpan Sparepart ke Transaksi
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'spare_part_id' => 'required|exists:spare_parts,id',
            'qty' => 'required|integer|min:1',
        ]);
        
        $transaction = Transaction::findOrFail($request->transaction_id);

        // CEGAH PERUBAHAN JIKA SUDAH LUNAS
        if ($transaction->payment_status_id == 1) { 
            return back()->with('error', 'Transaksi sudah LUNAS! Tidak bisa menambah item.');
        }

        DB::beginTransaction();

        try {
            // 1. Ambil data Sparepart dengan LOCK FOR UPDATE
            // Ini mencegah dua user mengedit stok barang yang sama di waktu bersamaan
            $sparePart = SparePart::where('id', $request->spare_part_id)->lockForUpdate()->first();

            if (! $sparePart) {
                return back()->with('error', 'Sparepart tidak ditemukan.');
            }

            // 2. Cek Stok
            if ($sparePart->stock < $request->qty) {
                return back()->with('error', "Stok tidak cukup! Sisa: {$sparePart->stock}");
            }

            // --- DETEKSI HARGA JUAL (SELL PRICE) ---
            $priceToCharge = $sparePart->sell_price ?? $sparePart->price ?? 0;
            // ---------------------------------------

            // 3. Insert ke Pivot Table
            TransactionSparePart::create([
                'transaction_id' => $request->transaction_id,
                'spare_part_id' => $sparePart->id,
                'qty' => $request->qty,
                'price_at_time' => $priceToCharge,
            ]);

            // 4. Kurangi Stok Master
            $sparePart->decrement('stock', $request->qty);

            // 5. Update Total Harga di Transaksi Induk
            $this->updateTransactionTotal($request->transaction_id);

            DB::commit();
            return back()->with('success', 'Sparepart berhasil ditambahkan: '.$sparePart->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // Cari item pivot
            $item = TransactionSparePart::where('id', $id)->first();

            if (! $item) {
                return back()->with('error', 'Data tidak ditemukan.');
            }

            // === [FIX KEAMANAN PENTING] ===
            $transaction = Transaction::where('id', $item->transaction_id)->first();
            
            if ($transaction && $transaction->payment_status_id == 1) {
                return back()->with('error', 'Transaksi sudah LUNAS! Item tidak bisa dihapus.');
            }
            // ==============================

            $transactionId = $item->transaction_id;

            // Kembalikan Stok (Gunakan lock juga biar aman walau jarang konflik di sini)
            $part = SparePart::where('id', $item->spare_part_id)->lockForUpdate()->first();
            if ($part) {
                $part->increment('stock', $item->qty);
            }

            // Hapus Item
            $item->delete();

            // Recalculate Total
            $this->updateTransactionTotal($transactionId);

            DB::commit();
            return back()->with('success', 'Sparepart dihapus & Stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: '.$e->getMessage());
        }
    }

    private function updateTransactionTotal($transactionId)
    {
        // Logic hitung total service
        $totalService = DB::table('transaction_services')
            ->where('transaction_id', $transactionId)
            ->select(DB::raw('SUM(price_at_time * qty) as total'))
            ->value('total');

        // Logic hitung total sparepart
        $totalParts = DB::table('transaction_spare_parts')
            ->where('transaction_id', $transactionId)
            ->select(DB::raw('SUM(price_at_time * qty) as total'))
            ->value('total');

        // Update transaksi
        DB::table('transactions')
            ->where('id', $transactionId)
            ->update(['total_amount' => ($totalService + $totalParts)]);
    }
}