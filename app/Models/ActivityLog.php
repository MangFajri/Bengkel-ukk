<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'metadata' => 'array', // Biar kolom JSON otomatis jadi array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper sakti untuk mencatat log dengan satu baris kode.
     * Cara pakai: ActivityLog::record('Menghapus Transaksi #10', ['trx_id' => 10]);
     */
    public static function record($action, $metadata = [])
    {
        self::create([
            'user_id' => Auth::id(), // Siapa pelakunya? (Otomatis user yang login)
            'action' => $action,     // Apa yang dia lakukan?
            'metadata' => $metadata, // Data tambahan (opsional)
        ]);
    }
}
