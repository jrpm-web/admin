<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();
            
            // Relación con proveedor
            $table->foreignId('proveedor_id')
                  ->constrained('proveedores')
                  ->onDelete('cascade');
            
            // Datos de la alerta
            $table->string('mensaje');
            $table->string('tipo')->default('recordatorio'); // recordatorio, vencimiento, alerta
            $table->string('prioridad')->default('media'); // baja, media, alta, critica
            $table->date('fecha_limite')->nullable();
            $table->text('observaciones')->nullable();
            
            // Estado
            $table->boolean('leida')->default(false);
            $table->boolean('activa')->default(true);
            
            // Usuario que creó la alerta
            $table->foreignId('creado_por')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->timestamps();
            
            // Índices
            $table->index(['proveedor_id', 'activa', 'leida']);
            $table->index('fecha_limite');
            $table->index('tipo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alertas');
    }
};