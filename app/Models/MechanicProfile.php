<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MechanicProfile extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit (best practice)
    protected $table = 'mechanic_profiles';

    // Laravel secara default menganggap ada kolom created_at & updated_at.
    // Karena di skema kita ada, kita tidak perlu menonaktifkannya.

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'nik',
        'certifications',
        'joined_date',
    ];

    /**
     * Tipe data asli dari atribut.
     */
    protected $casts = [
        'joined_date' => 'date',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan user yang memiliki profil mekanik ini.
     */
    public function user(): BelongsTo
    {
        // Profil ini dimiliki oleh satu User
        return $this->belongsTo(User::class, 'user_id');
    }
}