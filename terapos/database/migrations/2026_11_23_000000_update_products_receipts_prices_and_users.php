<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('aktif')->default(true);
            $table->dropUnique(['barcode']);
            $table->dropColumn('barcode');
        });

        Schema::table('detail_penerimaan_barangs', function (Blueprint $table) {
            $table->decimal('harga_beli', 15, 2)->nullable();
            $table->unsignedInteger('stok_sebelum')->nullable();
            $table->unsignedInteger('stok_sesudah')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('aktif')->default(true);
        });

        Schema::create('product_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('jenis_harga', 10);
            $table->decimal('harga_lama', 15, 2);
            $table->decimal('harga_baru', 15, 2);
            $table->nullableMorphs('reference');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'jenis_harga', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_histories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });

        Schema::table('detail_penerimaan_barangs', function (Blueprint $table) {
            $table->dropColumn(['harga_beli', 'stok_sebelum', 'stok_sesudah']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('barcode')->nullable()->unique();
            $table->dropColumn('aktif');
        });
    }
};
