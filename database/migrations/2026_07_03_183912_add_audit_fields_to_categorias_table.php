<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_audit_fields_to_categorias_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Agregar campos de auditoría si no existen
            if (!Schema::hasColumn('categorias', 'icono')) {
                $table->string('icono', 50)->default('bi-tag')->after('descripcion');
            }
            
            if (!Schema::hasColumn('categorias', 'color')) {
                $table->string('color', 20)->default('#6c757d')->after('icono');
            }
            
            if (!Schema::hasColumn('categorias', 'orden')) {
                $table->integer('orden')->default(0)->after('color');
            }
            
            if (!Schema::hasColumn('categorias', 'activo')) {
                $table->boolean('activo')->default(true)->after('orden');
            }
            
            if (!Schema::hasColumn('categorias', 'creado_por')) {
                $table->foreignId('creado_por')
                      ->nullable()
                      ->after('activo')
                      ->constrained('users')
                      ->onDelete('set null')
                      ->comment('Usuario que creó la categoría');
            }
            
            if (!Schema::hasColumn('categorias', 'actualizado_por')) {
                $table->foreignId('actualizado_por')
                      ->nullable()
                      ->after('creado_por')
                      ->constrained('users')
                      ->onDelete('set null')
                      ->comment('Usuario que actualizó la categoría');
            }
            
            // Agregar soft deletes si no existe
            if (!Schema::hasColumn('categorias', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $columns = ['icono', 'color', 'orden', 'activo', 'creado_por', 'actualizado_por', 'deleted_at'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('categorias', $column)) {
                    if ($column === 'creado_por' || $column === 'actualizado_por') {
                        $table->dropForeign([$column]);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};