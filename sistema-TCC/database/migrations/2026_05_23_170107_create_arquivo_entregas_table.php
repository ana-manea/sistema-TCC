<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arquivos_entrega', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entrega_id')->constrained('entregas')->cascadeOnDelete();
            $table->foreignId('enviado_por')->constrained('usuarios')->cascadeOnDelete();
            $table->string('arquivo_path');
            $table->unsignedInteger('versao')->default(1);
            $table->text('observacao')->nullable();
            $table->enum('status_validacao', ['pendente', 'validado', 'rejeitado'])->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arquivos_entrega');
    }
};
