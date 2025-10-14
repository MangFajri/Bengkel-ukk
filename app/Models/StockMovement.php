<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    // Tabel ini hanya punya created_at, jadi kita nonaktifkan updated_at
    const UPDATED_AT = null;

    protected $fillable = [
        'spare_part_id',
        'transaction_id',
        'change_qty',
        'note',
    ];

    protected $casts = [
        'change_qty' => 'integer',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan sparepart yang stoknya bergerak.
     */
    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }

    /**
     * Mendapatkan transaksi yang menyebabkan pergerakan stok ini (jika ada).
     */
    public function transaction(): BelongsTo
    {
        // Relasi ini bisa null, karena stok bisa bertambah dari pembelian, bukan transaksi penjualan
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}