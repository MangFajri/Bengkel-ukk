<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SparePart;
use App\Models\ActivityLog;
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
use Illuminate\Validation\Rule;

class AdminTransactionController extends Controller
{
    /**
     * Tampilkan daftar transaksi.
     */
    
    public function index()
    {
        $transactions = Transaction::with(['customer', 'vehicle', 'mechanic', 'serviceStatus', 'paymentStatus'])
            ->latest()
            ->paginate(10);
        
        return view('admin.transactions.index', compact('transactions'));
    }
    /**
     * Tampilkan form buat transaksi baru.
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $mechanics = User::where('role', 'mechanic')->where('is_active', true)->get();
        $vehicles = Vehicle::with('user')->get(); 
        $services = Service::where('is_active', true)->get();
        $spareParts = SparePart::where('is_active', true)->where('stock', '>', 0)->get();

        return view('admin.transactions.create', compact('customers', 'mechanics', 'vehicles', 'services', 'spareParts'));
    }

    /**
     * Simpan transaksi baru.
     */
    public function store(Request $request)
    {
        // Copy paste method store kamu yang tadi, itu sudah valid.
        // Saya persingkat di sini biar chat tidak kepanjangan.
        $request->validate([
            'type'          => 'required|in:existing,walkin',
            'mechanic_id'   => 'nullable|exists:users,id',
            'date'          => 'required|date',
            'notes'         => 'nullable|string',
            'service_ids'   => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'spare_parts'   => 'nullable|array',
            'spare_parts.*.id' => 'exists:spare_parts,id',
            'spare_parts.*.qty' => 'numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $customerId = null;
            $vehicleId = null;
            $totalPrice = 0;

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
                    'new_brand'        => 'required|string',
                    'new_model'        => 'required|string',
                    'new_plate_number' => [
                        'required', 'string',
                        Rule::unique('vehicles', 'plate_number')->whereNull('deleted_at')
                    ],
                ]);

                $trashedVehicle = Vehicle::onlyTrashed()->where('plate_number', $request->new_plate_number)->first();
                if ($trashedVehicle) $trashedVehicle->forceDelete();

                $newUser = User::create([
                    'name' => $request->new_name,
                    'email' => 'walkin_' . uniqid() . '@bengkel.com',
                    'phone' => $request->new_phone,
                    'password' => Hash::make('password'),
                    'role' => 'customer',
                    'is_active' => 1
                ]);
                $customerId = $newUser->id;

                $newVehicle = Vehicle::create([
                    'user_id' => $customerId,
                    'plate_number' => strtoupper($request->new_plate_number),
                    'brand' => $request->new_brand,
                    'model' => $request->new_model,
                    'year' => date('Y'),
                    'color' => 'Unknown',
                ]);
                $vehicleId = $newVehicle->id;
            }

            if (!empty($request->service_ids)) {
                $selectedServices = Service::whereIn('id', $request->service_ids)->get();
                $totalPrice += $selectedServices->sum('price');
            }

            if (!empty($request->spare_parts)) {
                foreach ($request->spare_parts as $item) {
                    $part = SparePart::find($item['id']);
                    if ($part->stock < $item['qty']) {
                        throw new \Exception("Stok barang {$part->name} tidak cukup! Sisa: {$part->stock}");
                    }
                    $totalPrice += $part->sell_price * $item['qty'];
                }
            }

            $transaction = Transaction::create([
                'customer_id' => $customerId,
                'vehicle_id' => $vehicleId,
                'mechanic_id' => $request->mechanic_id,
                'created_by' => Auth::id(),
                'date' => $request->date,
                'service_status_id' => 1,
                'payment_status_id' => 3,
                'total_amount' => $totalPrice,
                'check_in_at' => now(),
                'notes' => $request->notes,
            ]);

            if (!empty($request->service_ids)) {
                foreach ($selectedServices as $service) {
                    TransactionService::create([
                        'transaction_id' => $transaction->id,
                        'service_id' => $service->id,
                        'price_at_time' => $service->price,
                        'qty' => 1
                    ]);
                }
            }

            if (!empty($request->spare_parts)) {
                foreach ($request->spare_parts as $item) {
                    $part = SparePart::find($item['id']);
                    TransactionSparePart::create([
                        'transaction_id' => $transaction->id,
                        'spare_part_id' => $part->id,
                        'qty' => $item['qty'],
                        'price_at_time' => $part->sell_price,
                    ]);
                    $part->decrement('stock', $item['qty']);
                }
            }

            DB::commit();
            return redirect()->route('admin.transactions.index')->with('success', 'Transaksi berhasil dibuat!');
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
        $transaction->load([
            'customer' => function($q) { $q->withTrashed(); }, 
            'vehicle' => function($q) { $q->withTrashed(); }, 
            'mechanic' => function($q) { $q->withTrashed(); },
            // FIX: Tidak perlu .service, langsung services saja
            'services' => function($q) { $q->withTrashed(); }, 
            
            // FIX TYPO FATAL DISINI:
            // Kamu tulis 'sparePart' (singular), padahal nama function di Model 'spareParts' (plural)
            'spareParts' => function($q) { $q->withTrashed(); } 
        ]);
        
        $services        = Service::where('is_active', true)->get();
        $spareParts      = SparePart::where('is_active', true)->where('stock', '>', 0)->get();
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
     * Update status transaksi.
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
     * Update status pembayaran menjadi LUNAS.
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
        if ($transaction->payment_status_id == 1) {
            return back()->with('error', 'Transaksi LUNAS tidak bisa langsung dihapus demi keamanan data. Silakan ubah status menjadi "Belum Lunas" terlebih dahulu jika ingin menghapus.');
        }

        DB::beginTransaction();
        try {
            $transaction->load(['spareParts', 'services']);
            
            // Backup data untuk log sebelum dihapus
            $logData = [
                'trx_id' => $transaction->id,
                'customer' => $transaction->customer->name ?? 'Unknown',
                'total' => $transaction->total_amount,
                'items_count' => $transaction->spareParts->count() + $transaction->services->count()
            ];

            // 1. Kembalikan Stok Sparepart
            foreach ($transaction->spareParts as $detail) {
                $part = SparePart::withTrashed()->find($detail->spare_part_id);
                if ($part) {
                    $part->increment('stock', $detail->qty);
                }
            }
            
            // 2. Hapus Pivot
            $transaction->spareParts()->detach();
            $transaction->services()->detach();

            // 3. Hapus Transaksi
            $transaction->delete();

            // [CCTV] Catat Log Penghapusan
            ActivityLog::record(
                "Menghapus Transaksi #{$transaction->id} senilai Rp " . number_format($transaction->total_amount),
                $logData
            );

            DB::commit();
            return back()->with('success', 'Transaksi dihapus dan stok dikembalikan.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
    /**
     * Tampilkan detail transaksi.
     */
    public function show(Transaction $transaction)
    {
        return redirect()->route('admin.transactions.edit', $transaction->id);
    }

    public function print(Transaction $transaction){
        $transaction->load([
            'customer', 
            'vehicle', 
            'mechanic', 
            'paymentMethod',
            'services' => function($q) { 
                $q->withTrashed(); 
            },
            'spareParts' => function($q) { 
                $q->withTrashed(); 
            }
        ]);

        return view('admin.transactions.print', compact('transaction'));
    }
}