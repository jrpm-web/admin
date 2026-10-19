<?php
// app/Http/Controllers/UsuarioController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('configuracion.usuarios', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('configuracion.usuarios-create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'cedula' => 'nullable|string|max:20|unique:users,cedula',
            'telefono' => 'nullable|string|max:20',
            'cargo' => 'nullable|string|max:100',
            'departamento' => 'nullable|string|max:100',
            'rol' => 'required|in:admin,gerente,supervisor,usuario,tecnico',
            'estado' => 'required|in:activo,inactivo,suspendido',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cedula' => $request->cedula,
            'telefono' => $request->telefono,
            'cargo' => $request->cargo,
            'departamento' => $request->departamento,
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Usuario creado exitosamente',
            'user' => $user
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // ✅ Verificar que se encontró el usuario
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'apellidos' => 'nullable|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'cedula' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
                'telefono' => 'nullable|string|max:20',
                'cargo' => 'nullable|string|max:100',
                'departamento' => 'nullable|string|max:100',
                'rol' => 'required|in:admin,gerente,supervisor,usuario,tecnico',
                'estado' => 'required|in:activo,inactivo,suspendido',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $user->update([
                'name' => $request->name,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'cedula' => $request->cedula,
                'telefono' => $request->telefono,
                'cargo' => $request->cargo,
                'departamento' => $request->departamento,
                'rol' => $request->rol,
                'estado' => $request->estado,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            return response()->json([
                'success' => true,
                'message' => '✅ Usuario actualizado exitosamente',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // No permitir desactivar el propio usuario
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ No puedes cambiar tu propio estado'
                ], 403);
            }
            
            $user->estado = $user->estado === 'activo' ? 'inactivo' : 'activo';
            $user->save();

            $status = $user->estado === 'activo' ? 'activado' : 'desactivado';
            
            return response()->json([
                'success' => true,
                'message' => "✅ Usuario {$status} exitosamente",
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // No permitir eliminar el propio usuario
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ No puedes eliminar tu propia cuenta'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => '✅ Usuario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search users.
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $users = User::where('name', 'LIKE', "%{$search}%")
            ->orWhere('apellidos', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->orWhere('cedula', 'LIKE', "%{$search}%")
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }
}