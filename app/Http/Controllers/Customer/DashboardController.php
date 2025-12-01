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
        $user = Auth::user();
        $userId = $user->id;

        // 1. Statistik Dasar
        $totalVehicles = Vehicle::where('user_id', $userId)->count();
        
        // 2. Hitung Pengeluaran (Hanya yang LUNAS / ID 1)
        $totalSpent = Transaction::where('customer_id', $userId)
            ->where('payment_status_id', 1)
            ->sum('total_amount');

        // 3. Transaksi Terakhir (Untuk Card Status Terakhir)
        $latestTransaction = Transaction::with('serviceStatus')
            ->where('customer_id', $userId)
            ->latest()
            ->first();

        // 4. [FITUR BARU] Transaksi Aktif untuk Live Tracking
        // Kita cari transaksi yang statusnya: 1 (Booking), 2 (Menunggu), atau 3 (Proses)
        // Status 4 (Selesai) dan 5 (Batal) tidak dianggap aktif berjalan.
        $activeTransaction = Transaction::with(['vehicle', 'serviceStatus'])
            ->where('customer_id', $userId)
            ->whereIn('service_status_id', [1, 2, 3]) 
            ->latest()
            ->first();
            
        // Jumlah service aktif (untuk badge angka)
        $activeServices = Transaction::where('customer_id', $userId)
            ->whereIn('service_status_id', [1, 2, 3])
            ->count();

        // 5. Daftar Mobil (Limit 5 biar tidak kepanjangan)
        $myVehicles = Vehicle::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // 6. Riwayat (History)
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
            'recentTransactions',
            'latestTransaction',
            'activeTransaction' // <--- Data penting untuk Tracking Bar
        ));
    }
}