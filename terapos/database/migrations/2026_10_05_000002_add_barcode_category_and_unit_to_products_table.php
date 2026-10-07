<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('merk')->nullable()->change();
            $table->string('barcode')->nullable()->unique();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();
            $table->string('satuan')->default('pcs');
        });
    }

    public function down(): void
    {
        DB::table('products')->whereNull('merk')->update(['merk' => '']);

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropUnique(['barcode']);
            $table->string('merk')->nullable(false)->change();
            $table->dropColumn(['barcode', 'category_id', 'satuan']);
        });
    }
};
