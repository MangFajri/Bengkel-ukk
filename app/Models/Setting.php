<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    // Kita beri tahu Laravel bahwa Primary Key-nya bukan 'id' dan bukan auto-increment.
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    // Hanya ada updated_at
    const CREATED_AT = null;

    protected $fillable = [
        'key',
        'value',
        'label',
    ];
}