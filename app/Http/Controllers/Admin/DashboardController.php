<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\PaymentStatus; // Pastikan ini ada
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- LOGIKA PENCARIAN ID OTOMATIS (ANTI BUG) ---
        // Kita tanya database: "Status 'paid' itu ID-nya berapa sih?"
        $paidStatus = PaymentStatus::where('code', 'paid')->first();
        
        // Kalau ketemu pakai ID-nya, kalau tidak ketemu pakai default 1 (sesuai Tinker kamu)
        $paidId = $paidStatus ? $paidStatus->id : 1; 


        // 1. Total Pendapatan (Pakai $paidId yang sudah kita cari)
        $revenue = Transaction::where('payment_status_id', $paidId)->sum('total_amount');

        // 2. Jumlah Transaksi Bulan Ini
        $monthlyTransactions = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 3. Jumlah Customer Aktif
        $totalCustomers = User::where('role', 'customer')->count();

        // 4. Pekerjaan Pending (Booking, Confirmed, Working)
        // Kita anggap status service 1, 2, 3 adalah proses pending
        $pendingJobs = Transaction::whereIn('service_status_id', [1, 2, 3])->count();

        // 5. Transaksi Terbaru
        $recentTransactions = Transaction::with(['customer', 'vehicle', 'serviceStatus'])
            ->latest()
            ->take(5)
            ->get();

        // 6. Grafik Pendapatan Bulanan (Juga pakai $paidId)
        $monthlyRevenue = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('payment_status_id', $paidId) // <--- PENTING: Pakai ID yang benar
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'revenue', 
            'monthlyTransactions', 
            'totalCustomers', 
            'pendingJobs',
            'recentTransactions',
            'chartData'
        ));
    }
}