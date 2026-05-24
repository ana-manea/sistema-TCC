<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reunioes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tcc_id')->constrained('tccs')->cascadeOnDelete();
            $table->dateTime('data_hora');
            $table->string('local')->nullable();
            $table->text('observacoes')->nullable();
            $table->text('proximos_passos')->nullable();
            $table->enum('status', ['agendada', 'realizada', 'cancelada'])->default('agendada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reunioes');
    }
};
