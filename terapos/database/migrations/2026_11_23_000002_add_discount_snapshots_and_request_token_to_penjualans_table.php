<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->boolean('diskon_aktif')->nullable();
            $table->bigInteger('diskon_minimum_pembelian')->nullable();
            $table->string('diskon_operator')->nullable();
            $table->uuid('request_token')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropUnique(['request_token']);
            $table->dropColumn([
                'diskon_aktif',
                'diskon_minimum_pembelian',
                'diskon_operator',
                'request_token',
            ]);
        });
    }
};
