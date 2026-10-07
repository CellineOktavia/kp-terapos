<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('co_owner')->change();
        });

        DB::table('users')
            ->where('role', 'admin')
            ->update(['role' => 'co_owner']);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('role', 'co_owner')
            ->update(['role' => 'admin']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['owner', 'admin'])
                ->default('admin')
                ->change();
        });
    }
};