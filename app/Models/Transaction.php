<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Lebih fleksibel daripada fillable, aman selama controller validasi.

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =================================================================
    // 1. RELASI KE PARENT (BelongsTo)
    // =================================================================

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id')->withTrashed();
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id')->withTrashed();
    }

    public function serviceStatus(): BelongsTo
    {
        return $this->belongsTo(ServiceStatus::class, 'service_status_id');
    }

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // Relasi ke User pembuat transaksi (Admin/Kasir) untuk Nota
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    // =================================================================
    // 2. RELASI KE CHILD (BelongsToMany - BIAR FLEKSIBEL)
    // =================================================================

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'transaction_services', 'transaction_id', 'service_id')
                    ->withPivot('id', 'qty', 'price_at_time');
    }

    // UBAHAN PENTING: Pakai belongsToMany biar gampang ambil nama sparepart di View
    public function spareParts(): BelongsToMany
    {
        return $this->belongsToMany(SparePart::class, 'transaction_spare_parts', 'transaction_id', 'spare_part_id')
                    ->withPivot('id', 'qty', 'price_at_time', 'cost_price_at_time');
    }

    // =================================================================
    // 3. HELPER TAMBAHAN (ACCESSORS) - BIAR KODING VIEW BERSIH
    // =================================================================

    // Panggil di view: {{ $transaction->formatted_total }}
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    // Panggil di view: <span class="badge badge-{{ $transaction->status_color }}">
    public function getStatusColorAttribute()
    {
        return match($this->service_status_id) {
            1 => 'warning',   // Pending -> Kuning
            2 => 'primary',   // Pengerjaan -> Biru
            3 => 'info',      // Menunggu Bayar -> Biru Muda
            4 => 'success',   // Selesai -> Hijau
            5 => 'danger',    // Batal -> Merah
            default => 'secondary'
        };
    }
}