<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade')
                  ->comment('ID del producto asignado');
            $table->string('asignado_a', 100)->comment('Nombre de la persona asignada');
            $table->string('email_usuario', 255)->comment('Correo del usuario');
            $table->string('telefono_usuario', 50)->nullable()->comment('Teléfono de contacto');
            $table->date('fecha_asignacion')->comment('Fecha de asignación');
            $table->date('fecha_devolucion')->nullable()->comment('Fecha de devolución esperada');
            $table->text('notas')->nullable()->comment('Notas de la asignación');
            $table->enum('tipo', ['asignacion', 'prestamo'])->default('asignacion')->comment('Tipo de asignación');
            $table->enum('estado', ['activa', 'devuelta', 'vencida'])->default('activa')->comment('Estado de la asignación');
            $table->foreignId('usuario_registro_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('Usuario que registró la asignación');
            $table->timestamps();
            
            // Índices
            $table->index('producto_id');
            $table->index('asignado_a');
            $table->index('email_usuario');
            $table->index('fecha_asignacion');
            $table->index('estado');
            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};