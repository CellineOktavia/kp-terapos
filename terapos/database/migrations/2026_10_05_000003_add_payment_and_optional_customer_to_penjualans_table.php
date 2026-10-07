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
            $table->foreignId('customer_id')->nullable()->change();
            $table->decimal('bayar', 15, 2)->default(0);
            $table->decimal('kembalian', 15, 2)->default(0);
        });
    }

    public function down(): void
    {
        if (DB::table('penjualans')->whereNull('customer_id')->exists()) {
            throw new RuntimeException(
                'Cannot make penjualans.customer_id required while umum-customer transactions exist.'
            );
        }

        Schema::table('penjualans', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable(false)->change();
            $table->dropColumn(['bayar', 'kembalian']);
        });
    }
};
