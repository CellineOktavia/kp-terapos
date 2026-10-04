<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [

        'product_id',
        'jenis',
        'qty',
        'keterangan'

    ];

    public function product()
    {
        return $this->belongsTo(
            Product::class
        );
    }
}
