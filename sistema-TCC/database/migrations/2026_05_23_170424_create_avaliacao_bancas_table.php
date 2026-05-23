<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avaliacoes_banca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banca_id')->constrained('bancas')->cascadeOnDelete();
            $table->foreignId('orientando_id')->constrained('orientandos')->cascadeOnDelete();
            $table->foreignId('avaliador_id')->constrained('usuarios')->cascadeOnDelete();
            $table->decimal('nota', 4, 2);
            $table->text('parecer')->nullable();
            $table->enum('resultado', ['aprovado', 'aprovado_com_ressalvas', 'reprovado'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacoes_banca');
    }
};
