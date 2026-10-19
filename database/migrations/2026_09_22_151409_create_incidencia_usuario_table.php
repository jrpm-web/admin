<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencia_usuario', function (Blueprint $table) {
            $table->id();

            $table->foreignId('incidencia_id')
                  ->constrained('incidencias')
                  ->onDelete('cascade');

            // Usuario con el que se comparte
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Usuario que recibe acceso a la incidencia');

            // Quién compartió (para auditoría)
            $table->foreignId('compartido_por')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Nivel de permiso que se le da al usuario
            // ver: solo puede ver
            // comentar: ver + comentar
            // editar: ver + comentar + editar
            $table->enum('permiso', ['ver', 'comentar', 'editar'])
                  ->default('ver');

            $table->timestamp('compartido_en')->useCurrent();
            $table->timestamp('expira_en')->nullable()
                  ->comment('Fecha en que se revoca el acceso automáticamente');

            $table->timestamps();

            // Un usuario no puede estar compartido 2 veces en la misma incidencia
            $table->unique(['incidencia_id', 'user_id']);
            $table->index(['user_id', 'permiso']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencia_usuario');
    }
};