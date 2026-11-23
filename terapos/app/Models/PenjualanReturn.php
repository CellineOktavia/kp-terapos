<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanReturn extends Model
{
    protected $fillable = [
        'penjualan_id',
        'user_id',
        'request_token',
        'reason',
        'refund_amount',
    ];

    protected function casts(): array
    {
        return ['refund_amount' => 'decimal:2'];
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailPenjualanReturn::class);
    }
}
