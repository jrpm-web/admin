<?php
// database/migrations/xxxx_xx_xx_000004_create_detalles_red_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_red', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de Red
            $table->string('mac_address', 17)->unique()->nullable();
            $table->string('ip_asignada', 45)->nullable();
            $table->integer('puertos')->nullable();
            $table->string('estandar_wifi', 20)->nullable();
            $table->string('modelo_switch', 100)->nullable();
            $table->boolean('poe')->default(false);
            $table->string('firmware_version', 50)->nullable();
            $table->string('vlan_asignada', 50)->nullable();
            
            // Estado específico
            $table->enum('estado_red', [
                'operativo', 'caido', 'en_mantenimiento', 'desactualizado'
            ])->default('operativo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('mac_address');
            $table->index('ip_asignada');
            $table->index('estado_red');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_red');
    }
};