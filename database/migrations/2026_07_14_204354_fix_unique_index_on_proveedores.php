<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('proveedores', function (Blueprint $table) {
        // 1. Elimina el índice único actual (ajusta el nombre si es distinto)
        $table->dropUnique('proveedores_correo_unique');

        // 2. Crea un índice que combine el correo y el deleted_at
        // Esto permite que el correo se repita SI el deleted_at es distinto de null
        $table->unique(['correo', 'deleted_at']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
