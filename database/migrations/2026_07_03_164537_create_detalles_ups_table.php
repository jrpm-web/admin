<?php
// database/migrations/xxxx_xx_xx_000013_create_detalles_ups_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_ups', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de UPS
            $table->string('tipo_ups', 30)->nullable(); // Standby, Line-Interactive, Online
            $table->decimal('capacidad_va', 10, 2)->nullable();
            $table->decimal('capacidad_watts', 10, 2)->nullable();
            $table->integer('autonomia_minutos')->nullable();
            $table->string('tipo_bateria', 50)->nullable();
            $table->string('numero_serie', 50)->unique()->nullable();
            $table->string('modelo_exacto', 100)->nullable();
            $table->integer('salidas')->nullable();
            $table->boolean('gestion_remota')->default(false);
            $table->string('ip_gestion', 45)->nullable();
            $table->date('fecha_instalacion')->nullable();
            $table->date('fecha_proxima_mantenimiento')->nullable();
            
            // Estado específico
            $table->enum('estado_ups', [
                'operativo', 'mantenimiento', 'falla_bateria', 'fuera_servicio'
            ])->default('operativo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_serie');
            $table->index('estado_ups');
            $table->index('tipo_ups');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_ups');
    }
};