<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrossSubsidyProgram extends Model
{
    protected $fillable = [
        'source_product_id',
        'recipient_product_id',
        'allocation_percentage',
        'subsidy_amount',
        'maximum_subsidy',
        'minimum_selling_price',
        'is_active',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'allocation_percentage' => 'decimal:2',
            'subsidy_amount' => 'decimal:2',
            'maximum_subsidy' => 'decimal:2',
            'minimum_selling_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
                'allocation_percentage' => 0,
                'subsidy_amount' => 0,
                'maximum_subsidy' => 0,
                'minimum_selling_price' => 0,
                'is_active' => false,
            ]
        );
    }

    public function sourceProduct()
    {
        return $this->belongsTo(Product::class, 'source_product_id');
    }

    public function recipientProduct()
    {
        return $this->belongsTo(Product::class, 'recipient_product_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(CrossSubsidyLedgerEntry::class, 'program_id');
    }
}
