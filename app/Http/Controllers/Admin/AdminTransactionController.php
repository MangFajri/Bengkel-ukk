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
use Illuminate\Validation\Rule;

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
        // Ambil vehicle yang usernya aktif (optional optimization)
        $vehicles = Vehicle::with('user')->get(); 
        $services = Service::where('is_active', true)->get();
        // Tambahkan data spareparts agar bisa dipilih di form
        $spareParts = SparePart::where('is_active', true)->where('stock', '>', 0)->get();

        return view('admin.transactions.create', compact('customers', 'mechanics', 'vehicles', 'services', 'spareParts'));
    }

    /**
     * Logic Penyimpanan Data.
     */
    public function store(Request $request)
    {
        // Validasi input awal
        $request->validate([
            'type'          => 'required|in:existing,walkin',
            'mechanic_id'   => 'nullable|exists:users,id',
            'date'          => 'required|date', // Pastikan ada input tanggal
            'notes'         => 'nullable|string',
            // Validasi Jasa (Array)
            'service_ids'   => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            // Validasi Sparepart (Array of Objects)
            'spare_parts'   => 'nullable|array',
            'spare_parts.*.id' => 'exists:spare_parts,id',
            'spare_parts.*.qty' => 'numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $customerId = null;
            $vehicleId = null;
            $totalPrice = 0;

            // =================================================
            // 1. HANDLER USER & VEHICLE (Walk-in vs Existing)
            // =================================================
            if ($request->type == 'existing') {
                $request->validate([
                    'customer_id' => 'required|exists:users,id',
                    'vehicle_id'  => 'required|exists:vehicles,id',
                ]);
                $customerId = $request->customer_id;
                $vehicleId  = $request->vehicle_id;
            } else {
                // Validasi khusus Walk-in
                $request->validate([
                    'new_name'         => 'required|string|max:255',
                    'new_phone'        => 'required|string|max:20',
                    'new_brand'        => 'required|string',
                    'new_model'        => 'required|string',
                    'new_plate_number' => [
                        'required',
                        'string',
                        // Fix Soft Delete: Plat nomor boleh sama jika data lama sudah dihapus
                        Rule::unique('vehicles', 'plate_number')->whereNull('deleted_at')
                    ],
                ]);

                // --- TRASH CLEANER LOGIC (Untuk Walk-in) ---
                // Cek apakah plat nomor ada di tong sampah?
                $trashedVehicle = Vehicle::onlyTrashed()
                    ->where('plate_number', $request->new_plate_number)
                    ->first();

                if ($trashedVehicle) {
                    // Jika vehicle dihapus, usernya kemungkinan juga dihapus/tidak valid
                    // Kita hapus permanen vehicle lama agar plat bisa dipakai
                    $trashedVehicle->forceDelete();
                }
                // -------------------------------------------

                // Buat User Customer Baru (Walk-in)
                $newUser = User::create([
                    'name'      => $request->new_name,
                    // Email unik dummy
                    'email'     => 'walkin_' . uniqid() . '@bengkel.com',
                    'phone'     => $request->new_phone,
                    'password'  => Hash::make('password'), // Default password
                    'role'      => 'customer',
                    'is_active' => 1
                ]);

                $customerId = $newUser->id;

                // Buat Kendaraan Baru
                $newVehicle = Vehicle::create([
                    'user_id'      => $customerId,
                    'plate_number' => strtoupper($request->new_plate_number),
                    'brand'        => $request->new_brand,
                    'model'        => $request->new_model,
                    'year'         => date('Y'),
                    'color'        => 'Unknown', // Default
                ]);

                $vehicleId = $newVehicle->id;
            }

            // =================================================
            // 2. HITUNG TOTAL & VALIDASI STOK
            // =================================================
            
            // Hitung Jasa
            if (!empty($request->service_ids)) {
                $selectedServices = Service::whereIn('id', $request->service_ids)->get();
                $totalPrice += $selectedServices->sum('price');
            }

            // Hitung Sparepart & Cek Stok
            if (!empty($request->spare_parts)) {
                foreach ($request->spare_parts as $item) {
                    $part = SparePart::find($item['id']);
                    
                    // Cek Stok Cukup?
                    if ($part->stock < $item['qty']) {
                        throw new \Exception("Stok barang {$part->name} tidak cukup! Sisa: {$part->stock}");
                    }
                    
                    $totalPrice += $part->sell_price * $item['qty'];
                }
            }

            // =================================================
            // 3. SIMPAN TRANSAKSI UTAMA
            // =================================================
            $transaction = Transaction::create([
                'customer_id'       => $customerId,
                'vehicle_id'        => $vehicleId,
                'mechanic_id'       => $request->mechanic_id,
                'created_by'        => Auth::id(),
                'date'              => $request->date, // Simpan tanggal transaksi
                'service_status_id' => 1, // Default: Pending / Menunggu
                'payment_status_id' => 3, // Default: Unpaid / Belum Lunas
                'total_amount'      => $totalPrice,
                'check_in_at'       => now(),
                'notes'             => $request->notes,
            ]);

            // =================================================
            // 4. SIMPAN DETAIL JASA
            // =================================================
            if (!empty($request->service_ids)) {
                foreach ($selectedServices as $service) {
                    TransactionService::create([
                        'transaction_id' => $transaction->id,
                        'service_id'     => $service->id,
                        'price_at_time'  => $service->price, // Harga saat transaksi terjadi
                        'qty'            => 1
                    ]);
                }
            }

            // =================================================
            // 5. SIMPAN DETAIL SPAREPART & KURANGI STOK
            // =================================================
            if (!empty($request->spare_parts)) {
                foreach ($request->spare_parts as $item) {
                    $part = SparePart::find($item['id']);

                    // Simpan detail
                    TransactionSparePart::create([
                        'transaction_id' => $transaction->id,
                        'spare_part_id'  => $part->id,
                        'qty'            => $item['qty'],
                        'price_at_time'  => $part->sell_price, // Simpan harga saat ini
                    ]);

                    // LOGIKA PENTING: Kurangi Stok!
                    $part->decrement('stock', $item['qty']);
                }
            }

            DB::commit();
            return redirect()->route('admin.transactions.index')
                ->with('success', 'Transaksi berhasil dibuat! Total: Rp ' . number_format($totalPrice));

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
        // PERBAIKAN: Hapus .service pada 'services' karena ini relasi Many-to-Many
        // 'services' sudah langsung mengembalikan objek Service
        $transaction->load([
            'customer' => function($q) { $q->withTrashed(); }, 
            'vehicle' => function($q) { $q->withTrashed(); }, 
            'mechanic' => function($q) { $q->withTrashed(); },
            'services' => function($q) { $q->withTrashed(); }, // <--- INI YG BIKIN ERROR, SUDAH DIPERBAIKI (Hapus .service)
            'spareParts.sparePart' => function($q) { $q->withTrashed(); }
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

        // Validasi pembayaran tidak boleh kurang dari total (Opsional)
        if ($request->amount_paid < $transaction->total_amount) {
             // return back()->with('error', 'Jumlah pembayaran kurang!'); // Aktifkan jika perlu
        }

        $transaction->update([
            'payment_method_id' => $request->payment_method_id,
            'amount_paid'       => $request->amount_paid,
            'payment_status_id' => 1, // ID 1 = Paid/Lunas (Sesuaikan dengan seeder kamu)
        ]);

        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    /**
     * Hapus Transaksi (Dan Kembalikan Stok).
     */
    public function destroy(Transaction $transaction)
    {
        DB::beginTransaction();
        try {
            // 1. Kembalikan Stok Sparepart (Refund Stock)
            // Load detail sparepart
            $transaction->load('spareParts');
            foreach ($transaction->spareParts as $detail) {
                // Ambil item sparepart master (withTrashed jaga2 kalau masternya dihapus)
                $part = SparePart::withTrashed()->find($detail->spare_part_id);
                if ($part) {
                    $part->increment('stock', $detail->qty);
                }
            }

            // 2. Hapus Transaksi
            $transaction->delete();

            DB::commit();
            return back()->with('success', 'Transaksi dihapus dan stok sparepart telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
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
        // PERBAIKAN: Sama seperti edit, services jangan pakai .service
        $transaction->load([
            'customer' => function($q) { $q->withTrashed(); },
            'vehicle' => function($q) { $q->withTrashed(); },
            'mechanic' => function($q) { $q->withTrashed(); },
            'services' => function($q) { $q->withTrashed(); }, // <--- SUDAH DIPERBAIKI
            'spareParts.sparePart' => function($q) { $q->withTrashed(); },
            'paymentMethod'
        ]);

        return view('admin.transactions.print', compact('transaction'));
    }
}