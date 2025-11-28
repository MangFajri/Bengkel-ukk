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
     */
    public function index()
    {
        // Ambil transaksi dimana mechanic_id adalah user yang login
        // DAN status pembayarannya belum lunas (artinya masih proses pengerjaan)
        $jobs = Transaction::with(['customer', 'vehicle', 'serviceStatus'])
            ->where('mechanic_id', Auth::id())
            ->where('payment_status_id', '!=', 1) // Asumsi ID 1 = Lunas/Selesai total
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mechanic.dashboard', compact('jobs'));
    }

    /**
     * Tampilkan detail satu pekerjaan.
     */
    public function show($id)
    {
        // Validasi: Pastikan job ini punya saya
        $transaction = Transaction::with(['services', 'spareParts', 'customer', 'vehicle'])
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
        $jobs = Transaction::with(['customer', 'vehicle'])
            ->where('mechanic_id', Auth::id())
            ->where('payment_status_id', 1) // Asumsi ID 1 = Lunas/Selesai
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mechanic.jobs.history', compact('jobs'));
    }
}