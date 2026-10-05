<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('titulo');
            $table->string('categoria', 50);
            $table->unsignedInteger('tiempo')->nullable();
            $table->enum('dificultad', ['facil', 'media', 'dificil'])->nullable();
            $table->text('ingredientes')->nullable();
            $table->text('pasos')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'categoria']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
