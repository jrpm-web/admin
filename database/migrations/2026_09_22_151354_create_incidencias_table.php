<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();

            // ===== Datos de la incidencia =====
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('categoria', 100)->nullable(); // hardware, software, red, acceso, etc.

            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica'])->default('media');
            $table->enum('estado', ['abierta', 'en_proceso', 'en_revision', 'finalizada', 'cerrada'])
                  ->default('abierta');

            // ===== Propiedad y asignación =====
            // Dueño de la incidencia (el que la reporta)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Usuario dueño de la incidencia');

            // Usuario al que se le asigna (un "padre" o admin puede asignar)
            $table->foreignId('asignado_a')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Usuario responsable de resolverla');

            // Quién asignó (útil para auditoría)
            $table->foreignId('asignado_por')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Usuario que hizo la asignación');

            // ===== Visibilidad =====
            // privada: solo dueño y super admin
            // compartida: dueño + usuarios específicos (pivot) + super admin
            // publica: todos la pueden ver
            $table->enum('visibilidad', ['privada', 'compartida', 'publica'])
                  ->default('privada')
                  ->comment('Control de quién puede ver la incidencia');

            // ===== Fechas =====
            $table->timestamp('fecha_reporte')->useCurrent();
            $table->timestamp('fecha_asignacion')->nullable();
            $table->timestamp('fecha_cierre')->nullable();
            $table->timestamp('fecha_limite')->nullable(); // SLA / deadline

            // ===== Resolución =====
            $table->text('solucion')->nullable();

            // ===== Archivos adjuntos (JSON) =====
            $table->json('adjuntos')->nullable();

            // ===== Etiquetas libres =====
            $table->json('tags')->nullable();

            // ===== Metadatos =====
            $table->unsignedInteger('vistas')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // ===== Índices para performance =====
            $table->index(['estado', 'prioridad']);
            $table->index(['user_id', 'estado']);
            $table->index(['asignado_a', 'estado']);
            $table->index('visibilidad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};