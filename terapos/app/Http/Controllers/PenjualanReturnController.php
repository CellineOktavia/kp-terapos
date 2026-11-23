<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Services\PenjualanReturnService;
use Illuminate\Http\Request;

class PenjualanReturnController extends Controller
{
    public function store(Request $request, Penjualan $penjualan, PenjualanReturnService $returnService)
    {
        $validated = $request->validate([
            'request_token' => ['required', 'uuid'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.detail_penjualan_id' => ['required', 'integer', 'distinct', 'exists:detail_penjualans,id'],
            'items.*.qty' => ['required', 'integer', 'min:0'],
        ]);
        $items = array_values(array_filter(
            $validated['items'],
            fn (array $item) => (int) $item['qty'] > 0
        ));

        $return = $returnService->process(
            $penjualan,
            $validated['request_token'],
            (int) $request->user()->id,
            $items
        );

        return redirect()->route('penjualan.show', $penjualan)
            ->with('success', 'Retur tercatat. Dana yang perlu dikembalikan: Rp '
                . number_format((float) $return->refund_amount, 0, ',', '.') . '.');
    }

    public function cancel(Request $request, Penjualan $penjualan, PenjualanReturnService $returnService)
    {
        $validated = $request->validate([
            'request_token' => ['required', 'uuid'],
        ]);

        $return = $returnService->process(
            $penjualan,
            $validated['request_token'],
            (int) $request->user()->id,
            cancel: true
        );

        return redirect()->route('penjualan.show', $penjualan)
            ->with('success', 'Penjualan dibatalkan. Dana yang perlu dikembalikan: Rp '
                . number_format((float) $return->refund_amount, 0, ',', '.') . '.');
    }
}
