<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\Transaction;
use App\Models\TransactionService;
use App\Models\TransactionSparePart;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\ServiceStatus;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTransactionController extends Controller
{
    /**
     * Menampilkan semua transaksi.
     */
    public function index()
    {
        $transactions = Transaction::with(['customer', 'vehicle', 'mechanic', 'serviceStatus', 'paymentStatus'])
            ->latest()
            ->paginate(10);
        
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Form Input Transaksi Baru (Hybrid).
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $mechanics = User::where('role', 'mechanic')->where('is_active', true)->get();
        $vehicles = Vehicle::with('user')->get(); 
        $services = Service::where('is_active', true)->get();

        return view('admin.transactions.create', compact('customers', 'mechanics', 'vehicles', 'services'));
    }

    /**
     * Logic Penyimpanan Data.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type'          => 'required|in:existing,walkin',
            'service_ids'   => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'mechanic_id'   => 'nullable|exists:users,id',
            'notes'         => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $customerId = null;
            $vehicleId = null;

            if ($request->type == 'existing') {
                $request->validate([
                    'customer_id' => 'required|exists:users,id',
                    'vehicle_id'  => 'required|exists:vehicles,id',
                ]);
                $customerId = $request->customer_id;
                $vehicleId  = $request->vehicle_id;
            } else {
                $request->validate([
                    'new_name'         => 'required|string|max:255',
                    'new_phone'        => 'required|string|max:20',
                    'new_plate_number' => 'required|string|unique:vehicles,plate_number',
                    'new_brand'        => 'required|string',
                    'new_model'        => 'required|string',
                ]);

                $newUser = User::create([
                    'name'      => $request->new_name,
                    'email'     => 'walkin_' . time() . '@bengkel.com',
                    'phone'     => $request->new_phone,
                    'password'  => Hash::make('password'), 
                    'role'      => 'customer',
                    'is_active' => 1
                ]);

                $customerId = $newUser->id;

                $newVehicle = Vehicle::create([
                    'user_id'      => $customerId,
                    'plate_number' => strtoupper($request->new_plate_number),
                    'brand'        => $request->new_brand,
                    'model'        => $request->new_model,
                    'year'         => date('Y'),
                ]);

                $vehicleId = $newVehicle->id;
            }

            $selectedServices = Service::whereIn('id', $request->service_ids)->get();
            $initialTotal     = $selectedServices->sum('price');

            $transaction = Transaction::create([
                'customer_id'       => $customerId,
                'vehicle_id'        => $vehicleId,
                'mechanic_id'       => $request->mechanic_id,
                'created_by'        => Auth::id(),
                'service_status_id' => 2,
                'payment_status_id' => 3,
                'total_amount'      => $initialTotal,
                'check_in_at'       => now(),
                'notes'             => $request->notes ?? 'Walk-in Service Input by Admin',
            ]);

            foreach ($selectedServices as $service) {
                TransactionService::create([
                    'transaction_id' => $transaction->id,
                    'service_id'     => $service->id,
                    'price_at_time'  => $service->price,
                    'qty'            => 1
                ]);
            }

            DB::commit();
            return redirect()->route('admin.transactions.index')
                ->with('success', 'Service berhasil dibuat! No. Antrian #' . $transaction->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Halaman Edit / Kelola Transaksi.
     */
    public function edit(Transaction $transaction)
    {
        $transaction->load(['customer', 'vehicle', 'mechanic', 'services.service', 'spareParts.sparePart']);
        
        $services        = Service::where('is_active', true)->get();
        $spareParts      = SparePart::where('is_active', true)->get();
        $mechanics       = User::where('role', 'mechanic')->where('is_active', true)->get();
        $paymentMethods  = PaymentMethod::all();
        $paymentStatuses = PaymentStatus::all();
        $serviceStatuses = ServiceStatus::all();

        return view('admin.transactions.edit', compact(
            'transaction', 'services', 'spareParts', 'mechanics', 
            'paymentMethods', 'paymentStatuses', 'serviceStatuses'
        ));
    }

    /**
     * Update Status Service.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'mechanic_id'       => 'nullable|exists:users,id',
            'service_status_id' => 'required|exists:service_statuses,id',
            'payment_status_id' => 'required|exists:payment_statuses,id',
        ]);

        $transaction->update([
            'mechanic_id'       => $request->mechanic_id,
            'service_status_id' => $request->service_status_id,
            'payment_status_id' => $request->payment_status_id,
        ]);

        return redirect()->route('admin.transactions.index')
            ->with('success', 'Status transaksi #' . $transaction->id . ' diperbarui.');
    }

    /**
     * Update Pembayaran.
     */
    public function updatePaymentStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'amount_paid'       => 'required|numeric|min:0',
        ]);

        $transaction->update([
            'payment_method_id' => $request->payment_method_id,
            'amount_paid'       => $request->amount_paid,
            'payment_status_id' => 1, 
        ]);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
            return back()->with('success', 'Transaksi dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        return redirect()->route('admin.transactions.edit', $transaction->id);
    }

    /**
     * Cetak Struk Transaksi.
     */
    public function print(Transaction $transaction)
    {
        // Load semua data relasi biar lengkap di struk
        $transaction->load(['customer', 'vehicle', 'mechanic', 'services.service', 'spareParts.sparePart', 'paymentMethod']);

        return view('admin.transactions.print', compact('transaction'));
    }
}