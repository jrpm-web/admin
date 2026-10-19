<?php
// database/migrations/xxxx_xx_xx_000006_create_detalles_computadoras_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_computadoras', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones técnicas
            $table->string('procesador', 100)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('almacenamiento', 100)->nullable();
            $table->string('tipo_almacenamiento', 20)->nullable();
            $table->string('sistema_operativo', 100)->nullable();
            $table->string('version_so', 50)->nullable();
            $table->string('numero_serie', 50)->unique()->nullable();
            $table->string('modelo_exacto', 100)->nullable();
            $table->string('color', 30)->nullable();
            $table->integer('pulgadas_pantalla')->nullable();
            $table->string('resolucion_pantalla', 30)->nullable();
            
            // Estado específico
            $table->enum('estado_computadora', [
                'nuevo', 'reacondicionado', 'usado', 'para_reparar', 'obsoleto'
            ])->default('nuevo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_serie');
            $table->index('estado_computadora');
            $table->index('sistema_operativo');
            $table->index('modelo_exacto');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_computadoras');
    }
};