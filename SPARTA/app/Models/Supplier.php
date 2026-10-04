<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'nama_kontak',
        'telepon',
        'email',
        'alamat',
        'aktif',
    ];

    public function products()
    {
        return $this->hasMany(
            Product::class,
            'supplier_id'
        );
    }

    public function fakturs()
    {
        return $this->hasMany(Faktur::class);
    }
}
