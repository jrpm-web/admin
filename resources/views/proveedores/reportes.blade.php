<x-layout>
    <x-slot:title>VRZ-PROVEEDORES | Reportes</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                {{-- ============ FILTROS ============ --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('proveedores.reportes') }}" class="row g-3 align-items-end">

                            <div class="col-md-3">
                                <label for="proveedor_id" class="form-label fw-bold text-muted small">Filtrar por Proveedor</label>
                                <select name="proveedor_id" id="proveedor_id" class="form-select rounded-3">
                                    <option value="">-- Todos los proveedores --</option>
                                    @forelse($listaProveedores ?? [] as $prov)
                                        <option value="{{ $prov->id }}" {{ request('proveedor_id') == $prov->id ? 'selected' : '' }}>
                                            {{ $prov->proveedor }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="ciudad" class="form-label fw-bold text-muted small">Ciudad</label>
                                <select name="ciudad" id="ciudad" class="form-select rounded-3">
                                    <option value="">-- Todas --</option>
                                    @forelse($ciudades ?? [] as $ciu)
                                        <option value="{{ $ciu }}" {{ request('ciudad') == $ciu ? 'selected' : '' }}>
                                            {{ $ciu }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="anio" class="form-label fw-bold text-muted small">Año</label>
                                <select name="anio" id="anio" class="form-select rounded-3">
                                    @php $anioSel = request('anio', now()->year); @endphp
                                    @foreach(range(now()->year - 3, now()->year + 1) as $y)
                                        <option value="{{ $y }}" {{ $anioSel == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="devaluacion" class="form-label fw-bold text-muted small">
                                    Devaluación $ (% anual)
                                </label>
                                <input type="number" name="devaluacion" id="devaluacion"
                                       class="form-control rounded-3"
                                       min="0" max="500" step="0.1"
                                       value="{{ $devaluacion ?? 30 }}"
                                       placeholder="Ej: 30">
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary rounded-3 flex-fill">
                                    <i class="fas fa-filter me-1"></i> Filtrar
                                </button>

                                <button type="button" id="btnExportPdf" class="btn btn-danger rounded-3"
                                        title="Exportar a PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </button>

                                @if(request('proveedor_id') || request('ciudad') || request('q') || request('desde') || request('hasta') || request('anio') || request('devaluacion'))
                                    <a href="{{ route('proveedores.reportes') }}" class="btn btn-outline-secondary rounded-3">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ============ TARJETAS KPI ============ --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="stat-icon bg-gradient-primary text-white">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Total Facturas</div>
                                    <div class="fs-4 fw-bold">{{ number_format($stats['total_facturas'] ?? 0) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <div class="stat-icon bg-gradient-success text-white">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="text-muted small">Monto Total</div>
                                </div>
                                <div class="fw-bold" style="font-size:1.05rem;">
                                    ${{ number_format($stats['monto_total_usd'] ?? 0, 2, ',', '.') }}
                                </div>
                                <div class="fw-bold text-success" style="font-size:1.05rem;">
                                    Bs. {{ number_format($stats['monto_total_bs'] ?? 0, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="stat-icon bg-gradient-warning text-white">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Pendientes</div>
                                    <div class="fs-4 fw-bold">{{ $stats['pendientes'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card stat-card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="stat-icon bg-gradient-danger text-white">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <div class="text-muted small">Vencidas</div>
                                    <div class="fs-4 fw-bold">{{ $stats['vencidas'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ GRÁFICAS PRINCIPALES ============ --}}
                <div class="row g-3 mb-4">
                    <div class="col-lg-7">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-chart-line text-primary me-2"></i>
                                    Top proveedores por monto facturado
                                </h6>
                                <div class="d-flex gap-2 small">
                                    <span class="badge bg-primary-subtle text-primary">$ USD</span>
                                    <span class="badge bg-success-subtle text-success">Bs.</span>
                                </div>
                            </div>
                            <div class="card-body" style="min-height: 340px;">
                                <canvas id="chartProveedores"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-chart-area text-success me-2"></i>
                                    Evolución mensual
                                </h6>
                                <div class="d-flex gap-2 small">
                                    <span class="badge bg-primary-subtle text-primary">$ USD</span>
                                    <span class="badge bg-success-subtle text-success">Bs.</span>
                                </div>
                            </div>
                            <div class="card-body" style="min-height: 340px;">
                                <canvas id="chartMeses"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ HISTÓRICO POR SERVICIO ============ --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-chart-line text-warning me-2"></i>
                                    Histórico de pagos por servicio / producto ({{ $anioActual }})
                                </h6>
                                <div class="d-flex gap-2 small">
                                    <span class="badge bg-primary-subtle text-primary">$ USD (sólido)</span>
                                    <span class="badge bg-success-subtle text-success">Bs. (punteado)</span>
                                </div>
                            </div>
                            <div class="card-body" style="min-height: 420px;">
                                <canvas id="chartServiciosHistorico"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ INCREMENTO + PROYECCIÓN ANUAL ============ --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-percentage text-info me-2"></i>
                                    Incremento mes a mes y proyección anual ({{ $chartIncremento['anio'] }})
                                </h6>
                                <div class="d-flex gap-2 small flex-wrap">
                                    <span class="badge bg-info-subtle text-info">
                                        Prom. incremento: {{ $chartIncremento['incremento_prom_usd'] ?? 0 }}%
                                    </span>
                                    <span class="badge bg-primary-subtle text-primary">
                                        Monto $ (eje izq.)
                                    </span>
                                    <span class="badge bg-success-subtle text-success">
                                        Incremento % (eje der.)
                                    </span>
                                </div>
                            </div>
                            <div class="card-body" style="min-height: 420px;">
                                <canvas id="chartIncremento"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ INCREMENTO + PROYECCIÓN POR SERVICIO ============ --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-chart-bar text-info me-2"></i>
                                    Incremento mes a mes por servicio facturado ({{ $chartIncrementoServicios['anio'] }})
                                </h6>
                                <div class="d-flex gap-2 small flex-wrap">
                                    <span class="badge bg-primary-subtle text-primary">
                                        Monto $ (eje izq.)
                                    </span>
                                    <span class="badge bg-success-subtle text-success">
                                        Incremento % (eje der.)
                                    </span>
                                    <span class="badge bg-info-subtle text-info">
                                        <i class="fas fa-magic me-1"></i> Proyectado
                                    </span>
                                </div>
                            </div>
                            <div class="card-body" style="min-height: 420px;">
                                <canvas id="chartIncrementoServicios"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ TABLA DETALLE POR SERVICIO ============ --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-table text-info me-2"></i>
                            Detalle por servicio — {{ $chartIncrementoServicios['anio'] }}
                        </h6>
                        <div class="d-flex gap-2 small flex-wrap">
                            <span class="badge bg-info-subtle text-info">
                                <i class="fas fa-magic me-1"></i> Celdas azules = proyectado
                            </span>
                            <span class="badge bg-primary-subtle text-primary">
                                Δ% = variación vs mes anterior
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px; overflow: auto;">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 5;">
                                    <tr>
                                        <th style="position: sticky; left: 0; background: #f8f9fa; z-index: 6; min-width: 200px;">Servicio</th>
                                        @foreach($chartIncrementoServicios['labels'] as $mes)
                                            <th class="text-end" style="min-width: 90px;">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->translatedFormat('M') }}
                                            </th>
                                        @endforeach
                                        <th class="text-end bg-primary-subtle" style="min-width: 110px;">Total</th>
                                        <th class="text-end bg-info-subtle" style="min-width: 80px;">Prom Δ%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($chartIncrementoServicios['series'] as $serv)
                                        <tr>
                                            <td style="position: sticky; left: 0; background: #fff; z-index: 4;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width:24px;height:24px;font-size:11px;font-weight:600;flex-shrink:0;">
                                                        {{ strtoupper(substr($serv['nombre'], 0, 1)) }}
                                                    </div>
                                                    <span class="fw-semibold">{{ $serv['nombre'] }}</span>
                                                </div>
                                            </td>
                                            @foreach($serv['valores'] as $idx => $val)
                                                @php $esProy = $serv['es_proyectado'][$idx] ?? false; @endphp
                                                <td class="text-end {{ $esProy ? 'text-primary fst-italic proy-cell' : '' }}">
                                                    @if($val > 0)
                                                        @if($esProy)
                                                            <i class="fas fa-magic me-1" style="font-size:0.7em;"
                                                               title="Monto proyectado en base a los 2 meses anteriores"></i>
                                                        @endif
                                                        ${{ number_format($val, 2, '.', ',') }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-end fw-bold bg-primary-subtle">
                                                ${{ number_format($serv['total'], 2, '.', ',') }}
                                            </td>
                                            <td class="text-end bg-info-subtle">
                                                {{ $serv['prom_inc'] }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center text-muted py-4">
                                                No hay servicios facturados en {{ $chartIncrementoServicios['anio'] }}.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ============ TABLA PROVEEDORES × MESES ============ --}}
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-table text-primary me-2"></i>
                            Facturación por proveedor y mes ({{ $anioActual }}) — USD
                        </h6>
                        <div class="d-flex gap-2 small flex-wrap">
                            <span class="badge bg-primary-subtle text-primary">
                                Δ% = variación vs mes anterior
                            </span>
                            <span class="badge bg-info-subtle text-info">
                                Prom. incremento global: {{ $tablaProveedores['prom_inc_global'] ?? 0 }}%
                            </span>
                        </div>
                    </div>

                    {{-- LEYENDA --}}
                    <div class="card-body border-bottom bg-light-subtle py-2">
                        <div class="d-flex gap-3 small flex-wrap align-items-center">
                            <span class="text-muted fw-bold small">
                                <i class="fas fa-info-circle me-1"></i> Leyenda:
                            </span>
                            <span class="d-flex align-items-center gap-2">
                                <span class="legend-box legend-real"></span>
                                <span class="text-dark">Monto real facturado</span>
                            </span>
                            <span class="d-flex align-items-center gap-2">
                                <span class="legend-box legend-proyectado"></span>
                                <span class="text-primary">
                                    <i class="fas fa-magic me-1"></i>
                                    Monto proyectado (basado en los 2 meses anteriores)
                                </span>
                            </span>
                            <span class="d-flex align-items-center gap-2">
                                <span class="text-success fw-bold">▲</span>
                                <span class="text-muted">Incremento</span>
                            </span>
                            <span class="d-flex align-items-center gap-2">
                                <span class="text-danger fw-bold">▼</span>
                                <span class="text-muted">Decremento</span>
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 620px; overflow: auto;">
                            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.82rem;">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 5;">
                                    <tr>
                                        <th style="position: sticky; left: 0; background: #f8f9fa; z-index: 6; min-width: 200px;">
                                            Proveedor
                                        </th>
                                        @foreach($tablaProveedores['meses'] as $idx => $mes)
                                            <th class="text-end" style="min-width: 95px;">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m', $mes)->translatedFormat('M') }}
                                            </th>
                                            <th class="text-end text-muted" style="min-width: 65px; font-weight: 400;">
                                                Δ%
                                            </th>
                                        @endforeach
                                        <th class="text-end bg-primary-subtle" style="min-width: 110px;">Total</th>
                                        <th class="text-end bg-info-subtle" style="min-width: 80px;">Prom Δ%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tablaProveedores['proveedores'] as $fila)
                                        <tr>
                                            <td style="position: sticky; left: 0; background: #fff; z-index: 4;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width:24px;height:24px;font-size:11px;font-weight:600;flex-shrink:0;">
                                                        {{ strtoupper(substr($fila['nombre'], 0, 1)) }}
                                                    </div>
                                                    <span class="fw-semibold">{{ $fila['nombre'] }}</span>
                                                </div>
                                            </td>

                                            @foreach($fila['valores'] as $idx => $valor)
                                                @php $esProy = $fila['es_proyectado'][$idx] ?? false; @endphp

                                                <td class="text-end {{ $esProy ? 'text-primary fst-italic proy-cell' : '' }}">
                                                    @if($valor > 0)
                                                        @if($esProy)
                                                            <i class="fas fa-magic me-1" style="font-size:0.75em;"
                                                               title="Monto proyectado en base a los 2 meses anteriores"></i>
                                                        @endif
                                                        ${{ number_format($valor, 2, '.', ',') }}
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>

                                                <td class="text-end">
                                                    @php $inc = $fila['incrementos'][$idx] ?? null; @endphp
                                                    @if($inc === null)
                                                        <span class="text-muted">—</span>
                                                    @elseif($inc > 0)
                                                        <span class="{{ $esProy ? 'text-primary' : 'text-success' }}">
                                                            ▲ {{ number_format($inc, 1) }}%
                                                        </span>
                                                    @elseif($inc < 0)
                                                        <span class="{{ $esProy ? 'text-primary' : 'text-danger' }}">
                                                            ▼ {{ number_format(abs($inc), 1) }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">0%</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td class="text-end fw-bold bg-primary-subtle">
                                                ${{ number_format($fila['total'], 2, '.', ',') }}
                                            </td>
                                            <td class="text-end bg-info-subtle">
                                                {{ $fila['prom_inc'] }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="27" class="text-center text-muted py-4">
                                                No hay datos para mostrar en el año {{ $anioActual }}.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                @if(count($tablaProveedores['proveedores']) > 0)
                                    <tfoot class="table-secondary" style="position: sticky; bottom: 0; z-index: 4;">
                                        <tr>
                                            <th style="position: sticky; left: 0; background: #e2e3e5; z-index: 5;">
                                                TOTAL GENERAL
                                            </th>
                                            @foreach($tablaProveedores['totales_mes'] as $idx => $valor)
                                                @php $algunProy = $tablaProveedores['totales_mes_proyectado'][$idx] ?? false; @endphp
                                                <th class="text-end {{ $algunProy ? 'text-primary' : '' }}">
                                                    @if($valor > 0)
                                                        @if($algunProy)
                                                            <i class="fas fa-magic me-1" style="font-size:0.75em;"></i>
                                                        @endif
                                                        ${{ number_format($valor, 2, '.', ',') }}
                                                    @else
                                                        —
                                                    @endif
                                                </th>
                                                <th class="text-end">
                                                    @php $inc = $tablaProveedores['totales_inc'][$idx] ?? null; @endphp
                                                    @if($inc === null)
                                                        —
                                                    @elseif($inc > 0)
                                                        <span class="{{ $algunProy ? 'text-primary' : 'text-success' }}">
                                                            ▲ {{ number_format($inc, 1) }}%
                                                        </span>
                                                    @elseif($inc < 0)
                                                        <span class="{{ $algunProy ? 'text-primary' : 'text-danger' }}">
                                                            ▼ {{ number_format(abs($inc), 1) }}%
                                                        </span>
                                                    @else
                                                        0%
                                                    @endif
                                                </th>
                                            @endforeach
                                            <th class="text-end bg-primary text-white">
                                                ${{ number_format($tablaProveedores['gran_total'], 2, '.', ',') }}
                                            </th>
                                            <th class="text-end bg-info text-white">
                                                {{ $tablaProveedores['prom_inc_global'] }}%
                                            </th>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                {{-- ============ PROYECCIONES NOV/DIC + 2027 ============ --}}
                <div class="row g-3 mb-4">
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-calendar-check text-warning me-2"></i>
                                    Proyección meses restantes {{ $anioActual }}
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(($proyeccionRestante['aplica'] ?? false) && count($proyeccionRestante['proyecciones'] ?? []) > 0)
                                    <div class="alert alert-info border-0 py-2 px-3 small mb-3">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Proyectando con incremento promedio del
                                        <strong>{{ $proyeccionRestante['prom_inc'] }}%</strong> mensual.
                                    </div>

                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Mes</th>
                                                <th class="text-end">Monto proyectado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($proyeccionRestante['proyecciones'] as $proy)
                                                <tr>
                                                    <td class="fw-semibold">{{ $proy['nombre'] }}</td>
                                                    <td class="text-end text-primary fst-italic">
                                                        <i class="fas fa-magic me-1" style="font-size:0.75em;"></i>
                                                        ${{ number_format($proy['monto'], 2, '.', ',') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-secondary">
                                            <tr>
                                                <th>Total restante</th>
                                                <th class="text-end text-primary">
                                                    ${{ number_format($proyeccionRestante['total'], 2, '.', ',') }}
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                @else
                                    <div class="alert alert-secondary border-0 mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ $proyeccionRestante['nota'] ?? 'No hay meses por proyectar.' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 rounded-4 h-100">
                            <div class="card-header bg-white border-0">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-chart-line text-danger me-2"></i>
                                    Presupuesto 2027 (con devaluación del $)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning border-0 py-2 px-3 small mb-3">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Tasa de devaluación aplicada:
                                    <strong>{{ $proyeccion2027['tasa_devaluacion'] }}%</strong> anual
                                    (factor ×{{ $proyeccion2027['factor_aplicado'] }})
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="border rounded-3 p-2 bg-light">
                                            <div class="text-muted small">Promedio mensual (base)</div>
                                            <div class="fw-bold">${{ number_format($proyeccion2027['promedio_mensual'], 2, '.', ',') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded-3 p-2 bg-light">
                                            <div class="text-muted small">Base anual {{ $anioActual }}</div>
                                            <div class="fw-bold">${{ number_format($proyeccion2027['base_anual_actual'], 2, '.', ',') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="border rounded-3 p-3 bg-danger-subtle">
                                            <div class="text-muted small">Presupuesto 2027 estimado</div>
                                            <div class="fw-bold fs-4 text-danger">
                                                ${{ number_format($proyeccion2027['proyeccion_2027'], 2, '.', ',') }}
                                            </div>
                                            <div class="text-muted small">
                                                ≈ ${{ number_format($proyeccion2027['promedio_2027'], 2, '.', ',') }} / mes
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Paginación --}}
                @if(isset($proveedores) && !$proveedores->isEmpty())
                    <div class="mt-4">
                        {{ $proveedores->withQueryString()->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ============ CHART.JS ============ --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        const dataProveedores       = @json($chartProveedores);
        const dataMeses             = @json($chartMeses);
        const dataServicios         = @json($chartServiciosHistorico);
        const dataIncremento        = @json($chartIncremento);
        const dataIncServicios      = @json($chartIncrementoServicios);

        const isDark    = document.body.classList.contains('dark-mode');
        const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
        const textColor = isDark ? '#e2e8f0' : '#495057';

        // ============ GRÁFICA 1: PROVEEDORES ============
        const ctxProv = document.getElementById('chartProveedores');
        if (ctxProv) {
            new Chart(ctxProv, {
                type: 'line',
                data: {
                    labels: dataProveedores.labels || [],
                    datasets: [
                        {
                            label: 'USD',
                            data: dataProveedores.totales_usd || [],
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.12)',
                            borderWidth: 3, tension: 0.35, fill: true,
                            pointBackgroundColor: '#667eea', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Bs.',
                            data: dataProveedores.totales_bs || [],
                            borderColor: '#11998e',
                            backgroundColor: 'rgba(17, 153, 142, 0.10)',
                            borderWidth: 3, tension: 0.35, fill: false,
                            borderDash: [6, 4],
                            pointBackgroundColor: '#11998e', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: true, labels: { color: textColor, usePointStyle: true } },
                        tooltip: { callbacks: {
                            label: (ctx) => {
                                const prefix = ctx.dataset.yAxisID === 'y1' ? ' Bs. ' : ' $ ';
                                const val = Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                return `${ctx.dataset.label}: ${prefix}${val}`;
                            },
                            afterBody: (items) => {
                                const cant = dataProveedores.cantidad?.[items[0].dataIndex] ?? 0;
                                return `Facturas: ${cant}`;
                            }
                        }}
                    },
                    scales: {
                        y: { beginAtZero: true, position: 'left', grid: { color: gridColor },
                            title: { display: true, text: 'USD ($)', color: '#667eea' },
                            ticks: { color: '#667eea', callback: (v) => '$' + Number(v).toLocaleString() } },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Bs.', color: '#11998e' },
                            ticks: { color: '#11998e', callback: (v) => 'Bs. ' + Number(v).toLocaleString() } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });
        }

        // ============ GRÁFICA 2: MESES ============
        const ctxMes = document.getElementById('chartMeses');
        if (ctxMes) {
            new Chart(ctxMes, {
                type: 'line',
                data: {
                    labels: dataMeses.labels || [],
                    datasets: [
                        {
                            label: 'USD',
                            data: dataMeses.totales_usd || [],
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.12)',
                            borderWidth: 3, tension: 0.35, fill: true,
                            pointBackgroundColor: '#667eea', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Bs.',
                            data: dataMeses.totales_bs || [],
                            borderColor: '#11998e',
                            backgroundColor: 'rgba(17, 153, 142, 0.10)',
                            borderWidth: 3, tension: 0.35, fill: false,
                            borderDash: [6, 4],
                            pointBackgroundColor: '#11998e', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: true, labels: { color: textColor, usePointStyle: true } },
                        tooltip: { callbacks: {
                            label: (ctx) => {
                                const prefix = ctx.dataset.yAxisID === 'y1' ? ' Bs. ' : ' $ ';
                                const val = Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                return `${ctx.dataset.label}: ${prefix}${val}`;
                            },
                            afterBody: (items) => {
                                const cant = dataMeses.cantidad?.[items[0].dataIndex] ?? 0;
                                return `Facturas: ${cant}`;
                            }
                        }}
                    },
                    scales: {
                        y: { beginAtZero: true, position: 'left', grid: { color: gridColor },
                            title: { display: true, text: 'USD ($)', color: '#667eea' },
                            ticks: { color: '#667eea', callback: (v) => '$' + Number(v).toLocaleString() } },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Bs.', color: '#11998e' },
                            ticks: { color: '#11998e', callback: (v) => 'Bs. ' + Number(v).toLocaleString() } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });
        }

        // ============ GRÁFICA 3: HISTÓRICO DE SERVICIOS ============
        const ctxServHist = document.getElementById('chartServiciosHistorico');
        if (ctxServHist) {
            new Chart(ctxServHist, {
                type: 'line',
                data: {
                    labels: dataServicios.labels || [],
                    datasets: dataServicios.datasets || []
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            display: true, position: 'bottom',
                            labels: { color: textColor, usePointStyle: true, boxWidth: 10, padding: 12, font: { size: 11 } }
                        },
                        tooltip: { callbacks: {
                            label: (ctx) => {
                                const isBs = ctx.dataset.yAxisID === 'y1';
                                const prefix = isBs ? ' Bs. ' : ' $ ';
                                const val = Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                return `${ctx.dataset.label}: ${prefix}${val}`;
                            }
                        }}
                    },
                    scales: {
                        y: { beginAtZero: true, position: 'left', grid: { color: gridColor },
                            title: { display: true, text: 'USD ($)', color: '#667eea' },
                            ticks: { color: '#667eea', callback: (v) => '$' + Number(v).toLocaleString() } },
                        y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Bs.', color: '#11998e' },
                            ticks: { color: '#11998e', callback: (v) => 'Bs. ' + Number(v).toLocaleString() } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });
        }

        // ============ GRÁFICA 4: INCREMENTO + PROYECCIÓN ============
        const ctxInc = document.getElementById('chartIncremento');
        if (ctxInc) {
            const labels        = dataIncremento.labels || [];
            const incUsd        = dataIncremento.incrementos_usd || [];
            const valUsd        = dataIncremento.valores_usd || [];
            const esProy        = dataIncremento.es_proyectado || [];
            const ultimoRealIdx = dataIncremento.ultimo_mes_real_idx ?? -1;

            const incUsdReal = incUsd.map((v, i) => esProy[i] ? null : v);
            const valUsdReal = valUsd.map((v, i) => esProy[i] ? null : v);
            const incUsdProy = incUsd.map((v, i) => (i >= ultimoRealIdx + 1) ? v : null);
            const valUsdProy = valUsd.map((v, i) => (i >= ultimoRealIdx) ? v : null);

            new Chart(ctxInc, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Monto $ (real)',
                            data: valUsdReal,
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.12)',
                            borderWidth: 3, tension: 0.35, fill: true,
                            pointBackgroundColor: '#667eea', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Monto $ (proyectado)',
                            data: valUsdProy,
                            borderColor: '#667eea',
                            borderDash: [8, 4], borderWidth: 3, tension: 0.35, fill: false,
                            pointStyle: 'rectRot',
                            pointBackgroundColor: '#667eea', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 6, pointHoverRadius: 8,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Incremento % (real)',
                            data: incUsdReal,
                            borderColor: '#11998e',
                            backgroundColor: 'rgba(17, 153, 142, 0.10)',
                            borderWidth: 2.5, tension: 0.35, fill: false,
                            pointBackgroundColor: '#11998e', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
                            yAxisID: 'y1',
                        },
                        {
                            label: 'Incremento % (proyectado)',
                            data: incUsdProy,
                            borderColor: '#11998e',
                            borderDash: [8, 4], borderWidth: 2.5, tension: 0.35, fill: false,
                            pointStyle: 'rectRot',
                            pointBackgroundColor: '#11998e', pointBorderColor: '#fff',
                            pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: true, position: 'bottom',
                            labels: { color: textColor, usePointStyle: true, padding: 12 } },
                        tooltip: { callbacks: {
                            label: (ctx) => {
                                if (ctx.parsed.y === null || ctx.parsed.y === undefined) return null;
                                const isMonto = ctx.dataset.yAxisID === 'y';
                                const esProy  = ctx.dataset.label.toLowerCase().includes('proyectado');
                                const marca   = esProy ? ' (proy.)' : '';
                                if (isMonto) {
                                    return `${ctx.dataset.label}: $${Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}${marca}`;
                                }
                                return `${ctx.dataset.label}: ${Number(ctx.parsed.y).toFixed(2)}%${marca}`;
                            }
                        }}
                    },
                    scales: {
                        y: { beginAtZero: true, position: 'left', grid: { color: gridColor },
                            title: { display: true, text: 'Monto ($)', color: '#667eea' },
                            ticks: { color: '#667eea', callback: (v) => '$' + Number(v).toLocaleString() } },
                        y1: { position: 'right', grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Incremento (%)', color: '#11998e' },
                            ticks: { color: '#11998e', callback: (v) => v + '%' } },
                        x: { grid: { display: false }, ticks: { color: textColor } }
                    }
                }
            });
        }

        // ============ GRÁFICA 5: INCREMENTO + PROYECCIÓN POR SERVICIO ============
        const ctxIncServ = document.getElementById('chartIncrementoServicios');
        if (ctxIncServ && dataIncServicios) {
            const labels = dataIncServicios.labels || [];
            const series = dataIncServicios.series || [];

            const paleta = ['#667eea', '#11998e', '#f7971e', '#cb2d3e', '#38ef7d', '#2193b0', '#764ba2', '#ff5e62', '#00c6ff', '#f093fb'];

            const datasets = [];

            series.forEach((serv, i) => {
                const color = paleta[i % paleta.length];
                const ultimoReal = serv.ultimo_mes_real_idx ?? -1;

                const montoReal = serv.valores.map((v, idx) => serv.es_proyectado[idx] ? null : v);
                const montoProy = serv.valores.map((v, idx) => (idx >= ultimoReal + 1) ? v : null);

                const incReal = serv.incrementos.map((v, idx) => serv.es_proyectado[idx] ? null : v);
                const incProy = serv.incrementos.map((v, idx) => (idx >= ultimoReal + 1) ? v : null);

                // Monto real (sólido, eje y)
                datasets.push({
                    label: serv.nombre + ' ($)',
                    data: montoReal,
                    borderColor: color,
                    backgroundColor: color + '1a',
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: false,
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y',
                    order: 2,
                });

                // Monto proyectado (punteado, eje y)
                datasets.push({
                    label: serv.nombre + ' ($ proy.)',
                    data: montoProy,
                    borderColor: color,
                    borderDash: [8, 4],
                    borderWidth: 2.5,
                    tension: 0.35,
                    fill: false,
                    pointStyle: 'rectRot',
                    pointBackgroundColor: color,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    yAxisID: 'y',
                    order: 2,
                });

                // Incremento % real (oculto por defecto)
                if (incReal.some(v => v !== null)) {
                    datasets.push({
                        label: serv.nombre + ' (Δ%)',
                        data: incReal,
                        borderColor: color,
                        backgroundColor: color + '10',
                        borderWidth: 1.5,
                        borderDash: [3, 3],
                        tension: 0.35,
                        fill: false,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        yAxisID: 'y1',
                        order: 1,
                        hidden: true,
                    });
                }

                // Incremento % proyectado (oculto por defecto)
                if (incProy.some(v => v !== null)) {
                    datasets.push({
                        label: serv.nombre + ' (Δ% proy.)',
                        data: incProy,
                        borderColor: color,
                        borderDash: [3, 3],
                        borderWidth: 1.5,
                        tension: 0.35,
                        fill: false,
                        pointStyle: 'rectRot',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        yAxisID: 'y1',
                        order: 1,
                        hidden: true,
                    });
                }
            });

            new Chart(ctxIncServ, {
                type: 'line',
                data: { labels, datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: {
                                color: textColor,
                                usePointStyle: true,
                                boxWidth: 10,
                                padding: 10,
                                font: { size: 10 },
                                filter: (item) => !item.text.includes('Δ%'),
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    if (ctx.parsed.y === null || ctx.parsed.y === undefined) return null;
                                    const isPct = ctx.dataset.yAxisID === 'y1';
                                    if (isPct) {
                                        return `${ctx.dataset.label}: ${Number(ctx.parsed.y).toFixed(2)}%`;
                                    }
                                    return `${ctx.dataset.label}: $${Number(ctx.parsed.y).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            grid: { color: gridColor },
                            title: { display: true, text: 'Monto ($)', color: '#667eea' },
                            ticks: { color: '#667eea', callback: (v) => '$' + Number(v).toLocaleString() }
                        },
                        y1: {
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Incremento (%)', color: '#11998e' },
                            ticks: { color: '#11998e', callback: (v) => v + '%' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor }
                        }
                    }
                }
            });
        }

        // ============ EXPORTAR A PDF ============
        const btnPdf = document.getElementById('btnExportPdf');
        if (btnPdf) {
            btnPdf.addEventListener('click', async function () {
                const btn = this;
                const originalHTML = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                try {
                    const charts = {
                        chartProveedores:        'img_proveedores',
                        chartMeses:              'img_meses',
                        chartServiciosHistorico: 'img_servicios',
                        chartIncremento:         'img_incremento',
                        chartIncrementoServicios:'img_incremento_servicios',
                    };

                    const payload = {
                        _token:       '{{ csrf_token() }}',
                        anio:         '{{ $anioActual }}',
                        devaluacion:  '{{ $devaluacion }}',
                        proveedor_id: '{{ $proveedorId ?? "" }}',
                        ciudad:       '{{ request("ciudad") }}',
                        desde:        '{{ request("desde") }}',
                        hasta:        '{{ request("hasta") }}',
                        q:            '{{ request("q") }}',
                    };

                    for (const [id, key] of Object.entries(charts)) {
                        const canvas = document.getElementById(id);
                        if (!canvas) continue;
                        const chart = Chart.getChart(canvas);
                        if (chart) {
                            payload[key] = chart.toBase64Image('image/png', 1);
                        }
                    }

                    const response = await fetch('{{ route("proveedores.reportes.pdf") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/pdf',
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!response.ok) {
                        throw new Error('Error al generar el PDF: ' + response.status);
                    }

                    const blob = await response.blob();
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'reporte_proveedores_{{ $anioActual }}.pdf';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);

                } catch (e) {
                    console.error(e);
                    alert('Error al generar el PDF. Intenta de nuevo.\n\n' + e.message);
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }
            });
        }
    });
    </script>

    <style>
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .bg-gradient-warning { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); }
        .bg-gradient-danger  { background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%); }

        .rounded-4 { border-radius: 1rem !important; }

        .stat-card { transition: transform .2s ease, box-shadow .2s ease; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,.08) !important; }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .card { animation: fadeInUp .5s ease; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .table-responsive::-webkit-scrollbar { height: 10px; width: 10px; }
        .table-responsive::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
        .table-responsive::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .proy-cell { background: rgba(13, 110, 253, 0.06) !important; }

        .legend-box {
            display: inline-block;
            width: 16px; height: 16px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
        }
        .legend-real { background: #ffffff; }
        .legend-proyectado {
            background: rgba(13, 110, 253, 0.15);
            border-color: rgba(13, 110, 253, 0.4);
        }

        /* Dark mode */
        .dark-mode .card { background: #1a1a2e !important; color: #e2e8f0 !important; }
        .dark-mode .card-header { background: linear-gradient(135deg, #1e293b, #0f172a) !important; }
        .dark-mode .text-muted { color: #94a3b8 !important; }
        .dark-mode .text-dark { color: #e2e8f0 !important; }
        .dark-mode .form-control, .dark-mode .form-select {
            background: #2d2d44 !important; color: #e2e8f0 !important; border-color: #3d3d54 !important;
        }
        .dark-mode .bg-white { background: #1a1a2e !important; }
        .dark-mode .bg-light { background: #23233d !important; }
        .dark-mode .bg-light-subtle { background: #0f172a !important; }
        .dark-mode .bg-primary-subtle { background: rgba(102,126,234,.15) !important; }
        .dark-mode .bg-success-subtle { background: rgba(17,153,142,.15) !important; }
        .dark-mode .bg-warning-subtle { background: rgba(247,151,30,.15) !important; }
        .dark-mode .bg-info-subtle    { background: rgba(33,147,176,.15) !important; }
        .dark-mode .bg-danger-subtle  { background: rgba(203,45,62,.15) !important; }
        .dark-mode .table-light th { background: #2d2d44 !important; color: #e2e8f0 !important; }
        .dark-mode .table td { color: #e2e8f0 !important; border-color: #2d2d44 !important; }
        .dark-mode .table thead th[style*="background: #f8f9fa"] { background: #2d2d44 !important; }
        .dark-mode .table tbody td[style*="background: #fff"] { background: #1a1a2e !important; }
        .dark-mode .table tfoot th[style*="background: #e2e3e5"] { background: #2d2d44 !important; }
        .dark-mode .table-secondary { background: #23233d !important; }
        .dark-mode .table-secondary th { color: #e2e8f0 !important; }
        .dark-mode .proy-cell { background: rgba(13, 110, 253, 0.12) !important; }
        .dark-mode .legend-real { background: #1a1a2e; border-color: #3d3d54; }
        .dark-mode .legend-proyectada,
        .dark-mode .legend-proyectado { background: rgba(13, 110, 253, 0.25); border-color: rgba(13, 110, 253, 0.6); }
    </style>
</x-layout>