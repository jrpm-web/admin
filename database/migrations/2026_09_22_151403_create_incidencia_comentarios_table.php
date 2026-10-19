<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencia_comentarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('incidencia_id')
                  ->constrained('incidencias')
                  ->onDelete('cascade');

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Autor del comentario');

            $table->text('comentario');

            // Para comentarios internos que solo ve el staff (no el dueño)
            $table->boolean('es_interno')->default(false)
                  ->comment('true = solo visible para admins/padres');

            // Si el comentario adjunta archivos
            $table->json('adjuntos')->nullable();

            $table->timestamps();

            $table->index(['incidencia_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencia_comentarios');
    }
};