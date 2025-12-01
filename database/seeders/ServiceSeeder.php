<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama (biar gak duplikat kalau dijalankan ulang)
        // Nonaktifkan foreign key check sementara biar bisa truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('services')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $services = [
            [
                'code' => 'SRV-001',
                'name' => 'Ganti Oli Ringan',
                'description' => 'Jasa penggantian oli mesin untuk mobil kecil (LCGC/City Car) < 1500cc.',
                'price' => 50000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SRV-002',
                'name' => 'Ganti Oli Berat (SUV/MPV)',
                'description' => 'Jasa ganti oli untuk mobil besar > 1500cc atau Diesel.',
                'price' => 75000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SRV-003',
                'name' => 'Tune Up Standar',
                'description' => 'Pembersihan filter udara, busi, throttle body, dan pengecekan umum.',
                'price' => 250000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SRV-004',
                'name' => 'Service Rem 4 Roda',
                'description' => 'Pembersihan kampas rem, cek piringan, dan minyak rem.',
                'price' => 200000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SRV-005',
                'name' => 'Spooring & Balancing',
                'description' => 'Penyetelan kaki-kaki agar setir lurus dan ban awet.',
                'price' => 350000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SRV-006',
                'name' => 'Service AC Ringan',
                'description' => 'Cuci evaporator, kondensor, dan tambah freon.',
                'price' => 400000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SRV-007',
                'name' => 'Overhaul Mesin (Turun Mesin)',
                'description' => 'Perbaikan total bagian dalam mesin. Harga Jasa Dasar.',
                'price' => 2500000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SRV-008',
                'name' => 'Cuci Mobil + Vacuum',
                'description' => 'Cuci body luar dan vacuum interior sampai bersih.',
                'price' => 50000,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('services')->insert($services);
    }
}