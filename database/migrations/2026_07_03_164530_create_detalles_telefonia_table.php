<?php
// database/migrations/xxxx_xx_xx_000005_create_detalles_telefonia_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_telefonia', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de Telefonía
            $table->string('numero_telefonico', 20)->unique();
            $table->string('numero_sim', 30)->unique()->nullable();
            $table->string('operador', 50)->nullable();
            $table->string('plan_datos', 100)->nullable();
            $table->string('imei', 30)->unique()->nullable();
            $table->string('pin', 10)->nullable();
            $table->string('puk', 10)->nullable();
            $table->date('fecha_activacion')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            
            // Estado específico
            $table->enum('estado_telefonia', [
                'activo', 'inactivo', 'suspendido', 'cancelado', 'en_portabilidad'
            ])->default('activo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_telefonico');
            $table->index('numero_sim');
            $table->index('imei');
            $table->index('estado_telefonia');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_telefonia');
    }
};