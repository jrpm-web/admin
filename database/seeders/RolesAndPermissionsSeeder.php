<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Resetear caché
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ===== PERMISOS =====
        $permisos = [
            // Incidencias
            'incidencias.ver',
            'incidencias.crear',
            'incidencias.editar',
            'incidencias.eliminar',
            'incidencias.asignar',
            'incidencias.compartir',
            'incidencias.cerrar',
            'incidencias.ver-todas',

            // Facturas
            'facturas.ver',
            'facturas.crear',
            'facturas.editar',
            'facturas.eliminar',

            // Usuarios
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar',

            // Inventario, proveedores, etc.
            'inventario.ver',
            'inventario.gestionar',
            'proveedores.ver',
            'proveedores.gestionar',
            'reportes.ver',
            'configuracion.gestionar',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // ===== ROLES =====
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $padre = Role::firstOrCreate(['name' => 'padre']);
        $padre->givePermissionTo([
            'incidencias.ver',
            'incidencias.crear',
            'incidencias.editar',
            'incidencias.asignar',
            'incidencias.compartir',
            'incidencias.cerrar',
            'incidencias.ver-todas',
            'facturas.ver',
            'inventario.ver',
            'proveedores.ver',
            'reportes.ver',
            'usuarios.ver',
        ]);

        $usuario = Role::firstOrCreate(['name' => 'usuario']);
        $usuario->givePermissionTo([
            'incidencias.ver',
            'incidencias.crear',
            'facturas.ver',
            'inventario.ver',
        ]);

        // ===== ASIGNAR SUPER ADMIN AL PRIMER USUARIO =====
        $primerUsuario = User::first();
        if ($primerUsuario) {
            $primerUsuario->assignRole('super-admin');
            $this->command->info("✅ Rol 'super-admin' asignado a: {$primerUsuario->email}");
        }

        $this->command->info('✅ Roles y permisos creados correctamente.');
    }
}