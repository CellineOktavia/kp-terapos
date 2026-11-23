<?php

namespace App\Http\Controllers;

use App\Models\CrossSubsidyLedgerEntry;
use App\Models\CrossSubsidyProgram;
use App\Models\Product;
use App\Services\CrossSubsidyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CrossSubsidyController extends Controller
{
    public function edit(CrossSubsidyService $subsidyService)
    {
        $program = CrossSubsidyProgram::current()->load(['sourceProduct', 'recipientProduct', 'updatedBy']);
        $products = Product::query()
            ->where('aktif', true)
            ->orderBy('nama_produk')
            ->get(['id', 'kode_produk', 'nama_produk', 'harga_jual']);

        $collected = (float) CrossSubsidyLedgerEntry::query()
            ->where('program_id', $program->id)
            ->where('entry_type', 'contribution')
            ->sum('amount');
        $used = (float) CrossSubsidyLedgerEntry::query()
            ->where('program_id', $program->id)
            ->where('entry_type', 'subsidy_use')
            ->sum(DB::raw('ABS(amount)'));
        $returnedSubsidy = (float) CrossSubsidyLedgerEntry::query()
            ->where('program_id', $program->id)
            ->where('entry_type', 'subsidy_return')
            ->sum('amount');
        $entries = CrossSubsidyLedgerEntry::query()
            ->with(['product', 'penjualan', 'penjualanReturn', 'user'])
            ->where('program_id', $program->id)
            ->latest('id')
            ->paginate(25)
            ->withQueryString();
        $balance = $subsidyService->balance($program);

        return view('subsidi-silang.edit', compact(
            'program',
            'products',
            'collected',
            'used',
            'returnedSubsidy',
            'balance',
            'entries'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
            'source_product_id' => ['nullable', 'required_if:is_active,1', 'integer', 'exists:products,id'],
            'recipient_product_id' => ['nullable', 'required_if:is_active,1', 'integer', 'exists:products,id'],
            'allocation_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'subsidy_amount' => ['required', 'numeric', 'min:0'],
            'maximum_subsidy' => ['required', 'numeric', 'min:0'],
            'minimum_selling_price' => ['required', 'numeric', 'min:0'],
        ]);

        $isActive = (bool) $validated['is_active'];
        if ($isActive) {
            if ((float) $validated['allocation_percentage'] <= 0
                || (float) $validated['subsidy_amount'] <= 0
                || (float) $validated['maximum_subsidy'] <= 0) {
                throw ValidationException::withMessages([
                    'is_active' => 'Persentase margin, nominal subsidi, dan batas maksimal harus lebih dari nol saat program aktif.',
                ]);
            }

            if ((int) $validated['source_product_id'] === (int) $validated['recipient_product_id']) {
                throw ValidationException::withMessages([
                    'recipient_product_id' => 'Produk penerima subsidi harus berbeda dari produk pemberi subsidi.',
                ]);
            }

            $activeProducts = Product::query()
                ->whereIn('id', [$validated['source_product_id'], $validated['recipient_product_id']])
                ->where('aktif', true)
                ->count();
            if ($activeProducts !== 2) {
                throw ValidationException::withMessages([
                    'source_product_id' => 'Produk pemberi dan penerima subsidi harus berstatus aktif.',
                ]);
            }
        }

        DB::transaction(function () use ($validated): void {
            $program = CrossSubsidyProgram::query()->whereKey(1)->lockForUpdate()->firstOrFail();
            $program->update($validated + ['updated_by' => Auth::id()]);
        });

        return redirect()->route('subsidi-silang.edit')
            ->with('success', 'Pengaturan Subsidi Silang berhasil disimpan.');
    }
}
