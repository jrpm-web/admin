<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_missing_columns_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Columnas faltantes
            $table->string('apellidos', 100)->nullable()->after('name');
            $table->string('cedula', 20)->nullable()->unique()->after('email');
            $table->string('telefono', 20)->nullable()->after('cedula');
            $table->string('cargo', 100)->nullable()->after('telefono');
            $table->string('departamento', 100)->nullable()->after('cargo');
            $table->string('foto', 255)->nullable()->after('departamento');
            
            // Estado y roles
            $table->enum('estado', ['activo', 'inactivo', 'suspendido'])->default('activo')->after('foto');
            $table->enum('rol', ['admin', 'gerente', 'supervisor', 'usuario', 'tecnico'])->default('usuario')->after('estado');
            
            // Auditoría
            $table->timestamp('ultimo_acceso')->nullable()->after('rol');
            $table->timestamp('ultima_actividad')->nullable()->after('ultimo_acceso');
            $table->string('ip_ultimo_acceso', 45)->nullable()->after('ultima_actividad');
            $table->string('user_agent_ultimo_acceso', 255)->nullable()->after('ip_ultimo_acceso');
            
            // Preferencias
            $table->json('preferencias')->nullable()->after('user_agent_ultimo_acceso');
            $table->string('tema', 20)->default('light')->after('preferencias');
            $table->string('idioma', 10)->default('es')->after('tema');
            
            // Auditoría de creación
            $table->foreignId('creado_por')->nullable()->after('idioma')->constrained('users')->onDelete('set null');
            $table->foreignId('actualizado_por')->nullable()->after('creado_por')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'apellidos', 'cedula', 'telefono', 'cargo', 'departamento',
                'foto', 'estado', 'rol', 'ultimo_acceso', 'ultima_actividad',
                'ip_ultimo_acceso', 'user_agent_ultimo_acceso', 'preferencias',
                'tema', 'idioma', 'creado_por', 'actualizado_por'
            ]);
        });
    }
};