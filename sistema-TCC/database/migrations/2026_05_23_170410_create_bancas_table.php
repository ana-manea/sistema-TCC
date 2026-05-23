<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bancas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tcc_id')->constrained('tccs')->cascadeOnDelete();
            $table->dateTime('data_hora');
            $table->string('local')->nullable();
            $table->enum('status', ['agendada', 'realizada', 'cancelada'])->default('agendada');
            $table->text('parecer_final')->nullable();
            $table->enum('resultado_final', ['aprovado', 'aprovado_com_ressalvas', 'reprovado'])->nullable();
            $table->decimal('nota_final', 4, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bancas');
    }
};
