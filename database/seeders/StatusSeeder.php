<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Import Schema facade

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Matikan pengecekan foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel
        DB::table('service_statuses')->truncate();
        DB::table('payment_statuses')->truncate();

        // 3. Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();

        // 4. Isi data baru
        DB::table('service_statuses')->insert([
            ['code' => 'booking', 'label' => 'Booking Dikonfirmasi'],
            ['code' => 'waiting', 'label' => 'Menunggu'],
            ['code' => 'in_progress', 'label' => 'Sedang Dikerjakan'],
            ['code' => 'done', 'label' => 'Selesai'],
            ['code' => 'cancelled', 'label' => 'Dibatalkan'],
        ]);

        DB::table('payment_statuses')->insert([
            ['code' => 'paid', 'label' => 'Lunas'],
            ['code' => 'down_payment', 'label' => 'DP'],
            ['code' => 'unpaid', 'label' => 'Belum Bayar'],
        ]);
    }
}