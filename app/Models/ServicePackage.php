<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'service_packages';

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan semua jasa yang termasuk dalam paket ini.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'package_services', 'package_id', 'service_id')
                    ->withPivot('qty');
    }

    /**
     * Mendapatkan semua sparepart yang termasuk dalam paket ini.
     */
    public function spareParts(): BelongsToMany
    {
        return $this->belongsToMany(SparePart::class, 'package_spare_parts', 'package_id', 'spare_part_id')
                    ->withPivot('qty');
    }
}