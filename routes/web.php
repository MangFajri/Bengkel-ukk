<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SparePartController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function() {
    // Dashboard Admin
    Route::get('/dashboard', function () {
        // Logika untuk menampilkan dashboard admin
        return view('admin.dashboard'); // Placeholder
    })->name('dashboard');

    // Manajemen Pengguna (Users)
    Route::resource('users', UserController::class); // Contoh untuk CRUD lengkap

    // Manajemen Kendaraan (Vehicles)
    // Route::resource('vehicles', VehicleController::class);

    // Manajemen Jasa (Services)
    Route::resource('services', ServiceController::class);

    // Manajemen Sparepart (SpareParts)
    Route::resource('spare-parts', SparePartController::class);

    // Manajemen Transaksi (Transactions)
    Route::resource('transactions', TransactionController::class);
});


// =================================================================
// GRUP RUTE UNTUK MEKANIK
// =================================================================
Route::middleware(['auth', 'role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function() {
    // Dashboard Mekanik (Menampilkan Antrean Kerja)
    Route::get('/dashboard', function () {
        // Logika untuk menampilkan antrean kerja
        return "<h1>Dashboard Mekanik</h1>"; // Placeholder
    })->name('dashboard');

    // Melihat Detail & Mengupdate Pekerjaan
    // Route::get('/jobs/{transaction}', [MechanicJobController::class, 'show'])->name('jobs.show');
    // Route::post('/jobs/{transaction}/update-status', [MechanicJobController::class, 'updateStatus'])->name('jobs.updateStatus');
});


// =================================================================
// GRUP RUTE UNTUK PELANGGAN (CUSTOMER)
// =================================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function() {
    // Dashboard Pelanggan
    Route::get('/dashboard', function () {
        // Logika untuk menampilkan ringkasan data pelanggan
        return "<h1>Dashboard Pelanggan</h1>"; // Placeholder
    })->name('dashboard');
    
    // Melihat Riwayat Transaksi
    // Route::get('/transactions', [CustomerTransactionController::class, 'index'])->name('transactions.index');
    // Route::get('/transactions/{transaction}', [CustomerTransactionController::class, 'show'])->name('transactions.show');

    // Manajemen Kendaraan Milik Pelanggan
    // Route::resource('vehicles', CustomerVehicleController::class);
});


// Rute otentikasi yang dibuat oleh Laravel Breeze
require __DIR__.'/auth.php';