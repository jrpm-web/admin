<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->date('fecha_facturacion')->nullable()->after('proveedor');
            $table->integer('dias_alertar')->default(5)->after('fecha_facturacion');
            $table->boolean('monitoreo_activo')->default(true)->after('dias_alertar');
        });
    }

    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->dropColumn(['fecha_facturacion', 'dias_alertar', 'monitoreo_activo']);
        });
    }
};