<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\UserController;
// Beri nama alias yang jelas
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Mechanic\JobController;

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
// Rute ini akan mengarahkan pengguna ke dashboard yang sesuai dengan perannya.
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


// Grup Rute yang Membutuhkan Otentikasi
Route::middleware('auth')->group(function () {
    // Rute untuk profil pengguna (bisa diakses semua peran)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =================================================================
// GRUP RUTE UNTUK ADMIN
// =================================================================
// use case
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\TransactionServiceController;
use App\Http\Controllers\Admin\TransactionSparePartController;
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    // Dashboard Admin
    Route::get('/dashboard', function () {
        // Logika untuk menampilkan dashboard admin
        return view('admin.dashboard'); // Placeholder
    })->name('dashboard');

     // Rute resource untuk Transaksi
    Route::resource('transactions', AdminTransactionController::class);

    // Rute untuk menambah & menghapus JASA pada sebuah transaksi
    Route::post('transactions/{transaction}/services', [TransactionServiceController::class, 'store'])->name('transactions.services.store');
    Route::delete('transactions/services/{transactionService}', [TransactionServiceController::class, 'destroy'])->name('transactions.services.destroy');
    
    // Rute untuk menambah & menghapus SPAREPART pada sebuah transaksi
    Route::post('transactions/{transaction}/spare-parts', [TransactionSparePartController::class, 'store'])->name('transactions.spare-parts.store');
    Route::delete('transactions/spare-parts/{transactionSparePart}', [TransactionSparePartController::class, 'destroy'])->name('transactions.spare-parts.destroy');

    // Manajemen Pengguna (Users)
    Route::resource('users', UserController::class); // Contoh untuk CRUD lengkap

    // Manajemen Kendaraan (Vehicles)
    Route::resource('vehicles', VehicleController::class);

     // Manajemen Metode Pembayaran 
    Route::resource('payment-methods', PaymentMethodController::class);

    // Manajemen Jasa (Services)
    Route::resource('services', ServiceController::class);

    // Manajemen Sparepart (SpareParts)
    Route::resource('spare-parts', SparePartController::class);

    // Manajemen Transaksi (Transactions)
    Route::resource('transactions', AdminTransactionController::class);

     Route::post('transactions/{transaction}/pay', [AdminTransactionController::class, 'updatePaymentStatus'])->name('transactions.updatePayment');
});


// =================================================================
// GRUP RUTE UNTUK MEKANIK
// =================================================================
Route::middleware(['auth', 'role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function() {
    // Dashboard Mekanik (Menampilkan Antrean Kerja)
    Route::get('/dashboard', [MechanicDashboardController::class, 'index'])->name('dashboard');

    // Nanti kita akan tambahkan rute untuk melihat detail & update status
    Route::get('/jobs/{transaction}', [JobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{transaction}/update-status', [JobController::class, 'updateStatus'])->name('jobs.updateStatus');
});


// =================================================================
// GRUP RUTE UNTUK PELANGGAN (CUSTOMER)
// =================================================================
use App\Http\Controllers\Customer\CustomerTransactionController;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleController;
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function() {
    // Dashboard Pelanggan
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Melihat Riwayat Transaksi
   Route::get('/transactions', [CustomerTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [CustomerTransactionController::class, 'show'])->name('transactions.show');

    // Manajemen Kendaraan Milik Pelanggan
    Route::resource('vehicles', CustomerVehicleController::class);
});


// Rute otentikasi yang dibuat oleh Laravel Breeze
require __DIR__.'/auth.php';