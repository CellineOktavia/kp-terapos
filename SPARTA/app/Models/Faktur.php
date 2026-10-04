<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Supplier;
use App\Models\DetailFaktur;

class Faktur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_faktur',
        'user_id',
        'supplier_id',
        'total',
        'tanggal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function detailFakturs()
    {
        return $this->hasMany(DetailFaktur::class);
    }
}
