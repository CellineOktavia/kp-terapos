<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->decimal('diskon_persen', 5, 2)->default(0)->after('total');
            $table->decimal('diskon_nominal', 15, 2)->default(0)->after('diskon_persen');
        });
    }

    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn(['diskon_persen', 'diskon_nominal']);
        });
    }
};
