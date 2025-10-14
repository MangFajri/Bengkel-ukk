<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'services';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'is_active',
    ];

    /**
     * Tipe data asli dari atribut.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan semua paket layanan yang menyertakan jasa ini.
     */
    public function servicePackages(): BelongsToMany
    {
        // Jasa ini bisa ada di banyak ServicePackage, melalui tabel pivot 'package_services'
        return $this->belongsToMany(ServicePackage::class, 'package_services', 'service_id', 'package_id')
                    ->withPivot('qty') // Jika kita ingin mengambil jumlah qty dari tabel pivot
                    ->withTimestamps();
    }

    /**
     * Mendapatkan semua detail transaksi yang menggunakan jasa ini.
     */
    public function transactionServices(): HasMany
    {
        // Jasa ini bisa ada di banyak TransactionService
        return $this->hasMany(TransactionService::class, 'service_id');
    }
}