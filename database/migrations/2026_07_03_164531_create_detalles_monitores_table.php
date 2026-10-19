<?php
// database/migrations/xxxx_xx_xx_000007_create_detalles_monitores_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalles_monitores', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            
            // Especificaciones de Monitores
            $table->integer('pulgadas');
            $table->string('resolucion', 30)->nullable();
            $table->string('tipo_panel', 20)->nullable(); // IPS, VA, TN, OLED
            $table->string('relacion_aspecto', 10)->nullable(); // 16:9, 21:9
            $table->integer('refresh_rate')->nullable()->comment('Hz');
            $table->string('puertos', 100)->nullable(); // HDMI, DP, VGA
            $table->string('numero_serie', 50)->unique()->nullable();
            $table->string('modelo_exacto', 100)->nullable();
            $table->boolean('curvo')->default(false);
            
            // Estado específico
            $table->enum('estado_monitor', [
                'nuevo', 'reacondicionado', 'defectuoso', 'en_garantia'
            ])->default('nuevo');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('numero_serie');
            $table->index('estado_monitor');
            $table->index('pulgadas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalles_monitores');
    }
};