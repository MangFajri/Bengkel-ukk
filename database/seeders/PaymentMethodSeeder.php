<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel dulu biar gak duplikat kalau di-seed ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_methods')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $methods = [
            [
                'id' => 1,
                'name' => 'Tunai (Cash)',
                'details' => 'Pembayaran langsung di kasir.',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Transfer Bank (BCA)',
                'details' => 'No. Rek: 123-456-7890 a.n Bengkel Pro',
                'created_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Transfer Bank (Mandiri)',
                'details' => 'No. Rek: 098-765-4321 a.n Bengkel Pro',
                'created_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'QRIS / E-Wallet',
                'details' => 'Scan barcode di kasir (GoPay, OVO, Dana, LinkAja).',
                'created_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Kartu Debit / Kredit',
                'details' => 'Mesin EDC (BCA/Mandiri).',
                'created_at' => now(),
            ],
        ];

        DB::table('payment_methods')->insert($methods);
    }
}