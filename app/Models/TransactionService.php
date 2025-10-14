<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionService extends Model
{
    use HasFactory;

    protected $table = 'transaction_services';

    // Tabel ini tidak memiliki timestamps (created_at, updated_at)
    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'service_id',
        'price_at_time',
        'qty',
        'notes',
    ];

    protected $casts = [
        'price_at_time' => 'decimal:2',
        'qty' => 'integer',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan transaksi induk dari detail ini.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Mendapatkan data master jasa dari detail ini.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}