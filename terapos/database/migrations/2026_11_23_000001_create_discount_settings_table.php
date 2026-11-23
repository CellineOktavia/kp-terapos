<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->bigInteger('minimum_purchase')->default(300000);
            $table->decimal('percentage', 5, 2)->default(5);
            $table->string('condition_operator')->default('gt');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        DB::table('discount_settings')->insert([
            'id' => 1,
            'is_active' => true,
            'minimum_purchase' => 300000,
            'percentage' => 5,
            'condition_operator' => 'gt',
            'updated_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_settings');
    }
};
