<x-layout>
    <x-slot:title>RZN-OFFERINGS | Reportes de Facturas</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h4 class="mb-0 fw-bold">Reportes de Facturas</h4>
                                    <p class="opacity-75 mb-0 small">Análisis detallado de tus facturas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Filtros -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-primary w-100" onclick="aplicarFiltros()">
                                    <i class="fas fa-filter me-2"></i>
                                    Aplicar Filtros
                                </button>
                            </div>
                        </div>

                        <!-- Estadísticas Rápidas -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Total Facturas</h6>
                                                <h3 class="fw-bold mb-0">{{ $estadisticas['total_facturas'] }}</h3>
                                            </div>
                                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-file-invoice text-primary fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Total Facturado (Bs)</h6>
                                                <h3 class="fw-bold mb-0">Bs. {{ number_format($estadisticas['total_monto'], 2) }}</h3>
                                            </div>
                                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-money-bill-wave text-success fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Total Facturado (USD)</h6>
                                                <h3 class="fw-bold mb-0">$ {{ number_format($estadisticas['total_usd'], 2) }}</h3>
                                            </div>
                                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-dollar-sign text-info fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Incremento Anual</h6>
                                                <h3 class="fw-bold mb-0 {{ $estadisticas['incremento'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ $estadisticas['incremento'] >= 0 ? '+' : '' }}{{ $estadisticas['incremento'] }}%
                                                </h3>
                                            </div>
                                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-arrow-up text-warning fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gráficas principales -->
                        <div class="row g-4">
                            <!-- Gráfica de Torta -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-chart-pie text-primary me-2"></i>
                                            Distribución por Proveedor
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div style="position: relative; height: 300px;">
                                            <canvas id="tortaChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfica de Líneas con Barra de Información Fija -->
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4 d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-chart-line text-primary me-2"></i>
                                            Evolución Mensual (Bs)
                                        </h6>
                                        <span class="badge bg-primary">{{ $anio }}</span>
                                    </div>
                                    <div class="card-body">
                                        <!-- 🔹 Barra de información fija (siempre visible) -->
                                        <div id="legendContainer" class="bg-light p-3 rounded-4 mb-3 border" style="transition: all 0.2s ease;">
                                            <div class="row g-2 text-center align-items-center">
                                                <div class="col-4">
                                                    <span class="text-muted small text-uppercase">Mes</span>
                                                    <h6 id="legendMes" class="fw-bold mb-0">-</h6>
                                                </div>
                                                <div class="col-4">
                                                    <span class="text-muted small text-uppercase">Bs.</span>
                                                    <h6 id="legendBs" class="fw-bold text-primary mb-0">-</h6>
                                                </div>
                                                <div class="col-4">
                                                    <span class="text-muted small text-uppercase">USD</span>
                                                    <h6 id="legendUsd" class="fw-bold text-success mb-0">-</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="position: relative; height: 300px;">
                                            <canvas id="lineaBsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Gráfica de Proyección -->
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4 d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-chart-line text-warning me-2"></i>
                                            Proyección de Gastos Futuros
                                        </h6>
                                        <span class="badge bg-warning text-dark">6 meses de proyección</span>
                                    </div>
                                    <div class="card-body">
                                        <div style="position: relative; height: 320px;">
                                            <canvas id="proyeccionChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabla Resumen -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-transparent border-0 pt-4">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-list text-info me-2"></i>
                                            Resumen del Año
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light sticky-top">
                                                    <tr>
                                                        <th class="ps-3">Mes</th>
                                                        <th class="text-center">Facturas</th>
                                                        <th class="text-end">Bs.</th>
                                                        <th class="text-end">USD</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                                                        $query = App\Models\Factura::whereYear('fecha_factura', $anio);
                                                        if ($proveedorId) {
                                                            $query->where('proveedor_id', $proveedorId);
                                                        }
                                                        $datosMes = $query->select(
                                                            \DB::raw('MONTH(fecha_factura) as mes'),
                                                            \DB::raw('COUNT(*) as total_facturas'),
                                                            \DB::raw('SUM(monto) as total_monto'),
                                                            \DB::raw('SUM(monto_usd) as total_usd')
                                                        )
                                                        ->groupBy(\DB::raw('MONTH(fecha_factura)'))
                                                        ->orderBy(\DB::raw('MONTH(fecha_factura)'))
                                                        ->get()
                                                        ->keyBy('mes');
                                                        
                                                        $totalFacturas = 0;
                                                        $totalMonto = 0;
                                                        $totalUsd = 0;
                                                    @endphp
                                                    @for($i = 1; $i <= 12; $i++)
                                                        @php
                                                            $dato = $datosMes->get($i);
                                                            $mesNombre = $meses[$i-1];
                                                            $facturas = $dato->total_facturas ?? 0;
                                                            $monto = $dato->total_monto ?? 0;
                                                            $usd = $dato->total_usd ?? 0;
                                                            
                                                            $totalFacturas += $facturas;
                                                            $totalMonto += $monto;
                                                            $totalUsd += $usd;
                                                            
                                                            $hasData = $facturas > 0 || $monto > 0;
                                                        @endphp
                                                        <tr class="{{ $hasData ? 'table-active' : '' }}">
                                                            <td class="ps-3 fw-semibold">{{ $mesNombre }}</td>
                                                            <td class="text-center">
                                                                @if($facturas > 0)
                                                                    <span class="badge bg-primary">{{ $facturas }}</span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-end {{ $monto > 0 ? 'fw-semibold' : 'text-muted' }}">
                                                                @if($monto > 0)
                                                                    {{ number_format($monto, 0) }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td class="text-end {{ $usd > 0 ? 'fw-semibold' : 'text-muted' }}">
                                                                @if($usd > 0)
                                                                    {{ number_format($usd, 0) }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                                <tfoot class="table-dark">
                                                    <tr>
                                                        <td class="ps-3 fw-bold">Total</td>
                                                        <td class="text-center fw-bold">{{ $totalFacturas }}</td>
                                                        <td class="text-end fw-bold">{{ number_format($totalMonto, 0) }}</td>
                                                        <td class="text-end fw-bold">{{ number_format($totalUsd, 0) }}</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="p-2 bg-light border-top">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Los valores en negrita indican meses con actividad
                                            </small>
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

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .rounded-4 { border-radius: 1rem !important; }
        .card { animation: fadeInUp 0.5s ease; }
        .sticky-top { position: sticky; top: 0; z-index: 10; }
        .table-active td { background-color: rgba(102, 126, 234, 0.05) !important; }
        
        /* Estilo para la barra de información fija */
        #legendContainer {
            background: #f8f9fa;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        #legendContainer h6 {
            font-size: 1.2rem;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dark-mode .table-light { background: #2d2d44 !important; }
        .dark-mode .table-light th { color: #e2e8f0 !important; }
        .dark-mode .table td { color: #e2e8f0 !important; border-color: #2d2d44 !important; }
        .dark-mode .table-dark { background: #0f172a !important; }
        .dark-mode .table-active td { background-color: rgba(102, 126, 234, 0.15) !important; }
        .dark-mode .bg-light { background: #1a1a2e !important; }
        .dark-mode #legendContainer { background: #1a1a2e !important; border-color: #2d2d44 !important; }
        .dark-mode #legendContainer h6 { color: #e2e8f0 !important; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        // ============================================================
        // DATOS DE LAS GRÁFICAS
        // ============================================================
        const tortaData = @json($tortaData);
        const lineaBsData = @json($lineaBsData);
        const lineaUsdData = @json($lineaUsdData);
        const proyeccionData = @json($proyeccionData);

        let tortaChart = null;
        let lineaBsChart = null;
        let proyeccionChart = null;

        // ============================================================
        // FORMATO DE NÚMEROS
        // ============================================================
        function formatNumber(value) {
            return new Intl.NumberFormat('es-VE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        }

        // ============================================================
        // ACTUALIZAR BARRA DE INFORMACIÓN (siempre visible)
        // ============================================================
        function actualizarBarra(index, chart) {
            const bsValue = chart.data.datasets[0].data[index];
            const usdValue = chart.data.datasets[1].data[index];
            const mesLabel = chart.data.labels[index];

            document.getElementById('legendMes').textContent = mesLabel;
            document.getElementById('legendBs').textContent = 'Bs. ' + formatNumber(bsValue);
            document.getElementById('legendUsd').textContent = '$ ' + formatNumber(usdValue);
        }

        // ============================================================
        // INICIALIZAR GRÁFICAS
        // ============================================================
        function inicializarGraficas() {
            // 1. Gráfica de Torta con leyenda mejorada
            console.log('🔹 Inicializando gráfica de torta con datos:', tortaData);
            const ctxTorta = document.getElementById('tortaChart').getContext('2d');
            tortaChart = new Chart(ctxTorta, {
                type: 'doughnut',
                data: {
                    labels: tortaData.labels,
                    datasets: [{
                        data: tortaData.montos,
                        data1: tortaData.total_usd,
                        backgroundColor: tortaData.colores,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { size: 11 },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    return data.labels.map((label, i) => ({
                                        text: label + ': Bs. ' + formatNumber(data.datasets[0].data[i]) +  ' / ' + ' $ '+ formatNumber(data.datasets[0].data1[i]),
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        strokeStyle: data.datasets[0].backgroundColor[i],
                                        pointStyle: 'circle',
                                        index: i
                                    }));
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);

                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return 'Bs. ' + formatNumber(context.parsed) + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });

            // 2. Gráfica de Líneas con barra de información fija
            const ctxLineaBs = document.getElementById('lineaBsChart').getContext('2d');
            lineaBsChart = new Chart(ctxLineaBs, {
                type: 'line',
                data: {
                    labels: lineaBsData.labels,
                    datasets: [{
                        label: 'Monto en Bs',
                        data: lineaBsData.data,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }, {
                        label: 'Monto en USD',
                        data: lineaUsdData.data,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#22c55e',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        yAxisID: 'y1'
                    }]
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
                            callbacks: {
                                label: function(context) {
                                    if (context.dataset.label === 'Monto en Bs') {
                                        return 'Bs. ' + formatNumber(context.parsed.y);
                                    }
                                    return '$ ' + formatNumber(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000) {
                                        return 'Bs. ' + (value / 1000).toFixed(0) + 'k';
                                    }
                                    return 'Bs. ' + value.toFixed(0);
                                }
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            grid: { drawOnChartArea: false },
                            ticks: {
                                callback: function(value) {
                                    return '$ ' + value.toFixed(0);
                                }
                            }
                        }
                    },
                    // 🔹 Evento hover: actualiza la barra, no la oculta
                    onHover: function(event, chartElement) {
                        if (chartElement && chartElement.length > 0) {
                            const index = chartElement[0].index;
                            actualizarBarra(index, this);
                        }
                    }
                }
            });

            // 🔹 Inicializar la barra con el primer punto (índice 0)
            if (lineaBsData.labels && lineaBsData.labels.length > 0) {
                actualizarBarra(0, lineaBsChart);
            }

            // 3. Gráfica de Proyección
            const ctxProyeccion = document.getElementById('proyeccionChart').getContext('2d');
            
            const labelsProy = proyeccionData.labels;
            const datosBs = proyeccionData.datos_bs;
            const proyeccionBs = proyeccionData.proyeccion_bs;
            const datosUsd = proyeccionData.datos_usd;
            const proyeccionUsd = proyeccionData.proyeccion_usd;
            
            const proyBsCompleta = [...Array(datosBs.length).fill(null), ...proyeccionBs];
            const proyUsdCompleta = [...Array(datosUsd.length).fill(null), ...proyeccionUsd];
            const puntoCorte = datosBs.length;
            
            proyeccionChart = new Chart(ctxProyeccion, {
                type: 'line',
                data: {
                    labels: labelsProy,
                    datasets: [
                        {
                            label: '📊 Histórico BS',
                            data: datosBs,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.15)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#667eea',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            spanGaps: false
                        },
                        {
                            label: '📈 Proyección BS',
                            data: proyBsCompleta,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.05)',
                            borderWidth: 2,
                            borderDash: [8, 4],
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#667eea',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointStyle: 'triangle',
                            spanGaps: false
                        },
                        {
                            label: '📊 Histórico USD',
                            data: datosUsd,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.15)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            spanGaps: false
                        },
                        {
                            label: '📈 Proyección USD',
                            data: proyUsdCompleta,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.05)',
                            borderWidth: 2,
                            borderDash: [8, 4],
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointStyle: 'triangle',
                            spanGaps: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 12,
                                font: { size: 11, weight: 'bold' },
                                boxWidth: 15
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    if (context.parsed.y === null) return '';
                                    const label = context.dataset.label || '';
                                    const value = context.parsed.y;
                                    if (label.includes('USD') || label.includes('Proyección USD')) {
                                        return label + ': $ ' + formatNumber(value);
                                    }
                                    return label + ': Bs. ' + formatNumber(value);
                                },
                                afterBody: function(context) {
                                    const dataIndex = context[0].dataIndex;
                                    const label = context[0].chart.data.labels[dataIndex];
                                    if (label && label.includes('(proy)')) {
                                        return '📌 Valor proyectado';
                                    }
                                    return '📊 Valor histórico';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                font: { size: 10 },
                                maxRotation: 45,
                                minRotation: 30
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                font: { size: 10 },
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Bs. ' + (value / 1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        return 'Bs. ' + (value / 1000).toFixed(0) + 'k';
                                    }
                                    return 'Bs. ' + value.toFixed(0);
                                }
                            }
                        }
                    },
                    elements: {
                        line: {
                            borderWidth: 2
                        }
                    }
                },
                plugins: [{
                    id: 'verticalLine',
                    beforeDraw: function(chart) {
                        const ctx2 = chart.ctx;
                        const chartArea = chart.chartArea;
                        const xAxis = chart.scales.x;
                        
                        if (puntoCorte > 0 && puntoCorte < chart.data.labels.length) {
                            const x = xAxis.getPixelForValue(puntoCorte);
                            ctx2.save();
                            ctx2.beginPath();
                            ctx2.moveTo(x, chartArea.top);
                            ctx2.lineTo(x, chartArea.bottom);
                            ctx2.strokeStyle = 'rgba(239, 68, 68, 0.3)';
                            ctx2.lineWidth = 2;
                            ctx2.setLineDash([5, 5]);
                            ctx2.stroke();
                            ctx2.restore();
                            
                            ctx2.save();
                            ctx2.fillStyle = 'rgba(239, 68, 68, 0.7)';
                            ctx2.font = '10px sans-serif';
                            ctx2.textAlign = 'center';
                            ctx2.fillText('⚡ Histórico → Proyección', x, chartArea.top - 8);
                            ctx2.restore();
                        }
                    }
                }]
            });
        }

        // ============================================================
        // APLICAR FILTROS
        // ============================================================
        function aplicarFiltros() {
            const anio = document.getElementById('filterAnio').value;
            const proveedorId = document.getElementById('filterProveedor').value;
            
            let url = '{{ route("facturas.reportes") }}';
            const params = new URLSearchParams();
            
            if (anio) params.append('anio', anio);
            if (proveedorId) params.append('proveedor_id', proveedorId);
            
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            window.location.href = url;
        }

        // ============================================================
        // INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            inicializarGraficas();
            console.log('✅ Reportes de facturas cargados correctamente');
        });

        // ============================================================
        // REDIMENSIONAR GRÁFICAS
        // ============================================================
        window.addEventListener('resize', function() {
            if (tortaChart) tortaChart.resize();
            if (lineaBsChart) lineaBsChart.resize();
            if (proyeccionChart) proyeccionChart.resize();
        });
    </script>
</x-layout>