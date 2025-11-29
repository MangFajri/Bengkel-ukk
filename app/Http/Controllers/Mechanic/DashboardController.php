<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Hitung Statistik untuk Kartu Atas
        $stats = [
            'total_active' => Transaction::where('mechanic_id', $userId)
                                ->where('payment_status_id', '!=', 1) // Belum Lunas
                                ->count(),
            
            'finished'     => Transaction::where('mechanic_id', $userId)
                                ->where('service_status_id', 4) // Asumsi ID 4 = Selesai
                                ->count(),

            'today'        => Transaction::where('mechanic_id', $userId)
                                ->whereDate('created_at', today())
                                ->count(),
        ];

        // 2. Ambil 5 Pekerjaan Terbaru saja (untuk tabel ringkasan)
        $recentJobs = Transaction::with(['customer', 'vehicle', 'serviceStatus'])
            ->where('mechanic_id', $userId)
            ->where('payment_status_id', '!=', 1) 
            ->orderBy('created_at', 'desc')
            ->limit(5) // Cuma ambil 5 biar dashboard gak penuh
            ->get();

        return view('mechanic.dashboard', compact('stats', 'recentJobs'));
    }
}