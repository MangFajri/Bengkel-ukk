<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\PaymentStatus;
use App\Models\ServiceStatus;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil data transaksi dengan relasinya untuk efisiensi query
        $transactions = Transaction::with(['customer', 'vehicle', 'mechanic', 'serviceStatus', 'paymentStatus'])
                                    ->latest() // Urutkan dari yang terbaru
                                    ->paginate(10); // Paginasi
        
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil data untuk mengisi dropdown di form
        $customers = User::where('role', 'customer')->get();
        $vehicles = Vehicle::all(); // Nanti akan kita buat dinamis
        $mechanics = User::where('role', 'mechanic')->get();

        return view('admin.transactions.create', compact('customers', 'vehicles', 'mechanics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validatedData = $request->validated();

        // Ambil ID status default
        $waitingStatusId = ServiceStatus::where('code', 'waiting')->first()->id;
        $unpaidStatusId = PaymentStatus::where('code', 'unpaid')->first()->id;

        // Tambahkan data yang tidak ada di form
        $validatedData['created_by'] = Auth::id(); // Admin yang membuat
        $validatedData['service_status_id'] = $waitingStatusId;
        $validatedData['payment_status_id'] = $unpaidStatusId;

        // Buat transaksi baru
        $transaction = Transaction::create($validatedData);

        // Redirect ke halaman detail/edit untuk menambahkan jasa & sparepart
        // Untuk sekarang kita redirect ke index dulu
        return redirect()->route('admin.transactions.index')->with('success', 'Transaksi baru berhasil dibuat. Silakan tambahkan detail servis.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        // Mengambil semua data yang dibutuhkan untuk halaman edit
        $transaction->load(['customer', 'vehicle', 'mechanic', 'transactionServices.service', 'transactionSpareParts.sparePart']);
        
        $services = Service::where('is_active', true)->get();
        $spareParts = SparePart::where('is_active', true)->get();

        return view('admin.transactions.edit', compact('transaction', 'services', 'spareParts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
