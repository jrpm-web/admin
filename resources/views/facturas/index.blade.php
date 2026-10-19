<x-layout>
    <x-slot:title>RZN-OFFERINGS | Facturas</x-slot:title>

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
                                        <i class="fas fa-file-invoice me-2"></i>
                                        Dashboard de Facturas
                                    </h4>
                                    <p class="opacity-75 mb-0 small">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ now()->format('d/m/Y H:i') }} | 
                                        <span id="facturasCount">{{ $facturas->total() }}</span> facturas encontradas
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-light" onclick="toggleVista()">
                                    <i class="fas fa-th-large me-1"></i>
                                    <span id="vistaTexto">Vista Grid</span>
                                </button>
                                <a href="{{ route('facturas.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus me-1"></i>
                                    Nueva Factura
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Alertas con animación -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm animate__animated animate__fadeInDown" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm animate__animated animate__fadeInDown" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 1: ESTADÍSTICAS MEJORADAS -->
                        <!-- ============================================================ -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100 estadistica-card" data-animate="fadeInUp">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Total Facturas</h6>
                                                <h3 class="fw-bold mb-0" id="totalFacturas">{{ $estadisticas['total_facturas'] }}</h3>
                                                <small class="text-muted">
                                                    <i class="fas fa-arrow-{{ $estadisticas['tendencia_total'] >= 0 ? 'up text-success' : 'down text-danger' }} me-1"></i>
                                                    {{ abs($estadisticas['tendencia_total']) }}% vs mes anterior
                                                </small>
                                            </div>
                                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-file-invoice text-primary fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100 estadistica-card" data-animate="fadeInUp">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Pendientes</h6>
                                                <h3 class="fw-bold mb-0 text-warning">{{ $estadisticas['total_pendientes'] }}</h3>
                                                <small class="text-muted">
                                                    <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                                    {{ $estadisticas['porcentaje_pendiente'] }}% del total
                                                </small>
                                            </div>
                                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-clock text-warning fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100 estadistica-card" data-animate="fadeInUp">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Pagadas</h6>
                                                <h3 class="fw-bold mb-0 text-success">{{ $estadisticas['total_pagadas'] }}</h3>
                                                <small class="text-muted">
                                                    <i class="fas fa-check-circle me-1 text-success"></i>
                                                    {{ $estadisticas['porcentaje_pagado'] }}% del total
                                                </small>
                                            </div>
                                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-check-circle text-success fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 shadow-sm rounded-4 h-100 estadistica-card" data-animate="fadeInUp">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-muted small text-uppercase">Total en USD</h6>
                                                <h3 class="fw-bold mb-0">$ {{ number_format($estadisticas['total_monto_usd'], 2) }}</h3>
                                                <small class="text-muted">
                                                    <i class="fas fa-arrow-{{ $estadisticas['tendencia_monto'] >= 0 ? 'up text-success' : 'down text-danger' }} me-1"></i>
                                                    {{ abs($estadisticas['tendencia_monto']) }}% vs mes anterior
                                                </small>
                                            </div>
                                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                                <i class="fas fa-dollar-sign text-info fs-4"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 2: FILTROS AVANZADOS MEJORADOS -->
                        <!-- ============================================================ -->
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                            <h5 class="fw-bold mb-0">
                                                <i class="fas fa-sliders-h text-primary me-2"></i>
                                                Filtros de Búsqueda
                                            </h5>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-secondary btn-sm" onclick="guardarFiltros()">
                                                    <i class="fas fa-bookmark me-1"></i>
                                                    Guardar Filtros
                                                </button>
                                                <button class="btn btn-outline-secondary btn-sm" onclick="cargarFiltrosGuardados()">
                                                    <i class="fas fa-bookmark me-1"></i>
                                                    Cargar Filtros
                                                </button>
                                                <button class="btn btn-outline-danger btn-sm" onclick="limpiarFiltros()">
                                                    <i class="fas fa-undo me-1"></i>
                                                    Limpiar
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <form id="filtrosForm" method="GET" action="{{ route('facturas.index') }}" class="row g-3">
                                            <!-- Filtro Rápido: Mes Actual / Todos -->
                                            <div class="col-12 mb-2">
                                                <div class="btn-group flex-wrap" role="group">
                                                    <input type="radio" class="btn-check" name="rango" id="rango_mes" value="mes" {{ request('rango', 'mes') == 'mes' ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <label class="btn btn-outline-primary" for="rango_mes">
                                                        <i class="fas fa-calendar-alt me-1"></i> Mes Actual
                                                    </label>
                                                    
                                                    <input type="radio" class="btn-check" name="rango" id="rango_trimestre" value="trimestre" {{ request('rango') == 'trimestre' ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <label class="btn btn-outline-primary" for="rango_trimestre">
                                                        <i class="fas fa-calendar-week me-1"></i> Este Trimestre
                                                    </label>
                                                    
                                                    <input type="radio" class="btn-check" name="rango" id="rango_anio" value="anio" {{ request('rango') == 'anio' ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <label class="btn btn-outline-primary" for="rango_anio">
                                                        <i class="fas fa-calendar-year me-1"></i> Este Año
                                                    </label>
                                                    
                                                    <input type="radio" class="btn-check" name="rango" id="rango_todos" value="todos" {{ request('rango') == 'todos' ? 'checked' : '' }} onchange="this.form.submit()">
                                                    <label class="btn btn-outline-primary" for="rango_todos">
                                                        <i class="fas fa-history me-1"></i> Todos
                                                    </label>
                                                    
                                                    <input type="radio" class="btn-check" name="rango" id="rango_personalizado" value="personalizado" {{ request('rango') == 'personalizado' ? 'checked' : '' }} onchange="toggleRangoPersonalizado()">
                                                    <label class="btn btn-outline-primary" for="rango_personalizado">
                                                        <i class="fas fa-calendar-range me-1"></i> Personalizado
                                                    </label>
                                                </div>
                                            </div>

                                            <!-- Filtros Personalizados -->
                                            <div id="filtrosPersonalizados" style="{{ request('rango') == 'personalizado' ? 'display: flex;' : 'display: none;' }}" class="flex-wrap gap-2">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold small">
                                                        <i class="fas fa-calendar-plus me-1"></i>
                                                        Desde
                                                    </label>
                                                    <input type="date" 
                                                           name="fecha_desde" 
                                                           class="form-control form-control-sm"
                                                           value="{{ request('fecha_desde') }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fw-semibold small">
                                                        <i class="fas fa-calendar-minus me-1"></i>
                                                        Hasta
                                                    </label>
                                                    <input type="date" 
                                                           name="fecha_hasta" 
                                                           class="form-control form-control-sm"
                                                           value="{{ request('fecha_hasta') }}">
                                                </div>
                                            </div>

                                            <!-- Filtros adicionales en una fila -->
                                            <div class="col-12">
                                                <div class="row g-2">
                                                    <div class="col-md-3">
                                                        <label class="form-label fw-semibold small">
                                                            <i class="fas fa-building me-1"></i>
                                                            Proveedor
                                                        </label>
                                                        <select name="proveedor_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="">Todos los proveedores</option>
                                                            @foreach($proveedores as $proveedor)
                                                                <option value="{{ $proveedor->id }}" {{ request('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                                    {{ $proveedor->proveedor ?? $proveedor->nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="form-label fw-semibold small">
                                                            <i class="fas fa-circle me-1"></i>
                                                            Estado
                                                        </label>
                                                        <select name="estado" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="">Todos</option>
                                                            @foreach($estadosPago as $key => $label)
                                                                <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="form-label fw-semibold small">
                                                            <i class="fas fa-coins me-1"></i>
                                                            Moneda
                                                        </label>
                                                        <select name="moneda" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="">Todas</option>
                                                            @foreach($monedas as $key => $label)
                                                                <option value="{{ $key }}" {{ request('moneda') == $key ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="form-label fw-semibold small">
                                                            <i class="fas fa-sort-amount-down me-1"></i>
                                                            Ordenar
                                                        </label>
                                                        <select name="orden" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="fecha_desc" {{ request('orden') == 'fecha_desc' ? 'selected' : '' }}>Fecha (Reciente)</option>
                                                            <option value="fecha_asc" {{ request('orden') == 'fecha_asc' ? 'selected' : '' }}>Fecha (Antiguo)</option>
                                                            <option value="monto_desc" {{ request('orden') == 'monto_desc' ? 'selected' : '' }}>Monto (Mayor)</option>
                                                            <option value="monto_asc" {{ request('orden') == 'monto_asc' ? 'selected' : '' }}>Monto (Menor)</option>
                                                            <option value="proveedor" {{ request('orden') == 'proveedor' ? 'selected' : '' }}>Proveedor (A-Z)</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label class="form-label fw-semibold small">
                                                            <i class="fas fa-list-ol me-1"></i>
                                                            Por página
                                                        </label>
                                                        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                                                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Botón Filtrar para rango personalizado -->
                                            <div id="btnFiltrarContainer" style="{{ request('rango') == 'personalizado' ? 'display: block;' : 'display: none;' }}" class="col-12">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-search me-1"></i>
                                                    Aplicar Filtros
                                                </button>
                                            </div>
                                        </form>

                                        <!-- Resumen de filtros activos mejorado -->
                                        @php
                                            $filtrosActivos = [];
                                            if(request('rango') == 'mes') $filtrosActivos[] = ['label' => 'Mes Actual', 'icon' => 'fa-calendar-alt'];
                                            if(request('rango') == 'trimestre') $filtrosActivos[] = ['label' => 'Este Trimestre', 'icon' => 'fa-calendar-week'];
                                            if(request('rango') == 'anio') $filtrosActivos[] = ['label' => 'Este Año', 'icon' => 'fa-calendar-year'];
                                            if(request('rango') == 'personalizado' && (request('fecha_desde') || request('fecha_hasta'))) {
                                                $filtrosActivos[] = [
                                                    'label' => (request('fecha_desde') ? date('d/m/Y', strtotime(request('fecha_desde'))) : 'Inicio') . 
                                                             ' - ' . 
                                                             (request('fecha_hasta') ? date('d/m/Y', strtotime(request('fecha_hasta'))) : 'Hoy'),
                                                    'icon' => 'fa-calendar-range'
                                                ];
                                            }
                                            if(request('proveedor_id')) $filtrosActivos[] = ['label' => $proveedores->firstWhere('id', request('proveedor_id'))?->proveedor ?? 'N/A', 'icon' => 'fa-building'];
                                            if(request('estado')) $filtrosActivos[] = ['label' => $estadosPago[request('estado')] ?? request('estado'), 'icon' => 'fa-circle'];
                                            if(request('moneda')) $filtrosActivos[] = ['label' => $monedas[request('moneda')] ?? request('moneda'), 'icon' => 'fa-coins'];
                                        @endphp

                                        @if(!empty($filtrosActivos))
                                            <div class="mt-3 pt-2 border-top">
                                                <div class="d-flex flex-wrap align-items-center gap-2">
                                                    <small class="text-muted me-2">
                                                        <i class="fas fa-filter me-1"></i>
                                                        Filtros activos:
                                                    </small>
                                                    @foreach($filtrosActivos as $filtro)
                                                        <span class="badge bg-secondary d-inline-flex align-items-center gap-1 py-2 px-3">
                                                            <i class="fas {{ $filtro['icon'] }}"></i>
                                                            {{ $filtro['label'] }}
                                                        </span>
                                                    @endforeach
                                                    <a href="{{ route('facturas.index') }}" class="text-danger small">
                                                        <i class="fas fa-times-circle me-1"></i>
                                                        Quitar todos
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 3: GRÁFICA Y ESTADÍSTICAS (SIN FILTROS REDUNDANTES) -->
                        <!-- ============================================================ -->
                        <div class="row g-4 mb-4">
                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                            <h5 class="fw-bold mb-0">
                                                <i class="fas fa-chart-line text-primary me-2"></i>
                                                Evolución de Facturas
                                            </h5>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-outline-secondary btn-sm" onclick="descargarGrafica()" title="Descargar gráfica">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div style="position: relative; height: 300px;">
                                            <canvas id="facturasChart"></canvas>
                                        </div>
                                        <!-- Resumen de estadísticas de la gráfica -->
                                        <div id="graficaResumen" class="row g-3 mt-3 text-center">
                                            <div class="col-3">
                                                <small class="text-muted">Total</small>
                                                <div class="fw-bold" id="graficaTotal">$ 0.00</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="text-muted">Promedio</small>
                                                <div class="fw-bold" id="graficaPromedio">$ 0.00</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="text-muted">Proyección</small>
                                                <div class="fw-bold text-danger" id="graficaProyeccion">$ 0.00</div>
                                            </div>
                                            <div class="col-3">
                                                <small class="text-muted">Meses</small>
                                                <div class="fw-bold" id="graficaMeses">0</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Panel de resumen rápido -->
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3">
                                            <i class="fas fa-chart-pie text-primary me-2"></i>
                                            Resumen Rápido
                                        </h5>
                                        
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Porcentaje de pago</small>
                                                <small class="fw-bold">{{ $estadisticas['porcentaje_pagado'] ?? 0 }}%</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ $estadisticas['porcentaje_pagado'] ?? 0 }}%"
                                                     aria-valuenow="{{ $estadisticas['porcentaje_pagado'] ?? 0 }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Facturas pendientes</small>
                                                <small class="fw-bold text-warning">{{ $estadisticas['total_pendientes'] }}</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning" 
                                                     role="progressbar" 
                                                     style="width: {{ $estadisticas['porcentaje_pendiente'] ?? 0 }}%"
                                                     aria-valuenow="{{ $estadisticas['porcentaje_pendiente'] ?? 0 }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Facturas vencidas</small>
                                                <small class="fw-bold text-danger">{{ $estadisticas['total_vencidas'] ?? 0 }}</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-danger" 
                                                     role="progressbar" 
                                                     style="width: {{ $estadisticas['porcentaje_vencido'] ?? 0 }}%"
                                                     aria-valuenow="{{ $estadisticas['porcentaje_vencido'] ?? 0 }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="d-grid gap-2">
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Total en Bs</small>
                                                <span class="fw-bold">Bs. {{ number_format($estadisticas['total_monto_bs'] ?? 0, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Total en USD</small>
                                                <span class="fw-bold">$ {{ number_format($estadisticas['total_monto_usd'] ?? 0, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Total en RUB</small>
                                                <span class="fw-bold">RUB {{ number_format($estadisticas['total_monto_rub'] ?? 0, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted">Promedio por factura</small>
                                                <span class="fw-bold">$ {{ number_format($estadisticas['promedio_por_factura'] ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- SECCIÓN 4: TABLA DE FACTURAS MEJORADA -->
                        <!-- ============================================================ -->
                        <div class="row g-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm rounded-4">
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                            <div>
                                                <h5 class="fw-bold mb-0">
                                                    <i class="fas fa-list text-primary me-2"></i>
                                                    Listado de Facturas
                                                    <span class="badge bg-secondary ms-2" id="facturasTotal">{{ $facturas->total() }}</span>
                                                </h5>
                                                <small class="text-muted">
                                                    Mostrando {{ $facturas->firstItem() ?? 0 }} - {{ $facturas->lastItem() ?? 0 }} de {{ $facturas->total() }}
                                                </small>
                                            </div>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <div class="input-group" style="max-width: 250px;">
                                                    <span class="input-group-text bg-white border-end-0">
                                                        <i class="fas fa-search text-muted"></i>
                                                    </span>
                                                    <input type="text" 
                                                           id="searchFactura" 
                                                           class="form-control border-start-0" 
                                                           placeholder="Buscar en tabla..."
                                                           onkeyup="searchFacturas(this.value)">
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="fas fa-download me-1"></i>
                                                        Exportar
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" onclick="exportarDatos('excel')">
                                                            <i class="fas fa-file-excel text-success me-2"></i>Excel
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="exportarDatos('pdf')">
                                                            <i class="fas fa-file-pdf text-danger me-2"></i>PDF
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="exportarDatos('csv')">
                                                            <i class="fas fa-file-csv text-primary me-2"></i>CSV
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item" href="#" onclick="exportarDatos('print')">
                                                            <i class="fas fa-print me-2"></i>Imprimir
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Vista de Tabla -->
                                        <div id="vistaTabla">
                                            @if($facturas->isEmpty())
                                                <div class="text-center py-5">
                                                    <i class="fas fa-file-invoice text-muted" style="font-size: 48px;"></i>
                                                    <h5 class="mt-3 text-muted">No hay facturas que coincidan con los filtros</h5>
                                                    <p class="text-muted">Prueba ajustando los filtros de búsqueda</p>
                                                    <button class="btn btn-primary mt-2" onclick="limpiarFiltros()">
                                                        <i class="fas fa-undo me-2"></i> Limpiar Filtros
                                                    </button>
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-hover align-middle" id="facturasTable">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th style="width: 50px;">#</th>
                                                                <th>N° Factura</th>
                                                                <th>Proveedor</th>
                                                                <th>Monto</th>
                                                                <th>Moneda</th>
                                                                <th>Fecha</th>
                                                                <th>Estado</th>
                                                                <th style="width: 80px;">Archivos</th>
                                                                <th style="width: 120px;">Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="facturasTableBody">
                                                            @foreach($facturas as $factura)
                                                                <tr id="factura-row-{{ $factura->id }}" 
                                                                    class="{{ $factura->estaVencida() ? 'table-danger' : '' }}"
                                                                    data-factura-id="{{ $factura->id }}">
                                                                    <td>#{{ $factura->id }}</td>
                                                                    <td>
                                                                        <span class="fw-bold">{{ $factura->numero_factura }}</span>
                                                                        @if($factura->mes != date('F Y', strtotime($factura->fecha_factura)))
                                                                            <br>
                                                                            <small class="text-muted">{{ $factura->mes }}</small>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                                                 style="width: 28px; height: 28px; font-size: 10px; font-weight: 600;">
                                                                                {{ strtoupper(substr($factura->proveedor->proveedor ?? $factura->proveedor->nombre ?? 'P', 0, 1)) }}
                                                                            </div>
                                                                            <div>
                                                                                <div class="fw-semibold small">{{ $factura->proveedor->proveedor ?? $factura->proveedor->nombre ?? 'N/A' }}</div>
                                                                                @if($factura->proveedor->rif ?? null)
                                                                                    <small class="text-muted">{{ $factura->proveedor->rif }}</small>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class="fw-bold">
                                                                            {{ $factura->moneda === 'USD' ? '$' : ($factura->moneda === 'Bs' ? 'Bs. ' : 'RUB ') }}
                                                                            {{ number_format($factura->monto, 2) }}
                                                                        </span>
                                                                        @if($factura->monto_usd && $factura->moneda !== 'USD')
                                                                            <br>
                                                                            <small class="text-muted">($ {{ number_format($factura->monto_usd, 2) }})</small>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-{{ $factura->moneda === 'USD' ? 'success' : ($factura->moneda === 'Bs' ? 'warning' : 'info') }}">
                                                                            {{ $factura->moneda }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex flex-column">
                                                                            <small>{{ $factura->fecha_factura->format('d/m/Y') }}</small>
                                                                            @if($factura->fecha_pago)
                                                                                <small class="text-muted">Pago: {{ $factura->fecha_pago->format('d/m/Y') }}</small>
                                                                            @endif
                                                                            @if($factura->fecha_factura->diffInDays(now()) > 30 && $factura->estado_pago != 'pagado')
                                                                                <small class="text-danger">
                                                                                    <i class="fas fa-exclamation-circle"></i>
                                                                                    {{ $factura->fecha_factura->diffInDays(now()) }} días
                                                                                </small>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-{{ $factura->estado_pago_color }} px-3 py-2">
                                                                            <i class="fas fa-{{ $factura->estado_pago === 'pagado' ? 'check-circle' : ($factura->estado_pago === 'vencido' ? 'exclamation-circle' : 'clock') }} me-1"></i>
                                                                            {{ $factura->estado_pago_label }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        @if($factura->archivos->count() > 0)
                                                                            <span class="badge bg-info d-inline-flex align-items-center gap-1">
                                                                                <i class="fas fa-paperclip"></i>
                                                                                {{ $factura->archivos->count() }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-muted">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm" role="group">
                                                                            <a href="{{ route('facturas.show', $factura->id) }}" 
                                                                               class="btn btn-outline-info" 
                                                                               title="Ver detalle"
                                                                               data-bs-toggle="tooltip">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="{{ route('facturas.edit', $factura->id) }}" 
                                                                               class="btn btn-outline-primary" 
                                                                               title="Editar"
                                                                               data-bs-toggle="tooltip">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <button class="btn btn-outline-danger" 
                                                                                    onclick="deleteFactura({{ $factura->id }})" 
                                                                                    title="Eliminar"
                                                                                    data-bs-toggle="tooltip">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                                                    <div>
                                                        <small class="text-muted">
                                                            Mostrando {{ $facturas->firstItem() ?? 0 }} - {{ $facturas->lastItem() ?? 0 }} de {{ $facturas->total() }} facturas
                                                        </small>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Última actualización: {{ now()->format('H:i:s') }}
                                                        </small>
                                                    </div>
                                                    <div>
                                                        {{ $facturas->withQueryString()->links() }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Vista Grid (oculta por defecto) -->
                                        <div id="vistaGrid" style="display: none;">
                                            <div class="row g-3" id="gridContainer">
                                                <!-- Las tarjetas se generarán desde JavaScript -->
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
    </div>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ============================================================
        // VARIABLES GLOBALES
        // ============================================================
        let chartInstance = null;
        let searchTimeout = null;
        let vistaActual = 'tabla';

        // ============================================================
        // INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar gráfica aplicando los filtros generales iniciales
            cargarGrafica();
            
            // Inicializar estado de filtros personalizados
            if (document.getElementById('rango_personalizado').checked) {
                toggleRangoPersonalizado();
            }
            
            // Inicializar tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Cerrar alertas automáticamente
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    const closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) closeBtn.click();
                }, 5000);
            });
            
            // Enviar formulario al cambiar filtros (solo para selects)
            document.querySelectorAll('#filtrosForm select').forEach(select => {
                select.addEventListener('change', function() {
                    if (document.getElementById('rango_personalizado').checked) {
                        return;
                    }
                    document.getElementById('filtrosForm').submit();
                });
            });

            // Animación de entrada para estadísticas
            document.querySelectorAll('.estadistica-card').forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });

        // ============================================================
        // TOGGLE RANGO PERSONALIZADO
        // ============================================================
        function toggleRangoPersonalizado() {
            const personalizado = document.getElementById('rango_personalizado');
            const filtros = document.getElementById('filtrosPersonalizados');
            const btnFiltrar = document.getElementById('btnFiltrarContainer');
            
            if (personalizado.checked) {
                filtros.style.display = 'flex';
                btnFiltrar.style.display = 'block';
                if (!document.querySelector('input[name="fecha_desde"]').value) {
                    const hoy = new Date();
                    const primerDia = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                    document.querySelector('input[name="fecha_desde"]').value = formatDate(primerDia);
                    document.querySelector('input[name="fecha_hasta"]').value = formatDate(hoy);
                }
            } else {
                filtros.style.display = 'none';
                btnFiltrar.style.display = 'none';
            }
        }

        function formatDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // ============================================================
        // LIMPIAR FILTROS
        // ============================================================
        function limpiarFiltros() {
            Swal.fire({
                title: '¿Limpiar filtros?',
                text: 'Se eliminarán todos los filtros aplicados',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('filtrosForm');
                    form.querySelectorAll('input, select').forEach(input => {
                        if (input.type === 'radio') {
                            if (input.value === 'mes') input.checked = true;
                        } else {
                            input.value = '';
                        }
                    });
                    document.getElementById('filtrosPersonalizados').style.display = 'none';
                    document.getElementById('btnFiltrarContainer').style.display = 'none';
                    form.submit();
                }
            });
        }

        // ============================================================
        // GUARDAR FILTROS
        // ============================================================
        function guardarFiltros() {
            const form = document.getElementById('filtrosForm');
            const formData = new FormData(form);
            const filtros = {};
            for (let [key, value] of formData.entries()) {
                filtros[key] = value;
            }
            localStorage.setItem('filtrosGuardados', JSON.stringify(filtros));
            Swal.fire({
                icon: 'success',
                title: 'Filtros guardados',
                text: 'Los filtros han sido guardados correctamente',
                timer: 2000,
                showConfirmButton: false
            });
        }

        // ============================================================
        // CARGAR FILTROS GUARDADOS
        // ============================================================
        function cargarFiltrosGuardados() {
            const filtrosGuardados = localStorage.getItem('filtrosGuardados');
            if (!filtrosGuardados) {
                Swal.fire({
                    icon: 'info',
                    title: 'No hay filtros guardados',
                    text: 'Guarda primero algunos filtros',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            
            const filtros = JSON.parse(filtrosGuardados);
            const form = document.getElementById('filtrosForm');
            
            Object.keys(filtros).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'radio') {
                        if (input.value === filtros[key]) {
                            input.checked = true;
                        }
                    } else {
                        input.value = filtros[key];
                    }
                }
            });
            
            if (filtros.rango === 'personalizado') {
                document.getElementById('rango_personalizado').checked = true;
                toggleRangoPersonalizado();
            }
            
            form.submit();
        }

        // ============================================================
        // CARGAR GRÁFICA USANDO ÚNICAMENTE LOS FILTROS GENERALES
        // ============================================================
        function cargarGrafica() {
            const form = document.getElementById('filtrosForm');
            if (!form) return;

            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = `/facturas/grafica-data?${params.toString()}`;
            
            const chartCanvas = document.getElementById('facturasChart');
            if (chartCanvas) chartCanvas.style.opacity = '0.5';
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (chartCanvas) chartCanvas.style.opacity = '1';
                
                if (data.success) {
                    if (chartInstance) {
                        chartInstance.destroy();
                    }

                    const ctx = document.getElementById('facturasChart').getContext('2d');
                    const colors = [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(118, 75, 162, 0.8)',
                        'rgba(240, 147, 251, 0.8)',
                        'rgba(79, 172, 254, 0.8)',
                        'rgba(67, 233, 123, 0.8)'
                    ];
                    
                    const datasets = data.datasets.map((dataset, index) => ({
                        ...dataset,
                        borderColor: dataset.borderColor || colors[index % colors.length],
                        backgroundColor: dataset.backgroundColor || colors[index % colors.length].replace('0.8', '0.1'),
                        pointBackgroundColor: dataset.borderColor || colors[index % colors.length],
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        borderWidth: 2,
                        fill: index === 0
                    }));

                    chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: { size: 11 }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    padding: 12,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (context.parsed.y !== null) {
                                                label += '$ ' + context.parsed.y.toFixed(2);
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0,0,0,0.05)' },
                                    ticks: {
                                        callback: function(value) {
                                            return '$ ' + value.toFixed(2);
                                        }
                                    }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            }
                        }
                    });

                    document.getElementById('graficaTotal').textContent = '$ ' + (data.estadisticas.total_general || 0).toFixed(2);
                    document.getElementById('graficaPromedio').textContent = '$ ' + (data.estadisticas.promedio_general || 0).toFixed(2);
                    document.getElementById('graficaProyeccion').textContent = '$ ' + (data.estadisticas.proyeccion_final || 0).toFixed(2);
                    document.getElementById('graficaMeses').textContent = data.estadisticas.total_mes || 0;
                }
            })
            .catch(error => {
                if (chartCanvas) chartCanvas.style.opacity = '1';
                console.error('Error al cargar la gráfica:', error);
            });
        }

        // ============================================================
        // DESCARGAR GRÁFICA
        // ============================================================
        function descargarGrafica() {
            if (!chartInstance) {
                Swal.fire({
                    icon: 'info',
                    title: 'No hay gráfica',
                    text: 'Primero carga una gráfica',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }
            
            const link = document.createElement('a');
            link.download = 'grafica_facturas.png';
            link.href = chartInstance.toBase64Image();
            link.click();
        }

        // ============================================================
        // TOGGLE VISTA (Tabla / Grid)
        // ============================================================
        function toggleVista() {
            const tabla = document.getElementById('vistaTabla');
            const grid = document.getElementById('vistaGrid');
            const texto = document.getElementById('vistaTexto');
            
            if (vistaActual === 'tabla') {
                tabla.style.display = 'none';
                grid.style.display = 'block';
                vistaActual = 'grid';
                texto.textContent = 'Vista Tabla';
                generarGrid();
            } else {
                tabla.style.display = 'block';
                grid.style.display = 'none';
                vistaActual = 'tabla';
                texto.textContent = 'Vista Grid';
            }
        }

        // ============================================================
        // GENERAR VISTA GRID
        // ============================================================
        function generarGrid() {
            const rows = document.querySelectorAll('#facturasTableBody tr');
            const container = document.getElementById('gridContainer');
            container.innerHTML = '';
            
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                
                const facturaId = row.dataset.facturaId;
                const numeroFactura = row.querySelector('td:nth-child(2) .fw-bold')?.textContent || 'N/A';
                const proveedor = row.querySelector('td:nth-child(3) .fw-semibold')?.textContent || 'N/A';
                const monto = row.querySelector('td:nth-child(4) .fw-bold')?.textContent || '0';
                const moneda = row.querySelector('td:nth-child(5) .badge')?.textContent || 'N/A';
                const fecha = row.querySelector('td:nth-child(6) small')?.textContent || 'N/A';
                const estado = row.querySelector('td:nth-child(7) .badge')?.textContent || 'N/A';
                const estadoColor = row.querySelector('td:nth-child(7) .badge')?.className.split(' ').find(c => c.startsWith('bg-'))?.replace('bg-', '') || 'secondary';
                const archivos = row.querySelector('td:nth-child(8) .badge')?.textContent || '0';
                
                const card = document.createElement('div');
                card.className = 'col-md-4 col-lg-3';
                card.innerHTML = `
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-${estadoColor}">${estado}</span>
                                <span class="badge bg-secondary">${moneda}</span>
                            </div>
                            <h6 class="fw-bold mb-1">${numeroFactura}</h6>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-building me-1"></i>
                                ${proveedor}
                            </p>
                            <p class="fw-bold fs-5 mb-2">${monto}</p>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-calendar-alt me-1"></i>
                                ${fecha}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info">
                                    <i class="fas fa-paperclip me-1"></i>
                                    ${archivos} archivos
                                </span>
                                <div class="btn-group btn-group-sm">
                                    <a href="/facturas/${facturaId}" class="btn btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" onclick="deleteFactura(${facturaId})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // ============================================================
        // ELIMINAR FACTURA (con SweetAlert2)
        // ============================================================
        function deleteFactura(id) {
            Swal.fire({
                title: '¿Eliminar factura?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/facturas/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminada',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            document.getElementById(`factura-row-${id}`).remove();
                            const total = document.querySelectorAll('#facturasTableBody tr').length;
                            document.getElementById('facturasCount').textContent = total;
                            document.getElementById('facturasTotal').textContent = total;
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al eliminar'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar la factura'
                        });
                        console.error('Error:', error);
                    });
                }
            });
        }

        // ============================================================
        // BUSCAR FACTURAS (búsqueda en cliente)
        // ============================================================
        function searchFacturas(query) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const rows = document.querySelectorAll('#facturasTableBody tr');
                const term = query.toLowerCase().trim();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const show = term.length < 2 || text.includes(term);
                    row.style.display = show ? '' : 'none';
                });
                
                if (vistaActual === 'grid') {
                    generarGrid();
                }
            }, 300);
        }

        // ============================================================
        // EXPORTAR DATOS
        // ============================================================
        function exportarDatos(format) {
            const url = new URL(window.location.href);
            url.searchParams.set('export', format);
            
            if (format === 'print') {
                window.print();
                return;
            }
            
            window.location.href = url.toString();
        }

        // ============================================================
        // ESTILOS ADICIONALES
        // ============================================================
        const style = document.createElement('style');
        style.textContent = `
            .estadistica-card {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.5s ease;
            }
            .hover-card {
                transition: all 0.3s ease;
            }
            .hover-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
            }
            .btn-check:checked + .btn-outline-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-color: #764ba2;
            }
            #filtrosPersonalizados {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                align-items: flex-end;
                width: 100%;
            }
            #filtrosPersonalizados .col-md-3 {
                flex: 1;
                min-width: 180px;
            }
            @media print {
                .no-print { display: none !important; }
                .card { box-shadow: none !important; border: 1px solid #ddd !important; }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-layout>