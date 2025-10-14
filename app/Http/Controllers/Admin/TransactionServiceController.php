<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionService;
use Illuminate\Http\Request;

class TransactionServiceController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        $request->validate(['service_id' => 'required|exists:services,id']);
        $service = Service::find($request->service_id);

        TransactionService::create([
            'transaction_id' => $transaction->id,
            'service_id' => $service->id,
            'price_at_time' => $service->price,
        ]);

        // Panggil method baru dari objek $transaction
        $transaction->recalculateTotalAmount();

        return redirect()->route('admin.transactions.edit', $transaction->id)
                         ->with('success', 'Jasa berhasil ditambahkan ke transaksi.');
    }

    public function destroy(TransactionService $transactionService)
    {
        $transaction = $transactionService->transaction; // Ambil objek transaksi sebelum dihapus
        $transactionService->delete();

        // Panggil method baru dari objek $transaction
        $transaction->recalculateTotalAmount();

        return redirect()->route('admin.transactions.edit', $transaction->id)
                         ->with('success', 'Item jasa berhasil dihapus dari transaksi.');
    }
    
    // HAPUS METHOD PRIVATE recalculateTransactionTotal() DARI SINI
}