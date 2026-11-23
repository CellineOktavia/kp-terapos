<?php

namespace App\Services;

use App\Models\DiscountSetting;

class DiscountCalculator
{
    public function calculate(float $subtotal, DiscountSetting $setting): array
    {
        $eligible = $setting->is_active && match ($setting->condition_operator) {
            'gt' => $subtotal > $setting->minimum_purchase,
            'gte' => $subtotal >= $setting->minimum_purchase,
            default => false,
        };
        $percentage = $eligible ? (float) $setting->percentage : 0.0;
        $discount = round($subtotal * $percentage / 100, 2);

        return [
            'eligible' => $eligible,
            'percentage' => $percentage,
            'discount' => $discount,
            'total' => round($subtotal - $discount, 2),
        ];
    }
}
