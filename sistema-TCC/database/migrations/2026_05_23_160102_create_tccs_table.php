<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tccs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orientador_id')->nullable()->constrained('orientadores')->nullOnDelete();
            $table->string('tema');
            $table->text('descricao')->nullable();
            $table->enum('status', ['em_andamento', 'concluido', 'cancelado', 'suspenso'])->default('em_andamento');
            $table->enum('resultado_final', ['aprovado', 'aprovado_com_ressalvas', 'reprovado'])->nullable();
            $table->decimal('nota_final', 4, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tccs');
    }
};
