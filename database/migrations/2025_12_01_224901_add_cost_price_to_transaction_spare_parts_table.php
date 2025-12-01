<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaction_spare_parts', function (Blueprint $table) {
            // Kita taruh setelah price_at_time (Harga Jual) biar rapi
            $table->decimal('cost_price_at_time', 12, 2)->default(0)->after('price_at_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('transaction_spare_parts', function (Blueprint $table) {
            $table->dropColumn('cost_price_at_time');
        });
    }
};
