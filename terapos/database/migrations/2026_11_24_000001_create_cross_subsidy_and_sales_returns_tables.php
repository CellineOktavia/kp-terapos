<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->string('status')->default('completed');
            $table->decimal('cross_subsidy_amount', 15, 2)->default(0);
        });

        Schema::table('detail_penjualans', function (Blueprint $table) {
            $table->decimal('discount_share', 15, 2)->default(0);
            $table->decimal('cross_subsidy_per_unit', 15, 2)->default(0);
        });
        $this->backfillDiscountShares();

        Schema::create('cross_subsidy_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_product_id')->nullable()->constrained('products')->restrictOnDelete();
            $table->foreignId('recipient_product_id')->nullable()->constrained('products')->restrictOnDelete();
            $table->decimal('allocation_percentage', 5, 2)->default(0);
            $table->decimal('subsidy_amount', 15, 2)->default(0);
            $table->decimal('maximum_subsidy', 15, 2)->default(0);
            $table->decimal('minimum_selling_price', 15, 2)->default(0);
            $table->boolean('is_active')->default(false);
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::table('cross_subsidy_programs')->insert([
            'id' => 1,
            'source_product_id' => null,
            'recipient_product_id' => null,
            'allocation_percentage' => 0,
            'subsidy_amount' => 0,
            'maximum_subsidy' => 0,
            'minimum_selling_price' => 0,
            'is_active' => false,
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::create('penjualan_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('request_token')->unique();
            $table->string('reason');
            $table->decimal('refund_amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('detail_penjualan_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_return_id')->constrained('penjualan_returns')->cascadeOnDelete();
            $table->foreignId('detail_penjualan_id')->constrained('detail_penjualans')->restrictOnDelete();
            $table->unsignedInteger('qty');
            $table->decimal('refund_amount', 15, 2);
            $table->timestamps();
            $table->unique(['penjualan_return_id', 'detail_penjualan_id']);
        });

        Schema::create('cross_subsidy_ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('cross_subsidy_programs')->restrictOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('penjualan_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('detail_penjualan_id')->nullable()->constrained('detail_penjualans')->restrictOnDelete();
            $table->foreignId('penjualan_return_id')->nullable()->constrained('penjualan_returns')->restrictOnDelete();
            $table->foreignId('reverses_entry_id')->nullable()->constrained('cross_subsidy_ledger_entries')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('entry_type');
            $table->decimal('amount', 15, 2);
            $table->decimal('rate_snapshot', 5, 2)->nullable();
            $table->decimal('unit_amount_snapshot', 15, 2)->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->index(['program_id', 'entry_type']);
            $table->index(['detail_penjualan_id', 'entry_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cross_subsidy_ledger_entries');
        Schema::dropIfExists('detail_penjualan_returns');
        Schema::dropIfExists('penjualan_returns');
        Schema::dropIfExists('cross_subsidy_programs');

        Schema::table('detail_penjualans', function (Blueprint $table) {
            $table->dropColumn(['discount_share', 'cross_subsidy_per_unit']);
        });

        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn(['status', 'cross_subsidy_amount']);
        });
    }

    private function backfillDiscountShares(): void
    {
        DB::table('penjualans')
            ->select('id', 'diskon_nominal')
            ->orderBy('id')
            ->chunkById(100, function ($sales): void {
                foreach ($sales as $sale) {
                    $details = DB::table('detail_penjualans')
                        ->select('id', 'subtotal')
                        ->where('penjualan_id', $sale->id)
                        ->orderBy('id')
                        ->get();
                    $totalCents = (int) round((float) $sale->diskon_nominal * 100);
                    $weights = $details->map(fn (object $detail): int => (int) round((float) $detail->subtotal * 100));
                    $weightTotal = $weights->sum();
                    if ($totalCents <= 0 || $weightTotal <= 0) {
                        continue;
                    }

                    $allocations = [];
                    $remainders = [];
                    $allocated = 0;
                    foreach ($details as $index => $detail) {
                        $exact = $totalCents * ($weights[$index] / $weightTotal);
                        $base = (int) floor($exact);
                        $allocations[$index] = $base;
                        $remainders[$index] = $exact - $base;
                        $allocated += $base;
                    }

                    $indices = array_keys($remainders);
                    usort($indices, fn (int $left, int $right): int => $remainders[$right] <=> $remainders[$left]);
                    for ($i = 0, $remaining = $totalCents - $allocated; $i < $remaining; $i++) {
                        $allocations[$indices[$i]]++;
                    }

                    foreach ($details as $index => $detail) {
                        DB::table('detail_penjualans')
                            ->where('id', $detail->id)
                            ->update(['discount_share' => $allocations[$index] / 100]);
                    }
                }
            });
    }
};
