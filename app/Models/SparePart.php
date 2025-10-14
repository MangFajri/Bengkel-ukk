<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePart extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spare_parts';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'sku',
        'name',
        'brand',
        'cost_price',
        'sell_price',
        'stock',
        'is_active',
    ];

    /**
     * Tipe data asli dari atribut.
     */
    protected $casts = [
        'cost_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan semua paket layanan yang menyertakan sparepart ini.
     */
    public function servicePackages(): BelongsToMany
    {
        // Sparepart ini bisa ada di banyak ServicePackage, melalui tabel pivot 'package_spare_parts'
        return $this->belongsToMany(ServicePackage::class, 'package_spare_parts', 'spare_part_id', 'package_id')
                    ->withPivot('qty')
                    ->withTimestamps();
    }

    /**
     * Mendapatkan semua detail transaksi yang menggunakan sparepart ini.
     */
    public function transactionSpareParts(): HasMany
    {
        return $this->hasMany(TransactionSparePart::class, 'spare_part_id');
    }

    /**
     * Mendapatkan semua riwayat pergerakan stok untuk sparepart ini.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'spare_part_id');
    }
}