<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Menampilkan daftar pekerjaan (Index).
     * Redirect ke dashboard karena list pekerjaan ada di sana.
     */
    public function index()
    {
        return redirect()->route('mechanic.dashboard');
    }

    /**
     * Menampilkan detail pekerjaan.
     * * PENTING: 
     * Nama variabel parameter harus '$job' karena kita menggunakan Route::resource('jobs', ...).
     * Tapi tipe datanya tetap 'Transaction' agar Model Binding bekerja.
     */
    public function show(Transaction $job) 
    {
        // Security Check: Pastikan job ini milik mekanik yang login
        if ($job->mechanic_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pekerjaan ini.');
        }

        // Load relasi yang dibutuhkan untuk tampilan detail
        $job->load(['vehicle', 'customer', 'services', 'spareParts', 'serviceStatus']);

        // Kirim data ke view dengan nama variabel '$transaction' agar view tidak perlu diubah
        return view('mechanic.jobs.show', ['transaction' => $job]);
    }

    /**
     * Update status pengerjaan (Mulai / Selesai).
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        // Validasi kepemilikan job
        if ($transaction->mechanic_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status_code' => 'required|in:in_progress,done'
        ]);

        // Mapping status: in_progress = 3, done = 4
        $statusId = $request->status_code == 'in_progress' ? 3 : 4;

        $dataToUpdate = ['service_status_id' => $statusId];

        // Jika selesai, catat waktu checkout
        if ($statusId == 4) {
            $dataToUpdate['check_out_at'] = now();
        }

        $transaction->update($dataToUpdate);

        $msg = $statusId == 3 ? 'Pekerjaan dimulai! Semangat!' : 'Pekerjaan selesai. Kerja bagus!';
        
        return redirect()->route('mechanic.dashboard')->with('success', $msg);
    }

    /**
     * Menampilkan riwayat pekerjaan yang SUDAH SELESAI (Status: Done/4).
     */
    public function history()
    {
        $historyJobs = Transaction::with(['vehicle', 'customer', 'services'])
            ->where('mechanic_id', Auth::id())
            ->where('service_status_id', 4) // 4 = Done/Selesai
            ->orderBy('check_out_at', 'desc') // Yang baru selesai paling atas
            ->paginate(10);

        return view('mechanic.jobs.history', compact('historyJobs'));
    }
}