<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenerimaanBarang extends Model
{
    protected $fillable = [
        'penerimaan_barang_id',
        'product_id',
        'qty',
    ];

    public function penerimaanBarang()
    {
        return $this->belongsTo(PenerimaanBarang::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
