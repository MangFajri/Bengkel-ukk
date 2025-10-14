<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $table = 'payment_statuses';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'label',
    ];

    /**
     * Mendapatkan semua transaksi yang memiliki status ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payment_status_id');
    }
}