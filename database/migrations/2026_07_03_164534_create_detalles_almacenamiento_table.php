<?php
// database/migrations/xxxx_xx_xx_000012_create_detalles_almacenamiento_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_almacenamiento', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de Almacenamiento
            $table->string('tipo_almacenamiento', 30)->nullable(); // NAS, SAN, DAS
            $table->decimal('capacidad_total', 10, 2)->nullable()->comment('TB');
            $table->decimal('capacidad_usada', 10, 2)->nullable()->comment('TB');
            $table->string('raid_configurado', 20)->nullable(); // RAID 0, 1, 5, 10
            $table->string('sistema_operativo', 100)->nullable();
            $table->string('numero_serie', 50)->unique()->nullable();
            $table->string('modelo_exacto', 100)->nullable();
            $table->string('ip_principal', 45)->nullable();
            $table->integer('bahias')->nullable();
            $table->string('tipo_disco', 20)->nullable(); // HDD, SSD, NVMe
            $table->integer('discos_instalados')->nullable();
            $table->string('interfaz', 20)->nullable(); // SATA, SAS, FC, iSCSI
            
            // Estado específico
            $table->enum('estado_almacenamiento', [
                'operativo', 'mantenimiento', 'falla_parcial', 'fuera_servicio'
            ])->default('operativo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_serie');
            $table->index('estado_almacenamiento');
            $table->index('tipo_almacenamiento');
            $table->index('ip_principal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_almacenamiento');
    }
};