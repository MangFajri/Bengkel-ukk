<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// --- CONTROLLERS ADMIN ---
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\TransactionServiceController;
use App\Http\Controllers\Admin\TransactionSparePartController;

// --- CONTROLLERS MEKANIK ---
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardController;
use App\Http\Controllers\Mechanic\JobController;

// --- CONTROLLERS CUSTOMER ---
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleController;
use App\Http\Controllers\Customer\CustomerTransactionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Rute Dashboard Utama (Redirect sesuai Role)
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'mechanic') return redirect()->route('mechanic.dashboard');
    if ($user->role === 'customer') return redirect()->route('customer.dashboard');
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');


// Profil (Breeze Default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =================================================================
// 1. GRUP RUTE ADMIN
// =================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Data
    Route::resource('services', ServiceController::class);
    Route::resource('spare-parts', SparePartController::class);
    Route::resource('users', UserController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('payment-methods', PaymentMethodController::class);

    // Transaksi Utama
    Route::resource('transactions', AdminTransactionController::class);
    
    // Fitur Bayar & Cetak
    Route::post('transactions/{transaction}/pay', [AdminTransactionController::class, 'updatePaymentStatus'])->name('transactions.updatePayment');
    Route::get('transactions/{transaction}/print', [AdminTransactionController::class, 'print'])->name('transactions.print');

    // Detail Transaksi (Jasa & Sparepart) - API Style
    Route::post('transaction-services', [TransactionServiceController::class, 'store'])->name('transaction-services.store');
    Route::delete('transaction-services/{id}', [TransactionServiceController::class, 'destroy'])->name('transaction-services.destroy');

    Route::post('transaction-spare-parts', [TransactionSparePartController::class, 'store'])->name('transaction-spareparts.store');
    Route::delete('transaction-spare-parts/{id}', [TransactionSparePartController::class, 'destroy'])->name('transaction-spareparts.destroy');
});


// =================================================================
// 2. GRUP RUTE MEKANIK (PERBAIKAN UTAMA DISINI)
// =================================================================
Route::middleware(['auth', 'role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function () {

    Route::get('/dashboard', [MechanicDashboardController::class, 'index'])->name('dashboard');

    // Riwayat Pekerjaan
    Route::get('/jobs/history', [JobController::class, 'history'])->name('jobs.history');

    // Update Status (Pakai PATCH agar sesuai dengan @method('PATCH') di view)
    Route::patch('/jobs/{transaction}/update-status', [JobController::class, 'updateStatus'])->name('jobs.update-status');

    // === [REUSE LOGIC ADMIN UNTUK SPAREPART] ===
    // Kita arahkan ke Controller Admin biar validasi stok & lunas tetap jalan
    // URL kita buat simpel, transaction_id dikirim via input hidden di form
    Route::post('/jobs/spare-parts', [TransactionSparePartController::class, 'store'])
        ->name('spare-parts.store');
        
    Route::delete('/jobs/spare-parts/{id}', [TransactionSparePartController::class, 'destroy'])
        ->name('spare-parts.destroy');

    // Resource Controller Jobs (Paling bawah biar ga konflik)
    Route::resource('jobs', JobController::class)
        ->only(['index', 'show'])
        ->parameters(['jobs' => 'transaction']); // Biar variabel di controller namanya $transaction
});


// =================================================================
// 3. GRUP RUTE CUSTOMER
// =================================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('transactions', CustomerTransactionController::class);
    Route::resource('vehicles', CustomerVehicleController::class);
});

require __DIR__.'/auth.php';