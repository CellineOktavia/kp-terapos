<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrossSubsidyLedgerEntry extends Model
{
    protected $fillable = [
        'program_id',
        'product_id',
        'penjualan_id',
        'detail_penjualan_id',
        'penjualan_return_id',
        'reverses_entry_id',
        'user_id',
        'entry_type',
        'amount',
        'rate_snapshot',
        'unit_amount_snapshot',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'rate_snapshot' => 'decimal:2',
            'unit_amount_snapshot' => 'decimal:2',
        ];
    }

    public function program()
    {
        return $this->belongsTo(CrossSubsidyProgram::class, 'program_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function detailPenjualan()
    {
        return $this->belongsTo(DetailPenjualan::class);
    }

    public function penjualanReturn()
    {
        return $this->belongsTo(PenjualanReturn::class);
    }

    public function reversesEntry()
    {
        return $this->belongsTo(self::class, 'reverses_entry_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
