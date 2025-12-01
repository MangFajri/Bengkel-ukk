<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Filter Tanggal (Default: Bulan Ini)
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        // 2. Query Dasar: Transaksi LUNAS (payment_status_id = 1) dalam rentang tanggal
        $query = Transaction::where('payment_status_id', 1) // Hanya yang LUNAS
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);

        // --- HITUNG OMZET (Total Pendapatan) ---
        $totalRevenue = $query->sum('total_amount');

        // --- HITUNG HPP (Modal Barang Terjual) ---
        // Kita perlu join ke tabel sparepart untuk ambil cost_price
        // Rumus: SUM( qty_jual * harga_modal_saat_ini )
        // Catatan: Ini estimasi jika harga modal di master berubah, idealnya harga modal disimpan di pivot.
        $totalCost = DB::table('transaction_spare_parts')
            ->join('transactions', 'transaction_spare_parts.transaction_id', '=', 'transactions.id')
            ->join('spare_parts', 'transaction_spare_parts.spare_part_id', '=', 'spare_parts.id')
            ->where('transactions.payment_status_id', 1)
            ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$startDate, $endDate])
            ->sum(DB::raw('transaction_spare_parts.qty * COALESCE(transaction_spare_parts.cost_price_at_time, spare_parts.cost_price)'));

        // --- HITUNG PROFIT ---
        // Asumsi: Jasa tidak ada HPP (100% profit)
        $netProfit = $totalRevenue - $totalCost;

        // 3. Ambil Data Transaksi untuk Tabel Detail
        $transactions = $query->with(['customer', 'vehicle'])->latest()->get();

        return view('admin.reports.index', compact(
            'transactions', 
            'totalRevenue', 
            'totalCost', 
            'netProfit', 
            'startDate', 
            'endDate'
        ));
    }
}