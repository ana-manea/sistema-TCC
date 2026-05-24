<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banca_membros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banca_id')->constrained('bancas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('papel', ['presidente', 'membro_interno', 'membro_externo']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banca_membros');
    }
};
