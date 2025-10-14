<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SparePart;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionSparePart;
use Illuminate\Http\Request;

class TransactionSparePartController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $request->validate([
            'spare_part_id' => 'required|exists:spare_parts,id',
            'qty' => 'required|integer|min:1',
        ]);

        $sparePart = SparePart::find($request->spare_part_id);
        $requestedQty = $request->qty;

        if ($sparePart->stock < $requestedQty) {
            return redirect()->back()->withErrors(['qty' => 'Stok tidak mencukupi. Sisa stok: ' . $sparePart->stock]);
        }

        TransactionSparePart::create([
            'transaction_id' => $transaction->id,
            'spare_part_id' => $sparePart->id,
            'qty' => $requestedQty,
            'price_at_time' => $sparePart->sell_price,
        ]);

        $sparePart->decrement('stock', $requestedQty);

        StockMovement::create([
            'spare_part_id' => $sparePart->id,
            'transaction_id' => $transaction->id,
            'change_qty' => -$requestedQty,
            'note' => 'Penjualan untuk Transaksi #'.$transaction->id,
        ]);

        // Ganti panggilan lama dengan memanggil method dari objek $transaction
        $transaction->recalculateTotalAmount();

        return redirect()->route('admin.transactions.edit', $transaction->id)
                         ->with('success', 'Sparepart berhasil ditambahkan ke transaksi.');
    }

    public function destroy(TransactionSparePart $transactionSparePart)
    {
        $transaction = $transactionSparePart->transaction; // Ambil objek transaksi
        $sparePart = $transactionSparePart->sparePart; // Ambil objek sparepart
        $restoredQty = $transactionSparePart->qty;

        $sparePart->increment('stock', $restoredQty);

        StockMovement::create([
            'spare_part_id' => $sparePart->id,
            'transaction_id' => $transaction->id,
            'change_qty' => +$restoredQty,
            'note' => 'Pembatalan/Hapus item dari Transaksi #'.$transaction->id,
        ]);

        $transactionSparePart->delete();
        
        // Ganti panggilan lama dengan memanggil method dari objek $transaction
        $transaction->recalculateTotalAmount();

        return redirect()->route('admin.transactions.edit', $transaction->id)
                         ->with('success', 'Item sparepart berhasil dihapus dan stok telah dikembalikan.');
    }
}