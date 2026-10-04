<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [

        'kode_produk',
        'supplier_id',
        'nama_produk',
        'merk',
        'stok',
        'stok_minimum',
        'harga_beli',
        'harga_jual',
        'deskripsi',

    ];

    public function supplier()
    {
        return $this->belongsTo(
            Supplier::class
        );
    }

    public function detailFakturs()
    {
        return $this->hasMany(DetailFaktur::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(
            StockMovement::class
        );
    }

    public function detailPenjualans()
    {
        return $this->hasMany(
            DetailPenjualan::class
        );
    }
}
