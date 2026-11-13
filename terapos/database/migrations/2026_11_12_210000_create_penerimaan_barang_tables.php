<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_penerimaan')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('detail_penerimaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_barang_id')
                ->constrained('penerimaan_barangs')
                ->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('qty');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penerimaan_barangs');
        Schema::dropIfExists('penerimaan_barangs');
    }
};
