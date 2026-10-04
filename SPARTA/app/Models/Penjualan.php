<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [

        'nomor_penjualan',
        'customer_id',
        'user_id',
        'total',
        'tanggal'

    ];

    public function customer()
    {
        return $this->belongsTo(
            Customer::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function detailPenjualans()
    {
        return $this->hasMany(
            DetailPenjualan::class
        );
    }
}
