<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;
use App\Models\Factura;
use App\Models\Producto;
use App\Http\Requests\ProveedorStoreRequest;
use App\Http\Requests\UpdateProveedorRequest;
use App\Models\Proveedor;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class ProveedorController extends Controller
{
    // ================================================================
    // INDEX
    // ================================================================
    public function index()
    {
        $proveedores = Proveedor::with('categoria')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('proveedores.index', compact('proveedores'));
    }

    // ================================================================
    // CREATE
    // ================================================================
    public function create()
    {
        $categorias = Categoria::withoutGlobalScopes()
            ->orderBy('nombre_categoria', 'asc')
            ->get();

        return view('proveedores.create', compact('categorias'));
    }

    // ================================================================
    // STORE
    // ================================================================
    public function store(ProveedorStoreRequest $request)
    {
        Log::info('=== INICIO STORE PROVEEDOR ===');
        Log::info('Datos recibidos:', $request->all());

        try {
            $validated = $request->validated();
            Log::info('Datos validados:', $validated);

            $proveedor = Proveedor::create($validated);

            Log::info('Proveedor creado exitosamente:', [
                'id'        => $proveedor->id,
                'proveedor' => $proveedor->proveedor,
                'correo'    => $proveedor->correo,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'   => true,
                    'proveedor' => [
                        'id'        => $proveedor->id,
                        'nombre'    => $proveedor->proveedor,
                        'proveedor' => $proveedor->proveedor,
                    ],
                ], 201);
            }

            if ($request->input('accion') === 'crear_producto') {
                return redirect()
                    ->route('productos.create', ['proveedor_id' => $proveedor->id])
                    ->with('success', 'Proveedor creado con éxito. Ahora puedes registrar su producto.');
            }

            return redirect()
                ->route('proveedores.index')
                ->with([
                    'success'            => '✅ Proveedor "' . $proveedor->proveedor . '" registrado exitosamente!',
                    'show_quick_actions' => true,
                    'proveedor_id'       => $proveedor->id,
                    'proveedor_nombre'   => $proveedor->proveedor,
                ]);

        } catch (QueryException $e) {
            Log::error('ERROR DE BASE DE DATOS:', [
                'message' => $e->getMessage(),
                'sql'     => $e->getSql(),
            ]);

            $errorMessage = 'Error al guardar en la base de datos. ';
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $errorMessage .= 'El correo electrónico ya está registrado.';
            } elseif (str_contains($e->getMessage(), 'Field')) {
                $errorMessage .= 'Faltan campos obligatorios.';
            } else {
                $errorMessage .= 'Intente nuevamente.';
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => $errorMessage], 500);
            }

            return redirect()->back()->withInput()->with('error', '❌ ' . $errorMessage);

        } catch (\Exception $e) {
            Log::error('ERROR GENERAL:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->withInput()->with('error', '❌ Error: ' . $e->getMessage());
        }
    }

    // ================================================================
    // SEARCH
    // ================================================================
    public function search(Request $request)
    {
        dd($request);
    }

    // ================================================================
    // SHOW
    // ================================================================
    public function show($id)
    {
        $proveedor = Proveedor::with([
            'categoria',
            'productos.categoria',
            'productos.estatusActual',
            'facturas.producto',
            'facturas.archivos',
            'alertas',
        ])->findOrFail($id);

        $facturasPorMes = Factura::where('proveedor_id', $proveedor->id)
            ->select(
                DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m") as mes'),
                DB::raw('SUM(monto_usd) as total')
            )
            ->groupBy(DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'), 'desc')
            ->get();

        $productosRecurrentes = $proveedor->productos->filter(function ($producto) {
            return $producto->tipo_pago === 'recurrente';
        });

        $proximaFecha = $productosRecurrentes->pluck('proxima_facturacion')->filter()->min();

        $hoy     = now()->startOfDay();
        $proxima = $proximaFecha ? $proximaFecha->startOfDay() : null;
        $dias    = $proxima ? $hoy->diffInDays($proxima) : null;

        return view('proveedores.show', compact(
            'proveedor',
            'facturasPorMes',
            'productosRecurrentes',
            'proximaFecha',
            'dias'
        ));
    }

    // ================================================================
    // EDIT
    // ================================================================
    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $categorias = Categoria::withoutGlobalScopes()
            ->orderBy('nombre_categoria', 'asc')
            ->get();

        return view('proveedores.edit', compact('proveedor', 'categorias'));
    }

    // ================================================================
    // UPDATE
    // ================================================================
    public function update(UpdateProveedorRequest $request, $id)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->update($request->validated());

            return redirect()
                ->route('proveedores.index')
                ->with('success', '✅ Proveedor actualizado exitosamente!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Error al actualizar: ' . $e->getMessage());
        }
    }

    // ================================================================
    // DESTROY
    // ================================================================
    public function destroy($id, Request $request)
    {
        try {
            $proveedor = Proveedor::findOrFail($id);
            $proveedor->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Proveedor '{$proveedor->proveedor}' eliminado correctamente.",
                    'id'      => $id,
                ]);
            }

            return redirect()
                ->route('proveedores.index')
                ->with('success', "✅ Proveedor '{$proveedor->proveedor}' eliminado exitosamente!");

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', '❌ Error al eliminar: ' . $e->getMessage());
        }
    }

    // ================================================================
    // REPORTES (vista)
    // ================================================================
    public function reportes(Request $request)
    {
        $data = $this->construirDatosReporte($request);
        return view('proveedores.reportes', $data);
    }

    // ================================================================
    // EXPORTAR PDF
    // ================================================================
    public function exportarPdf(Request $request)
    {
        $data = $this->construirDatosReporte($request);

        $data['chartImages'] = [
            'proveedores'          => $request->input('img_proveedores'),
            'meses'                => $request->input('img_meses'),
            'servicios'            => $request->input('img_servicios'),
            'incremento'           => $request->input('img_incremento'),
            'incremento_servicios' => $request->input('img_incremento_servicios'),
        ];

        $data['fechaGeneracion'] = now()->format('d/m/Y H:i');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('proveedores.reportes_pdf', $data);
        $pdf->setPaper([0, 0, 936, 612], 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'DejaVu Sans',
        ]);

        return $pdf->download('reporte_proveedores_' . now()->format('Ymd_His') . '.pdf');
    }

    // ================================================================
    // CONSTRUCTOR DE DATOS (compartido entre reportes y exportarPdf)
    // ================================================================
    private function construirDatosReporte(Request $request): array
    {
        $proveedorId = $request->get('proveedor_id');
        $anioActual  = (int) $request->get('anio', now()->year);
        $devaluacion = (float) $request->get('devaluacion', 30);

        // ---------- 1. LISTADO DE PROVEEDORES ----------
        $query = Proveedor::query();
        if ($request->filled('ciudad')) $query->where('ciudad', $request->ciudad);
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('proveedor', 'like', "%{$q}%")
                    ->orWhere('asesor', 'like', "%{$q}%")
                    ->orWhere('correo', 'like', "%{$q}%")
                    ->orWhere('telefono_contacto', 'like', "%{$q}%");
            });
        }
        if ($proveedorId) $query->where('id', $proveedorId);
        $proveedores = $query->orderBy('proveedor')->paginate(20)->withQueryString();

        // ---------- 2. HELPER FILTROS ----------
        $aplicarFiltros = function ($query) use ($request) {
            if ($request->filled('proveedor_id')) {
                $query->where('proveedor_id', $request->proveedor_id);
            } else {
                if ($request->filled('ciudad') || $request->filled('q')) {
                    $sub = Proveedor::query();
                    if ($request->filled('ciudad')) $sub->where('ciudad', $request->ciudad);
                    if ($request->filled('q')) {
                        $q = $request->q;
                        $sub->where(function ($s) use ($q) {
                            $s->where('proveedor', 'like', "%{$q}%")
                                ->orWhere('asesor', 'like', "%{$q}%")
                                ->orWhere('correo', 'like', "%{$q}%")
                                ->orWhere('telefono_contacto', 'like', "%{$q}%");
                        });
                    }
                    $query->whereIn('proveedor_id', $sub->pluck('id'));
                }
            }
            if ($request->filled('desde')) $query->whereDate('fecha_factura', '>=', $request->desde);
            if ($request->filled('hasta')) $query->whereDate('fecha_factura', '<=', $request->hasta);
            return $query;
        };

        // ---------- 3. KPIs ----------
        $statsQuery     = $aplicarFiltros(Factura::query());
        $statsQueryBs   = $aplicarFiltros(Factura::query());
        $statsQueryRub  = $aplicarFiltros(Factura::query());
        $statsQueryPend = $aplicarFiltros(Factura::query());
        $statsQueryVenc = $aplicarFiltros(Factura::query());

        $stats = [
            'total'           => Proveedor::count(),
            'ciudades'        => Proveedor::whereNotNull('ciudad')->distinct('ciudad')->count('ciudad'),
            'total_facturas'  => (clone $statsQuery)->count(),
            'monto_total_usd' => (float) (clone $statsQuery)->sum('monto_usd'),
            'monto_total_bs'  => (float) $statsQueryBs->where('moneda', 'Bs')->sum('monto'),
            'monto_total_rub' => (float) $statsQueryRub->where('moneda', 'RUB')->sum('monto'),
            'pendientes'      => $statsQueryPend->where('estado_pago', 'pendiente')->count(),
            'vencidas'        => $statsQueryVenc->where('estado_pago', 'vencido')->count(),
        ];

        // ---------- 4. GRÁFICA 1 ----------
        $chartProveedoresQuery = $aplicarFiltros(
            Factura::select(
                'proveedor_id',
                DB::raw('SUM(COALESCE(monto_usd, 0)) as total_usd'),
                DB::raw('SUM(CASE WHEN moneda = "Bs" THEN monto ELSE 0 END) as total_bs'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->whereNotNull('proveedor_id')
            ->with('proveedor:id,proveedor')
            ->groupBy('proveedor_id')
            ->orderByDesc('total_usd')
            ->limit(10)
        );
        $totalesPorProveedor = $chartProveedoresQuery->get();

        $chartProveedores = [
            'labels'      => $totalesPorProveedor->map(fn ($f) => $f->proveedor->proveedor ?? 'N/A')->toArray(),
            'totales_usd' => $totalesPorProveedor->pluck('total_usd')->map(fn ($v) => (float) $v)->toArray(),
            'totales_bs'  => $totalesPorProveedor->pluck('total_bs')->map(fn ($v) => (float) $v)->toArray(),
            'cantidad'    => $totalesPorProveedor->pluck('cantidad')->toArray(),
        ];

        // ---------- 5. GRÁFICA 2 ----------
        $chartMesesQuery = $aplicarFiltros(
            Factura::select(
                DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m") as mes'),
                DB::raw('SUM(COALESCE(monto_usd, 0)) as total_usd'),
                DB::raw('SUM(CASE WHEN moneda = "Bs" THEN monto ELSE 0 END) as total_bs'),
                DB::raw('COUNT(*) as cantidad')
            )
            ->whereNotNull('fecha_factura')
            ->groupBy(DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
        );
        $totalesPorMes = $chartMesesQuery->get();

        $chartMeses = [
            'labels'      => $totalesPorMes->pluck('mes')->toArray(),
            'totales_usd' => $totalesPorMes->pluck('total_usd')->map(fn ($v) => (float) $v)->toArray(),
            'totales_bs'  => $totalesPorMes->pluck('total_bs')->map(fn ($v) => (float) $v)->toArray(),
            'cantidad'    => $totalesPorMes->pluck('cantidad')->toArray(),
        ];

        // ---------- 6. GRÁFICA 3 ----------
        $facturasServiciosQuery = $aplicarFiltros(
            Factura::select(
                'producto_id',
                DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m") as mes'),
                DB::raw('SUM(COALESCE(monto_usd, 0)) as total_usd'),
                DB::raw('SUM(CASE WHEN moneda = "Bs" THEN monto ELSE 0 END) as total_bs')
            )
            ->whereYear('fecha_factura', $anioActual)
            ->whereNotNull('fecha_factura')
            ->groupBy('producto_id', DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
            ->orderBy('mes')
        );
        $facturasServicios = $facturasServiciosQuery->get();

        $productoIds = $facturasServicios->pluck('producto_id')->filter()->unique()->values()->toArray();
        $productos   = Producto::withoutGlobalScopes()
            ->whereIn('id', $productoIds)
            ->get(['id', 'nombre'])
            ->keyBy('id');

        $mesesLabels = [];
        for ($m = 1; $m <= 12; $m++) {
            $mesesLabels[] = $anioActual . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
        }

        $serviciosAgrupados = $facturasServicios->groupBy(fn ($item) => $item->producto_id ?? 0);
        $datasetsServicios  = [];
        $coloresPaleta = ['#667eea', '#11998e', '#f7971e', '#cb2d3e', '#38ef7d', '#2193b0', '#764ba2', '#ff5e62'];
        $i = 0;

        foreach ($serviciosAgrupados as $prodId => $items) {
            $nombreServicio = ($prodId == 0)
                ? 'Servicios Generales / Sin Producto'
                : ($productos->get($prodId)->nombre ?? 'Producto #' . $prodId);

            $dataUsdPorMes = [];
            $dataBsPorMes  = [];
            foreach ($mesesLabels as $mes) {
                $encontrado = $items->firstWhere('mes', $mes);
                $dataUsdPorMes[] = $encontrado ? (float) $encontrado->total_usd : 0;
                $dataBsPorMes[]  = $encontrado ? (float) $encontrado->total_bs  : 0;
            }

            if (array_sum($dataUsdPorMes) > 0 || array_sum($dataBsPorMes) > 0) {
                $color = $coloresPaleta[$i % count($coloresPaleta)];
                $datasetsServicios[] = [
                    'label' => $nombreServicio . ' ($)', 'data' => $dataUsdPorMes,
                    'borderColor' => $color, 'backgroundColor' => $color,
                    'borderWidth' => 2.5, 'tension' => 0.35, 'fill' => false, 'yAxisID' => 'y',
                ];
                $datasetsServicios[] = [
                    'label' => $nombreServicio . ' (Bs.)', 'data' => $dataBsPorMes,
                    'borderColor' => $color, 'backgroundColor' => $color,
                    'borderWidth' => 2.5, 'borderDash' => [6, 4], 'tension' => 0.35, 'fill' => false, 'yAxisID' => 'y1',
                ];
                $i++;
            }
        }

        $chartServiciosHistorico = [
            'labels'   => $mesesLabels,
            'datasets' => $datasetsServicios,
        ];

        // ---------- 6.5. GRÁFICA 4 ----------
        $datosRealesAnio = [];
        $datosRealesQuery = $aplicarFiltros(
            Factura::select(
                DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m") as mes'),
                DB::raw('SUM(COALESCE(monto_usd, 0)) as total_usd'),
                DB::raw('SUM(CASE WHEN moneda = "Bs" THEN monto ELSE 0 END) as total_bs')
            )
            ->whereYear('fecha_factura', $anioActual)
            ->whereNotNull('fecha_factura')
            ->groupBy(DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
        );
        foreach ($datosRealesQuery->get() as $row) {
            $datosRealesAnio[$row->mes] = [
                'usd' => (float) $row->total_usd,
                'bs'  => (float) $row->total_bs,
            ];
        }
        $chartIncremento = $this->calcularProyeccionAnual($anioActual, $datosRealesAnio);

        // ---------- 6.6. GRÁFICA 5: INCREMENTO + PROYECCIÓN POR SERVICIO ----------
        $chartIncrementoServicios = $this->calcularProyeccionServicios($anioActual, $aplicarFiltros);

        // ---------- 9. TABLA ----------
        $tablaProveedores = $this->construirTablaProveedores($anioActual, $aplicarFiltros);

        // ---------- 10. PROYECCIONES ----------
        $proyeccionRestante = $this->proyectarMesesRestantes($tablaProveedores, $anioActual);
        $proyeccion2027     = $this->proyectar2027ConDevaluacion($tablaProveedores, $devaluacion);

        // ---------- 11. TOP CIUDADES ----------
        $porCiudad = Proveedor::select('ciudad', DB::raw('COUNT(*) as total'))
            ->whereNotNull('ciudad')->groupBy('ciudad')
            ->orderByDesc('total')->limit(10)->get();

        // ---------- 12. LISTAS ----------
        $ciudades         = Proveedor::whereNotNull('ciudad')->distinct()->orderBy('ciudad')->pluck('ciudad');
        $listaProveedores = Proveedor::orderBy('proveedor')->get(['id', 'proveedor']);

        return compact(
            'proveedores', 'stats',
            'chartProveedores', 'chartMeses', 'chartServiciosHistorico', 'chartIncremento',
            'chartIncrementoServicios',
            'tablaProveedores', 'proyeccionRestante', 'proyeccion2027',
            'porCiudad', 'ciudades', 'listaProveedores',
            'proveedorId', 'anioActual', 'devaluacion'
        );
    }

    // ================================================================
    // HELPER: PROYECCIÓN ANUAL POR MES
    // ================================================================
    private function calcularProyeccionAnual(int $anio, array $datosReales): array
    {
        $meses = [];
        for ($m = 1; $m <= 12; $m++) {
            $meses[] = $anio . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
        }

        $ultimoRealIdx = -1;
        for ($i = 11; $i >= 0; $i--) {
            $mes = $meses[$i];
            if (isset($datosReales[$mes]) && ($datosReales[$mes]['usd'] > 0 || $datosReales[$mes]['bs'] > 0)) {
                $ultimoRealIdx = $i;
                break;
            }
        }

        $incRealesUsd = [];
        $incRealesBs  = [];
        $prevUsd = null;
        $prevBs  = null;

        for ($i = 0; $i <= $ultimoRealIdx; $i++) {
            $mes = $meses[$i];
            $usd = $datosReales[$mes]['usd'] ?? 0;
            $bs  = $datosReales[$mes]['bs']  ?? 0;

            if ($prevUsd !== null && $prevUsd > 0 && $usd > 0) {
                $incRealesUsd[] = (($usd - $prevUsd) / $prevUsd) * 100;
            }
            if ($prevBs !== null && $prevBs > 0 && $bs > 0) {
                $incRealesBs[] = (($bs - $prevBs) / $prevBs) * 100;
            }

            if ($usd > 0) $prevUsd = $usd;
            if ($bs  > 0) $prevBs  = $bs;
        }

        $promUsd = count($incRealesUsd) > 0 ? array_sum($incRealesUsd) / count($incRealesUsd) : 0;
        $promBs  = count($incRealesBs)  > 0 ? array_sum($incRealesBs)  / count($incRealesBs)  : 0;

        $valoresUsd = [];
        $valoresBs  = [];
        $esProyectado = [];

        $acumUsd = $ultimoRealIdx >= 0 ? ($datosReales[$meses[$ultimoRealIdx]]['usd'] ?? 0) : 0;
        $acumBs  = $ultimoRealIdx >= 0 ? ($datosReales[$meses[$ultimoRealIdx]]['bs']  ?? 0) : 0;

        for ($i = 0; $i < 12; $i++) {
            if ($i <= $ultimoRealIdx) {
                $valoresUsd[]   = (float) ($datosReales[$meses[$i]]['usd'] ?? 0);
                $valoresBs[]    = (float) ($datosReales[$meses[$i]]['bs']  ?? 0);
                $esProyectado[] = false;
            } else {
                $acumUsd = $acumUsd * (1 + ($promUsd / 100));
                $acumBs  = $acumBs  * (1 + ($promBs  / 100));
                $valoresUsd[]   = round($acumUsd, 2);
                $valoresBs[]    = round($acumBs, 2);
                $esProyectado[] = true;
            }
        }

        $incUsd = [null];
        $incBs  = [null];

        for ($i = 1; $i < 12; $i++) {
            $prevU = $valoresUsd[$i - 1]; $curU = $valoresUsd[$i];
            $prevB = $valoresBs[$i - 1];  $curB = $valoresBs[$i];

            $incUsd[] = ($prevU > 0 && $curU > 0) ? round((($curU - $prevU) / $prevU) * 100, 2) : null;
            $incBs[]  = ($prevB > 0 && $curB > 0) ? round((($curB - $prevB) / $prevB) * 100, 2) : null;
        }

        return [
            'anio' => $anio, 'labels' => $meses,
            'valores_usd' => $valoresUsd, 'valores_bs' => $valoresBs,
            'incrementos_usd' => $incUsd, 'incrementos_bs' => $incBs,
            'es_proyectado' => $esProyectado,
            'incremento_prom_usd' => round($promUsd, 2),
            'incremento_prom_bs'  => round($promBs, 2),
            'ultimo_mes_real_idx' => $ultimoRealIdx,
        ];
    }

    // ================================================================
    // HELPER: PROYECCIÓN ANUAL POR SERVICIO/PRODUCTO
    // ================================================================
    private function calcularProyeccionServicios(int $anio, callable $aplicarFiltros): array
    {
        // 1. Obtener datos reales agrupados por producto y mes
        $rows = $aplicarFiltros(
            Factura::select(
                'producto_id',
                DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m") as mes'),
                DB::raw('SUM(COALESCE(monto_usd, 0)) as total_usd')
            )
            ->whereYear('fecha_factura', $anio)
            ->whereNotNull('fecha_factura')
            ->groupBy('producto_id', DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
        )->get();

        // 2. Nombres de productos
        $prodIds = $rows->pluck('producto_id')->filter()->unique()->values()->toArray();
        $productos = Producto::withoutGlobalScopes()
            ->whereIn('id', $prodIds)
            ->get(['id', 'nombre'])
            ->keyBy('id');

        // 3. Construir labels de meses (12)
        $meses = [];
        for ($m = 1; $m <= 12; $m++) {
            $meses[] = $anio . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
        }

        // 4. Agrupar por producto
        $porProducto = $rows->groupBy(fn($r) => $r->producto_id ?? 0);
        $series = [];

        foreach ($porProducto as $prodId => $items) {
            $nombre = ($prodId == 0)
                ? 'Servicios Generales'
                : ($productos->get($prodId)->nombre ?? 'Producto #' . $prodId);

            // Montos reales por mes
            $valoresReales = array_fill(0, 12, 0.0);
            foreach ($items as $item) {
                $idx = (int) substr($item->mes, -2) - 1;
                if ($idx >= 0 && $idx < 12) {
                    $valoresReales[$idx] = (float) $item->total_usd;
                }
            }

            if (array_sum($valoresReales) <= 0) continue;

            // Calcular proyección
            $valores = [];
            $esProyectado = [];

            for ($i = 0; $i < 12; $i++) {
                if ($valoresReales[$i] > 0) {
                    $valores[] = $valoresReales[$i];
                    $esProyectado[] = false;
                    continue;
                }

                // Buscar los 2 meses anteriores con datos
                $anteriores = [];
                for ($j = $i - 1; $j >= 0 && count($anteriores) < 2; $j--) {
                    if (($valores[$j] ?? 0) > 0) {
                        $anteriores[] = $valores[$j];
                    }
                }

                if (count($anteriores) >= 2) {
                    $ultimo = $anteriores[0];
                    $penultimo = $anteriores[1];
                    $inc = (($ultimo - $penultimo) / $penultimo) * 100;
                    $proy = $ultimo * (1 + ($inc / 100));
                    $valores[] = max(0, round($proy, 2));
                    $esProyectado[] = true;
                } elseif (count($anteriores) === 1) {
                    $valores[] = round($anteriores[0], 2);
                    $esProyectado[] = true;
                } else {
                    $valores[] = 0;
                    $esProyectado[] = false;
                }
            }

            // Incrementos mensuales
            $incrementos = [null];
            for ($i = 1; $i < 12; $i++) {
                $prev = $valores[$i - 1];
                $curr = $valores[$i];
                $incrementos[] = ($prev > 0 && $curr > 0)
                    ? round((($curr - $prev) / $prev) * 100, 2)
                    : null;
            }

            // Promedio de incrementos
            $incValidos = array_filter($incrementos, fn($v) => $v !== null);
            $promInc = count($incValidos) > 0 ? array_sum($incValidos) / count($incValidos) : 0;

            // Último mes real (para saber dónde empieza la proyección)
            $ultimoRealIdx = -1;
            for ($i = 11; $i >= 0; $i--) {
                if ($valoresReales[$i] > 0) {
                    $ultimoRealIdx = $i;
                    break;
                }
            }

            $series[] = [
                'id'                  => $prodId,
                'nombre'              => $nombre,
                'valores'             => $valores,
                'valores_reales'      => $valoresReales,
                'es_proyectado'       => $esProyectado,
                'incrementos'         => $incrementos,
                'prom_inc'            => round($promInc, 2),
                'total'               => round(array_sum($valores), 2),
                'total_real'          => round(array_sum($valoresReales), 2),
                'ultimo_mes_real_idx' => $ultimoRealIdx,
            ];
        }

        // Ordenar por total descendente
        usort($series, fn($a, $b) => $b['total'] <=> $a['total']);

        return [
            'anio'   => $anio,
            'labels' => $meses,
            'series' => $series,
        ];
    }

    // ================================================================
    // HELPER: TABLA PROVEEDORES × MESES
    // ================================================================
    private function construirTablaProveedores(int $anio, callable $aplicarFiltros): array
    {
        $rows = $aplicarFiltros(
            Factura::select(
                'proveedor_id',
                DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m") as mes'),
                DB::raw('SUM(COALESCE(monto_usd, 0)) as total_usd')
            )
            ->whereYear('fecha_factura', $anio)
            ->whereNotNull('proveedor_id')
            ->whereNotNull('fecha_factura')
            ->groupBy('proveedor_id', DB::raw('DATE_FORMAT(fecha_factura, "%Y-%m")'))
        )->get();

        $provIds = $rows->pluck('proveedor_id')->unique();
        $nombres = Proveedor::whereIn('id', $provIds)->pluck('proveedor', 'id');

        $meses = [];
        for ($m = 1; $m <= 12; $m++) {
            $meses[] = $anio . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
        }

        $porProveedor = $rows->groupBy('proveedor_id');

        $tabla = [];
        $totalesMes           = array_fill(0, 12, 0);
        $totalesMesReal       = array_fill(0, 12, 0);
        $totalesMesProyectado = array_fill(0, 12, false);
        $granTotal            = 0;

        foreach ($porProveedor as $provId => $items) {
            $valoresReales = array_fill(0, 12, 0);
            foreach ($items as $item) {
                $idxMes = (int) substr($item->mes, -2) - 1;
                if ($idxMes >= 0 && $idxMes < 12) {
                    $valoresReales[$idxMes] = (float) $item->total_usd;
                }
            }

            $valores       = [];
            $esProyectado  = [];

            for ($i = 0; $i < 12; $i++) {
                if ($valoresReales[$i] > 0) {
                    $valores[]      = $valoresReales[$i];
                    $esProyectado[] = false;
                    continue;
                }

                $anteriores = [];
                for ($j = $i - 1; $j >= 0 && count($anteriores) < 2; $j--) {
                    if (($valores[$j] ?? 0) > 0) {
                        $anteriores[] = $valores[$j];
                    }
                }

                if (count($anteriores) >= 2) {
                    $ultimo    = $anteriores[0];
                    $penultimo = $anteriores[1];
                    $inc = (($ultimo - $penultimo) / $penultimo) * 100;
                    $proyectado = $ultimo * (1 + ($inc / 100));
                    $valores[]      = max(0, round($proyectado, 2));
                    $esProyectado[] = true;
                } elseif (count($anteriores) === 1) {
                    $valores[]      = round($anteriores[0], 2);
                    $esProyectado[] = true;
                } else {
                    $valores[]      = 0;
                    $esProyectado[] = false;
                }
            }

            $incrementos = [null];
            for ($i = 1; $i < 12; $i++) {
                $prev = $valores[$i - 1];
                $curr = $valores[$i];
                $incrementos[] = ($prev > 0 && $curr > 0)
                    ? round((($curr - $prev) / $prev) * 100, 2)
                    : null;
            }

            $totalReal  = array_sum($valoresReales);
            $totalFinal = array_sum($valores);
            $granTotal += $totalFinal;

            for ($i = 0; $i < 12; $i++) {
                $totalesMes[$i] += $valores[$i];
                $totalesMesReal[$i] += $valoresReales[$i];
                if ($esProyectado[$i]) {
                    $totalesMesProyectado[$i] = true;
                }
            }

            $incValidos = array_filter($incrementos, fn ($v) => $v !== null);
            $promInc    = count($incValidos) > 0 ? array_sum($incValidos) / count($incValidos) : 0;

            $tabla[] = [
                'id'             => $provId,
                'nombre'         => $nombres[$provId] ?? 'Proveedor #' . $provId,
                'valores'        => $valores,
                'valores_reales' => $valoresReales,
                'es_proyectado'  => $esProyectado,
                'incrementos'    => $incrementos,
                'total'          => round($totalFinal, 2),
                'total_real'     => round($totalReal, 2),
                'prom_inc'       => round($promInc, 2),
            ];
        }

        usort($tabla, fn ($a, $b) => $b['total'] <=> $a['total']);

        $totalesInc = [null];
        for ($i = 1; $i < 12; $i++) {
            $prev = $totalesMes[$i - 1];
            $curr = $totalesMes[$i];
            $totalesInc[] = ($prev > 0 && $curr > 0)
                ? round((($curr - $prev) / $prev) * 100, 2)
                : null;
        }

        $incGlobalValidos = array_filter($totalesInc, fn ($v) => $v !== null);
        $promIncGlobal = count($incGlobalValidos) > 0
            ? array_sum($incGlobalValidos) / count($incGlobalValidos)
            : 0;

        return [
            'meses'                  => $meses,
            'proveedores'            => $tabla,
            'totales_mes'            => $totalesMes,
            'totales_mes_real'       => $totalesMesReal,
            'totales_mes_proyectado' => $totalesMesProyectado,
            'totales_inc'            => $totalesInc,
            'gran_total'             => round($granTotal, 2),
            'prom_inc_global'        => round($promIncGlobal, 2),
        ];
    }

    // ================================================================
    // HELPER: PROYECTAR MESES RESTANTES
    // ================================================================
    private function proyectarMesesRestantes(array $tabla, int $anio): array
    {
        $hoy        = now();
        $ultimoRealIdx = -1;
        for ($i = 11; $i >= 0; $i--) {
            if ($tabla['totales_mes'][$i] > 0) {
                $ultimoRealIdx = $i;
                break;
            }
        }

        if ($anio > (int) $hoy->year) {
            return [
                'aplica'       => true,
                'prom_inc'     => $tabla['prom_inc_global'],
                'proyecciones' => [],
                'total'        => 0,
                'nota'         => 'Año futuro: todos los meses son proyección.',
            ];
        }

        if ($anio < (int) $hoy->year) {
            return [
                'aplica'       => false,
                'prom_inc'     => 0,
                'proyecciones' => [],
                'total'        => 0,
                'nota'         => 'Año pasado: no hay proyección.',
            ];
        }

        $promInc = $tabla['prom_inc_global'];
        $proyecciones = [];
        $total = 0;

        $mesesReales = array_filter($tabla['totales_mes'], fn ($v) => $v > 0);
        $base = count($mesesReales) > 0
            ? array_sum($mesesReales) / count($mesesReales)
            : 0;

        $acum = $ultimoRealIdx >= 0 ? $tabla['totales_mes'][$ultimoRealIdx] : $base;

        for ($i = $ultimoRealIdx + 1; $i < 12; $i++) {
            $acum = $acum * (1 + ($promInc / 100));
            $proyecciones[] = [
                'mes_idx' => $i,
                'mes'     => $tabla['meses'][$i],
                'nombre'  => $this->nombreMes($i + 1),
                'monto'   => round($acum, 2),
            ];
            $total += $acum;
        }

        return [
            'aplica'          => true,
            'prom_inc'        => round($promInc, 2),
            'proyecciones'    => $proyecciones,
            'total'           => round($total, 2),
            'ultimo_mes_real' => $ultimoRealIdx,
        ];
    }

    // ================================================================
    // HELPER: PROYECCIÓN 2027 CON DEVALUACIÓN
    // ================================================================
    private function proyectar2027ConDevaluacion(array $tabla, float $devaluacion): array
    {
        $mesesReales = array_filter($tabla['totales_mes'], fn ($v) => $v > 0);

        if (empty($mesesReales)) {
            return [
                'promedio_mensual'  => 0,
                'base_anual_actual' => 0,
                'tasa_devaluacion'  => $devaluacion,
                'factor_aplicado'   => 1,
                'proyeccion_2027'   => 0,
                'promedio_2027'     => 0,
                'meses_2027'        => [],
            ];
        }

        $promedioMensual = array_sum($mesesReales) / count($mesesReales);
        $baseAnualActual = $promedioMensual * 12;
        $factor          = 1 + ($devaluacion / 100);

        $proyeccion2027 = $baseAnualActual * $factor;
        $promedio2027   = $proyeccion2027 / 12;

        $meses2027 = [];
        for ($m = 1; $m <= 12; $m++) {
            $meses2027[] = [
                'mes'    => '2027-' . str_pad($m, 2, '0', STR_PAD_LEFT),
                'nombre' => $this->nombreMes($m) . ' 2027',
                'monto'  => round($promedio2027, 2),
            ];
        }

        return [
            'promedio_mensual'  => round($promedioMensual, 2),
            'base_anual_actual' => round($baseAnualActual, 2),
            'tasa_devaluacion'  => $devaluacion,
            'factor_aplicado'   => round($factor, 4),
            'proyeccion_2027'   => round($proyeccion2027, 2),
            'promedio_2027'     => round($promedio2027, 2),
            'meses_2027'        => $meses2027,
        ];
    }

    // ================================================================
    // HELPER: NOMBRE DEL MES
    // ================================================================
    private function nombreMes(int $m): string
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ][$m] ?? '';
    }

    // ================================================================
    // EXPORTAR CSV
    // ================================================================
    public function exportarReporte(Request $request)
    {
        $query = Proveedor::query();

        if ($request->filled('ciudad'))       $query->where('ciudad', $request->ciudad);
        if ($request->filled('desde'))        $query->whereDate('created_at', '>=', $request->desde);
        if ($request->filled('hasta'))        $query->whereDate('created_at', '<=', $request->hasta);
        if ($request->filled('proveedor_id')) $query->where('id', $request->proveedor_id);
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('proveedor', 'like', "%{$q}%")
                    ->orWhere('asesor', 'like', "%{$q}%")
                    ->orWhere('correo', 'like', "%{$q}%");
            });
        }

        $proveedores = $query->orderBy('proveedor')->get();
        $filename = 'reporte_proveedores_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($proveedores) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['ID', 'Proveedor', 'Asesor', 'Ciudad', 'Correo', 'Teléfono', 'Creado']);
            foreach ($proveedores as $p) {
                fputcsv($out, [
                    $p->id, $p->proveedor, $p->asesor, $p->ciudad, $p->correo,
                    $p->telefono_contacto, optional($p->created_at)->format('d/m/Y H:i'),
                ]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}