<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaanBarang extends Model
{
    protected $fillable = [
        'nomor_penerimaan',
        'user_id',
        'tanggal',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPenerimaanBarangs()
    {
        return $this->hasMany(DetailPenerimaanBarang::class);
    }
}
