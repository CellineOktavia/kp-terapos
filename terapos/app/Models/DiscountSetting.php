<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountSetting extends Model
{
    protected $fillable = [
        'is_active',
        'minimum_purchase',
        'percentage',
        'condition_operator',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'minimum_purchase' => 'integer',
            'percentage' => 'decimal:2',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'is_active' => true,
                'minimum_purchase' => 300000,
                'percentage' => 5,
                'condition_operator' => 'gt',
            ]
        );
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
