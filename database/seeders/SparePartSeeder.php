<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SparePartSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel dulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('spare_parts')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $parts = [
            // KATEGORI OLI
            [
                'sku' => 'OLI-SHELL-5W30', // Tambah SKU (Wajib)
                'name' => 'Oli Shell Helix 5W-30 (Galon)',
                'brand' => 'Shell', // Tambah Brand (Optional tapi bagus)
                'stock' => 50,
                'cost_price' => 350000, // Ganti 'price' jadi 'cost_price'
                'sell_price' => 450000,
                'created_at' => now(),
            ],
            [
                'sku' => 'OLI-PERTAMINA-GOLD',
                'name' => 'Oli Pertamina Fastron Gold',
                'brand' => 'Pertamina',
                'stock' => 24,
                'cost_price' => 90000,
                'sell_price' => 120000,
                'created_at' => now(),
            ],
            
            // KATEGORI FILTER
            [
                'sku' => 'FLT-AVANZA',
                'name' => 'Filter Oli Avanza/Xenia',
                'brand' => 'Toyota Genuine Parts',
                'stock' => 100, 
                'cost_price' => 25000,
                'sell_price' => 35000,
                'created_at' => now(),
            ],
            [
                'sku' => 'FLT-INNOVA',
                'name' => 'Filter Udara Innova Reborn',
                'brand' => 'Denso',
                'stock' => 15, 
                'cost_price' => 110000,
                'sell_price' => 150000,
                'created_at' => now(),
            ],

            // KATEGORI PENGEREMAN
            [
                'sku' => 'BRK-JAZZ-F',
                'name' => 'Kampas Rem Depan Honda Jazz',
                'brand' => 'Nissin',
                'stock' => 8,
                'cost_price' => 350000,
                'sell_price' => 450000,
                'created_at' => now(),
            ],
            [
                'sku' => 'FLUID-PRESTONE',
                'name' => 'Minyak Rem Prestone (Merah)',
                'brand' => 'Prestone',
                'stock' => 30,
                'cost_price' => 35000,
                'sell_price' => 50000,
                'created_at' => now(),
            ],

            // KATEGORI MESIN
            [
                'sku' => 'SPK-NGK-IR',
                'name' => 'Busi Iridium NGK (Set 4pcs)',
                'brand' => 'NGK',
                'stock' => 5,
                'cost_price' => 450000,
                'sell_price' => 600000,
                'created_at' => now(),
            ],
            [
                'sku' => 'AKI-GS-MF',
                'name' => 'Aki GS Astra MF (Kering)',
                'brand' => 'GS Astra',
                'stock' => 3,
                'cost_price' => 950000,
                'sell_price' => 1200000,
                'created_at' => now(),
            ],
            
            // KATEGORI BAN
            [
                'sku' => 'TYRE-BS-185',
                'name' => 'Ban Bridgestone Ecopia 185/70 R14',
                'brand' => 'Bridgestone',
                'stock' => 4,
                'cost_price' => 650000,
                'sell_price' => 850000,
                'created_at' => now(),
            ]
        ];

        foreach($parts as $part) {
             DB::table('spare_parts')->insert($part);
        }
    }
}