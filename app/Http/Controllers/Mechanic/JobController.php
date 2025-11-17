<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\ServiceStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Menampilkan detail dari sebuah pekerjaan (transaksi).
     */
    public function show(Transaction $transaction)
    {
        // Pastikan mekanik hanya bisa melihat tugas yang ditugaskan kepadanya
        if ($transaction->mechanic_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK.');
        }

        // Muat semua relasi yang dibutuhkan untuk ditampilkan di view
        $transaction->load(['customer', 'vehicle', 'serviceStatus', 'transactionServices.service', 'transactionSpareParts.sparePart']);

        // Ambil daftar status yang bisa dipilih oleh mekanik
        $statuses = ServiceStatus::whereIn('code', ['in_progress', 'done'])->get();

        return view('mechanic.jobs.show', compact('transaction', 'statuses'));
    }

    /**
     * Mengupdate status dari sebuah pekerjaan (transaksi).
     * INI METHOD YANG KITA TAMBAHKAN.
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        // 1. Validasi input dari form
        $request->validate([
            'service_status_id' => 'required|exists:service_statuses,id',
        ]);

        // 2. Pastikan mekanik yang mengubah status adalah mekanik yang ditugaskan
        if ($transaction->mechanic_id !== auth()->id()) {
            abort(403, 'AKSES DITOLAK. Anda tidak ditugaskan untuk pekerjaan ini.');
        }

        // 3. Update status transaksi
        $transaction->service_status_id = $request->service_status_id;

        // Jika status diubah menjadi 'done' (Selesai), catat waktu keluarnya
        $newStatus = ServiceStatus::find($request->service_status_id);
        if ($newStatus && $newStatus->code === 'done') {
            $transaction->check_out_at = now(); // now() adalah helper untuk mendapatkan waktu saat ini
        }
        
        $transaction->save(); // Simpan perubahan ke database

        // 4. Redirect kembali ke halaman detail pekerjaan dengan pesan sukses
        return redirect()->route('mechanic.jobs.show', $transaction->id)
                         ->with('success', 'Status pekerjaan berhasil diperbarui.');
    }
}