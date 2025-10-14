<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; // Tambahkan SoftDeletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // Tambahkan cast untuk is_active
        ];
    }

    // =================================================================
    // RELASI ELOQUENT
    // =================================================================

    /**
     * Mendapatkan profil mekanik yang dimiliki oleh user.
     */
    public function mechanicProfile(): HasOne
    {
        // User ini memiliki satu profil mekanik
        return $this->hasOne(MechanicProfile::class, 'user_id');
    }

    /**
     * Mendapatkan semua kendaraan yang dimiliki oleh user (jika dia customer).
     */
    public function vehicles(): HasMany
    {
        // User ini memiliki banyak kendaraan
        return $this->hasMany(Vehicle::class, 'user_id');
    }
    
    /**
     * Mendapatkan semua transaksi yang dibuat oleh user (jika dia admin/customer).
     */
    public function transactionsCreated(): HasMany
    {
        // User ini memiliki banyak transaksi (sebagai pembuat)
        return $this->hasMany(Transaction::class, 'created_by');
    }

    /**
     * Mendapatkan semua transaksi milik user (jika dia customer).
     */
    public function customerTransactions(): HasMany
    {
        // User ini memiliki banyak transaksi (sebagai pelanggan)
        return $this->hasMany(Transaction::class, 'customer_id');
    }
    
    /**
     * Mendapatkan semua transaksi yang ditugaskan kepada user (jika dia mekanik).
     */
    public function assignedTransactions(): HasMany
    {
        // User ini memiliki banyak transaksi (sebagai mekanik yang ditugaskan)
        return $this->hasMany(Transaction::class, 'mechanic_id');
    }
}