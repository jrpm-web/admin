<?php
// database/migrations/xxxx_xx_xx_000008_create_detalles_cctv_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_cctv', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de CCTV
            $table->string('resolucion', 30)->nullable(); // 4MP, 8MP, 4K
            $table->string('tipo_camara', 30)->nullable(); // Domo, Bala, PTZ, IP
            $table->boolean('poe')->default(false);
            $table->string('angulo_vision', 20)->nullable(); // 90°, 120°
            $table->boolean('vision_nocturna')->default(false);
            $table->string('distancia_vision', 30)->nullable(); // 20m, 50m
            $table->string('almacenamiento_local', 50)->nullable(); // MicroSD hasta 256GB
            $table->string('ip_asignada', 45)->nullable();
            $table->string('mac_address', 17)->unique()->nullable();
            $table->string('modelo_exacto', 100)->nullable();
            $table->string('lente', 30)->nullable(); // 2.8mm, 4mm, 6mm
            $table->boolean('audio')->default(false);
            
            // Estado específico
            $table->enum('estado_cctv', [
                'operativo', 'desconectado', 'en_mantenimiento', 'defectuoso'
            ])->default('operativo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('mac_address');
            $table->index('ip_asignada');
            $table->index('estado_cctv');
            $table->index('tipo_camara');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_cctv');
    }
};