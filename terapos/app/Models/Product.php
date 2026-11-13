<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_produk',
        'barcode',
        'category_id',
        'nama_produk',
        'merk',
        'satuan',
        'stok',
        'stok_minimum',
        'harga_beli',
        'harga_jual',
        'deskripsi',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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

    public function detailPenerimaanBarangs()
    {
        return $this->hasMany(DetailPenerimaanBarang::class);
    }
}
