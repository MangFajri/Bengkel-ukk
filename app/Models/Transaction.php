<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'created_by',
        'mechanic_id',
        'promo_id',
        'payment_method_id',
        'service_status_id',
        'payment_status_id',
        'check_in_at',
        'check_out_at',
        'notes',
        'total_amount',
        'amount_paid',
    ];

    /**
     * Tipe data asli dari atribut.
     */
    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan user (customer) dari transaksi ini.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Mendapatkan kendaraan dari transaksi ini.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Mendapatkan user (admin/customer) yang membuat transaksi ini.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Mendapatkan user (mekanik) yang ditugaskan pada transaksi ini.
     */
    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    /**
     * Mendapatkan promo yang digunakan pada transaksi ini.
     */
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class, 'promo_id');
    }

    /**
     * Mendapatkan metode pembayaran dari transaksi ini.
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * Mendapatkan status servis dari transaksi ini.
     */
    public function serviceStatus(): BelongsTo
    {
        return $this->belongsTo(ServiceStatus::class, 'service_status_id');
    }

    /**
     * Mendapatkan status pembayaran dari transaksi ini.
     */
    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }
    
    /**
     * Mendapatkan semua detail jasa yang ada pada transaksi ini.
     */
    public function transactionServices(): HasMany
    {
        return $this->hasMany(TransactionService::class, 'transaction_id');
    }

    /**
     * Mendapatkan semua detail sparepart yang ada pada transaksi ini.
     */
    public function transactionSpareParts(): HasMany
    {
        return $this->hasMany(TransactionSparePart::class, 'transaction_id');
    }
    
    /**
     * Mendapatkan semua pergerakan stok yang terkait dengan transaksi ini.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'transaction_id');
    }

    // =================================================================
    // LOGIKA BISNIS
    // =================================================================
    
    /**
     * Menghitung ulang total_amount berdasarkan detail jasa dan sparepart.
     */
    public function recalculateTotalAmount(): void
    {
        // Muat ulang relasi untuk memastikan data yang dihitung adalah yang terbaru
        $this->load(['transactionServices', 'transactionSpareParts']);

        // Hitung total dari semua detail jasa
        $totalServices = $this->transactionServices->sum('price_at_time');

        // Hitung total dari semua detail sparepart (harga * jumlah)
        $totalSpareParts = $this->transactionSpareParts->sum(function ($detail) {
            return $detail->price_at_time * $detail->qty;
        });

        // Jumlahkan keduanya, update properti total_amount, dan simpan ke database
        $this->total_amount = $totalServices + $totalSpareParts;
        $this->save();
    }
} 