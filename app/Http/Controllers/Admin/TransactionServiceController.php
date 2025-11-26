<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionService;
use Illuminate\Http\Request;

class TransactionServiceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'service_id'     => 'required|exists:services,id',
        ]);

        $service = Service::findOrFail($request->service_id);

        TransactionService::create([
            'transaction_id' => $request->transaction_id,
            'service_id'     => $service->id,
            'qty'            => 1,
            'price_at_time'  => $service->price
        ]);

        // Update Total
        $this->updateTransactionTotal($request->transaction_id);

        return back()->with('success', 'Layanan jasa berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $item = TransactionService::findOrFail($id);
        $transactionId = $item->transaction_id;
        
        $item->delete();

        // Update Total
        $this->updateTransactionTotal($transactionId);

        return back()->with('success', 'Layanan jasa dihapus.');
    }

    // Helper Private (Copy paste dari Sparepart controller biar aman)
    private function updateTransactionTotal($transactionId)
    {
        $transaction = Transaction::with(['services', 'spareParts'])->find($transactionId);
        
        $totalService = $transaction->services->sum(function($s) {
            return $s->price_at_time * $s->qty;
        });

        $totalParts = $transaction->spareParts->sum(function($p) {
            return $p->price_at_time * $p->qty;
        });

        $transaction->update(['total_amount' => $totalService + $totalParts]);
    }
}