<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencia_historial', function (Blueprint $table) {
            $table->id();

            $table->foreignId('incidencia_id')
                  ->constrained('incidencias')
                  ->onDelete('cascade');

            // Quién hizo la acción
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            // Tipo de acción
            $table->enum('accion', [
                'creada',
                'actualizada',
                'asignada',
                'reasignada',
                'compartida',
                'descompartida',
                'estado_cambiado',
                'prioridad_cambiada',
                'comentada',
                'cerrada',
                'reabierta',
                'eliminada',
            ]);

            // Descripción legible
            $table->string('descripcion')->nullable();

            // Valores antes/después (JSON) para revertir o mostrar diffs
            $table->json('valores_anteriores')->nullable();
            $table->json('valores_nuevos')->nullable();

            // Metadata adicional (ej: a quién se asignó)
            $table->json('metadata')->nullable();

            $table->timestamp('ocurrido_en')->useCurrent();
            $table->timestamps();

            $table->index(['incidencia_id', 'ocurrido_en']);
            $table->index(['user_id', 'accion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencia_historial');
    }
};