<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historico_tcc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tcc_id')->constrained('tccs')->cascadeOnDelete();
            $table->foreignId('alterado_por')->constrained('usuarios')->cascadeOnDelete();
            $table->string('status_anterior')->nullable();
            $table->string('status_novo');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_tcc');
    }
};
