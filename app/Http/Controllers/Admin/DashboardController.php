<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Pendapatan (Status Paid/Lunas = 1)
        $revenue = Transaction::where('payment_status_id', 1)->sum('total_amount');

        // 2. Jumlah Transaksi Bulan Ini
        $monthlyTransactions = Transaction::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        // 3. Jumlah Customer Aktif
        $totalCustomers = User::where('role', 'customer')->count();

        // 4. Pekerjaan Pending
        $pendingJobs = Transaction::whereIn('service_status_id', [2, 3])->count();

        // 5. Transaksi Terbaru
        $recentTransactions = Transaction::with(['customer', 'vehicle', 'serviceStatus'])
            ->latest()
            ->take(5)
            ->get();

        // 6. Grafik Pendapatan Bulanan
        $monthlyRevenue = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('payment_status_id', 1)
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();
            
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
        }

        // Kirim variabel $revenue, dll ke view
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