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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            
            // Campos principales del producto
            $table->string('nombre', 200);
            $table->string('codigo', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(0);
            
            // Claves foráneas
            $table->foreignId('categoria_id')
                  ->constrained('categorias')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
                  
            $table->foreignId('proveedor_id')
                  ->constrained('proveedores')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
            
            // Campos adicionales
            $table->string('unidad_medida', 50)->nullable();
            $table->string('ubicacion', 100)->nullable();
            $table->boolean('activo')->default(true);
            
            // Timestamps
            $table->timestamps();
            
            // Soft deletes
            $table->softDeletes();
            
            // Índices para mejorar el rendimiento
            $table->index('nombre');
            $table->index('codigo');
            $table->index('categoria_id');
            $table->index('proveedor_id');
            $table->index('activo');
            $table->index('stock');
            
            // Índice compuesto para búsquedas frecuentes
            $table->index(['categoria_id', 'activo']);
            $table->index(['proveedor_id', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
