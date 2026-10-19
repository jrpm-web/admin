<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estatus_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade')
                  ->comment('ID del producto');
            $table->enum('estatus', [
                'disponible',
                'asignado',
                'mantenimiento',
                'reparacion',
                'prestamo',
                'baja'
            ])->default('disponible')->comment('Estatus del producto');
            $table->text('observaciones')->nullable()->comment('Observaciones del estatus');
            $table->foreignId('usuario_registro_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Usuario que registró el cambio');
            $table->timestamp('fecha_cambio')->useCurrent()->comment('Fecha del cambio de estatus');
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('estatus');
            $table->index('fecha_cambio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estatus_productos');
    }
};