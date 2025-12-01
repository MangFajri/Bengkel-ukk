<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\Transaction; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // LOGIC NOTIFIKASI SIDEBAR ADMIN
        // Mengirim variabel $pendingPaymentCount ke file sidebar admin secara otomatis
        View::composer('layouts.partials.admin-sidebar', function ($view) {
            
            // Hitung transaksi yang:
            // 1. Punya bukti bayar (tidak null)
            // 2. Status pembayaran BELUM LUNAS (ID != 1)
            $pendingPaymentCount = Transaction::whereNotNull('payment_proof')
                ->where('payment_status_id', '!=', 1)
                ->count();

            $view->with('pendingPaymentCount', $pendingPaymentCount);
        });
    }
}