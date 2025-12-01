<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Seeder (Pastikan ini ada user admin, mekanik, customer defaultmu)
        // $this->call(UserSeeder::class); 
        
        // Data Master Aplikasi Bengkel
        $this->call([
            StatusSeeder::class,         // Status Service (Pending, Proses, dll)
            PaymentMethodSeeder::class,  // Metode Pembayaran
            ServiceSeeder::class,        // Jasa Service (BARU)
            SparePartSeeder::class,      // Sparepart (BARU)
        ]);
    }
}