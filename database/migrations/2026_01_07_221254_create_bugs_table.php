<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descricao');
            $table->enum('status', ['aberto', 'em progresso', 'resolvido', 'fechado'])->default('aberto');
            $table->enum('prioridade', ['baixa', 'media', 'alta', 'critica'])->default('media');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};
