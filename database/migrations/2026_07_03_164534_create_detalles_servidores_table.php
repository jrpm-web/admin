<?php
// database/migrations/xxxx_xx_xx_000011_create_detalles_servidores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_servidores', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de Servidores
            $table->string('procesador', 100)->nullable();
            $table->string('ram', 50)->nullable();
            $table->string('almacenamiento', 100)->nullable();
            $table->string('tipo_almacenamiento', 20)->nullable();
            $table->string('sistema_operativo', 100)->nullable();
            $table->string('version_so', 50)->nullable();
            $table->string('numero_serie', 50)->unique()->nullable();
            $table->string('modelo_exacto', 100)->nullable();
            $table->string('tipo_servidor', 30)->nullable(); // Rack, Tower, Blade, Virtual
            $table->integer('numero_rack')->nullable();
            $table->integer('unidades_rack')->nullable()->comment('U');
            $table->boolean('virtualizado')->default(false);
            $table->boolean('cluster')->default(false);
            $table->decimal('consumo_watts', 8, 2)->nullable();
            $table->string('ip_principal', 45)->nullable();
            
            // Estado específico
            $table->enum('estado_servidor', [
                'operativo', 'mantenimiento', 'fuera_servicio', 'obsoleto'
            ])->default('operativo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_serie');
            $table->index('estado_servidor');
            $table->index('tipo_servidor');
            $table->index('ip_principal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_servidores');
    }
};