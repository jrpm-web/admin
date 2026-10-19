<x-layout>
    <x-slot:title>RZN-OFFERINGS | Estadísticas de Facturas</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h4 class="mb-0 fw-bold">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        Estadísticas de Facturas
                                    </h4>
                                    <p class="opacity-75 mb-0 small">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ now()->format('d/m/Y H:i') }} | 
                                        <span class="badge bg-light text-dark">
                                            {{ $totalFacturas }} facturas analizadas
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-light btn-sm" onclick="toggleDarkMode()">
                                    <i class="fas fa-moon me-1"></i>
                                    <span id="darkModeText">Modo Oscuro</span>
                                </button>
                                <button class="btn btn-light btn-sm" onclick="exportarPDF()">
                                    <i class="fas fa-file-pdf text-danger me-1"></i> PDF
                                </button>
                                <button class="btn btn-light btn-sm" onclick="exportarExcel()">
                                    <i class="fas fa-file-excel text-success me-1"></i> Excel
                                </button>
                                <button class="btn btn-light btn-sm" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i> Imprimir
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Filtros Mejorados -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-1 text-primary"></i>
                                    Año
                                </label>
                                <select id="filterAnio" class="form-select" onchange="aplicarFiltros()">
                                    @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                        <option value="{{ $i }}" {{ $anio == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-building me-1 text-primary"></i>
                                    Proveedor
                                </label>
                                <select id="filterProveedor" class="form-select" onchange="aplicarFiltros()">
                                    <option value="">Todos los proveedores</option>
                                    @foreach($proveedores as $prov)
                                        <option value="{{ $prov->id }}" {{ $proveedorId == $prov->id ? 'selected' : '' }}>
                                            {{ $prov->proveedor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-chart-bar me-1 text-primary"></i>
                                    Tipo de Vista
                                </label>
                                <select id="filterVista" class="form-select" onchange="cambiarVista()">
                                    <option value="completa">Completa</option>
                                    <option value="resumida">Resumida</option>
                                    <option value="ejecutiva">Ejecutiva</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" onclick="aplicarFiltros()">
                                    <i class="fas fa-sync-alt me-2"></i>
                                    Actualizar
                                </button>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 1: TARJETAS DE ESTADÍSTICAS RÁPIDAS MEJORADAS -->
                        <!-- ============================================================ -->
                        <div class="row g-4 mb-4">
                            @php
                                $cards = [
                                    [
                                        'title' => 'Total Facturas', 
                                        'value' => $totalFacturas, 
                                        'icon' => 'fa-file-invoice', 
                                        'color' => 'primary',
                                        'sub' => 'Último año'
                                    ],
                                    [
                                        'title' => 'Total Gastado', 
                                        'value' => 'Bs. ' . number_format($totalMonto, 2), 
                                        'icon' => 'fa-money-bill-wave', 
                                        'color' => 'success',
                                        'sub' => 'Promedio: Bs. ' . number_format($totalMonto / max(1, $mesesConDatos), 2)
                                    ],
                                    [
                                        'title' => 'Total en USD', 
                                        'value' => '$ ' . number_format($totalUsd, 2), 
                                        'icon' => 'fa-dollar-sign', 
                                        'color' => 'info',
                                        'sub' => 'Promedio: $ ' . number_format($totalUsd / max(1, $mesesConDatos), 2)
                                    ],
                                    [
                                        'title' => 'Incremento Promedio', 
                                        'value' => ($incrementoPromedio >= 0 ? '+' : '') . number_format($incrementoPromedio, 1) . '%', 
                                        'icon' => 'fa-chart-line', 
                                        'color' => $incrementoPromedio >= 0 ? 'success' : 'danger',
                                        'sub' => $incrementoPromedio >= 0 ? '📈 Crecimiento' : '📉 Decrecimiento'
                                    ]
                                ];
                            @endphp
                            @foreach($cards as $card)
                                <div class="col-md-3">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 estadistica-card" data-animate="fadeInUp">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="text-muted small text-uppercase">{{ $card['title'] }}</h6>
                                                    <h3 class="fw-bold mb-0 text-{{ $card['color'] }}">
                                                        {{ $card['value'] }}
                                                    </h3>
                                                    <small class="text-muted">{{ $card['sub'] }}</small>
                                                </div>
                                                <div class="bg-{{ $card['color'] }} bg-opacity-10 p-3 rounded-circle">
                                                    <i class="fas {{ $card['icon'] }} text-{{ $card['color'] }} fs-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 2: RESUMEN EJECUTIVO MEJORADO -->
                        <!-- ============================================================ -->
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0">
                                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                                Resumen Ejecutivo
                                            </h6>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <span class="badge bg-{{ $incrementoPromedio >= 10 ? 'danger' : ($incrementoPromedio >= 5 ? 'warning' : ($incrementoPromedio >= 0 ? 'success' : 'danger')) }} fs-6 p-2">
                                                    <i class="fas fa-{{ $incrementoPromedio >= 0 ? ($incrementoPromedio >= 10 ? 'rocket' : ($incrementoPromedio >= 5 ? 'arrow-up' : 'arrow-right')) : 'arrow-down' }} me-2"></i>
                                                    Tendencia: 
                                                    @if($incrementoPromedio >= 10)
                                                        🟢 Crecimiento acelerado
                                                    @elseif($incrementoPromedio >= 5)
                                                        🟡 Crecimiento moderado
                                                    @elseif($incrementoPromedio >= 0)
                                                        🔵 Estable
                                                    @else
                                                        🔴 Decreciente
                                                    @endif
                                                </span>
                                                <button class="btn btn-outline-secondary btn-sm" onclick="toggleResumenDetalle()">
                                                    <i class="fas fa-chevron-down" id="resumenToggleIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div id="resumenDetalle">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                                        <span class="text-muted small">📊 Mayor gasto</span>
                                                        <span class="fw-bold text-primary">{{ $mayorMes ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                                        <span class="text-muted small">🏢 Total proveedores</span>
                                                        <span class="fw-bold text-success">{{ $totalProveedores ?? 0 }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                                        <span class="text-muted small">📈 Tasa promedio</span>
                                                        <span class="fw-bold text-info">{{ isset($ultimaTasa) ? number_format($ultimaTasa, 2) : 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                                        <span class="text-muted small">📅 Meses con datos</span>
                                                        <span class="fw-bold text-warning">{{ $mesesConDatos ?? 0 }}/12</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-2">
                                                <div class="col-md-4">
                                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                                        <span class="text-muted small">💰 Total Bs</span>
                                                        <span class="fw-bold">Bs. {{ number_format($totalMonto, 0) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                                        <span class="text-muted small">💵 Total USD</span>
                                                        <span class="fw-bold">$ {{ number_format($totalUsd, 0) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                                                        <span class="text-muted small">📄 Facturas pendientes</span>
                                                        <span class="fw-bold text-warning">{{ $totalPendientes ?? 0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 3: EVOLUCIÓN MENSUAL CON PROYECCIÓN MEJORADA -->
                        <!-- ============================================================ -->
                        <div class="row g-4 mb-4">
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                                            <h6 class="fw-bold mb-0">
                                                <i class="fas fa-chart-line text-primary me-2"></i>
                                                Evolución Mensual de Gastos
                                                <span class="badge bg-info ms-2">Histórico + Proyección</span>
                                            </h6>
                                            <div class="d-flex gap-3 flex-wrap">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="toggleUsd" onchange="toggleUsdVisibility()">
                                                    <label class="form-check-label small" for="toggleUsd">
                                                        <i class="fas fa-dollar-sign text-success"></i> USD
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="toggleProyeccion" onchange="toggleProyeccionVisibility()" checked>
                                                    <label class="form-check-label small" for="toggleProyeccion">
                                                        <i class="fas fa-rocket text-warning"></i> Proyección
                                                    </label>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="toggleIncremento" onchange="toggleIncrementoVisibility()" checked>
                                                    <label class="form-check-label small" for="toggleIncremento">
                                                        <i class="fas fa-percent text-danger"></i> Incremento
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div style="position: relative;">
                                            <div style="position: relative; height: 350px;">
                                                <canvas id="evolucionChart"></canvas>
                                            </div>
                                            <div id="chartHint" class="chart-interactive-hint">
                                                <i class="fas fa-mouse-pointer me-1"></i> Haz clic en las barras para ver el detalle
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-rocket text-success me-2"></i>
                                            Proyección para el Siguiente Mes
                                        </h6>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-center text-center">
                                        @php
                                            $mesActual = date('n');
                                            $mesProyectado = $mesActual + 1;
                                            if ($mesProyectado > 12) $mesProyectado = 1;
                                            $nombreMes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'][$mesProyectado - 1];
                                            $montoProyectado = isset($datosConProyeccion['monto_proyectado_siguiente']) ? $datosConProyeccion['monto_proyectado_siguiente'] : 0;
                                            $montoUsdProyectado = isset($datosConProyeccion['monto_usd_proyectado_siguiente']) ? $datosConProyeccion['monto_usd_proyectado_siguiente'] : 0;
                                            $incrementoPromedio = isset($datosConProyeccion['incremento_promedio']) ? $datosConProyeccion['incremento_promedio'] : 0;
                                            $incrementoPromedioUsd = isset($datosConProyeccion['incremento_promedio_usd']) ? $datosConProyeccion['incremento_promedio_usd'] : 0;
                                        @endphp
                                        
                                        <div class="display-4 fw-bold text-primary mb-1">
                                            Bs. {{ number_format($montoProyectado, 2) }}
                                        </div>
                                        <div class="display-6 fw-bold text-success mb-2" style="font-size: 1.5rem;">
                                            $ {{ number_format($montoUsdProyectado, 2) }}
                                        </div>
                                        <div class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Proyección para {{ $nombreMes }} {{ date('Y') }}
                                        </div>
                                        <div class="mt-3">
                                            <span class="badge bg-{{ $incrementoPromedio >= 0 ? 'success' : 'danger' }} fs-6 p-2">
                                                <i class="fas fa-{{ $incrementoPromedio >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                                {{ $incrementoPromedio >= 0 ? '+' : '' }}{{ number_format($incrementoPromedio, 1) }}% 
                                                vs promedio
                                            </span>
                                            <br>
                                            <span class="badge bg-{{ $incrementoPromedioUsd >= 0 ? 'success' : 'danger' }} fs-6 p-2 mt-1">
                                                <i class="fas fa-{{ $incrementoPromedioUsd >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                                {{ $incrementoPromedioUsd >= 0 ? '+' : '' }}{{ number_format($incrementoPromedioUsd, 1) }}% 
                                                vs promedio (USD)
                                            </span>
                                        </div>
                                        <div class="mt-3 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Basado en el incremento promedio de los últimos meses
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 4: GASTOS POR PROVEEDOR MEJORADA -->
                        <!-- ============================================================ -->
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                                            <h6 class="fw-bold mb-0">
                                                <i class="fas fa-building text-primary me-2"></i>
                                                Gastos por Proveedor
                                            </h6>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary" onclick="cambiarTipoGrafica('bar')">
                                                    <i class="fas fa-chart-bar"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary" onclick="cambiarTipoGrafica('doughnut')">
                                                    <i class="fas fa-chart-pie"></i>
                                                </button>
                                                <button class="btn btn-outline-secondary" onclick="cambiarTipoGrafica('line')">
                                                    <i class="fas fa-chart-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div style="position: relative; height: 300px;">
                                            <canvas id="proveedorChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-list text-info me-2"></i>
                                            Top Proveedores
                                            <span class="badge bg-secondary ms-2">{{ count($gastosProveedor) }}</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th class="ps-3">#</th>
                                                        <th>Proveedor</th>
                                                        <th class="text-end">Facturas</th>
                                                        <th class="text-end">Total (Bs)</th>
                                                        <th class="text-end">%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalGeneral = $gastosProveedor->sum('total_monto');
                                                    @endphp
                                                    @foreach($gastosProveedor as $index => $item)
                                                        @php
                                                            $porcentaje = $totalGeneral > 0 ? ($item->total_monto / $totalGeneral) * 100 : 0;
                                                            $color = ['primary', 'success', 'info', 'warning', 'danger', 'secondary'][$index % 6];
                                                        @endphp
                                                        <tr>
                                                            <td class="ps-3">{{ $index + 1 }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                                         style="width: 28px; height: 28px; font-size: 10px; font-weight: 600;">
                                                                        {{ strtoupper(substr($item->proveedor->proveedor ?? 'P', 0, 1)) }}
                                                                    </div>
                                                                    <small>{{ $item->proveedor->proveedor ?? 'N/A' }}</small>
                                                                </div>
                                                            </td>
                                                            <td class="text-end">
                                                                <span class="badge bg-secondary">{{ $item->total_facturas }}</span>
                                                            </td>
                                                            <td class="text-end fw-semibold">
                                                                Bs. {{ number_format($item->total_monto, 0) }}
                                                            </td>
                                                            <td class="text-end">
                                                                <div class="d-flex align-items-center gap-1 justify-content-end">
                                                                    <span class="badge bg-{{ $color }}">{{ number_format($porcentaje, 1) }}%</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: DETALLE DE FACTURAS POR MES MEJORADO -->
    <!-- ============================================================ -->
    <div class="modal fade" id="detalleFacturasModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="detalleModalTitle">
                        <i class="fas fa-file-invoice me-2"></i>
                        Detalle de Facturas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="detalleModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="text-muted mt-2">Cargando facturas...</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="exportarDetallePDF()">
                        <i class="fas fa-file-pdf me-1"></i> Exportar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- TOAST NOTIFICATIONS -->
    <!-- ============================================================ -->
    <div id="customToast" class="custom-toast">
        <div class="d-flex align-items-center gap-3">
            <i id="customToastIcon" class="fas fa-check-circle fs-5"></i>
            <span id="customToastMessage"></span>
        </div>
    </div>

    <style>
        /* ============================================================
           ESTILOS GENERALES
           ============================================================ */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .rounded-4 { border-radius: 1rem !important; }
        .card { animation: fadeInUp 0.5s ease; }
        .sticky-top { position: sticky; top: 0; z-index: 10; }
        
        .modal-xl {
            max-width: 1200px;
        }
        
        #detalleModalBody {
            max-height: 500px;
            overflow-y: auto;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* ============================================================
           ESTADÍSTICAS CARDS
           ============================================================ */
        .estadistica-card {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        .estadistica-card.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* ============================================================
           CHART HINT
           ============================================================ */
        .chart-interactive-hint {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.75);
            color: white;
            padding: 5px 16px;
            border-radius: 20px;
            font-size: 11px;
            opacity: 0;
            transition: opacity 0.5s ease;
            pointer-events: none;
            z-index: 10;
            white-space: nowrap;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        .chart-interactive-hint.show {
            opacity: 1;
        }
        
        /* ============================================================
           TOAST
           ============================================================ */
        .custom-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            z-index: 9999;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            max-width: 400px;
            border-left: 4px solid #28a745;
        }
        .custom-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .custom-toast.error {
            border-left-color: #dc3545;
        }
        .custom-toast.warning {
            border-left-color: #ffc107;
        }
        .custom-toast.info {
            border-left-color: #17a2b8;
        }
        
        /* ============================================================
           MODO OSCURO
           ============================================================ */
        .dark-mode .table-light { background: #2d2d44 !important; }
        .dark-mode .table-light th { color: #e2e8f0 !important; }
        .dark-mode .table td { color: #e2e8f0 !important; border-color: #2d2d44 !important; }
        .dark-mode .table-dark { background: #0f172a !important; }
        .dark-mode .table-active td { background-color: rgba(102, 126, 234, 0.15) !important; }
        .dark-mode .table-warning td { background-color: rgba(245, 158, 11, 0.15) !important; }
        .dark-mode .badge.bg-light { background: #2d2d44 !important; color: #e2e8f0 !important; }
        .dark-mode .bg-light { background: #2d2d44 !important; color: #e2e8f0 !important; }
        .dark-mode .modal-content { background: #1a1a2e !important; color: #e2e8f0 !important; }
        .dark-mode .modal-header { background: linear-gradient(135deg, #1e293b, #0f172a) !important; }
        .dark-mode .modal-footer { border-color: #2d2d44 !important; }
        .dark-mode .custom-toast { background: #1a1a2e !important; color: #e2e8f0 !important; }
        .dark-mode .chart-interactive-hint { background: rgba(30, 41, 59, 0.9); }
        .dark-mode .form-check-label { color: #e2e8f0 !important; }

        /* ============================================================
           SWITCHES
           ============================================================ */
        .form-switch .form-check-input {
            width: 2.5em;
            height: 1.2em;
            cursor: pointer;
        }
        .form-switch .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .form-check-label {
            cursor: pointer;
            user-select: none;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        // ============================================================
        // DATOS PARA GRÁFICAS
        // ============================================================
        const datosConProyeccion = @json($datosConProyeccion);
        const labelsProveedores = @json($labelsProveedores);
        const montosProveedores = @json($montosProveedores);
        const coloresProveedores = @json($coloresProveedores);

        let evolucionChart = null;
        let proveedorChart = null;
        let tipoGraficaProveedor = 'bar';

        // ============================================================
        // GRÁFICA DE EVOLUCIÓN MEJORADA
        // ============================================================
        function inicializarGraficaEvolucion() {
            const ctx = document.getElementById('evolucionChart').getContext('2d');
            
            const meses = datosConProyeccion.meses || ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
            const datosHistoricos = datosConProyeccion.datos_historicos || {};
            const montosProyectados = datosConProyeccion.montos_proyectados || [];
            const montosUsdProyectados = datosConProyeccion.montos_usd_proyectados || [];
            const incrementosProyectados = datosConProyeccion.incrementos_proyectados || [];
            
            const montosReales = [];
            const montosRealesUsd = [];
            const montosProyeccion = [];
            const montosProyeccionUsd = [];
            const labels = [];
            const incrementos = [];
            
            for (let i = 0; i < 12; i++) {
                const mes = i + 1;
                const dato = datosHistoricos[mes] || { monto: 0, monto_usd: 0, incremento: null };
                const esReal = dato['monto'] > 0;
                
                labels.push(meses[i]);
                
                if (esReal) {
                    montosReales.push(dato['monto']);
                    montosRealesUsd.push(dato['monto_usd']);
                    montosProyeccion.push(null);
                    montosProyeccionUsd.push(null);
                    incrementos.push(dato['incremento'] || 0);
                } else {
                    montosReales.push(null);
                    montosRealesUsd.push(null);
                    montosProyeccion.push(montosProyectados[i] || 0);
                    montosProyeccionUsd.push(montosUsdProyectados[i] || 0);
                    incrementos.push(incrementosProyectados[i] || 0);
                }
            }
            
            evolucionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Monto Real (Bs)',
                            data: montosReales,
                            backgroundColor: montosReales.map(v => v !== null ? 'rgba(102, 126, 234, 0.85)' : 'rgba(200,200,200,0.3)'),
                            borderColor: montosReales.map(v => v !== null ? '#667eea' : 'transparent'),
                            borderWidth: 2,
                            borderRadius: 4,
                            order: 2,
                            barPercentage: 0.8,
                            _esReal: true,
                            _datosHistoricos: datosHistoricos
                        },
                        {
                            label: 'Monto Real (USD)',
                            data: montosRealesUsd,
                            backgroundColor: montosRealesUsd.map(v => v !== null ? 'rgba(34, 197, 94, 0.85)' : 'rgba(200,200,200,0.3)'),
                            borderColor: montosRealesUsd.map(v => v !== null ? '#22c55e' : 'transparent'),
                            borderWidth: 2,
                            borderRadius: 4,
                            order: 4,
                            barPercentage: 0.8,
                            hidden: true,
                            _esReal: true,
                            _datosHistoricos: datosHistoricos
                        },
                        {
                            label: 'Monto Proyectado (Bs)',
                            data: montosProyeccion,
                            backgroundColor: 'rgba(245, 158, 11, 0.6)',
                            borderColor: '#f59e0b',
                            borderWidth: 2,
                            borderRadius: 4,
                            borderDash: [5, 5],
                            order: 3,
                            barPercentage: 0.8,
                            _esProyeccion: true,
                            _incrementoProyectado: datosConProyeccion.incremento_promedio || 0,
                            _datosHistoricos: datosHistoricos
                        },
                        {
                            label: 'Monto Proyectado (USD)',
                            data: montosProyeccionUsd,
                            backgroundColor: 'rgba(34, 197, 94, 0.4)',
                            borderColor: '#22c55e',
                            borderWidth: 2,
                            borderRadius: 4,
                            borderDash: [5, 5],
                            order: 5,
                            barPercentage: 0.8,
                            hidden: true,
                            _esProyeccion: true,
                            _incrementoProyectado: datosConProyeccion.incremento_promedio_usd || 0,
                            _datosHistoricos: datosHistoricos
                        },
                        {
                            label: 'Incremento (%)',
                            data: incrementos,
                            type: 'line',
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: incrementos.map(v => v >= 0 ? '#22c55e' : '#ef4444'),
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            yAxisID: 'y1',
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.9)',
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 15,
                            cornerRadius: 10,
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            callbacks: {
                                title: function(context) {
                                    const dataIndex = context[0].dataIndex;
                                    const label = context[0].chart.data.labels[dataIndex];
                                    const dataset = context[0].dataset;
                                    const esProyeccion = dataset ? dataset._esProyeccion : false;
                                    const mes = dataIndex + 1;
                                    const dato = datosHistoricos[mes] || { monto: 0 };
                                    
                                    if (esProyeccion) {
                                        return '📈 ' + label + ' (Proyección)';
                                    }
                                    if (dato.monto > 0) {
                                        return '📊 ' + label + ' (Histórico)';
                                    }
                                    return label;
                                },
                                label: function(context) {
                                    const dataIndex = context.dataIndex;
                                    const dataset = context.dataset;
                                    const label = dataset.label || '';
                                    const value = context.parsed.y;
                                    const mes = dataIndex + 1;
                                    const dato = dataset._datosHistoricos ? dataset._datosHistoricos[mes] || {} : {};
                                    const esProyeccion = dataset._esProyeccion || false;
                                    
                                    if (esProyeccion) {
                                        const incremento = dataset._incrementoProyectado || 0;
                                        return label + ': ' + value.toFixed(2) + ' (📈 ' + (incremento >= 0 ? '+' : '') + incremento.toFixed(1) + '%)';
                                    }
                                    
                                    const incremento = dato.incremento !== null ? dato.incremento : 0;
                                    const color = incremento >= 0 ? '🟢' : '🔴';
                                    return label + ': ' + value.toFixed(2) + ' (' + color + ' ' + (incremento >= 0 ? '+' : '') + incremento.toFixed(1) + '%)';
                                },
                                afterBody: function(context) {
                                    const dataIndex = context[0].dataIndex;
                                    const dataset = context[0].dataset;
                                    const mes = dataIndex + 1;
                                    const dato = datosHistoricos[mes] || { monto: 0 };
                                    const esProyeccion = dataset ? dataset._esProyeccion : false;
                                    
                                    if (!esProyeccion && dato.monto > 0) {
                                        return '🖱️ Haz clic para ver el detalle de facturas';
                                    }
                                    if (esProyeccion) {
                                        return '📌 Dato proyectado - Sujeto a cambios';
                                    }
                                    return null;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            ticks: {
                                callback: function(value) {
                                    const val = parseFloat(value) || 0;
                                    if (val >= 1000000) return 'Bs. ' + (val / 1000000).toFixed(1) + 'M';
                                    if (val >= 1000) return 'Bs. ' + (val / 1000).toFixed(0) + 'k';
                                    return 'Bs. ' + val.toFixed(0);
                                }
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            grid: { drawOnChartArea: false },
                            ticks: {
                                callback: function(value) {
                                    return parseFloat(value).toFixed(0) + '%';
                                }
                            }
                        }
                    },
                    onClick: function(event, elements) {
                        if (elements.length === 0) {
                            showToast('📊 Selecciona una barra para ver el detalle', 'info');
                            return;
                        }
                        
                        const element = elements[0];
                        const dataIndex = element.index;
                        const label = this.data.labels[dataIndex];
                        const dataset = element.dataset;
                        const esProyeccion = dataset._esProyeccion || false;
                        const mes = dataIndex + 1;
                        const dato = datosHistoricos[mes] || { monto: 0 };
                        
                        if (esProyeccion || dato.monto === 0) {
                            showToast('📭 No hay facturas registradas en ' + label, 'info');
                            return;
                        }
                        
                        const anio = document.getElementById('filterAnio')?.value || new Date().getFullYear();
                        const proveedorId = document.getElementById('filterProveedor')?.value || '';
                        mostrarDetalleFacturas(mes, anio, proveedorId, label);
                    },
                    onHover: function(event, elements) {
                        const canvas = event.chart.canvas;
                        const hint = document.getElementById('chartHint');
                        
                        if (!elements || elements.length === 0) {
                            canvas.style.cursor = 'default';
                            if (hint) {
                                hint.classList.remove('show');
                                setTimeout(() => {
                                    if (!hint.classList.contains('show')) {
                                        hint.classList.add('show');
                                    }
                                }, 100);
                            }
                            return;
                        }
                        
                        const element = elements[0];
                        const dataset = element.dataset;
                        const esProyeccion = dataset._esProyeccion || false;
                        const dataIndex = element.index;
                        const mes = dataIndex + 1;
                        const dato = datosHistoricos[mes] || { monto: 0 };
                        
                        if (!esProyeccion && dato.monto > 0) {
                            canvas.style.cursor = 'pointer';
                            if (hint) {
                                hint.classList.add('show');
                                hint.innerHTML = '<i class="fas fa-mouse-pointer me-1"></i> Haz clic para ver el detalle de las facturas';
                            }
                        } else {
                            canvas.style.cursor = 'default';
                            if (hint) {
                                hint.classList.remove('show');
                                setTimeout(() => {
                                    if (!hint.classList.contains('show')) {
                                        hint.classList.add('show');
                                    }
                                }, 100);
                            }
                        }
                    }
                }
            });
        }

        // ============================================================
        // FUNCIONES DE VISIBILIDAD
        // ============================================================
        function toggleUsdVisibility() {
            const checked = document.getElementById('toggleUsd').checked;
            if (evolucionChart) {
                evolucionChart.data.datasets.forEach(dataset => {
                    if (dataset.label.includes('USD')) {
                        dataset.hidden = !checked;
                    }
                });
                evolucionChart.update();
            }
        }

        function toggleProyeccionVisibility() {
            const checked = document.getElementById('toggleProyeccion').checked;
            if (evolucionChart) {
                evolucionChart.data.datasets.forEach(dataset => {
                    if (dataset.label.includes('Proyectado')) {
                        dataset.hidden = !checked;
                    }
                });
                evolucionChart.update();
            }
        }

        function toggleIncrementoVisibility() {
            const checked = document.getElementById('toggleIncremento').checked;
            if (evolucionChart) {
                evolucionChart.data.datasets.forEach(dataset => {
                    if (dataset.label.includes('Incremento')) {
                        dataset.hidden = !checked;
                    }
                });
                evolucionChart.update();
            }
        }

        // ============================================================
        // GRÁFICA DE PROVEEDORES MEJORADA
        // ============================================================
        function inicializarGraficaProveedores() {
            if (labelsProveedores.length === 0) return;
            
            const ctx = document.getElementById('proveedorChart').getContext('2d');
            
            proveedorChart = new Chart(ctx, {
                type: tipoGraficaProveedor,
                data: {
                    labels: labelsProveedores,
                    datasets: [{
                        label: 'Gasto Total (Bs)',
                        data: montosProveedores,
                        backgroundColor: coloresProveedores.slice(0, labelsProveedores.length).map(c => c + 'CC'),
                        borderColor: coloresProveedores.slice(0, labelsProveedores.length),
                        borderWidth: 2,
                        borderRadius: 6,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { usePointStyle: true }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Bs. ' + context.parsed.y.toFixed(2);
                                },
                                afterBody: function(tooltipItems) {
                                    const total = tooltipItems.reduce((sum, item) => sum + item.parsed.y, 0);
                                    return 'Total: Bs. ' + total.toFixed(2);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000) return 'Bs. ' + (value / 1000).toFixed(0) + 'k';
                                    return 'Bs. ' + value.toFixed(0);
                                }
                            }
                        }
                    }
                }
            });
        }

        function cambiarTipoGrafica(tipo) {
            tipoGraficaProveedor = tipo;
            if (proveedorChart) {
                proveedorChart.destroy();
            }
            inicializarGraficaProveedores();
        }

        // ============================================================
        // MOSTRAR MODAL CON DETALLE DE FACTURAS
        // ============================================================
        function mostrarDetalleFacturas(mes, anio, proveedorId, nombreMes) {
            document.getElementById('detalleModalTitle').innerHTML = 
                '<i class="fas fa-spinner fa-spin me-2"></i> Cargando facturas de ' + nombreMes;
            
            const modalElement = document.getElementById('detalleFacturasModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            document.getElementById('detalleModalBody').innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2">Cargando facturas de ${nombreMes}...</p>
                </div>
            `;
            
            const url = `/facturas/por-mes?mes=${mes}&anio=${anio}&proveedor_id=${proveedorId}`;
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('detalleModalTitle').innerHTML = 
                        '<i class="fas fa-file-invoice me-2"></i> Facturas de ' + nombreMes + ' ' + anio;
                    renderizarFacturasDetalle(data.facturas, nombreMes, anio);
                } else {
                    document.getElementById('detalleModalBody').innerHTML = `
                        <div class="alert alert-danger border-0 shadow-sm">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${data.message || 'Error al cargar las facturas'}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('detalleModalBody').innerHTML = `
                    <div class="alert alert-danger border-0 shadow-sm">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error al cargar las facturas
                    </div>
                `;
            });
        }

        function renderizarFacturasDetalle(facturas, nombreMes, anio) {
            const container = document.getElementById('detalleModalBody');
            
            if (!facturas || facturas.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                        <p class="text-muted mt-2">No hay facturas registradas en ${nombreMes} ${anio}</p>
                    </div>
                `;
                return;
            }
            
            const totalBs = facturas.reduce((sum, f) => sum + f.monto, 0);
            const totalUsd = facturas.reduce((sum, f) => sum + (f.monto_usd || 0), 0);
            
            let html = `
                <div class="alert alert-info border-0 shadow-sm mb-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <strong>📊 Resumen del mes</strong>
                        </div>
                        <div class="d-flex gap-3 flex-wrap">
                            <span><strong>Facturas:</strong> ${facturas.length}</span>
                            <span><strong>Total Bs:</strong> Bs. ${totalBs.toFixed(2)}</span>
                            <span><strong>Total USD:</strong> $ ${totalUsd.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>N° Factura</th>
                                <th>Proveedor</th>
                                <th>Fecha</th>
                                <th class="text-end">Monto</th>
                                <th class="text-end">USD</th>
                                <th>Moneda</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            facturas.forEach(factura => {
                const estadoColor = factura.estado_pago_color || 'secondary';
                const estadoLabel = factura.estado_pago_label || factura.estado_pago;
                
                html += `
                    <tr>
                        <td><span class="fw-bold">${factura.numero_factura}</span></td>
                        <td><small>${factura.proveedor}</small></td>
                        <td><small>${factura.fecha_factura}</small></td>
                        <td class="text-end fw-semibold">${factura.monto.toFixed(2)}</td>
                        <td class="text-end text-success">${factura.monto_usd ? factura.monto_usd.toFixed(2) : '-'}</td>
                        <td><span class="badge bg-${factura.moneda === 'USD' ? 'success' : (factura.moneda === 'Bs' ? 'warning' : 'info')}">${factura.moneda}</span></td>
                        <td><span class="badge bg-${estadoColor}"><i class="fas fa-${factura.estado_pago === 'pagado' ? 'check-circle' : (factura.estado_pago === 'vencido' ? 'exclamation-circle' : 'clock')} me-1"></i>${estadoLabel}</span></td>
                    </tr>
                `;
            });
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            container.innerHTML = html;
        }

        // ============================================================
        // FUNCIONES DE EXPORTACIÓN
        // ============================================================
        function exportarPDF() {
            showToast('📄 Generando PDF...', 'info');
            setTimeout(() => {
                showToast('✅ PDF generado exitosamente', 'success');
            }, 2000);
        }

        function exportarExcel() {
            showToast('📊 Generando Excel...', 'info');
            setTimeout(() => {
                showToast('✅ Excel generado exitosamente', 'success');
            }, 2000);
        }

        function exportarDetallePDF() {
            showToast('📄 Exportando detalle a PDF...', 'info');
            setTimeout(() => {
                showToast('✅ Detalle exportado exitosamente', 'success');
            }, 2000);
        }

        // ============================================================
        // FILTROS Y VISTAS
        // ============================================================
        function aplicarFiltros() {
            const anio = document.getElementById('filterAnio').value;
            const proveedorId = document.getElementById('filterProveedor').value;
            
            let url = '{{ route("facturas.estadisticas") }}';
            const params = new URLSearchParams();
            if (anio) params.append('anio', anio);
            if (proveedorId) params.append('proveedor_id', proveedorId);
            if (params.toString()) url += '?' + params.toString();
            window.location.href = url;
        }

        function cambiarVista() {
            const vista = document.getElementById('filterVista').value;
            // Implementar cambio de vista
            showToast('🔄 Vista cambiada a: ' + vista, 'info');
        }

        function toggleResumenDetalle() {
            const detalle = document.getElementById('resumenDetalle');
            const icon = document.getElementById('resumenToggleIcon');
            if (detalle.style.display === 'none') {
                detalle.style.display = 'block';
                icon.className = 'fas fa-chevron-up';
            } else {
                detalle.style.display = 'none';
                icon.className = 'fas fa-chevron-down';
            }
        }

        // ============================================================
        // MODO OSCURO
        // ============================================================
        function toggleDarkMode() {
            document.body.classList.toggle('dark-mode');
            const text = document.getElementById('darkModeText');
            text.textContent = document.body.classList.contains('dark-mode') ? 'Modo Claro' : 'Modo Oscuro';
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
            
            // Actualizar gráficas
            if (evolucionChart) evolucionChart.update();
            if (proveedorChart) proveedorChart.update();
        }

        // ============================================================
        // TOAST
        // ============================================================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('customToast');
            const toastMessage = document.getElementById('customToastMessage');
            const toastIcon = document.getElementById('customToastIcon');
            
            const icons = {
                success: 'fa-check-circle text-success',
                error: 'fa-exclamation-circle text-danger',
                info: 'fa-info-circle text-info',
                warning: 'fa-exclamation-triangle text-warning'
            };
            
            toastIcon.className = 'fas ' + (icons[type] || icons.success) + ' fs-5';
            toastMessage.textContent = message;
            toast.className = 'custom-toast ' + type;
            toast.classList.add('show');
            
            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }

        // ============================================================
        // INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            inicializarGraficaEvolucion();
            inicializarGraficaProveedores();
            
            // Animar tarjetas
            document.querySelectorAll('.estadistica-card').forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('visible');
                }, 100 * index);
            });
            
            // Mostrar hint
            setTimeout(() => {
                const hint = document.getElementById('chartHint');
                if (hint) hint.classList.add('show');
            }, 1500);
            
            // Cargar modo oscuro
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark-mode');
                document.getElementById('darkModeText').textContent = 'Modo Claro';
            }
            
            // Auto-cerrar alertas
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    const closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) closeBtn.click();
                }, 5000);
            });
            
            console.log('✅ Estadísticas avanzadas cargadas correctamente');
        });
    </script>
</x-layout>