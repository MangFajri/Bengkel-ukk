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
        // Ambil ID mekanik yang sedang login
        $mechanicId = Auth::id();

        // Ambil ID status yang relevan ('waiting' dan 'in_progress')
        $statusCodes = ['waiting', 'in_progress'];

        // Query untuk mengambil tugas
        $jobs = Transaction::with(['customer', 'vehicle', 'serviceStatus'])
                           ->where('mechanic_id', $mechanicId)
                           ->whereHas('serviceStatus', function ($query) use ($statusCodes) {
                               $query->whereIn('code', $statusCodes);
                           })
                           ->orderBy('check_in_at', 'asc') // Urutkan dari yang paling lama
                           ->paginate(10);
        
        return view('mechanic.dashboard', compact('jobs'));
    }
}