<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\ServiceStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Tampilkan daftar pekerjaan SAYA SAJA (Active Jobs).
     * Digunakan jika kamu mengakses route /mechanic/jobs
     */
    public function index()
    {
        $jobs = Transaction::with([
                'customer' => function($q) { $q->withTrashed(); }, 
                'vehicle' => function($q) { $q->withTrashed(); },
                'serviceStatus'
            ])
            ->where('mechanic_id', Auth::id())
            ->where('payment_status_id', '!=', 1) 
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pakai paginate biar halaman tidak panjang ke bawah

        // PERBAIKAN: Arahkan ke view 'mechanic.jobs.index', BUKAN 'mechanic.dashboard'
        return view('mechanic.jobs.index', compact('jobs'));
    }

    /**
     * Tampilkan detail satu pekerjaan (Saat klik tombol "Kerjakan").
     */
    public function show($id)
    {
        // Validasi: Pastikan job ini punya saya
        $transaction = Transaction::with([
                'services', 
                'spareParts', 
                // PENTING: withTrashed() mencegah error "property name on null"
                'customer' => function($q) { $q->withTrashed(); }, 
                'vehicle' => function($q) { $q->withTrashed(); }
            ])
            ->where('id', $id)
            ->where('mechanic_id', Auth::id())
            ->firstOrFail(); // 404 jika mencoba akses job orang lain

        $statuses = ServiceStatus::all(); // Untuk dropdown update status

        return view('mechanic.jobs.show', compact('transaction', 'statuses'));
    }

    /**
     * Update status pengerjaan (Misal: Menunggu -> Sedang Dikerjakan -> Selesai).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'service_status_id' => 'required|exists:service_statuses,id',
        ]);

        $transaction = Transaction::where('id', $id)
            ->where('mechanic_id', Auth::id())
            ->firstOrFail();

        $transaction->update([
            'service_status_id' => $request->service_status_id
        ]);

        return back()->with('success', 'Status pekerjaan berhasil diperbarui!');
    }

    /**
     * Halaman Riwayat (Pekerjaan yang sudah selesai/lunas).
     */
    public function history()
    {
        // PERBAIKAN: Nama variabel disesuaikan dengan View ($historyJobs)
        $historyJobs = Transaction::with([
                'customer' => function($q) { $q->withTrashed(); }, 
                'vehicle' => function($q) { $q->withTrashed(); }
            ])
            ->where('mechanic_id', Auth::id())
            // Asumsi: Job masuk history kalau status servis 'Selesai' (ID 4) atau Bayar Lunas (ID 1)
            // Sesuaikan logika ini dengan maumu. Biasanya patokannya Status Service = Selesai.
            ->where('service_status_id', 4) 
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pakai paginate biar halaman history rapi

        // Kirim dengan nama 'historyJobs' agar cocok dengan blade
        return view('mechanic.jobs.history', compact('historyJobs'));
    }
}