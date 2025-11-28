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
        // Ambil User ID mekanik yang sedang login
        $userId = Auth::id();

        // Ambil daftar pekerjaan (Transaction) yang ditugaskan ke mekanik ini
        // Kondisi: mechanic_id = ID user login DAN status pembayaran BELUM LUNAS (id != 1)
        // Kita asumsikan ID 1 adalah 'Lunas' atau 'Selesai'.
        $jobs = Transaction::with(['customer', 'vehicle', 'serviceStatus'])
            ->where('mechanic_id', $userId)
            ->where('payment_status_id', '!=', 1) 
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim variabel $jobs ke view mechanic.dashboard
        return view('mechanic.dashboard', compact('jobs'));
    }
}