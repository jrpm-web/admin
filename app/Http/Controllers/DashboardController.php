<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Proveedor;
use App\Models\Alerta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Mostrar el dashboard principal
     */
    public function index()
    {
        // ============================================================
        // ALERTAS Y RECORDATORIOS
        // ============================================================
        try {
            $alertas = Alerta::with(['proveedor', 'creador'])
                ->where('activa', true)
                ->orderBy('fecha_limite', 'asc')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            $totalAlertas = Alerta::where('activa', true)
                ->where('leida', false)
                ->count();
        } catch (\Exception $e) {
            $alertas = collect([]);
            $totalAlertas = 0;
        }

        // ============================================================
        // MONITOREO DE PROVEEDORES
        // ============================================================
        $monitoreoProveedores = Proveedor::with(['facturas' => function($query) {
            $query->orderBy('fecha_factura', 'desc')->limit(1);
        }])
        ->where('monitoreo_activo', true)
        ->orderBy('proveedor', 'asc')
        ->paginate(10);
        
        // Procesar datos de monitoreo
        $monitoreoProveedores->getCollection()->transform(function($proveedor) {
            $ultimaFactura = $proveedor->facturas->first();
            
            // Calcular días sin facturar (usando el accesor del modelo)
            $diasSinFacturar = $proveedor->dias_sin_facturar;
            
            // Calcular próxima fecha esperada
            $proximaFechaEsperada = $proveedor->proxima_fecha_esperada;
            
            // Calcular días hasta próxima fecha (redondeado hacia abajo)
            $diasHastaProxima = null;
            if ($proximaFechaEsperada) {
                $diasHastaProxima = floor(now()->diffInDays($proximaFechaEsperada, false));
            }
            
            // Calcular promedio mensual
            $facturasUltimos6Meses = $proveedor->facturas()
                ->where('fecha_factura', '>=', now()->subMonths(6))
                ->count();
            $promedioMensual = $facturasUltimos6Meses > 0 ? round($facturasUltimos6Meses / 6, 1) : 0;
            
            return (object) [
                'proveedor' => $proveedor,
                'ultima_factura' => $ultimaFactura,
                'dias_sin_facturar' => $diasSinFacturar,
                'proxima_fecha_esperada' => $proximaFechaEsperada,
                'dias_hasta_proxima' => $diasHastaProxima,
                'promedio_mensual' => $promedioMensual,
                'proveedor_id' => $proveedor->id
            ];
        });

        // ============================================================
        // FACTURAS RECIENTES
        // ============================================================
        $ultimasFacturas = Factura::with(['proveedor'])
            ->orderBy('fecha_factura', 'desc')
            ->limit(5)
            ->get();

        // ============================================================
        // PRÓXIMAS FECHAS DE FACTURACIÓN
        // ============================================================
        $proximasFechas = Proveedor::whereNotNull('fecha_facturacion')
            ->where('monitoreo_activo', true)
            ->with(['facturas' => function($query) {
                $query->orderBy('fecha_factura', 'desc')->limit(1);
            }])
            ->get()
            ->map(function($proveedor) {
                $ultimaFactura = $proveedor->facturas->first();
                $fechaEsperada = $ultimaFactura 
                    ? $ultimaFactura->fecha_factura->copy()->addMonth()
                    : $proveedor->fecha_facturacion;
                
                return (object) [
                    'proveedor' => $proveedor,
                    'fecha_esperada' => $fechaEsperada
                ];
            })
            ->filter(function($item) {
                return $item->fecha_esperada && $item->fecha_esperada->isFuture();
            })
            ->sortBy('fecha_esperada')
            ->take(5);

        // ============================================================
        // ESTADÍSTICAS GENERALES
        // ============================================================
        $totalFacturas = Factura::count();
        $totalProveedores = Proveedor::count();
        $pendientes = Factura::where('estado_pago', 'pendiente')->count();
        $totalUSD = Factura::sum('monto_usd') ?? 0;

        // ============================================================
        // ESTADÍSTICAS ADICIONALES PARA EL DASHBOARD
        // ============================================================
        $proveedoresConMonitoreo = Proveedor::where('monitoreo_activo', true)
            ->whereNotNull('fecha_facturacion')
            ->count();
        
        $proveedoresCriticos = Proveedor::where('monitoreo_activo', true)
            ->whereNotNull('fecha_facturacion')
            ->get()
            ->filter(function($proveedor) {
                $dias = $proveedor->dias_sin_facturar;
                return $dias !== null && $dias > 30;
            })
            ->count();

        // ============================================================
        // DATOS PARA GRÁFICO (si lo necesitas)
        // ============================================================
        $datosGrafico = $this->prepareChartData();

        // ============================================================
        // RETORNAR VISTA CON TODAS LAS VARIABLES
        // ============================================================
        return view('dashboard', compact(
            'alertas',
            'totalAlertas',
            'monitoreoProveedores',
            'ultimasFacturas',
            'proximasFechas',
            'totalFacturas',
            'totalProveedores',
            'pendientes',
            'totalUSD',
            'proveedoresConMonitoreo',
            'proveedoresCriticos',
            'datosGrafico'
        ));
    }

    /**
     * Prepara los datos para el gráfico de líneas
     */
    private function prepareChartData()
    {
        // Definir los meses del año
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        
        // Obtener proveedores con facturas
        $proveedores = Proveedor::has('facturas')
            ->withSum(['facturas' => function($query) {
                $query->whereYear('fecha_factura', date('Y'));
            }], 'monto')
            ->orderBy('facturas_sum_monto', 'desc')
            ->limit(5)
            ->get();
        
        $datasets = [];
        
        // Colores para los datasets
        $colores = [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
            '#06b6d4', '#ec4899', '#84cc16', '#f97316', '#6366f1'
        ];
        
        foreach ($proveedores as $index => $proveedor) {
            $dataPorMes = [];
            
            // Obtener montos por mes para este proveedor
            for ($mes = 1; $mes <= 12; $mes++) {
                $monto = Factura::where('proveedor_id', $proveedor->id)
                    ->whereYear('fecha_factura', date('Y'))
                    ->whereMonth('fecha_factura', $mes)
                    ->sum('monto');
                
                $dataPorMes[] = round($monto, 2);
            }
            
            $datasets[] = [
                'label' => $proveedor->proveedor,
                'data' => $dataPorMes,
                'borderColor' => $colores[$index % count($colores)],
                'backgroundColor' => $this->hexToRgba($colores[$index % count($colores)], 0.1),
                'borderWidth' => 2,
                'pointBackgroundColor' => $colores[$index % count($colores)],
                'pointBorderColor' => '#ffffff',
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
                'tension' => 0.3,
                'fill' => true
            ];
        }
        
        // Si no hay datos, mostrar un dataset vacío
        if (empty($datasets)) {
            $datasets[] = [
                'label' => 'Sin datos',
                'data' => array_fill(0, 12, 0),
                'borderColor' => '#94a3b8',
                'backgroundColor' => 'rgba(148, 163, 184, 0.1)',
                'borderWidth' => 2,
                'fill' => true
            ];
        }
        
        return [
            'labels' => $meses,
            'datasets' => $datasets,
            'maxValue' => $this->getMaxChartValue($datasets)
        ];
    }

    /**
     * Convierte color hex a rgba
     */
     private function hexToRgba($hex, $alpha = 0.1)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        return "rgba($r, $g, $b, $alpha)";
    }

    /**
     * Obtiene el valor máximo del gráfico para escalar el eje Y
     */
    private function getMaxChartValue($datasets)
    {
        $maxValue = 0;
        
        foreach ($datasets as $dataset) {
            $datasetMax = max($dataset['data']);
            if ($datasetMax > $maxValue) {
                $maxValue = $datasetMax;
            }
        }
        
        // Agregar un margen del 10%
        return $maxValue * 1.1;
    }

    /**
     * Endpoint para obtener datos actualizados via AJAX
     */
    public function getData(Request $request)
    {
        $query = Factura::with('proveedor');
        
        // Filtro por mes
        if ($request->has('month') && $request->month) {
            $query->whereMonth('fecha_factura', $request->month);
        }
        
        // Filtro por proveedor
        if ($request->has('proveedor_id') && $request->proveedor_id) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        
        $facturas = $query->orderBy('fecha_factura', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'facturas' => $facturas,
            'stats' => [
                'total_facturas' => Factura::count(),
                'total_proveedores' => Proveedor::count(),
                'pendientes' => Factura::where('estado_pago', 'pendiente')->count(),
                'total_usd' => Factura::sum('monto_usd')
            ],
            'chart_data' => $this->prepareChartData()
        ]);
    }


    /**
     * Obtener estadísticas para el dashboard
     */
    public function getEstadisticas()
    {
        // Facturas del mes actual
        $mesActual = Factura::whereMonth('fecha_factura', now()->month)
            ->whereYear('fecha_factura', now()->year)
            ->sum('monto');
        
        // Facturas del mes anterior
        $mesAnterior = Factura::whereMonth('fecha_factura', now()->subMonth()->month)
            ->whereYear('fecha_factura', now()->subMonth()->year)
            ->sum('monto');
        
        // Crecimiento mensual
        $crecimientoMensual = $mesAnterior > 0 
            ? (($mesActual - $mesAnterior) / $mesAnterior) * 100 
            : 0;
        
        return response()->json([
            'success' => true,
            'mes_actual' => $mesActual,
            'mes_anterior' => $mesAnterior,
            'crecimiento_mensual' => round($crecimientoMensual, 2)
        ]);
    }
}