<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentStatus;
use App\Models\Transaction;
use App\Models\User;
use App\Models\ServiceStatus; // <-- Tambah ini
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // --- LOGIKA PENCARIAN ID OTOMATIS (ANTI BUG) ---
        $paidStatus = PaymentStatus::where('code', 'paid')->first();
        $paidId = $paidStatus ? $paidStatus->id : 1;

        // 1. Total Pendapatan
        $revenue = Transaction::where('payment_status_id', $paidId)->sum('total_amount');

        // 2. Jumlah Transaksi Bulan Ini
        $monthlyTransactions = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 3. Jumlah Customer Aktif
        $totalCustomers = User::where('role', 'customer')->count();

        // 4. Pekerjaan Pending (Booking, Confirmed, Working)
        $pendingJobs = Transaction::whereIn('service_status_id', [1, 2, 3])->count();

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
            ->where('payment_status_id', $paidId)
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
        }

        // 7. Sparepart Terlaris (Top 5)
        $topSpareparts = DB::table('transaction_spare_parts')
            ->join('transactions', 'transaction_spare_parts.transaction_id', '=', 'transactions.id')
            ->join('spare_parts', 'transaction_spare_parts.spare_part_id', '=', 'spare_parts.id')
            ->where('transactions.payment_status_id', $paidId)
            ->select('spare_parts.name', DB::raw('SUM(transaction_spare_parts.qty) as total_sold'))
            ->groupBy('spare_parts.id', 'spare_parts.name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // --- [BARU] 8. Data Pie Chart (Komposisi Status Service) ---
        // Kita hitung berapa yg selesai, pending, batal, dll.
        $statusStats = Transaction::select('service_status_id', DB::raw('count(*) as total'))
            ->groupBy('service_status_id')
            ->pluck('total', 'service_status_id')
            ->toArray();
        
        // Mapping data agar urut sesuai label chart nanti (1: Booking, 2: Menunggu, 3: Proses, 4: Selesai, 5: Batal)
        // Sesuaikan urutan array ini dengan urutan label di JS View nanti
        $pieData = [
            $statusStats[1] ?? 0, // Booking
            $statusStats[2] ?? 0, // Menunggu
            $statusStats[3] ?? 0, // Proses
            $statusStats[4] ?? 0, // Selesai
            $statusStats[5] ?? 0  // Batal
        ];

        return view('admin.dashboard', compact(
            'revenue',
            'monthlyTransactions',
            'totalCustomers',
            'pendingJobs',
            'recentTransactions',
            'chartData',
            'topSpareparts',
            'pieData' // <-- Kirim data pie chart
        ));
    }
}