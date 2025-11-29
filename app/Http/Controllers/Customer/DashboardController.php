<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Statistik Ringkas
        $totalVehicles = Vehicle::where('user_id', $userId)->count();
        
        // Hitung service yang sedang berjalan (Status < 4, asumsi 4=Selesai)
        $activeServices = Transaction::where('customer_id', $userId)
            ->where('service_status_id', '<', 4) // 1=Pending, 2=Confirmed, 3=Working
            ->count();

        // Total pengeluaran customer ini (Hanya yang lunas)
        $totalSpent = Transaction::where('customer_id', $userId)
            ->whereHas('paymentStatus', function($q) {
                $q->where('code', 'paid');
            })
            ->sum('total_amount');

        // 2. Daftar Mobil Milik Customer (Ambil 3 terbaru)
        $myVehicles = Vehicle::where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get();

        // 3. Riwayat Service Terakhir
        $recentTransactions = Transaction::with(['vehicle', 'serviceStatus'])
            ->where('customer_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact(
            'totalVehicles',
            'activeServices',
            'totalSpent',
            'myVehicles',
            'recentTransactions'
        ));
    }
}