<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitacoes_orientador', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orientando_id')->constrained('orientandos')->cascadeOnDelete();
            $table->foreignId('orientador_id')->constrained('orientadores')->cascadeOnDelete();
            $table->text('mensagem')->nullable();
            $table->text('resposta')->nullable();
            $table->enum('status', ['pendente', 'aceita', 'recusada'])->default('pendente');
            $table->timestamp('respondido_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_orientador');
    }
};
