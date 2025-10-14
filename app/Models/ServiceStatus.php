<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceStatus extends Model
{
    use HasFactory;

    protected $table = 'service_statuses';

    // Matikan timestamps (created_at, updated_at) karena tabel ini tidak memilikinya.
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
        return $this->hasMany(Transaction::class, 'service_status_id');
    }
}