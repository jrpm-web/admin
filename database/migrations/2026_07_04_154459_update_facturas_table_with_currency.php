<?php
// database/migrations/xxxx_xx_xx_xxxxxx_update_facturas_table_with_currency.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            // Moneda y tasa
            $table->enum('moneda', ['Bs', 'USD', 'RUB'])->default('Bs')->after('monto');
            $table->decimal('tasa_referencia', 12, 2)->nullable()->after('moneda');
            $table->decimal('monto_usd', 12, 2)->nullable()->after('tasa_referencia');
            
            // Fechas
            $table->date('fecha_pago')->nullable()->after('fecha_factura');
            $table->enum('estado_pago', ['pendiente', 'pagado', 'parcial', 'vencido'])->default('pendiente')->after('fecha_pago');
            
            // Observaciones
            $table->text('observaciones')->nullable()->after('estado_pago');
            
            // Auditoría
            $table->foreignId('creado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->constrained('users')->onDelete('set null');
            
            // Índices
            $table->index('moneda');
            $table->index('estado_pago');
            $table->index('fecha_pago');
        });
    }

    public function down(): void
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn([
                'moneda', 'tasa_referencia', 'monto_usd',
                'fecha_pago', 'estado_pago', 'observaciones',
                'creado_por', 'actualizado_por'
            ]);
        });
    }
};