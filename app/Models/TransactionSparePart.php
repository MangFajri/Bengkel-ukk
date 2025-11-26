<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionSparePart extends Model
{
    use HasFactory;

    // Tabel ini tidak punya created_at/updated_at
    public $timestamps = false; 

    protected $fillable = [
        'transaction_id',
        'service_id',
        'price_at_time',
        'qty',
        'notes'
    ];

    /**
     * Relasi balik ke Transaksi Utama
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Relasi ke Master Data Service (untuk ambil nama service)
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}