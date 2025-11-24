<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- KUMPULAN CONTROLLER BREEZE/PROFIL ---
use App\Http\Controllers\ProfileController;

// --- KUMPULAN CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\TransactionServiceController;
use App\Http\Controllers\Admin\TransactionSparePartController;

// --- KUMPULAN CONTROLLER MEKANIK ---
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardController;
use App\Http\Controllers\Mechanic\JobController;

// --- KUMPULAN CONTROLLER CUSTOMER ---
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerTransactionController;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleController;

// --- KUMPULAN MODEL (UNTUK LOGIKA RUTE) ---
// !!! PERBAIKAN PENTING: 'app' menjadi 'App' (Case-sensitive) !!!
use App\Models\Transaction;
use App\Models\User;
use App\Models\PaymentStatus;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah tempat mendaftarkan rute web untuk aplikasi.
| Rute-rute ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web".
|
*/

// Rute Halaman Utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard Utama (Setelah Login)
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'mechanic') {
        return redirect()->route('mechanic.dashboard');
    } elseif ($user->role === 'customer') {
        return redirect()->route('customer.dashboard');
    }

    // Fallback jika peran tidak terdefinisi
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');


// Grup Rute yang Membutuhkan Otentikasi (Profil)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =================================================================
// GRUP RUTE UNTUK ADMIN
// =================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    
    // Dashboard
    Route::get('/dashboard', function () {
        
        // 1. Ambil ID status 'Lunas'
        $paidStatusId = PaymentStatus::where('code', 'paid')->firstOrFail()->id;

        // 2. Hitung Total Pendapatan (hanya dari transaksi yang LUNAS)
        $totalRevenue = Transaction::where('payment_status_id', $paidStatusId)->sum('total_amount');
        
        // 3. Hitung Jumlah Pelanggan
        $totalCustomers = User::where('role', 'customer')->count();
        
        // 4. Hitung Pekerjaan yang Sedang Berjalan
        $pendingJobs = Transaction::whereHas('serviceStatus', function($query) {
            $query->whereIn('code', ['waiting', 'in_progress']);
        })->count();
        
        // 5. Hitung Jumlah Mekanik
        $totalMechanics = User::where('role', 'mechanic')->count();

        // 6. Kirim semua data ke view
        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalCustomers',
            'pendingJobs',
            'totalMechanics'
        ));
        
    })->name('dashboard');

    // --- Master Data ---
    Route::resource('services', ServiceController::class);
    Route::resource('spare-parts', SparePartController::class);
    Route::resource('users', UserController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    
    // --- Transaksional ---
    // (Rute 'transactions' hanya perlu didaftarkan SATU KALI)
    Route::resource('transactions', AdminTransactionController::class);

    // Rute Detail Transaksi (Jasa & Sparepart)
    Route::post('transactions/{transaction}/services', [TransactionServiceController::class, 'store'])->name('transactions.services.store');
    Route::delete('transactions/services/{transactionService}', [TransactionServiceController::class, 'destroy'])->name('transactions.services.destroy');
    Route::post('transactions/{transaction}/spare-parts', [TransactionSparePartController::class, 'store'])->name('transactions.spare-parts.store');
    Route::delete('transactions/spare-parts/{transactionSparePart}', [TransactionSparePartController::class, 'destroy'])->name('transactions.spare-parts.destroy');

    // Rute Pembayaran
    Route::post('transactions/{transaction}/pay', [AdminTransactionController::class, 'updatePaymentStatus'])->name('transactions.updatePayment');
});


// =================================================================
// GRUP RUTE UNTUK MEKANIK
// =================================================================
Route::middleware(['auth', 'role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function() {
    Route::get('/dashboard', [MechanicDashboardController::class, 'index'])->name('dashboard');
    Route::get('/jobs/{transaction}', [JobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{transaction}/update-status', [JobController::class, 'updateStatus'])->name('jobs.updateStatus');
});


// =================================================================
// GRUP RUTE UNTUK PELANGGAN (CUSTOMER)
// =================================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function() {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [CustomerTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [CustomerTransactionController::class, 'show'])->name('transactions.show');
    Route::resource('vehicles', CustomerVehicleController::class);
});


// Rute otentikasi yang dibuat oleh Laravel Breeze
require __DIR__.'/auth.php';