<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $fillable = [
        'nomor_penjualan',
        'user_id',
        'total',
        'diskon_persen',
        'diskon_nominal',
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
}
