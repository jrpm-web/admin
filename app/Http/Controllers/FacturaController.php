<?php
// app/Http/Controllers/FacturaController.php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\FacturaArchivo;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class FacturaController extends Controller
{
    // ============================================================
    // MÉTODOS CRUD
    // ============================================================

    public function index(Request $request)
    {
        $query = Factura::with(['proveedor', 'creador', 'archivos']);

        $rango = $request->get('rango', 'mes');

        if ($rango === 'mes') {
            $query->whereMonth('fecha_factura', now()->month)
                  ->whereYear('fecha_factura', now()->year);
        } elseif ($rango === 'trimestre') {
            $trimestre = ceil(now()->month / 3);
            $query->whereMonth('fecha_factura', '>=', ($trimestre - 1) * 3 + 1)
                  ->whereMonth('fecha_factura', '<=', $trimestre * 3)
                  ->whereYear('fecha_factura', now()->year);
        } elseif ($rango === 'anio') {
            $query->whereYear('fecha_factura', now()->year);
        } elseif ($rango === 'personalizado') {
            if ($request->filled('fecha_desde')) {
                $query->whereDate('fecha_factura', '>=', $request->fecha_desde);
            }
            if ($request->filled('fecha_hasta')) {
                $query->whereDate('fecha_factura', '<=', $request->fecha_hasta);
            }
        }

        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado_pago', $request->estado);
        }

        if ($request->filled('moneda')) {
            $query->where('moneda', $request->moneda);
        }

        $orden = $request->get('orden', 'fecha_desc');
        switch ($orden) {
            case 'fecha_asc':
                $query->orderBy('fecha_factura', 'asc');
                break;
            case 'fecha_desc':
                $query->orderBy('fecha_factura', 'desc');
                break;
            case 'monto_asc':
                $query->orderBy('monto', 'asc');
                break;
            case 'monto_desc':
                $query->orderBy('monto', 'desc');
                break;
            case 'proveedor':
                $query->orderBy('proveedor_id', 'asc');
                break;
            default:
                $query->orderBy('fecha_factura', 'desc');
        }

        $perPage = $request->get('per_page', 15);
        $facturas = $query->paginate($perPage)->appends($request->all());
        
        $estadisticas = $this->getEstadisticasCompletas($query);

        $proveedores = Proveedor::orderBy('proveedor', 'asc')->get();
        $monedas = Factura::MONEDAS;
        $estadosPago = Factura::ESTADOS_PAGO;

        return view('facturas.index', compact(
            'facturas',
            'proveedores',
            'monedas',
            'estadosPago',
            'estadisticas'
        ));
    }

    public function create(Request $request)
    {
        $proveedores = Proveedor::orderBy('proveedor', 'asc')->get();
        $monedas = Factura::MONEDAS;
        $estadosPago = Factura::ESTADOS_PAGO;

        $proveedorId = $request->query('proveedor_id');
        $proveedorSeleccionado = $proveedorId ? Proveedor::find($proveedorId) : null;

        return view('facturas.create', compact('proveedores', 'monedas', 'estadosPago', 'proveedorSeleccionado'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_factura' => 'required|string|max:50|unique:facturas,numero_factura',
            'proveedor_id'   => 'required|exists:proveedores,id',
            'producto_id'    => 'required|exists:productos,id',
            'monto'          => 'required|numeric|min:0.01',
            'moneda'         => 'required|in:Bs,USD,RUB',
            'tasa_referencia'=> 'nullable|numeric|min:0',
            'fecha_factura'  => 'required|date',
            'fecha_pago'     => 'nullable|date|after_or_equal:fecha_factura',
            'estado_pago'    => 'required|in:pendiente,pagado,parcial,vencido',
            'observaciones'  => 'nullable|string|max:500',
            'archivo_factura'=> 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,zip|max:5120'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Cálculo de monto en USD
        $monto_usd = null;
        if ($request->moneda === 'USD') {
            $monto_usd = $request->monto;
        } elseif ($request->filled('tasa_referencia') && $request->tasa_referencia > 0) {
            $monto_usd = $request->monto / $request->tasa_referencia;
        }

        $mes = Carbon::parse($request->fecha_factura)->translatedFormat('F Y');

        // INTENTA GUARDAR AQUÍ DIRECTAMENTE PARA VER EL ERROR REAL SI FALLA
        $factura = Factura::create([
            'numero_factura'  => $request->numero_factura,
            'proveedor_id'    => $request->proveedor_id,
            'producto_id'     => $request->producto_id,
            'monto'           => $request->monto,
            'moneda'          => $request->moneda,
            'tasa_referencia' => $request->tasa_referencia,
            'monto_usd'       => $monto_usd,
            'mes'             => $mes,
            'fecha_factura'   => $request->fecha_factura,
            'fecha_pago'      => $request->fecha_pago,
            'estado_pago'     => $request->estado_pago,
            'observaciones'   => $request->observaciones,
            'creado_por'      => Auth::id(),
        ]);

            // Manejo del archivo hacia la carpeta public/factura
        if ($request->hasFile('archivo_factura')) {
            $file = $request->file('archivo_factura');
            
            // 1. Obtener los datos del archivo ANTES de moverlo
            $nombreOriginal = $file->getClientOriginalName();
            $extension      = $file->getClientOriginalExtension();
            $tamanio        = $file->getSize(); // <-- Aquí ya no fallará
            $tipoArchivo    = $extension;
            $mimeType       = $file->getMimeType();
            // 2. Generar nombre y ruta
            $nombreGuardado = time() . '_' . uniqid() . '.' . $extension;
            $destinationPath = public_path('factura');
            
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // 3. Mover el archivo
            $file->move($destinationPath, $nombreGuardado);
            
            $rutaRelativa = 'factura/' . $nombreGuardado;

            // 4. Crear el registro en la base de datos con las variables previamente guardadas
            FacturaArchivo::create([
                
                'factura_id'      => $factura->id,
                'nombre_original' => $nombreOriginal,
                'nombre_guardado' => $nombreGuardado,
                'ruta'            => $rutaRelativa,
                'tipo_archivo'    => $tipoArchivo,
                'tamaño'          => $tamanio,
                'mime_type'       => $mimeType,
                'categoria'       => 'factura',
                'descripcion'     => 'Factura ' . $factura->numero_factura,
                'subido_por'      => Auth::id()
            
            ]);
        }

        return redirect()->route('facturas.index')->with('success', 'Factura creada exitosamente');
    }

    public function show($id)
    {
        $factura = Factura::with([
            'proveedor',
            'creador',
            'actualizador',
            'archivos' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        return view('facturas.show', compact('factura'));
    }

    public function edit($id)
    {
        $factura = Factura::with('archivos')->findOrFail($id);
        $proveedores = Proveedor::orderBy('proveedor', 'asc')->get();
        $monedas = Factura::MONEDAS;
        $estadosPago = Factura::ESTADOS_PAGO;

        return view('facturas.edit', compact('factura', 'proveedores', 'monedas', 'estadosPago'));
    }

    public function update(Request $request, $id)
    {
        $factura = Factura::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'numero_factura' => 'required|string|max:50|unique:facturas,numero_factura,' . $id,
            'proveedor_id' => 'required|exists:proveedores,id',
            'monto' => 'required|numeric|min:0.01',
            'moneda' => 'required|in:Bs,USD,RUB',
            'tasa_referencia' => 'nullable|numeric|min:0',
            'fecha_factura' => 'required|date',
            'fecha_pago' => 'nullable|date|after_or_equal:fecha_factura',
            'estado_pago' => 'required|in:pendiente,pagado,parcial,vencido',
            'observaciones' => 'nullable|string|max:500',
            'archivo_factura' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,zip|max:5120'
        ], [
            'archivo_factura.max' => 'El archivo no debe pesar más de 5MB.',
            'archivo_factura.mimes' => 'El archivo debe ser: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX o ZIP.',
            'numero_factura.unique' => 'Este número de factura ya existe en el sistema.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rutaAbsolutaNueva = null;

        try {
            DB::beginTransaction();

            $monto_usd = null;
            if ($request->moneda === 'USD') {
                $monto_usd = $request->monto;
            } elseif ($request->tasa_referencia && $request->tasa_referencia > 0) {
                $monto_usd = $request->monto / $request->tasa_referencia;
            }

            $factura->update([
                'numero_factura' => $request->numero_factura,
                'proveedor_id' => $request->proveedor_id,
                'monto' => $request->monto,
                'moneda' => $request->moneda,
                'tasa_referencia' => $request->tasa_referencia,
                'monto_usd' => $monto_usd,
                'mes' => date('F Y', strtotime($request->fecha_factura)),
                'fecha_factura' => $request->fecha_factura,
                'fecha_pago' => $request->fecha_pago,
                'estado_pago' => $request->estado_pago,
                'observaciones' => $request->observaciones,
                'actualizado_por' => Auth::id(),
            ]);

            if ($request->hasFile('archivo_factura')) {
                $archivoAnterior = $factura->archivos()
                    ->where('categoria', 'factura')
                    ->first();

                if ($archivoAnterior) {
                    $this->borrarArchivoFisico($archivoAnterior->ruta);
                    $archivoAnterior->delete();
                }

                $resultado = $this->guardarArchivo(
                    $request->file('archivo_factura'),
                    $factura,
                    'factura'
                );
                $rutaAbsolutaNueva = $resultado['ruta_absoluta'];

                FacturaArchivo::create([
                    'factura_id'      => $factura->id,
                    'nombre_original' => $resultado['nombre_original'],
                    'nombre_guardado' => $resultado['nombre_guardado'],
                    'ruta'            => $resultado['ruta_relativa'],
                    'tipo_archivo'    => $resultado['extension'],
                    'tamaño'          => $resultado['tamano'],
                    'mime_type'       => $resultado['mime_type'],
                    'categoria'       => 'factura',
                    'descripcion'     => 'Factura ' . $factura->numero_factura,
                    'hash'            => $resultado['hash'],
                    'subido_por'      => Auth::id()
                ]);
            }

            DB::commit();

            return redirect()
                ->route('facturas.index')
                ->with('success', '✅ Factura "' . $factura->numero_factura . '" actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($rutaAbsolutaNueva && file_exists($rutaAbsolutaNueva)) {
                @unlink($rutaAbsolutaNueva);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar la factura: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $factura = Factura::findOrFail($id);
            $numero = $factura->numero_factura;

            $archivos = $factura->archivos;
            foreach ($archivos as $archivo) {
                $this->borrarArchivoFisico($archivo->ruta);
                $archivo->delete();
            }

            $directorio = public_path('facturas/' . $factura->id);
            if (is_dir($directorio)) {
                @rmdir($directorio);
            }

            $factura->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Factura "' . $numero . '" eliminada exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => '❌ Error al eliminar la factura: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // HELPERS DE ARCHIVOS (move a public/)
    // ============================================================

    /**
     * Guarda un archivo subido en public/facturas/{factura_id}/
     */
    private function guardarArchivo($archivo, Factura $factura, string $categoria): array
    {
        // 1. Validar subida
        if (!$archivo->isValid()) {
            throw new \RuntimeException(
                'El archivo no se subió correctamente. Código PHP: ' .
                $archivo->getError() . ' - ' . $archivo->getErrorMessage() .
                ' (Revisa upload_tmp_dir en php.ini)'
            );
        }

        // 2. Verificar ruta temporal
        $rutaTemporal = $archivo->getRealPath();
        if ($rutaTemporal === false || $rutaTemporal === '') {
            throw new \RuntimeException(
                'La ruta temporal del archivo está vacía. ' .
                'Causa probable: upload_tmp_dir mal configurado en php.ini.'
            );
        }

        $extension      = strtolower($archivo->getClientOriginalExtension());
        $nombreOriginal = $archivo->getClientOriginalName();
        $tamano         = $archivo->getSize();
        $mimeType       = $archivo->getMimeType();
        $nombreGuardado = $this->generarNombreAleatorio($extension);

        // 3. Hash ANTES de mover
        $hash = hash_file('sha256', $rutaTemporal);

        // 4. Directorio destino
        $directorioDestino = public_path('facturas/' . $factura->id);
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0755, true) && !is_dir($directorioDestino)) {
                throw new \RuntimeException('No se pudo crear el directorio: ' . $directorioDestino);
            }
        }

        // 5. Mover archivo
        $archivo->move($directorioDestino, $nombreGuardado);

        $rutaAbsoluta = $directorioDestino . DIRECTORY_SEPARATOR . $nombreGuardado;
        $rutaRelativa = 'facturas/' . $factura->id . '/' . $nombreGuardado;

        return [
            'nombre_original' => $nombreOriginal,
            'nombre_guardado' => $nombreGuardado,
            'ruta_relativa'   => $rutaRelativa,
            'ruta_absoluta'   => $rutaAbsoluta,
            'extension'       => $extension,
            'tamano'          => $tamano,
            'mime_type'       => $mimeType,
            'hash'            => $hash,
            'categoria'       => $categoria,
        ];
    }

    /**
     * Borra un archivo físico en public/ a partir de su ruta relativa.
     */
    private function borrarArchivoFisico(string $rutaRelativa): bool
    {
        $rutaAbsoluta = public_path($rutaRelativa);
        if (file_exists($rutaAbsoluta)) {
            return @unlink($rutaAbsoluta);
        }
        return false;
    }

    // ============================================================
    // ESTADÍSTICAS COMPLETAS
    // ============================================================

    private function getEstadisticasCompletas($query)
    {
        $mesAnterior = now()->subMonth();
        $queryMesAnterior = Factura::whereMonth('fecha_factura', $mesAnterior->month)
                                   ->whereYear('fecha_factura', $mesAnterior->year);

        if (request()->filled('proveedor_id')) {
            $queryMesAnterior->where('proveedor_id', request()->proveedor_id);
        }

        $totalFacturas   = (clone $query)->count();
        $totalPagadas    = (clone $query)->where('estado_pago', 'pagado')->count();
        $totalPendientes = (clone $query)->where('estado_pago', 'pendiente')->count();
        $totalVencidas   = (clone $query)->where('estado_pago', 'vencido')->count();
        $totalParciales  = (clone $query)->where('estado_pago', 'parcial')->count();

        $totalMontoBs  = (clone $query)->where('moneda', 'Bs')->sum('monto');
        $totalMontoUsd = (clone $query)->sum('monto_usd');
        $totalMontoRub = (clone $query)->where('moneda', 'RUB')->sum('monto');

        $totalFacturasAnterior = $queryMesAnterior->count();
        $totalMontoUsdAnterior = $queryMesAnterior->sum('monto_usd');

        $tendenciaTotal = 0;
        if ($totalFacturasAnterior > 0) {
            $tendenciaTotal = (($totalFacturas - $totalFacturasAnterior) / $totalFacturasAnterior) * 100;
        }

        $tendenciaMonto = 0;
        if ($totalMontoUsdAnterior > 0) {
            $tendenciaMonto = (($totalMontoUsd - $totalMontoUsdAnterior) / $totalMontoUsdAnterior) * 100;
        }

        $porcentajePagado    = $totalFacturas > 0 ? ($totalPagadas / $totalFacturas) * 100 : 0;
        $porcentajePendiente = $totalFacturas > 0 ? ($totalPendientes / $totalFacturas) * 100 : 0;
        $porcentajeVencido   = $totalFacturas > 0 ? ($totalVencidas / $totalFacturas) * 100 : 0;
        $porcentajeParcial   = $totalFacturas > 0 ? ($totalParciales / $totalFacturas) * 100 : 0;
        $promedioPorFactura  = $totalFacturas > 0 ? $totalMontoUsd / $totalFacturas : 0;

        return [
            'total_facturas'          => $totalFacturas,
            'total_pagadas'           => $totalPagadas,
            'total_pendientes'        => $totalPendientes,
            'total_vencidas'          => $totalVencidas,
            'total_parciales'         => $totalParciales,
            'total_monto_bs'          => $totalMontoBs,
            'total_monto_usd'         => $totalMontoUsd,
            'total_monto_rub'         => $totalMontoRub,
            'tendencia_total'         => $tendenciaTotal,
            'tendencia_monto'         => $tendenciaMonto,
            'porcentaje_pagado'       => round($porcentajePagado, 1),
            'porcentaje_pendiente'    => round($porcentajePendiente, 1),
            'porcentaje_vencido'      => round($porcentajeVencido, 1),
            'porcentaje_parcial'      => round($porcentajeParcial, 1),
            'promedio_por_factura'    => $promedioPorFactura,
            'total_facturas_anterior' => $totalFacturasAnterior,
            'total_monto_usd_anterior'=> $totalMontoUsdAnterior,
        ];
    }

    // ============================================================
    // GRÁFICA PRINCIPAL
    // ============================================================

    public function getGraficaData(Request $request)
    {
        $proveedorId = $request->get('proveedor_id');
        $periodos = $request->get('periodos', 12);

        $query = Factura::with('proveedor');

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $fechaInicio = now()->subMonths($periodos)->startOfMonth();
        $query->where('fecha_factura', '>=', $fechaInicio);

        $facturas = $query->orderBy('fecha_factura', 'asc')->get();

        $data = [];
        foreach ($facturas as $factura) {
            $mes = $factura->fecha_factura->format('M Y');
            if (!isset($data[$mes])) {
                $data[$mes] = [
                    'monto_total' => 0,
                    'monto_usd'   => 0,
                    'facturas'    => [],
                    'promedio'    => 0
                ];
            }
            $data[$mes]['monto_total'] += $factura->monto;
            $data[$mes]['monto_usd']   += $factura->monto_usd ?? 0;
            $data[$mes]['facturas'][]  = $factura;
        }

        $labels = [];
        $montos = [];
        $montosUsd = [];
        $promedios = [];
        $proyecciones = [];

        foreach ($data as $mes => $values) {
            $labels[] = $mes;
            $montosUsd[] = $values['monto_usd'];
            $montos[] = $values['monto_usd'];
            $acumulado = array_sum($montos);
            $count = count($labels);
            $promedios[] = $acumulado / $count;

            if (count($labels) >= 3) {
                $proyecciones[] = $this->calcularProyeccionSimple($montos);
            } else {
                $proyecciones[] = null;
            }
        }

        $proyeccionFinal = end($proyecciones) ?? 0;
        $promedioGeneral = count($montos) > 0 ? array_sum($montos) / count($montos) : 0;
        $totalGeneral = array_sum($montos);
        $totalUsd = array_sum($montosUsd);

        return response()->json([
            'success' => true,
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Monto Mensual',
                    'data' => $montos,
                    'borderColor' => '#667eea',
                    'backgroundColor' => 'rgba(102, 126, 234, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Promedio Mensual',
                    'data' => $promedios,
                    'borderColor' => '#f59e0b',
                    'borderDash' => [5, 5],
                    'fill' => false,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Proyección',
                    'data' => $proyecciones,
                    'borderColor' => '#ef4444',
                    'borderDash' => [10, 5],
                    'fill' => false,
                    'tension' => 0.4,
                ]
            ],
            'estadisticas' => [
                'total_mes'        => count($labels),
                'total_general'    => $totalGeneral,
                'total_usd'        => $totalUsd,
                'promedio_general' => $promedioGeneral,
                'proyeccion_final' => $proyeccionFinal,
            ]
        ]);
    }

    private function calcularProyeccionSimple($datos)
    {
        $n = count($datos);
        if ($n < 3) return null;

        $x = range(1, $n);
        $y = $datos;

        $sumX  = array_sum($x);
        $sumY  = array_sum($y);
        $sumXY = array_sum(array_map(function ($x, $y) { return $x * $y; }, $x, $y));
        $sumX2 = array_sum(array_map(function ($x) { return $x * $x; }, $x));

        $denominador = ($n * $sumX2 - $sumX * $sumX);
        if ($denominador == 0) return null;

        $m = ($n * $sumXY - $sumX * $sumY) / $denominador;
        $b = ($sumY - $m * $sumX) / $n;

        $proximo = $n + 1;
        return $m * $proximo + $b;
    }

    // ============================================================
    // HELPERS DE ARCHIVOS PÚBLICOS
    // ============================================================

    private function generarNombreAleatorio($extension)
    {
        $bytes = random_bytes(32);
        $hash = bin2hex($bytes);
        $timestamp = time();
        return $timestamp . '_' . $hash . '.' . $extension;
    }

    public function descargarArchivo($facturaId, $archivoId)
    {
        $archivo = FacturaArchivo::where('factura_id', $facturaId)
            ->findOrFail($archivoId);

        $rutaAbsoluta = public_path($archivo->ruta);

        if (!file_exists($rutaAbsoluta)) {
            return back()->with('error', 'El archivo no se encuentra en el servidor.');
        }

        return response()->download($rutaAbsoluta, $archivo->nombre_original);
    }

    public function verArchivo($facturaId, $archivoId)
    {
        $archivo = FacturaArchivo::where('factura_id', $facturaId)
            ->findOrFail($archivoId);

        $rutaAbsoluta = public_path($archivo->ruta);

        if (!file_exists($rutaAbsoluta)) {
            abort(404, 'Archivo no encontrado');
        }

        if (in_array($archivo->tipo_archivo, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
            return response()->file($rutaAbsoluta);
        }

        if ($archivo->tipo_archivo === 'pdf') {
            return response()->file($rutaAbsoluta, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $archivo->nombre_original . '"'
            ]);
        }

        return response()->download($rutaAbsoluta, $archivo->nombre_original);
    }

    public function eliminarArchivo($archivoId)
    {
        try {
            $archivo = FacturaArchivo::findOrFail($archivoId);
            $facturaId = $archivo->factura_id;

            $this->borrarArchivoFisico($archivo->ruta);
            $archivo->delete();

            $directorio = public_path('facturas/' . $facturaId);
            if (is_dir($directorio)) {
                $archivosRestantes = FacturaArchivo::where('factura_id', $facturaId)->count();
                if ($archivosRestantes === 0) {
                    @rmdir($directorio);
                }
            }

            return response()->json([
                'success' => true,
                'message' => '✅ Archivo eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al eliminar el archivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function subirArchivo(Request $request, $facturaId)
    {
        $factura = Factura::findOrFail($facturaId);

        $validator = Validator::make($request->all(), [
            'archivo' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,zip|max:5120',
            'categoria' => 'required|in:factura,soporte,anexo,comprobante,otro',
            'descripcion' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rutaAbsoluta = null;

        try {
            DB::beginTransaction();

            $resultado = $this->guardarArchivo(
                $request->file('archivo'),
                $factura,
                $request->categoria
            );
            $rutaAbsoluta = $resultado['ruta_absoluta'];

            FacturaArchivo::create([
                'factura_id'      => $factura->id,
                'nombre_original' => $resultado['nombre_original'],
                'nombre_guardado' => $resultado['nombre_guardado'],
                'ruta'            => $resultado['ruta_relativa'],
                'tipo_archivo'    => $resultado['extension'],
                'tamaño'          => $resultado['tamano'],
                'mime_type'       => $resultado['mime_type'],
                'categoria'       => $request->categoria,
                'descripcion'     => $request->descripcion ?? $request->categoria,
                'hash'            => $resultado['hash'],
                'subido_por'      => Auth::id()
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', '✅ Archivo subido exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($rutaAbsoluta && file_exists($rutaAbsoluta)) {
                @unlink($rutaAbsoluta);
            }

            return redirect()
                ->back()
                ->with('error', '❌ Error al subir el archivo: ' . $e->getMessage());
        }
    }

    // ============================================================
    // ESTADÍSTICAS Y REPORTES
    // ============================================================

    public function estadisticas(Request $request)
    {
        $proveedores = Proveedor::orderBy('proveedor', 'asc')->get();

        $anio = $request->get('anio', date('Y'));
        $proveedorId = $request->get('proveedor_id', null);

        $datosConProyeccion = $this->getDatosMensualesConProyeccion($anio, $proveedorId);

        $query = Factura::whereYear('fecha_factura', $anio);
        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $totalFacturas   = $query->count();
        $totalMonto      = $query->sum('monto');
        $totalUsd        = $query->sum('monto_usd');
        $totalPendientes = (clone $query)->where('estado_pago', 'pendiente')->count();
        $totalPagadas    = (clone $query)->where('estado_pago', 'pagado')->count();
        $totalVencidas   = (clone $query)->where('estado_pago', 'vencido')->count();

        $gastosProveedor = Factura::select(
            'proveedor_id',
            DB::raw('SUM(monto) as total_monto'),
            DB::raw('SUM(monto_usd) as total_usd'),
            DB::raw('COUNT(*) as total_facturas'),
            DB::raw('AVG(monto) as promedio_monto')
        )
        ->whereYear('fecha_factura', $anio)
        ->groupBy('proveedor_id')
        ->with('proveedor')
        ->orderBy(DB::raw('SUM(monto)'), 'desc')
        ->get();

        $labelsProveedores = [];
        $montosProveedores = [];
        $coloresProveedores = [
            '#667eea', '#764ba2', '#f093fb', '#4facfe',
            '#43e97b', '#fa709a', '#fee140', '#00b894',
            '#e17055', '#0984e3', '#00cec9', '#6c5ce7'
        ];

        foreach ($gastosProveedor as $index => $item) {
            $labelsProveedores[] = $item->proveedor->proveedor ?? 'Sin proveedor';
            $montosProveedores[] = round($item->total_monto, 2);
        }

        $mesesConDatos = 0;
        $mayorMes = '';
        $mayorMonto = 0;
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $datosHistoricos = $datosConProyeccion['datos_historicos'] ?? [];

        foreach ($datosHistoricos as $mes => $dato) {
            if ($dato['monto'] > 0) {
                $mesesConDatos++;
                if ($dato['monto'] > $mayorMonto) {
                    $mayorMonto = $dato['monto'];
                    $mayorMes = $meses[$mes - 1] . ' ' . $anio;
                }
            }
        }

        $totalProveedores  = $gastosProveedor->count();
        $ultimaTasa        = $datosConProyeccion['ultima_tasa'] ?? null;
        $incrementoPromedio = $datosConProyeccion['incremento_promedio'] ?? 0;

        return view('facturas.estadisticas', compact(
            'proveedores',
            'anio',
            'proveedorId',
            'totalFacturas',
            'totalMonto',
            'totalUsd',
            'totalPendientes',
            'totalPagadas',
            'totalVencidas',
            'gastosProveedor',
            'labelsProveedores',
            'montosProveedores',
            'coloresProveedores',
            'datosConProyeccion',
            'incrementoPromedio',
            'mesesConDatos',
            'mayorMes',
            'mayorMonto',
            'totalProveedores',
            'ultimaTasa'
        ));
    }

    private function getDatosMensualesConProyeccion($anio, $proveedorId = null)
    {
        $query = Factura::select(
            DB::raw('MONTH(fecha_factura) as mes'),
            DB::raw('SUM(monto) as total_monto'),
            DB::raw('SUM(monto_usd) as total_monto_usd'),
            DB::raw('AVG(tasa_referencia) as tasa_promedio'),
            DB::raw('COUNT(*) as total_facturas')
        )
        ->whereYear('fecha_factura', $anio);

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $resultados = $query->groupBy(DB::raw('MONTH(fecha_factura)'))
            ->orderBy(DB::raw('MONTH(fecha_factura)'))
            ->get()
            ->keyBy('mes');

        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $datosHistoricos = [];
        $incrementosLista = [];
        $incrementosUsdLista = [];

        $montoAnterior = null;
        $montoUsdAnterior = null;

        for ($i = 1; $i <= 12; $i++) {
            $registro    = $resultados->get($i);
            $monto       = $registro ? $registro->total_monto : 0;
            $montoUsd    = $registro ? $registro->total_monto_usd : 0;
            $tasaPromedio = $registro ? $registro->tasa_promedio : null;
            $facturas    = $registro ? $registro->total_facturas : 0;

            $incremento = null;
            if ($montoAnterior !== null && $montoAnterior > 0 && $monto > 0) {
                $incremento = (($monto - $montoAnterior) / $montoAnterior) * 100;
                $incrementosLista[] = $incremento;
            }

            $incrementoUsd = null;
            if ($montoUsdAnterior !== null && $montoUsdAnterior > 0 && $montoUsd > 0) {
                $incrementoUsd = (($montoUsd - $montoUsdAnterior) / $montoUsdAnterior) * 100;
                $incrementosUsdLista[] = $incrementoUsd;
            }

            $datosHistoricos[$i] = [
                'monto'          => $monto,
                'monto_usd'      => $montoUsd,
                'tasa_promedio'  => $tasaPromedio,
                'incremento'     => $incremento,
                'incremento_usd' => $incrementoUsd,
                'facturas'       => $facturas,
                'es_real'        => $monto > 0
            ];

            if ($monto > 0) {
                $montoAnterior = $monto;
                $montoUsdAnterior = $montoUsd;
            }
        }

        $incrementoPromedio = 0;
        if (count($incrementosLista) > 0) {
            $incrementoPromedio = array_sum($incrementosLista) / count($incrementosLista);
        }

        $incrementoPromedioUsd = 0;
        if (count($incrementosUsdLista) > 0) {
            $incrementoPromedioUsd = array_sum($incrementosUsdLista) / count($incrementosUsdLista);
        }

        $ultimoMontoReal    = 0;
        $ultimoMontoUsdReal = 0;
        $ultimoMesConDatos  = 0;
        $ultimaTasa         = null;

        for ($i = 12; $i >= 1; $i--) {
            if ($datosHistoricos[$i]['monto'] > 0) {
                $ultimoMontoReal    = $datosHistoricos[$i]['monto'];
                $ultimoMontoUsdReal = $datosHistoricos[$i]['monto_usd'];
                $ultimoMesConDatos  = $i;
                $ultimaTasa         = $datosHistoricos[$i]['tasa_promedio'];
                break;
            }
        }

        $mesesProyectados = [];
        $montosProyectados = [];
        $montosUsdProyectados = [];
        $incrementosProyectados = [];
        $incrementosUsdProyectados = [];
        $tasaProyectada = $ultimaTasa;

        $montoProyectadoAcumulado    = $ultimoMontoReal;
        $montoUsdProyectadoAcumulado = $ultimoMontoUsdReal;

        for ($i = 1; $i <= 12; $i++) {
            if ($datosHistoricos[$i]['monto'] > 0) {
                $mesesProyectados[] = $meses[$i - 1];
                $montosProyectados[] = $datosHistoricos[$i]['monto'];
                $montosUsdProyectados[] = $datosHistoricos[$i]['monto_usd'];
                $incrementosProyectados[] = $datosHistoricos[$i]['incremento'] ?? 0;
                $incrementosUsdProyectados[] = $datosHistoricos[$i]['incremento_usd'] ?? 0;
                $montoProyectadoAcumulado = $datosHistoricos[$i]['monto'];
                $montoUsdProyectadoAcumulado = $datosHistoricos[$i]['monto_usd'];
                $tasaProyectada = $datosHistoricos[$i]['tasa_promedio'] ?? $tasaProyectada;
            } else {
                $montoProyectado    = $montoProyectadoAcumulado * (1 + ($incrementoPromedio / 100));
                $montoUsdProyectado = $montoUsdProyectadoAcumulado * (1 + ($incrementoPromedioUsd / 100));

                $mesesProyectados[] = $meses[$i - 1] . '*';
                $montosProyectados[] = $montoProyectado;
                $montosUsdProyectados[] = $montoUsdProyectado;
                $incrementosProyectados[] = $incrementoPromedio;
                $incrementosUsdProyectados[] = $incrementoPromedioUsd;

                $montoProyectadoAcumulado = $montoProyectado;
                $montoUsdProyectadoAcumulado = $montoUsdProyectado;
            }
        }

        $mesSiguiente = $ultimoMesConDatos + 1;
        if ($mesSiguiente > 12) {
            $mesSiguiente = 1;
        }
        $montoProyectadoSiguiente    = $ultimoMontoReal * (1 + ($incrementoPromedio / 100));
        $montoUsdProyectadoSiguiente = $ultimoMontoUsdReal * (1 + ($incrementoPromedioUsd / 100));

        return [
            'meses'                          => $meses,
            'datos_historicos'               => $datosHistoricos,
            'meses_proyectados'              => $mesesProyectados,
            'montos_proyectados'             => $montosProyectados,
            'montos_usd_proyectados'         => $montosUsdProyectados,
            'incrementos_proyectados'        => $incrementosProyectados,
            'incrementos_usd_proyectados'    => $incrementosUsdProyectados,
            'incremento_promedio'            => $incrementoPromedio,
            'incremento_promedio_usd'        => $incrementoPromedioUsd,
            'ultimo_monto_real'              => $ultimoMontoReal,
            'ultimo_monto_usd_real'          => $ultimoMontoUsdReal,
            'ultimo_mes_con_datos'           => $ultimoMesConDatos,
            'monto_proyectado_siguiente'     => $montoProyectadoSiguiente,
            'monto_usd_proyectado_siguiente' => $montoUsdProyectadoSiguiente,
            'mes_siguiente'                  => $mesSiguiente,
            'ultima_tasa'                    => $ultimaTasa,
            'tasa_proyectada'                => $tasaProyectada
        ];
    }

    public function reportes(Request $request)
    {
        $proveedores = Proveedor::orderBy('proveedor', 'asc')->get();

        $anio = $request->get('anio', date('Y'));
        $proveedorId = $request->get('proveedor_id', null);

        $tortaData     = $this->getTortaData($anio, $proveedorId);
        $lineaBsData   = $this->getLineaData($anio, $proveedorId, 'Bs');
        $lineaUsdData  = $this->getLineaData($anio, $proveedorId, 'USD');
        $proyeccionData = $this->getProyeccionData($proveedorId);
        $estadisticas  = $this->getEstadisticas($anio, $proveedorId);

        return view('facturas.reportes', compact(
            'proveedores',
            'anio',
            'proveedorId',
            'tortaData',
            'lineaBsData',
            'lineaUsdData',
            'proyeccionData',
            'estadisticas'
        ));
    }

    private function getTortaData($anio, $proveedorId = null)
    {
        $query = Factura::select(
            'proveedor_id',
            DB::raw('SUM(monto) as total_monto'),
            DB::raw('SUM(monto_usd) as total_usd')
        )
        ->whereYear('fecha_factura', $anio)
        ->groupBy('proveedor_id')
        ->with('proveedor');

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $resultados = $query->get();

        $labels = [];
        $montos = [];
        $montosUsd = [];
        $colores = [
            '#667eea', '#764ba2', '#f093fb', '#4facfe',
            '#43e97b', '#fa709a', '#fee140', '#00b894',
            '#e17055', '#0984e3', '#00cec9', '#6c5ce7'
        ];

        foreach ($resultados as $index => $item) {
            $labels[]    = $item->proveedor->proveedor ?? 'Sin proveedor';
            $montos[]    = $item->total_monto;
            $montosUsd[] = $item->total_usd;
        }

        return [
            'labels'    => $labels,
            'montos'    => $montos,
            'colores'   => array_slice($colores, 0, count($labels)),
            'total_usd' => $montosUsd
        ];
    }

    private function getLineaData($anio, $proveedorId = null, $moneda = 'Bs')
    {
        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        $query = Factura::select(
            DB::raw('MONTH(fecha_factura) as mes'),
            DB::raw('SUM(monto) as total_monto'),
            DB::raw('SUM(monto_usd) as total_usd'),
            DB::raw('COUNT(*) as total_facturas')
        )
        ->whereYear('fecha_factura', $anio);

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $resultados = $query->groupBy(DB::raw('MONTH(fecha_factura)'))
            ->orderBy(DB::raw('MONTH(fecha_factura)'))
            ->get()
            ->keyBy('mes');

        $data = [];
        $dataFacturas = [];

        for ($i = 1; $i <= 12; $i++) {
            $registro = $resultados->get($i);
            if ($moneda === 'Bs') {
                $data[] = $registro ? round($registro->total_monto, 2) : 0;
            } else {
                $data[] = $registro ? round($registro->total_usd, 2) : 0;
            }
            $dataFacturas[] = $registro ? $registro->total_facturas : 0;
        }

        return [
            'labels'   => $meses,
            'data'     => $data,
            'facturas' => $dataFacturas,
            'moneda'   => $moneda
        ];
    }

    private function getProyeccionData($proveedorId = null)
    {
        $query = Factura::select(
            DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m") as mes'),
            DB::raw('SUM(monto) as total_monto'),
            DB::raw('SUM(monto_usd) as total_usd')
        )
        ->where('fecha_factura', '>=', now()->subMonths(12))
        ->where('fecha_factura', '<=', now());

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $resultados = $query->groupBy(DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
            ->get();

        $labels = [];
        $datosBs = [];
        $datosUsd = [];
        $proyeccionBs = [];
        $proyeccionUsd = [];

        foreach ($resultados as $item) {
            $labels[]   = $item->mes;
            $datosBs[]  = round($item->total_monto, 2);
            $datosUsd[] = round($item->total_usd, 2);
        }

        if (count($datosBs) >= 3) {
            $proyeccionBs  = $this->calcularProyeccionLineal($datosBs, 6);
            $proyeccionUsd = $this->calcularProyeccionLineal($datosUsd, 6);

            $ultimoMes = end($labels);
            for ($i = 1; $i <= 6; $i++) {
                $fecha = date('Y-m', strtotime($ultimoMes . " +{$i} months"));
                $labels[] = $fecha . ' (proy)';
            }
        }

        return [
            'labels'         => $labels,
            'datos_bs'       => $datosBs,
            'datos_usd'      => $datosUsd,
            'proyeccion_bs'  => $proyeccionBs,
            'proyeccion_usd' => $proyeccionUsd,
            'total_meses'    => count($datosBs)
        ];
    }

    private function calcularProyeccionLineal($datos, $mesesProyectar)
    {
        $n = count($datos);
        if ($n < 3) return array_fill(0, $mesesProyectar, 0);

        $x = range(1, $n);
        $y = $datos;

        $sumX  = array_sum($x);
        $sumY  = array_sum($y);
        $sumXY = array_sum(array_map(function ($x, $y) { return $x * $y; }, $x, $y));
        $sumX2 = array_sum(array_map(function ($x) { return $x * $x; }, $x));

        $denominador = ($n * $sumX2 - $sumX * $sumX);
        if ($denominador == 0) return array_fill(0, $mesesProyectar, 0);

        $m = ($n * $sumXY - $sumX * $sumY) / $denominador;
        $b = ($sumY - $m * $sumX) / $n;

        $proyeccion = [];
        for ($i = 1; $i <= $mesesProyectar; $i++) {
            $proximo = $n + $i;
            $valor = max(0, $m * $proximo + $b);
            $proyeccion[] = round($valor, 2);
        }

        return $proyeccion;
    }

    private function getEstadisticas($anio, $proveedorId = null)
    {
        $query = Factura::whereYear('fecha_factura', $anio);

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $totalFacturas  = $query->count();
        $totalMonto     = $query->sum('monto');
        $totalUsd       = $query->sum('monto_usd');
        $promedioMensual = $totalMonto / 12;
        $promedioUsd    = $totalUsd / 12;

        $pendientes = (clone $query)->where('estado_pago', 'pendiente')->count();
        $pagadas    = (clone $query)->where('estado_pago', 'pagado')->count();
        $vencidas   = (clone $query)->where('estado_pago', 'vencido')->count();

        $queryMes = Factura::whereYear('fecha_factura', $anio);
        if ($proveedorId) {
            $queryMes->where('proveedor_id', $proveedorId);
        }

        $meses = $queryMes->select(
            DB::raw('MONTH(fecha_factura) as mes'),
            DB::raw('SUM(monto) as total_monto')
        )
        ->groupBy(DB::raw('MONTH(fecha_factura)'))
        ->orderBy(DB::raw('MONTH(fecha_factura)'))
        ->get()
        ->keyBy('mes');

        $ultimos3 = 0;
        $anteriores3 = 0;
        for ($i = 1; $i <= 12; $i++) {
            if ($i >= 10) {
                $ultimos3 += $meses->get($i)->total_monto ?? 0;
            } elseif ($i >= 7) {
                $anteriores3 += $meses->get($i)->total_monto ?? 0;
            }
        }

        $incremento = $anteriores3 > 0 ? (($ultimos3 - $anteriores3) / $anteriores3) * 100 : 0;

        return [
            'total_facturas'   => $totalFacturas,
            'total_monto'      => $totalMonto,
            'total_usd'        => $totalUsd,
            'promedio_mensual' => $promedioMensual,
            'promedio_usd'     => $promedioUsd,
            'pendientes'       => $pendientes,
            'pagadas'          => $pagadas,
            'vencidas'         => $vencidas,
            'incremento'       => round($incremento, 2),
            'anio'             => $anio
        ];
    }

    public function getFacturasPorMes(Request $request)
    {
        $mes = $request->get('mes');
        $anio = $request->get('anio', date('Y'));
        $proveedorId = $request->get('proveedor_id', null);

        $query = Factura::with(['proveedor', 'creador'])
            ->whereYear('fecha_factura', $anio)
            ->whereMonth('fecha_factura', $mes);

        if ($proveedorId) {
            $query->where('proveedor_id', $proveedorId);
        }

        $facturas = $query->orderBy('fecha_factura', 'desc')->get();

        return response()->json([
            'success' => true,
            'facturas' => $facturas->map(function ($factura) {
                return [
                    'id'                 => $factura->id,
                    'numero_factura'     => $factura->numero_factura,
                    'proveedor'          => $factura->proveedor->proveedor ?? 'N/A',
                    'monto'              => $factura->monto,
                    'monto_usd'          => $factura->monto_usd,
                    'moneda'             => $factura->moneda,
                    'tasa_referencia'    => $factura->tasa_referencia,
                    'fecha_factura'      => $factura->fecha_factura->format('d/m/Y'),
                    'estado_pago'        => $factura->estado_pago,
                    'estado_pago_label'  => $factura->estado_pago_label,
                    'estado_pago_color'  => $factura->estado_pago_color,
                    'has_archivos'       => $factura->archivos()->exists(),
                    'archivos_count'     => $factura->archivos()->count(),
                ];
            })
        ]);
    }
}