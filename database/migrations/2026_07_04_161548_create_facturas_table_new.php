<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_facturas_table_new.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar tabla existente si existe
        Schema::dropIfExists('facturas');

        // Crear tabla completa
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            
            // Datos de la factura
            $table->string('numero_factura', 50)->unique();
            $table->foreignId('proveedor_id')
                  ->constrained('proveedores')
                  ->onDelete('restrict');
            $table->decimal('monto', 12, 2);
            
            // Moneda y conversión
            $table->enum('moneda', ['Bs', 'USD', 'RUB'])->default('Bs');
            $table->decimal('tasa_referencia', 12, 2)->nullable();
            $table->decimal('monto_usd', 12, 2)->nullable();
            
            // Fechas
            $table->string('mes', 20);
            $table->date('fecha_factura');
            $table->date('fecha_pago')->nullable();
            
            // Estado de pago
            $table->enum('estado_pago', ['pendiente', 'pagado', 'parcial', 'vencido'])
                  ->default('pendiente');
            
            // Observaciones
            $table->text('observaciones')->nullable();
            
            // Auditoría
            $table->foreignId('creado_por')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
                  
            $table->foreignId('actualizado_por')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            
            $table->timestamps();
            
            // ✅ IMPORTANTE: SoftDeletes
            $table->softDeletes(); // Esto agrega la columna deleted_at
            
            // Índices
            $table->index('proveedor_id');
            $table->index('mes');
            $table->index('fecha_factura');
            $table->index('fecha_pago');
            $table->index('estado_pago');
            $table->index('moneda');
            $table->index('numero_factura');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};