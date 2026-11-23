<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $fillable = [

        'penjualan_id',
        'product_id',
        'qty',
        'harga',
        'subtotal',
        'discount_share',
        'cross_subsidy_per_unit',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'discount_share' => 'decimal:2',
            'cross_subsidy_per_unit' => 'decimal:2',
        ];
    }

    public function penjualan()
    {
        return $this->belongsTo(
            Penjualan::class
        );
    }

    public function product()
    {
        return $this->belongsTo(
            Product::class
        );
    }

    public function returns()
    {
        return $this->hasMany(DetailPenjualanReturn::class);
    }

    public function crossSubsidyEntries()
    {
        return $this->hasMany(CrossSubsidyLedgerEntry::class);
    }
}
