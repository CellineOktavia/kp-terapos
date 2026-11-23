<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualanReturn extends Model
{
    protected $fillable = [
        'penjualan_return_id',
        'detail_penjualan_id',
        'qty',
        'refund_amount',
    ];

    protected function casts(): array
    {
        return ['refund_amount' => 'decimal:2'];
    }

    public function penjualanReturn()
    {
        return $this->belongsTo(PenjualanReturn::class);
    }

    public function detailPenjualan()
    {
        return $this->belongsTo(DetailPenjualan::class);
    }
}
