<?php
// database/migrations/xxxx_xx_xx_000010_create_detalles_muebles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_muebles', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de Muebles
            $table->string('material', 50)->nullable();
            $table->string('dimensiones', 50)->nullable(); // 120x60x75 cm
            $table->string('color', 30)->nullable();
            $table->decimal('capacidad_peso', 8, 2)->nullable()->comment('kg');
            $table->string('estilo', 50)->nullable();
            $table->string('numero_serie', 50)->unique()->nullable();
            $table->date('fecha_garantia')->nullable();
            $table->string('tipo_mueble', 50)->nullable(); // Escritorio, Silla, Estante, etc.
            
            // Estado específico
            $table->enum('estado_mueble', [
                'nuevo', 'usado', 'dañado', 'en_reparacion'
            ])->default('nuevo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_serie');
            $table->index('estado_mueble');
            $table->index('tipo_mueble');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_muebles');
    }
};