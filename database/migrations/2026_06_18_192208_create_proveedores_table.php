<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            
            // Datos principales solicitados
            $table->string('proveedor', 100);
            $table->string('asesor', 100);
            $table->string('telefono_contacto', 50)->nullable();
            $table->string('correo')->unique();
            $table->string('descripcion_corta', 255)->nullable();
            
            // Timestamps automáticos (created_at, updated_at)
            $table->timestamps();
            
            // Soft deletes para evitar pérdida de datos y mantener integridad en auditorías (deleted_at)
            $table->softDeletes();
            $table->index('proveedor');
            $table->index('correo');
            $table->index('asesor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
