<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionService extends Model
{
    use HasFactory;

    protected $table = 'transaction_services';
    public $timestamps = false;

    // --- INI WAJIB ADA AGAR BISA DISIMPAN ---
    protected $fillable = [
        'transaction_id',
        'service_id',
        'qty', // Walaupun default 1, tetap masukkan saja
        'price_at_time',
    ];
    // ----------------------------------------

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id')->withTrashed();
    }
}