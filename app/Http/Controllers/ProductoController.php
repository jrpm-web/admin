<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\EstatusProducto;
use App\Models\Asignacion;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class ProductoController extends Controller
{
    public function index()
    {
       $productos = Producto::with(['categoria', 'proveedor'])
        ->orderBy('nombre', 'asc')
        ->paginate(10);
    
    // 🔧 Cargar los detalles de manera eficiente
    foreach ($productos as $producto) {
        // El accesor getDetallesAttribute se encarga de cargar los detalles
        // según la categoría del producto
        $producto->detalles;
    }
    
    $categorias = Categoria::orderBy('nombre_categoria')->get();
    
    return view('Inventario.index', compact('productos', 'categorias'));
    }

    public function create(Request $request)
    {
        $proveedores = Proveedor::orderBy('proveedor', 'asc')->get();
        $categorias = Categoria::orderBy('nombre_categoria', 'asc')->get();
        
        // Recuperar proveedor_id de la URL
        $proveedorId = $request->query('proveedor_id');
        $proveedorSeleccionado = null;
        $categoriaIdSeleccionada = null;
        $categoriaSeleccionada =  null;

        if ($proveedorId) {
            $proveedorSeleccionado = Proveedor::find($proveedorId);
            // Si el proveedor tiene categoría, la tomamos
            if ($proveedorSeleccionado && $proveedorSeleccionado->categoria_id) {
                $categoriaIdSeleccionada = $proveedorSeleccionado->categoria_id;
                $categoriaSeleccionada = $categorias->firstWhere('id', $categoriaIdSeleccionada);

            }
        }

        return view('Inventario.create', compact('proveedores', 'categorias', 'proveedorSeleccionado','categoriaIdSeleccionada','categoriaSeleccionada'));
        }

        /**
         * Almacenar un nuevo producto.
         */
        public function store(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:100',
        'codigo' => 'required|string|max:50|unique:productos',
        'descripcion' => 'nullable|string|max:500',
        'precio_compra' => 'nullable|numeric|min:0',
        'precio_venta' => 'nullable|numeric|min:0',
        'stock' => 'nullable|integer|min:0',
        'stock_minimo' => 'nullable|integer|min:0',
        'categoria_id' => 'nullable|exists:categorias,id',
        'proveedor_id' => 'nullable|exists:proveedores,id',
        'unidad_medida' => 'nullable|string|max:50',
        'ubicacion' => 'nullable|string|max:100',
        'activo' => 'boolean',
        // Nuevos campos
        'tipo_pago' => 'required|in:unico,recurrente',
        'frecuencia_pago' => 'required_if:tipo_pago,recurrente|in:mensual,trimestral,semestral,anual',
        'dia_facturacion' => 'required_if:tipo_pago,recurrente|integer|min:1|max:31',
        'dias_notificacion' => 'nullable|integer|min:1|max:30',
        'notificar_antes' => 'boolean'
    ]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        DB::beginTransaction();

        // Crear producto
        $producto = Producto::create([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock ?? 0,
            'stock_minimo' => $request->stock_minimo ?? 0,
            'categoria_id' => $request->categoria_id,
            'proveedor_id' => $request->proveedor_id,
            'unidad_medida' => $request->unidad_medida,
            'ubicacion' => $request->ubicacion,
            'activo' => $request->has('activo'),
            // Nuevos campos
            'tipo_pago' => $request->tipo_pago,
            'frecuencia_pago' => $request->frecuencia_pago,
            'dia_facturacion' => $request->dia_facturacion,
            'dias_notificacion' => $request->dias_notificacion ?? 5,
            'notificar_antes' => $request->has('notificar_antes')
        ]);

        // Calcular próxima facturación si es recurrente
        if ($producto->tipo_pago === 'recurrente') {
            $producto->actualizarProximaFacturacion();
        }

        // Crear estatus inicial
        EstatusProducto::create([
            'producto_id' => $producto->id,
            'estatus' => EstatusProducto::ESTATUS_DISPONIBLE,
            'fecha_cambio' => now()
        ]);

        DB::commit();

        return redirect()
            ->route('productos.index')
            ->with('success', '✅ Producto "' . $producto->nombre . '" creado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()
            ->back()
            ->withInput()
            ->with('error', '❌ Error al crear el producto: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    $producto = Producto::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:100',
        'codigo' => 'required|string|max:50|unique:productos,codigo,' . $id,
        'descripcion' => 'nullable|string|max:500',
        'precio_compra' => 'nullable|numeric|min:0',
        'precio_venta' => 'nullable|numeric|min:0',
        'stock' => 'nullable|integer|min:0',
        'stock_minimo' => 'nullable|integer|min:0',
        'categoria_id' => 'nullable|exists:categorias,id',
        'proveedor_id' => 'nullable|exists:proveedores,id',
        'unidad_medida' => 'nullable|string|max:50',
        'ubicacion' => 'nullable|string|max:100',
        'activo' => 'boolean',
        'tipo_pago' => 'required|in:unico,recurrente',
        'frecuencia_pago' => 'required_if:tipo_pago,recurrente|in:mensual,trimestral,semestral,anual',
        'dia_facturacion' => 'required_if:tipo_pago,recurrente|integer|min:1|max:31',
        'dias_notificacion' => 'nullable|integer|min:1|max:30',
        'notificar_antes' => 'boolean'
    ]);

    if ($validator->fails()) {
        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        DB::beginTransaction();

        $producto->update([
            'nombre' => $request->nombre,
            'codigo' => $request->codigo,
            'descripcion' => $request->descripcion,
            'precio_compra' => $request->precio_compra,
            'precio_venta' => $request->precio_venta,
            'stock' => $request->stock ?? 0,
            'stock_minimo' => $request->stock_minimo ?? 0,
            'categoria_id' => $request->categoria_id,
            'proveedor_id' => $request->proveedor_id,
            'unidad_medida' => $request->unidad_medida,
            'ubicacion' => $request->ubicacion,
            'activo' => $request->has('activo'),
            'tipo_pago' => $request->tipo_pago,
            'frecuencia_pago' => $request->frecuencia_pago,
            'dia_facturacion' => $request->dia_facturacion,
            'dias_notificacion' => $request->dias_notificacion ?? 5,
            'notificar_antes' => $request->has('notificar_antes')
        ]);

        // Actualizar próxima facturación si es recurrente
        if ($producto->tipo_pago === 'recurrente') {
            $producto->actualizarProximaFacturacion();
        } else {
            $producto->proxima_facturacion = null;
            $producto->save();
        }

        DB::commit();

        return redirect()
            ->route('productos.index')
            ->with('success', '✅ Producto "' . $producto->nombre . '" actualizado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()
            ->back()
            ->withInput()
            ->with('error', '❌ Error al actualizar el producto: ' . $e->getMessage());
    }
}


    public function edit($id)
    {
        $producto = Producto::with(['estatusActual', 'asignacionActiva'])->findOrFail($id);
        $categorias = Categoria::withoutGlobalScopes()
            ->orderBy('nombre_categoria', 'asc')
            ->get();
        $proveedores = Proveedor::withoutGlobalScopes()
            ->orderBy('proveedor', 'asc')
            ->get();
        
        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    /**
     * Actualizar producto.
     */
    
    /**
     * Eliminar producto (Soft Delete).
     */
    public function destroy($id)
    {
        try {
            $producto = Producto::findOrFail($id);
            $producto->delete();

            return redirect()
                ->route('productos.index')
                ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()
                ->route('productos.index')
                ->with('error', 'Error al eliminar el producto.');
        }
    }
    public function getByProveedor($proveedorId)
{
    $productos = Producto::where('proveedor_id', $proveedorId)
                ->select('id', 'nombre', 'tipo_pago')
                ->orderBy('nombre')
                ->get();

    return response()->json($productos);
}
}