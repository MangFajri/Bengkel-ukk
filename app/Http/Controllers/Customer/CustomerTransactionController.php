<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerTransactionController extends Controller
{
    public function index()
    {
        $customerId = Auth::id();

        $transactions = Transaction::with(['vehicle', 'serviceStatus'])
                                    ->where('customer_id', $customerId) 
                                    ->latest()
                                    ->paginate(10);

        return view('customer.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan detail dari sebuah riwayat transaksi.
     */
    public function show(Transaction $transaction)
    {
        // LAPISAN KEAMANAN: Pastikan transaksi ini milik customer yang sedang login.
        if ($transaction->customer_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK.'); // Jika bukan, tolak akses.
        }

        // Muat semua relasi yang dibutuhkan untuk ditampilkan
        $transaction->load(['vehicle', 'mechanic', 'serviceStatus', 'paymentStatus', 'transactionServices.service', 'transactionSpareParts.sparePart']);

        return view('customer.transactions.show', compact('transaction'));
    }
}