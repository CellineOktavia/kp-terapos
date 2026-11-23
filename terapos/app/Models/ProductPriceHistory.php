<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceHistory extends Model
{
    protected $fillable = [
        'product_id',
        'jenis_harga',
        'harga_lama',
        'harga_baru',
        'reference_type',
        'reference_id',
        'user_id',
        'keterangan',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
