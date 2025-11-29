<?php

use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
// --- KUMPULAN CONTROLLER BREEZE/PROFIL ---
use App\Http\Controllers\Admin\PaymentMethodController;
// --- KUMPULAN CONTROLLER ADMIN ---
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\TransactionServiceController;
use App\Http\Controllers\Admin\TransactionSparePartController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Customer\CustomerTransactionController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleController;
// --- KUMPULAN CONTROLLER MEKANIK ---
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardController;
use App\Http\Controllers\Mechanic\JobController;
// --- KUMPULAN CONTROLLER CUSTOMER ---
use App\Http\Controllers\ProfileController;
use App\Models\PaymentStatus;
use App\Models\Transaction;
// --- KUMPULAN MODEL (UNTUK LOGIKA RUTE) ---
// !!! PERBAIKAN PENTING: 'app' menjadi 'App' (Case-sensitive) !!!
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
   

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
  // 1. Tambah & Hapus Jasa (Service)
    // Kita sederhanakan URL-nya biar tidak butuh parameter {transaction} di URL
    Route::post('transaction-services', [TransactionServiceController::class, 'store'])
        ->name('transaction-services.store');
        
    Route::delete('transaction-services/{id}', [TransactionServiceController::class, 'destroy'])
        ->name('transaction-services.destroy');

    // 2. Tambah & Hapus Sparepart
    Route::post('transaction-spare-parts', [TransactionSparePartController::class, 'store'])
        ->name('transaction-spareparts.store');
        
    Route::delete('transaction-spare-parts/{id}', [TransactionSparePartController::class, 'destroy'])
        ->name('transaction-spareparts.destroy');

    // Rute Pembayaran
    Route::post('transactions/{transaction}/pay', [AdminTransactionController::class, 'updatePaymentStatus'])->name('transactions.updatePayment');
    // Rute Cetak Struk
    Route::get('transactions/{transaction}/print', [AdminTransactionController::class, 'print'])->name('transactions.print');
});

// =================================================================
// GRUP RUTE UNTUK MEKANIK
// =================================================================
Route::middleware(['auth', 'role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [MechanicDashboardController::class, 'index'])->name('dashboard');

    // --- PERBAIKAN/TAMBAHAN ---

    // 1. Route Riwayat (Taruh SEBELUM resource biar gak ketimpa logic {job})
    Route::get('/jobs/history', [JobController::class, 'history'])->name('jobs.history');

    // 2. Route Update Status
    Route::post('/jobs/{transaction}/update-status', [JobController::class, 'updateStatus'])->name('jobs.updateStatus');

    // 3. Resource Controller (Standard CRUD)
    Route::resource('jobs', JobController::class)
        ->only(['index', 'show'])
        ->parameters(['jobs' => 'transaction']);

});

// =================================================================
// GRUP RUTE UNTUK PELANGGAN (CUSTOMER)
// =================================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

    // --- PERBAIKAN DI SINI ---
    // Gunakan 'resource' agar route create, store, dll otomatis ada.
    Route::resource('transactions', CustomerTransactionController::class);
    // -------------------------

    Route::resource('vehicles', CustomerVehicleController::class);
});

// --- RUTE PERBAIKAN DATABASE (HAPUS SETELAH DIPAKAI) ---
Route::get('/fix-data', function () {
    // 1. Ubah semua transaksi jadi PAID (Lunas - ID 2)
    \App\Models\Transaction::query()->update(['payment_status_id' => 2]);
    
    // 2. Ubah semua transaksi jadi DONE (Selesai - ID 4)
    \App\Models\Transaction::query()->update(['service_status_id' => 4]);

    return "Data Transaksi Berhasil Diperbaiki! Silakan cek Dashboard.";
});
// Rute otentikasi yang dibuat oleh Laravel Breeze
require __DIR__.'/auth.php';
