<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $customer = Auth::user();

        // Muat relasi kendaraan dan transaksi milik user tersebut
        $customer->load(['vehicles', 'customerTransactions']);

        return view('customer.dashboard', compact('customer'));
    }
}