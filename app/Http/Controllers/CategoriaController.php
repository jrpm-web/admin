<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todas las categorías con paginación y relaciones
        $categorias = Categoria::withoutGlobalScopes()
            ->with(['creador', 'actualizador']) // ✅ Cargar relaciones
            ->orderBy('nombre_categoria', 'asc')
            ->paginate(10);
        
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Validación
    $validator = Validator::make($request->all(), [
        'nombre_categoria' => [
            'required',
            'string',
            'max:100',
            'unique:categorias,nombre_categoria',
            'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\.]+$/'
        ],
        'descripcion' => [
            'nullable',
            'string',
            'max:255'
        ]
    ], [
        'nombre_categoria.required' => 'El nombre de la categoría es obligatorio',
        'nombre_categoria.unique'   => 'Ya existe una categoría con este nombre',
        'nombre_categoria.max'      => 'El nombre no puede tener más de 100 caracteres',
        'nombre_categoria.regex'    => 'El nombre solo puede contener letras, números, espacios, guiones y puntos',
        'descripcion.max'           => 'La descripción no puede tener más de 255 caracteres'
    ]);

    // Detectar si la petición viene del modal (flag enviado por JavaScript)
    $esDesdeModal = $request->has('_desde_proveedor') || $request->ajax() || $request->wantsJson();

    // Si falla la validación
    if ($validator->fails()) {
        if ($esDesdeModal) {
            // Devolver errores en JSON para el modal
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
        // Comportamiento normal: redirigir con errores
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        // Crear la categoría
        $categoria = Categoria::create([
            'nombre_categoria' => trim($request->nombre_categoria),
            'descripcion'      => $request->descripcion ? trim($request->descripcion) : null,
            'creado_por'       => Auth::id(),
            'activo'           => true,
            'orden'            => 0,
        ]);

        // Si es desde el modal, devolver JSON con éxito
        if ($esDesdeModal) {
            return response()->json([
                'success'          => true,
                'categoria_id'     => $categoria->id,
                'nombre_categoria' => $categoria->nombre_categoria,
                'message'          => 'Categoría creada correctamente'
            ]);
        }

        // Comportamiento normal: redirigir con mensaje de éxito
        return redirect()
            ->route('categorias.index')
            ->with('success', '✅ Categoría "' . $categoria->nombre_categoria . '" creada exitosamente');

    } catch (\Exception $e) {
        // Si es desde el modal, devolver error en JSON
        if ($esDesdeModal) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
        // Comportamiento normal: redirigir con error
        return redirect()
            ->back()
            ->withInput()
            ->with('error', '❌ Error al crear la categoría: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        $categoria->load(['creador', 'actualizador', 'productos']);
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        // Validación de los datos
        $validator = Validator::make($request->all(), [
            'nombre_categoria' => [
                'required',
                'string',
                'max:100',
                'unique:categorias,nombre_categoria,' . $categoria->id,
                'regex:/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-\.]+$/'
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255'
            ]
        ], [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio',
            'nombre_categoria.unique' => 'Ya existe una categoría con este nombre',
            'nombre_categoria.max' => 'El nombre no puede tener más de 100 caracteres',
            'nombre_categoria.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos',
            'descripcion.max' => 'La descripción no puede tener más de 255 caracteres'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // ✅ Actualizar la categoría con el usuario que la modifica
            $categoria->update([
                'nombre_categoria' => trim($request->nombre_categoria),
                'descripcion' => $request->descripcion ? trim($request->descripcion) : null,
                'actualizado_por' => Auth::id(), // ✅ Registra quién actualiza
            ]);

            return redirect()
                ->route('categorias.index')
                ->with('success', '✅ Categoría "' . $categoria->nombre_categoria . '" actualizada exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        try {
            $nombre = $categoria->nombre_categoria;
            
            // Verificar si tiene productos asociados
            if ($categoria->productos()->count() > 0) {
                return redirect()
                    ->route('categorias.index')
                    ->with('error', '❌ No se puede eliminar la categoría "' . $nombre . '" porque tiene productos asociados');
            }
            
            $categoria->delete();

            return redirect()
                ->route('categorias.index')
                ->with('success', '✅ Categoría "' . $nombre . '" eliminada exitosamente');

        } catch (\Exception $e) {
            return redirect()
                ->route('categorias.index')
                ->with('error', '❌ Error al eliminar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Toggle category status (activate/deactivate).
     */
    public function toggleStatus(Request $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->activo = !$categoria->activo;
            $categoria->actualizado_por = Auth::id();
            $categoria->save();

            $status = $categoria->activo ? 'activada' : 'desactivada';
            
            return response()->json([
                'success' => true,
                'message' => "✅ Categoría {$status} exitosamente",
                'categoria' => $categoria
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search categories.
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $categorias = Categoria::where('nombre_categoria', 'LIKE', "%{$search}%")
            ->orWhere('descripcion', 'LIKE', "%{$search}%")
            ->orderBy('nombre_categoria')
            ->get();

        return response()->json([
            'success' => true,
            'categorias' => $categorias
        ]);
    }
}