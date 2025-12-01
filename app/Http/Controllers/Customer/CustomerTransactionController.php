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
use Illuminate\Support\Facades\Storage;

class CustomerTransactionController extends Controller
{
    public function index()
    {
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
        $request->validate([
            'vehicle_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $exists = Vehicle::where('id', $value)
                        ->where('user_id', Auth::id())
                        ->exists();
                    if (! $exists) {
                        $fail('Kendaraan yang dipilih tidak valid atau bukan milik Anda.');
                    }
                },
            ],
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'notes' => 'nullable|string|max:500',
            'booking_date' => 'nullable|date|after_or_equal:today',
        ]);

        try {
            DB::beginTransaction();

            // 1. Hitung Total Biaya
            $selectedServices = Service::whereIn('id', $request->service_ids)->get();
            $initialTotal = $selectedServices->sum('price');

            // 2. Tentukan Status
            // LOGIC BARU: Customer Booking = Status 2 (Menunggu)
            $waitingStatusId = 2;

            // Default Payment = 3 (Belum Bayar)
            $unpaidId = 3;

            // 3. Tanggal Booking
            $bookingDate = $request->booking_date ?? now();

            $transaction = Transaction::create([
                // Generate Kode Unik (Opsional, tapi bagus buat UX)
                // 'code' => 'TRX-' . mt_rand(100000, 999999),
                'customer_id' => Auth::id(),
                'vehicle_id' => $request->vehicle_id,
                'service_status_id' => $waitingStatusId, // <-- PENTING: Status Menunggu
                'payment_status_id' => $unpaidId,
                'payment_method_id' => null,
                'notes' => $request->notes,
                'total_amount' => $initialTotal,
                'created_by' => Auth::id(),
                'created_at' => $bookingDate,
            ]);

            // 4. Simpan Detail Services
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
                ->with('success', 'Booking berhasil dikirim! Silakan tunggu konfirmasi Admin.');

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

        $transaction->load(['vehicle', 'serviceStatus', 'services', 'spareParts']);

        return view('customer.transactions.show', compact('transaction'));
    }

    public function uploadProof(Request $request, Transaction $transaction)
    {
        // Validasi: Pastikan ini transaksi milik user yang login
        if ($transaction->customer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|max:2048', // Max 2MB, harus gambar
        ]);

        // Hapus bukti lama jika ada (misal dia salah upload sebelumnya)
        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        // Simpan file baru ke folder 'payments' di storage public
        $path = $request->file('payment_proof')->store('payments', 'public');

        $transaction->update([
            'payment_proof' => $path,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Tunggu verifikasi admin.');
    }
}
