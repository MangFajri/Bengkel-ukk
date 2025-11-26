<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionSparePart extends Model
{
    use HasFactory;

    protected $table = 'transaction_spare_parts';

    // --- INI YANG KURANG SEBELUMNYA ---
    protected $fillable = [
        'transaction_id',
        'spare_part_id',
        'qty',
        'price_at_time',
    ];
    // ----------------------------------

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class);
    }
}