<?php
// database/migrations/xxxx_xx_xx_000009_create_detalles_impresoras_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_impresoras', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de Impresoras
            $table->string('tipo_impresora', 30)->nullable(); // Láser, Inyección, Térmica
            $table->string('marca', 50)->nullable();
            $table->string('modelo_exacto', 100)->nullable();
            $table->string('numero_serie', 50)->unique()->nullable();
            $table->boolean('color')->default(false);
            $table->boolean('doble_cara')->default(false);
            $table->integer('velocidad_impresion')->nullable()->comment('ppm');
            $table->string('conexion', 50)->nullable(); // USB, WiFi, Ethernet
            $table->string('ip_asignada', 45)->nullable();
            $table->integer('nivel_toner')->nullable()->comment('Porcentaje');
            $table->string('consumibles', 100)->nullable(); // Modelos de tóner/tinta
            $table->integer('contador_impresiones')->default(0);
            
            // Estado específico
            $table->enum('estado_impresora', [
                'operativo', 'mantenimiento', 'fuera_servicio', 'sin_consumibles'
            ])->default('operativo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_serie');
            $table->index('estado_impresora');
            $table->index('tipo_impresora');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_impresoras');
    }
};