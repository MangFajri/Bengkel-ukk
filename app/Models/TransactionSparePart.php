<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot; // Kita ganti Model jadi Pivot
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class TransactionSparePart extends Pivot
{
    use HasFactory;

    protected $table = 'transaction_spare_parts';
    
    // Set true jika tabel pivotmu punya created_at/updated_at
    // Jika di migration tidak ada $table->timestamps(), set ke false
    public $timestamps = false; 

    protected $fillable = [
        'transaction_id',
        'spare_part_id',
        'qty',
        'price_at_time',
    ];

    /**
     * =====================================================================
     * 🤖 THE ROBOT (BOOTED METHOD)
     * Logic otomatis kurangi stok & hitung total harga
     * =====================================================================
     */
    protected static function booted()
    {
        // 1. Saat Sparepart DITAMBAHKAN (Created)
        static::created(function ($item) {
            // A. Kurangi Stok Gudang
            $part = SparePart::find($item->spare_part_id);
            if ($part) {
                $part->decrement('stock', $item->qty);
            }

            // B. Hitung Ulang Total Transaksi
            self::recalculateTransactionTotal($item->transaction_id);
        });

        // 2. Saat Sparepart DIHAPUS (Deleted)
        static::deleted(function ($item) {
            // A. Balikin Stok ke Gudang (Refund)
            $part = SparePart::find($item->spare_part_id);
            if ($part) {
                $part->increment('stock', $item->qty);
            }

            // B. Hitung Ulang Total Transaksi
            self::recalculateTransactionTotal($item->transaction_id);
        });

        // 3. Saat Data DIUPDATE (Misal ganti jumlah qty)
        static::updated(function ($item) {
            // Kita paksa hitung ulang total duitnya saja biar aman
            self::recalculateTransactionTotal($item->transaction_id);
        });
    }

    /**
     * Fungsi Rahasia: Kalkulator Otomatis Total Harga
     */
    private static function recalculateTransactionTotal($trxId)
    {
        $trx = Transaction::find($trxId);
        if (!$trx) return;

        // Hitung Total Jasa (Services)
        $serviceTotal = DB::table('transaction_services')
                        ->where('transaction_id', $trxId)
                        ->sum(DB::raw('price_at_time * qty'));

        // Hitung Total Barang (Spareparts)
        $partTotal = DB::table('transaction_spare_parts')
                        ->where('transaction_id', $trxId)
                        ->sum(DB::raw('price_at_time * qty'));

        // Update Angka Total di Tabel Transaksi
        $trx->update(['total_amount' => $serviceTotal + $partTotal]);
    }

    // =====================================================================
    // RELATIONSHIPS
    // =====================================================================

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function sparePart(): BelongsTo
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id')->withTrashed();
    }
}