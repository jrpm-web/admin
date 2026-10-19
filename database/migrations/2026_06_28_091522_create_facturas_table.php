<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura', 50)->unique();
            
            // Relación con la tabla proveedores
            $table->foreignId('proveedor_id')
                  ->constrained('proveedores')
                  ->onDelete('restrict'); // 'restrict' evita que borres un proveedor si tiene facturas asociadas
                  
            $table->decimal('monto', 12, 2);
            $table->string('mes', 20); // Ej: "Enero 2024"
            $table->date('fecha_factura');
            $table->timestamps();
            
            // Índices para optimizar las búsquedas dinámicas
            $table->index('proveedor_id');
            $table->index('mes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('facturas');
    }
};