<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Tipo de pago: 'unico' o 'recurrente'
            $table->enum('tipo_pago', ['unico', 'recurrente'])->default('unico')->after('activo');
            
            // Campos para pagos recurrentes
            $table->enum('frecuencia_pago', ['mensual', 'trimestral', 'semestral', 'anual'])->nullable()->after('tipo_pago');
            $table->integer('dia_facturacion')->nullable()->after('frecuencia_pago'); // Día del mes (1-31)
            $table->date('proxima_facturacion')->nullable()->after('dia_facturacion');
            $table->boolean('notificar_antes')->default(true)->after('proxima_facturacion');
            $table->integer('dias_notificacion')->default(5)->after('notificar_antes');
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'tipo_pago',
                'frecuencia_pago',
                'dia_facturacion',
                'proxima_facturacion',
                'notificar_antes',
                'dias_notificacion'
            ]);
        });
    }
};