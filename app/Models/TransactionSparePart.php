<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionSparePart extends Model
{
    use HasFactory;

    protected $table = 'transaction_spare_parts';

    public $timestamps = false;

    protected $fillable = [
        'transaction_id',
        'spare_part_id',
        'qty',
        'price_at_time',
        'serial_number',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price_at_time' => 'decimal:2',
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
     * Mendapatkan data master sparepart dari detail ini.
     */
    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }
}