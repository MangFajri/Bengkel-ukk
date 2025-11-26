<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionService;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerTransactionController extends Controller
{
    /**
     * Menampilkan riwayat booking/transaksi customer.
     */
    public function index()
    {
        // Ambil transaksi milik user yg login, urutkan dari yang terbaru
        $transactions = Transaction::with(['vehicle', 'serviceStatus', 'paymentStatus'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->get();

        return view('customer.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan form booking service baru.
     */
    public function create()
    {
        // Ambil mobil milik customer untuk dipilih di form
        $vehicles = Vehicle::where('user_id', Auth::id())->get();
        
        // Ambil daftar layanan yang aktif (Ganti Oli, Tune Up, dll)
        $services = Service::where('is_active', true)->get();

        // Cek apakah customer punya mobil? Kalau belum, suruh tambah dulu.
        if ($vehicles->isEmpty()) {
            return redirect()->route('customer.vehicles.create')
                ->with('error', 'Anda harus menambahkan kendaraan sebelum melakukan booking service.');
        }

        return view('customer.transactions.create', compact('vehicles', 'services'));
    }

    /**
     * Menyimpan data booking service ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_ids' => 'required|array|min:1', // Wajib pilih minimal 1 layanan
            'service_ids.*' => 'exists:services,id',
            'notes' => 'nullable|string|max:500', // Keluhan tambahan
            'check_in_date' => 'required|date|after_or_equal:today', // Tanggal booking minimal hari ini
        ]);

        // Pastikan mobil yang dipilih benar-benar milik user ini (Security Check)
        $vehicleCheck = Vehicle::where('id', $request->vehicle_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$vehicleCheck) {
            return back()->withErrors(['vehicle_id' => 'Kendaraan tidak valid atau bukan milik Anda.']);
        }

        // Gunakan DB Transaction biar aman (kalau gagal simpan detail, data utama gak ke-save)
        try {
            DB::beginTransaction();

            // 2. Hitung Estimasi Total Biaya Awal
            // Kita hitung total harga dari service yang dipilih
            $selectedServices = Service::whereIn('id', $request->service_ids)->get();
            $initialTotal = $selectedServices->sum('price');

            // 3. Simpan ke Tabel 'transactions'
            $transaction = Transaction::create([
                'customer_id' => Auth::id(),
                'vehicle_id' => $request->vehicle_id,
                'service_status_id' => 2, // ID 2 = 'waiting' (Menunggu Konfirmasi - Sesuai Seeder StatusSeeder)
                'payment_status_id' => 3, // ID 3 = 'unpaid' (Belum Bayar)
                'check_in_at' => $request->check_in_date . ' ' . date('H:i:s'), // Gabung tanggal input + jam sekarang
                'notes' => $request->notes,
                'total_amount' => $initialTotal, // Total sementara (bisa nambah kalau ada sparepart nanti)
                'created_by' => Auth::id(),
            ]);

            // 4. Simpan Detail Layanan ke Tabel 'transaction_services'
            foreach ($selectedServices as $service) {
                TransactionService::create([
                    'transaction_id' => $transaction->id,
                    'service_id' => $service->id,
                    'price_at_time' => $service->price, // Simpan harga saat ini (penting buat histori harga)
                    'qty' => 1
                ]);
            }

            DB::commit(); // Simpan semua perubahan

            return redirect()->route('customer.transactions.index')
                ->with('success', 'Booking berhasil dibuat! Mohon tunggu konfirmasi admin.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail transaksi/booking.
     */
    public function show(Transaction $transaction)
    {
        // Security: Cek kepemilikan
        if ($transaction->customer_id !== Auth::id()) {
            abort(403);
        }

        $transaction->load(['vehicle', 'serviceStatus', 'paymentStatus', 'services', 'spareParts']);

        return view('customer.transactions.show', compact('transaction'));
    }
}