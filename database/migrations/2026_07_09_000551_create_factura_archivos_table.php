<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_archivos', function (Blueprint $table) {
            $table->id();
            
            // Relación con la factura
            $table->foreignId('factura_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            
            // Datos del archivo
            $table->string('nombre_original');
            $table->string('nombre_guardado');
            $table->string('ruta');
            $table->string('tipo_archivo', 50);
            $table->bigInteger('tamaño');
            $table->string('mime_type', 100);
            
            // Metadatos adicionales
            $table->string('categoria')->default('factura'); // factura, soporte, anexo, etc.
            $table->text('descripcion')->nullable();
            
            // Hash para verificar integridad
            $table->string('hash', 64)->nullable();
            
            // Usuario que subió el archivo
            $table->foreignId('subido_por')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            // Auditoría
            $table->timestamps();
            
            // Índices para optimizar búsquedas
            $table->index('factura_id');
            $table->index('tipo_archivo');
            $table->index('categoria');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_archivos');
    }
};