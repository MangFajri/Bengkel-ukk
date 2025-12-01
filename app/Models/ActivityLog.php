<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Kita matikan timestamp otomatis (biar gak nyari updated_at)
    public $timestamps = false;

    // [FIX DISINI] Tambahkan 'created_at' => 'datetime'
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime', // <--- Wajib ada biar bisa di-format() di Blade
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($action, $metadata = [])
    {
        self::create([
            'user_id' => Auth::id() ?? null,
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }
}