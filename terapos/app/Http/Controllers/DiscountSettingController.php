<?php

namespace App\Http\Controllers;

use App\Models\DiscountSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DiscountSettingController extends Controller
{
    public function edit()
    {
        $setting = DiscountSetting::current()->load('updatedBy');

        return view('diskon.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        if (is_string($request->input('minimum_purchase'))) {
            $minimumPurchase = trim($request->input('minimum_purchase'));
            if (preg_match('/^-?\d{1,3}(?:\.\d{3})+$/', $minimumPurchase)) {
                $minimumPurchase = str_replace('.', '', $minimumPurchase);
            }

            $request->merge([
                'minimum_purchase' => $minimumPurchase,
            ]);
        }

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
            'condition_operator' => ['required', Rule::in(['gt', 'gte'])],
            'minimum_purchase' => ['required', 'integer', 'min:0'],
            'percentage' => ['required', 'numeric', 'gt:0', 'max:100'],
        ]);

        DB::transaction(function () use ($validated): void {
            $setting = DiscountSetting::query()->whereKey(1)->lockForUpdate()->firstOrFail();
            $setting->update($validated + ['updated_by' => Auth::id()]);
        });

        return redirect()->route('diskon.edit')->with('success', 'Pengaturan diskon berhasil disimpan.');
    }
}
