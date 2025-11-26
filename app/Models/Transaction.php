<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'mechanic_id',
        'created_by',
        'promo_id',
        'payment_method_id', // Pastikan kolom ini ada di fillable
        'service_status_id',
        'payment_status_id',
        'check_in_at',
        'check_out_at',
        'notes',
        'total_amount',
        'amount_paid',
    ];

    // =================================================================
    // RELASI KE PARENT (BelongsTo)
    // =================================================================

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function serviceStatus(): BelongsTo
    {
        return $this->belongsTo(ServiceStatus::class, 'service_status_id');
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    /**
     * Relasi Payment Method (INI YANG TADI ERROR)
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // =================================================================
    // RELASI KE CHILD (HasMany)
    // =================================================================

    public function services(): HasMany
    {
        return $this->hasMany(TransactionService::class, 'transaction_id');
    }

    public function spareParts(): HasMany
    {
        return $this->hasMany(TransactionSparePart::class, 'transaction_id');
    }
}