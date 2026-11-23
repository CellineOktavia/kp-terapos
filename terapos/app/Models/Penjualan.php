<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'nomor_penjualan',
        'request_token',
        'user_id',
        'total',
        'diskon_persen',
        'diskon_nominal',
        'diskon_aktif',
        'diskon_minimum_pembelian',
        'diskon_operator',
        'status',
        'cross_subsidy_amount',
        'tanggal',
        'bayar',
        'kembalian',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'diskon_persen' => 'decimal:2',
            'diskon_nominal' => 'decimal:2',
            'diskon_aktif' => 'boolean',
            'diskon_minimum_pembelian' => 'integer',
            'cross_subsidy_amount' => 'decimal:2',
            'bayar' => 'decimal:2',
            'kembalian' => 'decimal:2',

            // tanggal transaksi bisnis
            'tanggal' => 'date',

            // created_at tetap otomatis menjadi Carbon
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class);
    }

    public function returns()
    {
        return $this->hasMany(PenjualanReturn::class);
    }

    public function returnedDetails()
    {
        return $this->hasManyThrough(
            DetailPenjualanReturn::class,
            PenjualanReturn::class,
            'penjualan_id',
            'penjualan_return_id'
        );
    }
}
