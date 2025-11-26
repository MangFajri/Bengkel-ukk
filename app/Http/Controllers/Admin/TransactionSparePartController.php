<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionSparePartController extends Controller
{
    /**
     * Simpan Sparepart ke Transaksi (Support FIFO/VIVO dengan pemisahan harga).
     */
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'spare_part_id'  => 'required|exists:spare_parts,id',
            'qty'            => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // 1. Ambil data Sparepart Menggunakan Query Builder
            $sparePart = DB::table('spare_parts')->where('id', $request->spare_part_id)->first();

            if (!$sparePart) {
                return back()->with('error', 'Sparepart tidak ditemukan.');
            }

            // 2. Cek Stok
            if ($sparePart->stock < $request->qty) {
                return back()->with('error', "Stok tidak cukup! Sisa: {$sparePart->stock}");
            }

            // --- DETEKSI HARGA JUAL (SELL PRICE) ---
            // Kita prioritaskan 'sell_price' atau 'harga_jual' karena ini transaksi ke customer.
            $priceToCharge = 0;

            if (isset($sparePart->sell_price)) {
                $priceToCharge = $sparePart->sell_price;
            } elseif (isset($sparePart->harga_jual)) {
                $priceToCharge = $sparePart->harga_jual;
            } elseif (isset($sparePart->price)) {
                $priceToCharge = $sparePart->price; // Fallback ke kolom lama jika ada
            } elseif (isset($sparePart->harga)) {
                $priceToCharge = $sparePart->harga;
            } else {
                // Jika masih tidak ketemu, kita stop dan kasih info (Debugging)
                DB::rollBack();
                dd("ERROR: Kolom harga tidak ditemukan. Pastikan ada kolom 'sell_price' atau 'harga_jual' di tabel spare_parts.", $sparePart);
            }
            // ---------------------------------------

            // 3. Insert ke Pivot Table
            // Kita simpan harga saat ini (price_at_time) agar jika harga master berubah, riwayat transaksi tetap aman.
            DB::table('transaction_spare_parts')->insert([
                'transaction_id' => $request->transaction_id,
                'spare_part_id'  => $sparePart->id,
                'qty'            => $request->qty,
                'price_at_time'  => $priceToCharge, 
            ]);

            // 4. Kurangi Stok Master (Metode FIFO/VIVO stok fisik berkurang di sini)
            DB::table('spare_parts')
                ->where('id', $sparePart->id)
                ->decrement('stock', $request->qty);

            // 5. Update Total Harga di Transaksi Induk
            $this->updateTransactionTotal($request->transaction_id);

            DB::commit();
            return back()->with('success', 'Sparepart berhasil ditambahkan: ' . $sparePart->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $item = DB::table('transaction_spare_parts')->where('id', $id)->first();

            if (!$item) {
                return back()->with('error', 'Data tidak ditemukan.');
            }

            $transactionId = $item->transaction_id;
            
            // Kembalikan Stok
            DB::table('spare_parts')
                ->where('id', $item->spare_part_id)
                ->increment('stock', $item->qty);

            // Hapus Item
            DB::table('transaction_spare_parts')->where('id', $id)->delete();

            // Recalculate Total
            $this->updateTransactionTotal($transactionId);

            DB::commit();
            return back()->with('success', 'Sparepart dihapus & Stok dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
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