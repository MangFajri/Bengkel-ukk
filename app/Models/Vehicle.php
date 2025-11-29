<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vehicles';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'plate_number',    // Sesuai dengan kolom di DB (bukan license_plate)
        'engine_number',   // <-- Tambahan Baru
        'chassis_number',  // <-- Tambahan Baru
        'brand',
        'model',
        'year',
        'color',           // <-- Tambahan Baru
    ];

    /**
     * Tipe data asli dari atribut.
     */
    protected $casts = [
        'year' => 'integer',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan user (customer) yang memiliki kendaraan ini.
     */
    public function user(): BelongsTo
    {
        // Kendaraan ini dimiliki oleh satu User
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendapatkan semua riwayat transaksi untuk kendaraan ini.
     */
    public function transactions(): HasMany
    {
        // Kendaraan ini bisa memiliki banyak transaksi
        return $this->hasMany(Transaction::class, 'vehicle_id');
    }
}