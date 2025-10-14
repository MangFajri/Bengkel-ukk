<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'metadata',
    ];

    /**
     * Atribut yang harus di-cast.
     * 'metadata' di-cast sebagai array/object, membuatnya mudah dimanipulasi di Laravel.
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan user yang melakukan aktivitas ini (jika tercatat).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}