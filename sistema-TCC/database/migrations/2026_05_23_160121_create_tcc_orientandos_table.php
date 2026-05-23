<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tcc_orientandos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tcc_d')->constrained('tccs')->cascadeOnDelete();
            $table->foreignId('orientando_id')->constrained('orientandos')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['tcc_id', 'orientando_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tcc_orientandos');
    }
};
