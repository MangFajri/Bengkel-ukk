<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    // Tabel ini hanya punya created_at, jadi kita nonaktifkan updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'name',
        'details',
    ];

    /**
     * Mendapatkan semua transaksi yang menggunakan metode pembayaran ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payment_method_id');
    }
}