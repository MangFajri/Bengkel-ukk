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
        // Ambil pekerjaan yang ditugaskan ke mekanik ini
        // Status: 2 (Waiting) dan 3 (In Progress)
        $activeJobs = Transaction::with(['vehicle', 'customer', 'serviceStatus'])
            ->where('mechanic_id', Auth::id())
            ->whereIn('service_status_id', [2, 3]) 
            ->orderBy('service_status_id', 'desc') // Prioritaskan yang sedang dikerjakan (3)
            ->orderBy('check_in_at', 'asc') // Lalu urutkan yang masuk duluan
            ->get();

        // Hitung statistik sederhana
        $jobsToday = Transaction::where('mechanic_id', Auth::id())
            ->whereDate('updated_at', today())
            ->where('service_status_id', 4) // 4 = Done
            ->count();

        return view('mechanic.dashboard', compact('activeJobs', 'jobsToday'));
    }
}