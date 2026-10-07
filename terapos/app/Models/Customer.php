<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    protected $fillable = [

        'kode_customer',
        'nama_customer',
        'telepon',
        'email',
        'alamat',
        'aktif',

    ];

    public function penjualans()
    {
        return $this->hasMany(
            Penjualan::class
        );
    }
}
