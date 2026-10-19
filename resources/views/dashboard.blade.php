<x-layout>
    <x-slot:title>VRZ-Inicio | Sistema de Gestión</x-slot:title>

    @php
        // ============================================================
        // VALORES POR DEFECTO PARA EL DASHBOARD
        // ============================================================
        $alertas = $alertas ?? collect([]);
        $totalAlertas = $totalAlertas ?? 0;
        $monitoreoProveedores = $monitoreoProveedores ?? collect([]);
        $ultimasFacturas = $ultimasFacturas ?? collect([]);
        $proximasFechas = $proximasFechas ?? collect([]);
        $totalFacturas = $totalFacturas ?? 0;
        $totalProveedores = $totalProveedores ?? 0;
        $pendientes = $pendientes ?? 0;
        $totalUSD = $totalUSD ?? 0;
        $proveedoresConMonitoreo = $proveedoresConMonitoreo ?? 0;
        $proveedoresCriticos = $proveedoresCriticos ?? 0;
        $datosGrafico = $datosGrafico ?? ['labels' => [], 'datasets' => []];
    @endphp

    <div class="container-fluid">
        <!-- ============================================================ -->
        <!-- BIENVENIDA Y ESTADO GENERAL -->
        <!-- ============================================================ -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            <i class="fas fa-check-circle me-2"></i>
                            ¡Bienvenido, {{ Auth::user()->name }}!
                        </h4>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Último acceso: {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('d/m/Y H:i') : 'Primera vez' }}
                            | <i class="fas fa-calendar-alt me-1"></i>
                            Hoy: {{ now()->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary fs-6 p-2">
                            <i class="fas fa-file-invoice me-1"></i>
                            Total Facturas: {{ $totalFacturas }}
                        </span>
                        <span class="badge bg-warning fs-6 p-2">
                            <i class="fas fa-clock me-1"></i>
                            Pendientes: {{ $pendientes }}
                        </span>
                        <span class="badge bg-danger fs-6 p-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Alertas: {{ $totalAlertas }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECCIÓN 1: ALERTAS Y RECORDATORIOS -->
        <!-- ============================================================ -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-bell text-warning me-2"></i>
                                Alertas y Recordatorios
                                @if($totalAlertas > 0)
                                    <span class="badge bg-danger ms-2">{{ $totalAlertas }}</span>
                                @endif
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="marcarTodasLeidas()">
                                    <i class="fas fa-check-double me-1"></i>
                                    Marcar todas como leídas
                                </button>
                                <button class="btn btn-outline-primary btn-sm" onclick="recargarAlertas()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($alertas->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th style="width: 60px;">Estado</th>
                                            <th>Proveedor</th>
                                            <th>Tipo</th>
                                            <th>Mensaje</th>
                                            <th>Fecha Límite</th>
                                            <th>Días</th>
                                            <th style="width: 100px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($alertas as $alerta)
                                            @php
                                                $diasRestantes = $alerta->fecha_limite ? now()->diffInDays($alerta->fecha_limite, false) : null;
                                                $color = $diasRestantes !== null && $diasRestantes < 0 ? 'danger' : ($diasRestantes <= 3 ? 'warning' : 'info');
                                                $icono = $diasRestantes !== null && $diasRestantes < 0 ? 'fa-exclamation-triangle' : ($diasRestantes <= 3 ? 'fa-clock' : 'fa-info-circle');
                                            @endphp
                                            <tr class="{{ $alerta->leida ? '' : 'fw-bold' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if(!$alerta->leida)
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-circle me-1"></i> Nueva
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-check-circle me-1"></i> Leída
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px; font-size: 12px; font-weight: 600;">
                                                            {{ strtoupper(substr($alerta->proveedor->proveedor ?? 'P', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $alerta->proveedor->proveedor ?? 'N/A' }}</div>
                                                            <small class="text-muted">{{ $alerta->proveedor->rif ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $alerta->tipo === 'vencimiento' ? 'danger' : ($alerta->tipo === 'recordatorio' ? 'warning' : 'info') }}">
                                                        <i class="fas fa-{{ $alerta->tipo === 'vencimiento' ? 'exclamation-triangle' : ($alerta->tipo === 'recordatorio' ? 'bell' : 'info-circle') }} me-1"></i>
                                                        {{ ucfirst($alerta->tipo) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>{{ $alerta->mensaje }}</div>
                                                    @if($alerta->observaciones)
                                                        <small class="text-muted">{{ $alerta->observaciones }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($alerta->fecha_limite)
                                                        <span class="text-{{ $color }}">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            {{ $alerta->fecha_limite->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($diasRestantes !== null)
                                                        <span class="badge bg-{{ $diasRestantes < 0 ? 'danger' : ($diasRestantes <= 3 ? 'warning' : 'success') }}">
                                                            <i class="fas {{ $icono }} me-1"></i>
                                                            {{ $diasRestantes < 0 ? 'Vencido hace ' . abs($diasRestantes) . ' días' : $diasRestantes . ' días' }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-success" onclick="marcarLeida({{ $alerta->id }})" title="Marcar como leída">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <a href="{{ route('facturas.create', ['proveedor_id' => $alerta->proveedor_id]) }}" 
                                                           class="btn btn-outline-primary" title="Registrar factura">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </a>
                                                        <button class="btn btn-outline-danger" onclick="eliminarAlerta({{ $alerta->id }})" title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($alertas->hasPages())
                                <div class="mt-3">
                                    {{ $alertas->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                                <h5 class="mt-3 text-muted">¡Todo en orden!</h5>
                                <p class="text-muted">No hay alertas pendientes en este momento.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECCIÓN 2: MONITOREO DE FACTURACIÓN POR PROVEEDOR -->
        <!-- ============================================================ -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-chart-simple text-primary me-2"></i>
                                Monitoreo de Facturación por Proveedor
                            </h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-secondary btn-sm" onclick="recargarMonitoreo()">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Actualizar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($monitoreoProveedores->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Proveedor</th>
                                            <th>Última Factura</th>
                                            <th>Días sin facturar</th>
                                            <th>Estado</th>
                                            <th>Próxima fecha esperada</th>
                                            <th>Facturas/mes</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monitoreoProveedores as $monitoreo)
                                        
                                            @php
                                                  
                                                $diasSinFacturar = $monitoreo->dias_sin_facturar ?? null;
                                                $estadoColor = $diasSinFacturar !== null && $diasSinFacturar > 30 ? 'danger' : ($diasSinFacturar > 15 ? 'warning' : 'success');
                                                $estadoIcono = $diasSinFacturar !== null && $diasSinFacturar > 30 ? 'fa-exclamation-triangle' : ($diasSinFacturar > 15 ? 'fa-clock' : 'fa-check-circle');
                                                $estadoTexto = $diasSinFacturar !== null && $diasSinFacturar > 30 ? '⚠️ Alerta Roja' : ($diasSinFacturar > 15 ? '⚡ Atención' : '✅ Normal');
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 32px; height: 32px; font-size: 12px; font-weight: 600;">
                                                            {{ strtoupper(substr($monitoreo->proveedor->proveedor ?? 'P', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            
                                                            <div class="fw-semibold">{{ $monitoreo->proveedor->proveedor ?? 'N/A' }}</div>
                                                            <small class="text-muted">{{ $monitoreo->proveedor->rif ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($monitoreo->ultima_factura)
                                                        <div>
                                                            <span class="fw-semibold">{{ $monitoreo->ultima_factura->numero_factura }}</span>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar-alt me-1"></i>
                                                                {{ $monitoreo->ultima_factura->fecha_factura->format('d/m/Y') }}
                                                            </small>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-dollar-sign me-1"></i>
                                                                $ {{ number_format($monitoreo->ultima_factura->monto_usd ?? $monitoreo->ultima_factura->monto, 2) }}
                                                            </small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Sin facturas</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($diasSinFacturar !== null)
                                                        <span class="badge bg-{{ $estadoColor }} fs-6 p-2">
                                                            <i class="fas {{ $estadoIcono }} me-1"></i>
                                                            {{ $diasSinFacturar }} días
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $estadoColor }} p-2">
                                                        <i class="fas {{ $estadoIcono }} me-1"></i>
                                                        {{ $estadoTexto }}
                                                    </span>
                                                    @if($diasSinFacturar !== null && $diasSinFacturar > 30)
                                                        <div class="mt-1">
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                                ¡Requiere atención inmediata!
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($monitoreo->proxima_fecha_esperada)
                                                        <span class="text-{{ $monitoreo->proxima_fecha_esperada->isPast() ? 'danger' : 'info' }}">
                                                            <i class="fas fa-calendar-day me-1"></i>
                                                            {{ $monitoreo->proxima_fecha_esperada->format('d/m/Y') }}
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ $monitoreo->proxima_fecha_esperada->isPast() ? 'Vencido' : 'Faltan ' . now()->diffInDays($monitoreo->proxima_fecha_esperada) . ' días' }}
                                                        </small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <span class="badge bg-secondary">{{ $monitoreo->promedio_mensual ?? 0 }}</span>
                                                        <br>
                                                        <small class="text-muted">promedio</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('facturas.create', ['proveedor_id' => $monitoreo->proveedor_id]) }}" 
                                                           class="btn btn-outline-primary" title="Registrar factura">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                        <a href="{{ route('facturas.index', ['proveedor_id' => $monitoreo->proveedor_id]) }}" 
                                                           class="btn btn-outline-info" title="Ver facturas">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($diasSinFacturar > 30)
                                                            <button class="btn btn-outline-danger" onclick="crearAlertaRecordatorio({{ $monitoreo->proveedor_id }})" title="Crear recordatorio">
                                                                <i class="fas fa-bell"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($monitoreoProveedores->hasPages())
                                <div class="mt-3">
                                    {{ $monitoreoProveedores->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-building text-muted" style="font-size: 48px;"></i>
                                <h5 class="mt-3 text-muted">No hay proveedores registrados</h5>
                                <p class="text-muted">Agrega proveedores para comenzar el monitoreo.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECCIÓN 3: RESUMEN DE FACTURAS RECIENTES -->
        <!-- ============================================================ -->
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-file-invoice text-primary me-2"></i>
                            Últimas Facturas Registradas
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($ultimasFacturas->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($ultimasFacturas as $factura)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-semibold">{{ $factura->numero_factura }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-building me-1"></i>
                                                {{ $factura->proveedor->proveedor ?? 'N/A' }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $factura->fecha_factura->format('d/m/Y') }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            
                                            <div class="fw-bold">
                                                {{ $factura->moneda === 'USD' ? '$' : ($factura->moneda === 'Bs' ? 'Bs. ' : 'RUB ') }}
                                                {{ number_format($factura->monto, 2) }}  / {{ $factura->monto_usd}}
                                            </div>
                                            <span class="badge bg-{{ $factura->estado_pago_color }}">
                                                {{ $factura->estado_pago_label }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-end">
                                <a href="{{ route('facturas.index') }}" class="btn btn-outline-primary btn-sm">
                                    Ver todas <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-invoice text-muted" style="font-size: 32px;"></i>
                                <p class="text-muted mt-2">No hay facturas registradas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-calendar-check text-success me-2"></i>
                            Próximas Fechas de Facturación
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($proximasFechas->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($proximasFechas as $item)
                                    @php
                                        $dias = $item->fecha_esperada ? now()->diffInDays($item->fecha_esperada, false) : null;
                                        $color = $dias !== null && $dias < 0 ? 'danger' : ($dias <= 3 ? 'warning' : 'success');
                                    @endphp
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-semibold">{{ $item->proveedor->proveedor ?? 'N/A' }}</div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Próxima facturación esperada
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-{{ $color }}">
                                                <i class="fas fa-calendar-day me-1"></i>
                                                {{ $item->fecha_esperada ? $item->fecha_esperada->format('d/m/Y') : 'N/A' }}
                                            </div>
                                            @if($dias !== null)
                                                <span class="badge bg-{{ $dias < 0 ? 'danger' : ($dias <= 3 ? 'warning' : 'info') }}">
                                                    <i class="fas fa-{{ $dias < 0 ? 'exclamation-triangle' : 'clock' }} me-1"></i>
                                                    {{ $dias < 0 ? 'Vencido hace ' . abs($dias) . ' días' : $dias . ' días' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-end">
                                <a href="{{ route('proveedores.index') }}" class="btn btn-outline-success btn-sm">
                                    Gestionar proveedores <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-check text-muted" style="font-size: 32px;"></i>
                                <p class="text-muted mt-2">No hay fechas programadas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- SECCIÓN 4: ESTADÍSTICAS RÁPIDAS -->
        <!-- ============================================================ -->
        <div class="row g-4 mt-2">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small text-uppercase">Facturas Pendientes</h6>
                                <h3 class="fw-bold mb-0 text-warning">{{ $pendientes }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small text-uppercase">Proveedores Activos</h6>
                                <h3 class="fw-bold mb-0 text-primary">{{ $totalProveedores }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-building text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small text-uppercase">Total Facturas</h6>
                                <h3 class="fw-bold mb-0 text-info">{{ $totalFacturas }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-file-invoice text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted small text-uppercase">Total en USD</h6>
                                <h3 class="fw-bold mb-0 text-success">$ {{ number_format($totalUSD, 2) }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-dollar-sign text-success fs-4"></i>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ============================================================
        // MARCAR ALERTA COMO LEÍDA
        // ============================================================
        function marcarLeida(id) {
            fetch(`/alertas/${id}/leer`, {
                method: 'POST',
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
                        title: '¡Marcada como leída!',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo marcar la alerta como leída'
                });
            });
        }

        // ============================================================
        // MARCAR TODAS COMO LEÍDAS
        // ============================================================
        function marcarTodasLeidas() {
            Swal.fire({
                title: '¿Marcar todas como leídas?',
                text: 'Esta acción marcará todas las alertas como leídas',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, marcar todas',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/alertas/marcar-todas-leidas', {
                        method: 'POST',
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
                                title: '¡Todas las alertas marcadas como leídas!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron marcar las alertas'
                        });
                    });
                }
            });
        }

        // ============================================================
        // ELIMINAR ALERTA
        // ============================================================
        function eliminarAlerta(id) {
            Swal.fire({
                title: '¿Eliminar alerta?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/alertas/${id}`, {
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
                                title: '¡Eliminada!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar la alerta'
                        });
                    });
                }
            });
        }

        // ============================================================
        // CREAR ALERTA DE RECORDATORIO
        // ============================================================
        function crearAlertaRecordatorio(proveedorId) {
            Swal.fire({
                title: 'Crear recordatorio',
                html: `
                    <div class="mb-3">
                        <label class="form-label">Mensaje del recordatorio</label>
                        <input id="mensajeRecordatorio" class="form-control" placeholder="Ej: Recordatorio de facturación...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha límite</label>
                        <input id="fechaLimite" class="form-control" type="date" value="${new Date().toISOString().split('T')[0]}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones (opcional)</label>
                        <textarea id="observacionesRecordatorio" class="form-control" rows="2"></textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Crear recordatorio',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const mensaje = document.getElementById('mensajeRecordatorio').value;
                    const fechaLimite = document.getElementById('fechaLimite').value;
                    const observaciones = document.getElementById('observacionesRecordatorio').value;
                    
                    if (!mensaje) {
                        Swal.showValidationMessage('El mensaje es obligatorio');
                        return false;
                    }
                    
                    return { mensaje, fechaLimite, observaciones };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = result.value;
                    
                    fetch('/alertas/crear', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            proveedor_id: proveedorId,
                            mensaje: data.mensaje,
                            fecha_limite: data.fechaLimite,
                            observaciones: data.observaciones,
                            tipo: 'recordatorio'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Recordatorio creado!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo crear el recordatorio'
                        });
                    });
                }
            });
        }

        // ============================================================
        // RECARGAR ALERTAS
        // ============================================================
        function recargarAlertas() {
            Swal.fire({
                icon: 'info',
                title: 'Actualizando...',
                text: 'Por favor espera',
                timer: 1000,
                showConfirmButton: false
            });
            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        // ============================================================
        // RECARGAR MONITOREO
        // ============================================================
        function recargarMonitoreo() {
            Swal.fire({
                icon: 'info',
                title: 'Actualizando monitoreo...',
                text: 'Por favor espera',
                timer: 1000,
                showConfirmButton: false
            });
            setTimeout(() => {
                location.reload();
            }, 1000);
        }

        // ============================================================
        // INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Auto cerrar alertas
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    const closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) closeBtn.click();
                }, 5000);
            });

            // Actualizar alertas cada 5 minutos
            setInterval(() => {
                fetch('/alertas/actualizar', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.cambios > 0) {
                        // Recargar página si hay cambios
                        location.reload();
                    }
                })
                .catch(error => console.log('Error actualizando alertas:', error));
            }, 300000); // 5 minutos

            console.log('✅ Dashboard de monitoreo cargado correctamente');
        });
    </script>

    <style>
        /* ============================================================
           ESTILOS DEL DASHBOARD
           ============================================================ */
        .rounded-4 { border-radius: 1rem !important; }
        .card { transition: all 0.3s ease; }
        .card:hover { transform: translateY(-2px); }
        
        .list-group-item {
            border-color: rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .list-group-item:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        .badge {
            font-weight: 500;
        }
        
        /* Animaciones para alertas nuevas */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        
        .badge.bg-danger:not(.bg-secondary) {
            animation: pulse 2s infinite;
        }
        
        /* Scroll personalizado para tablas */
        .table-responsive::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Modo oscuro */
        .dark-mode .table-light { background: #2d2d44 !important; }
        .dark-mode .table-light th { color: #e2e8f0 !important; }
        .dark-mode .table td { color: #e2e8f0 !important; border-color: #2d2d44 !important; }
        .dark-mode .list-group-item { background: transparent !important; border-color: #2d2d44 !important; color: #e2e8f0 !important; }
        .dark-mode .list-group-item:hover { background: rgba(102, 126, 234, 0.1) !important; }
        .dark-mode .table-responsive::-webkit-scrollbar-track { background: #2d2d44; }
        .dark-mode .table-responsive::-webkit-scrollbar-thumb { background: #4a4a6a; }
    </style>
</x-layout>