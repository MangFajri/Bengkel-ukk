<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PaymentStatus;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\Transaction;
use App\Models\TransactionService;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerTransactionController extends Controller
{
    public function index()
    {
        // Tambahkan 'services' ke dalam with([])
        $transactions = Transaction::with(['vehicle', 'serviceStatus', 'services'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('user_id', Auth::id())->get();
        $services = Service::where('is_active', true)->get();

        if ($vehicles->isEmpty()) {
            return redirect()->route('customer.vehicles.create')
                ->with('error', 'Daftarkan kendaraan dulu sebelum booking.');
        }

        return view('customer.transactions.create', compact('vehicles', 'services'));
    }

    public function store(Request $request)
    {
        // 1. VALIDASI KETAT
        $request->validate([
            // Pastikan vehicle_id ada DAN milik user yang login
            'vehicle_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $exists = Vehicle::where('id', $value)
                        ->where('user_id', Auth::id()) // Kunci pengaman disini
                        ->exists();
                    if (! $exists) {
                        $fail('Kendaraan yang dipilih tidak valid atau bukan milik Anda.');
                    }
                },
            ],
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'notes' => 'nullable|string|max:500',
            // Tambahkan tanggal booking (opsional, kalau tidak diisi berarti hari ini)
            'booking_date' => 'nullable|date|after_or_equal:today',
        ]);

        try {
            DB::beginTransaction();

            // 2. Hitung Total Biaya Awal
            $selectedServices = Service::whereIn('id', $request->service_ids)->get();
            $initialTotal = $selectedServices->sum('price');

            // 3. Cari ID Status Otomatis (Anti Error ID)
            $pendingStatus = ServiceStatus::where('code', 'pending')->first();
            $pendingId = $pendingStatus ? $pendingStatus->id : 1; // Fallback ke 1

            $unpaidStatus = PaymentStatus::where('code', 'unpaid')->first();
            $unpaidId = $unpaidStatus ? $unpaidStatus->id : 1;

            // 4. Buat Transaksi
            // Kita pakai field created_at sebagai tanggal booking kalau user tidak isi tanggal
            $bookingDate = $request->booking_date ?? now();

            $transaction = Transaction::create([
                'code' => 'TRX-'.mt_rand(100000, 999999),
                'customer_id' => Auth::id(),
                'vehicle_id' => $request->vehicle_id,
                'service_status_id' => $pendingId, // Status Pending
                'payment_status_id' => $unpaidId,  // Status Belum Bayar
                'payment_method_id' => null,       // Belum pilih metode bayar
                'notes' => $request->notes,
                'total_amount' => $initialTotal,
                'created_by' => Auth::id(),
                'created_at' => $bookingDate, // Manipulasi tanggal dibuat sesuai booking
            ]);

            // 5. Simpan Detail Services
            foreach ($selectedServices as $service) {
                TransactionService::create([
                    'transaction_id' => $transaction->id,
                    'service_id' => $service->id,
                    'price_at_time' => $service->price,
                    'qty' => 1,
                ]);
            }

            DB::commit();

            return redirect()->route('customer.transactions.index')
                ->with('success', 'Booking berhasil! Tunggu konfirmasi admin ya.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal booking: '.$e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->customer_id !== Auth::id()) {
            abort(403);
        }

        // Load relasi yang diperlukan
        $transaction->load(['vehicle', 'serviceStatus', 'services', 'spareParts']);

        return view('customer.transactions.show', compact('transaction'));
    }
}
