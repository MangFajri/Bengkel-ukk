<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. STATISTIK KARTU ATAS
        $stats = [
            // Yang sedang dikerjakan sekarang
            'active' => Transaction::where('mechanic_id', $userId)
                ->where('service_status_id', 3) // 3 = Sedang Dikerjakan
                ->count(),
            
            // Yang sudah selesai total
            'finished' => Transaction::where('mechanic_id', $userId)
                ->where('service_status_id', 4) // 4 = Selesai
                ->count(),

            // Selesai hari ini
            'today_done' => Transaction::where('mechanic_id', $userId)
                ->where('service_status_id', 4)
                ->whereDate('updated_at', today())
                ->count(),
        ];

        // 2. CURRENT JOB (Mobil yang sedang dipegang saat ini)
        // Ambil satu saja yang paling prioritas (sedang dikerjakan)
        $currentJob = Transaction::with(['vehicle', 'customer', 'services'])
            ->where('mechanic_id', $userId)
            ->where('service_status_id', 3) // In Progress
            ->first();

        // 3. INCOMING QUEUE (Antrian Tugas)
        // Mobil yang sudah ditugaskan ke mekanik ini TAPI belum dikerjakan (Status 1=Booking atau 2=Menunggu)
        $incomingJobs = Transaction::with(['vehicle', 'serviceStatus'])
            ->where('mechanic_id', $userId)
            ->whereIn('service_status_id', [1, 2]) 
            ->orderBy('created_at', 'asc') // Yang masuk duluan, dikerjakan duluan
            ->limit(5)
            ->get();

        // 4. DATA GRAFIK KINERJA (7 Hari Terakhir)
        // Menghitung jumlah mobil selesai per hari
        $performanceData = Transaction::select(
                DB::raw('DATE(updated_at) as date'), 
                DB::raw('count(*) as total')
            )
            ->where('mechanic_id', $userId)
            ->where('service_status_id', 4) // Selesai
            ->where('updated_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('total', 'date')
            ->toArray();

        // Format data untuk Chart.js
        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('d M'); // Label tgl
            $chartValues[] = $performanceData[$date] ?? 0; // Nilai atau 0
        }

        return view('mechanic.dashboard', compact(
            'stats', 
            'currentJob', 
            'incomingJobs',
            'chartLabels',
            'chartValues'
        ));
    }
}