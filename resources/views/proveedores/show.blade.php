<x-layout>
    <x-slot:title>VRZ-PROVEEDORES | {{ $proveedor->proveedor }}</x-slot:title>

    <div class="container-fluid py-4">
        <!-- Botón volver y acciones -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Volver
            </a>
            <div class="d-flex gap-2">
                <a href="{{ route('proveedores.edit', $proveedor) }}" class="btn btn-outline-warning">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
                <a href="{{ route('productos.create') }}?proveedor_id={{ $proveedor->id }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Nuevo Producto
                </a>
                <a href="{{ route('facturas.create') }}?proveedor_id={{ $proveedor->id }}" class="btn btn-success">
                    <i class="fas fa-file-invoice me-1"></i> Nueva Factura
                </a>
            </div>
        </div>

        <!-- Tarjeta principal del proveedor -->
        <div class="card shadow-lg border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="fw-bold mb-2">{{ $proveedor->proveedor }}</h2>
                        <div class="d-flex flex-wrap gap-3 mb-2">
                            <span class="badge bg-primary fs-6">{{ $proveedor->categoria->nombre_categoria ?? 'Sin categoría' }}</span>
                            <span class="badge bg-{{ $proveedor->estado_color }} fs-6">
                                <i class="fas {{ $proveedor->estado_icono }} me-1"></i>
                                {{ $proveedor->estado_label }}
                            </span>
                        </div>
                        <p class="text-muted">
                            <i class="fas fa-user me-1"></i> Asesor: {{ $proveedor->asesor ?? 'No asignado' }}
                        </p>
                        <p class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i> Registrado: {{ $proveedor->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <!-- Monitoreo basado en productos recurrentes -->
                    <div class="col-md-4 text-md-end">
                        @php
    $recurrentes = $productosRecurrentes; // ya filtrados
    $proxima = $proximaFecha; // pasada desde el controlador
    $dias = $dias ?? null; // pasada desde el controlador
@endphp

@if($proxima)
    <div class="alert alert-{{ $dias < 0 ? 'danger' : ($dias <= 5 ? 'warning' : 'info') }} mb-0">
        <i class="fas fa-clock me-2"></i>
        <strong>Próxima factura recurrente</strong>
        <br>
        <small>
            Fecha: {{ $proxima->format('d/m/Y') }}
            <br>
            @if($dias < 0)
                🔴 Vencido hace {{ abs($dias) }} días
            @elseif($dias == 0)
                🟡 ¡Hoy!
            @elseif($dias <= 5)
                🟠 Próximo ({{ $dias }} días)
            @else
                ✅ {{ $dias }} días restantes
            @endif
            <br>
            Productos recurrentes: {{ $recurrentes->count() }}
        </small>
    </div>
@else
    <div class="alert alert-secondary mb-0">
        <i class="fas fa-pause-circle me-2"></i>
        Sin productos recurrentes
    </div>
@endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de métricas -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-file-invoice fa-2x text-primary mb-2"></i>
                        <h3 class="fw-bold">{{ $proveedor->facturas()->count() }}</h3>
                        <p class="text-muted mb-0">Facturas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-box fa-2x text-success mb-2"></i>
                        <h3 class="fw-bold">{{ $proveedor->productos()->count() }}</h3>
                        <p class="text-muted mb-0">Productos/Servicios</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-dollar-sign fa-2x text-warning mb-2"></i>
                        <h3 class="fw-bold">${{ number_format($proveedor->facturas()->sum('monto_usd'), 2) }}</h3>
                        <p class="text-muted mb-0">Total facturado (USD)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-alt fa-2x text-info mb-2"></i>
                        <h3 class="fw-bold">{{ $proveedor->dias_sin_facturar_formateado ?? '--' }}</h3>
                        <p class="text-muted mb-0">Días sin facturar</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pestañas -->
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-0">
                <ul class="nav nav-tabs nav-justified border-bottom-0 pt-3 px-3" id="proveedorTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
                            <i class="fas fa-info-circle me-1"></i> General
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="productos-tab" data-bs-toggle="tab" data-bs-target="#productos" type="button">
                            <i class="fas fa-box me-1"></i> Productos <span class="badge bg-secondary">{{ $proveedor->productos()->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="facturas-tab" data-bs-toggle="tab" data-bs-target="#facturas" type="button">
                            <i class="fas fa-file-invoice me-1"></i> Facturas <span class="badge bg-secondary">{{ $proveedor->facturas()->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="monitoreo-tab" data-bs-toggle="tab" data-bs-target="#monitoreo" type="button">
                            <i class="fas fa-chart-line me-1"></i> Monitoreo
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="archivos-tab" data-bs-toggle="tab" data-bs-target="#archivos" type="button">
                            <i class="fas fa-paperclip me-1"></i> Archivos
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4" id="proveedorTabsContent">
                    <!-- Tab General -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <h5 class="fw-bold"><i class="fas fa-address-card text-primary me-2"></i>Información de contacto</h5>
                                <dl class="row mt-3">
                                    <dt class="col-sm-4">Correo</dt>
                                    <dd class="col-sm-8"><a href="mailto:{{ $proveedor->correo }}">{{ $proveedor->correo }}</a></dd>
                                    <dt class="col-sm-4">Teléfono</dt>
                                    <dd class="col-sm-8">{{ $proveedor->telefono_contacto ?? 'No registrado' }}</dd>
                                    <dt class="col-sm-4">Ciudad</dt>
                                    <dd class="col-sm-8">{{ $proveedor->ciudad ?? 'No registrada' }}</dd>
                                    <dt class="col-sm-4">Dirección</dt>
                                    <dd class="col-sm-8">{{ $proveedor->direccion ?? 'No registrada' }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-bold"><i class="fas fa-cog text-primary me-2"></i>Configuración de monitoreo (proveedor)</h5>
                                <dl class="row mt-3">
                                    <dt class="col-sm-5">Monitoreo activo</dt>
                                    <dd class="col-sm-7">{{ $proveedor->monitoreo_activo ? '✅ Sí' : '❌ No' }}</dd>
                                    <dt class="col-sm-5">Fecha facturación</dt>
                                    <dd class="col-sm-7">{{ $proveedor->fecha_facturacion ? $proveedor->fecha_facturacion->format('d/m/Y') : 'No configurada' }}</dd>
                                    <dt class="col-sm-5">Días para alertar</dt>
                                    <dd class="col-sm-7">{{ $proveedor->dias_alertar ?? 'No configurado' }}</dd>
                                    <dt class="col-sm-5">Última factura</dt>
                                    <dd class="col-sm-7">{{ $proveedor->ultima_factura ? $proveedor->ultima_factura->fecha_factura->format('d/m/Y') : 'Sin facturas' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Productos -->
                    <div class="tab-pane fade" id="productos" role="tabpanel">
                        @if($proveedor->productos->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Este proveedor no tiene productos registrados.</p>
                                <a href="{{ route('productos.create') }}?proveedor_id={{ $proveedor->id }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i> Crear Producto
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Tipo pago</th>
                                            <th>Próxima facturación</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($proveedor->productos as $producto)
                                            <tr>
                                                <td>{{ $producto->nombre }}</td>
                                                <td>{{ $producto->categoria->nombre_categoria ?? 'N/A' }}</td>
                                                <td>{{ $producto->tipo_pago_label }}</td>
                                                <td>
                                                    @if($producto->proxima_facturacion)
                                                        {{ $producto->proxima_facturacion->format('d/m/Y') }}
                                                        <span class="badge bg-{{ $producto->estado_facturacion_color }}">
                                                            {{ $producto->estado_facturacion_label }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">No configurado</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $producto->estatus_color }}">
                                                        {{ $producto->estatus_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('productos.show', $producto) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('facturas.create') }}?producto_id={{ $producto->id }}" class="btn btn-sm btn-outline-success">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Tab Facturas (con botón modal) -->
                    <div class="tab-pane fade" id="facturas" role="tabpanel">
                        @if($proveedor->facturas->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Este proveedor no tiene facturas registradas.</p>
                                <a href="{{ route('facturas.create') }}?proveedor_id={{ $proveedor->id }}" class="btn btn-success">
                                    <i class="fas fa-plus-circle me-1"></i> Crear Factura
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>N° Factura</th>
                                            <th>Fecha</th>
                                            <th>Fecha pago</th>
                                            <th>Producto</th>
                                            <th>Monto</th>
                                            <th>Moneda</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($proveedor->facturas->sortByDesc('fecha_factura') as $factura)
                                            <tr>
                                                <td>{{ $factura->numero_factura }}</td>
                                                <td>{{ $factura->fecha_factura->format('d/m/Y') }}</td>
                                                <td>{{ $factura->fecha_pago ? $factura->fecha_pago->format('d/m/Y') : 'Pendiente' }}</td>
                                                <td>{{ $factura->producto->nombre ?? 'N/A' }}</td>
                                                <td>{{ $factura->monto_formateado }}</td>
                                                <td>{{ $factura->moneda_simbolo }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $factura->estado_pago_color }}">
                                                        {{ $factura->estado_pago_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info btn-ver-factura" 
                                                            data-factura='@json($factura->load('archivos'))'
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#facturaModal">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td colspan="3" class="text-end">Total:</td>
                                            <td colspan="5">${{ number_format($proveedor->facturas->sum('monto_usd'), 2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Tab Monitoreo -->
                    <div class="tab-pane fade" id="monitoreo" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="fw-bold"><i class="fas fa-chart-bar text-primary me-2"></i>Facturación mensual (USD)</h5>
                                        <canvas id="facturacionChart" height="250"></canvas>
                                        <small class="text-muted">Evolución de los últimos meses</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="fw-bold"><i class="fas fa-list text-primary me-2"></i>Resumen por mes</h5>
                                        <ul class="list-group list-group-flush">
                                            @forelse($facturasPorMes->take(6) as $item)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m', $item->mes)->format('M Y') }}
                                                    <span class="badge bg-primary rounded-pill">${{ number_format($item->total, 2) }}</span>
                                                </li>
                                            @empty
                                                <li class="list-group-item text-muted">Sin datos</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="fw-bold"><i class="fas fa-clock text-primary me-2"></i>Alertas</h5>
                                        @if($proveedor->alertas->where('activa', true)->isEmpty())
                                            <p class="text-muted">No hay alertas activas.</p>
                                        @else
                                            <ul class="list-unstyled">
                                                @foreach($proveedor->alertas->where('activa', true) as $alerta)
                                                    <li class="alert alert-{{ $alerta->color }} py-2">
                                                        <i class="fas fa-{{ $alerta->icono }} me-2"></i>
                                                        {{ $alerta->mensaje }}
                                                        <small class="d-block text-muted">{{ $alerta->created_at->diffForHumans() }}</small>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Archivos -->
                    <div class="tab-pane fade" id="archivos" role="tabpanel">
                        @php
                            $archivos = $proveedor->facturas->flatMap->archivos;
                        @endphp

                        @if($archivos->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-paperclip fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay archivos adjuntos a las facturas de este proveedor.</p>
                                <a href="{{ route('facturas.create') }}?proveedor_id={{ $proveedor->id }}" class="btn btn-primary">
                                    <i class="fas fa-upload me-1"></i> Subir archivo
                                </a>
                            </div>
                        @else
                            <div class="row">
                                @foreach($archivos as $archivo)
                                    <div class="col-md-3 col-sm-4 col-6 mb-3">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body text-center">
                                                <i class="fas {{ $archivo->icono }} fa-3x mb-2"></i>
                                                <p class="small text-truncate" title="{{ $archivo->nombre_original }}">
                                                    {{ $archivo->nombre_original }}
                                                </p>
                                                <p class="small text-muted">{{ $archivo->tamaño_formateado }}</p>
                                                <a href="{{ $archivo->url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="{{ $archivo->url }}" download class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                            <div class="card-footer small text-muted">
                                                Factura #{{ $archivo->factura->numero_factura ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Factura -->
    <!-- Modal Factura Mejorado -->
<div class="modal fade" id="facturaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-invoice fa-2x me-3"></i>
                    <div>
                        <h5 class="modal-title fw-bold" id="facturaModalLabel">Detalles de Factura</h5>
                        <small class="opacity-75" id="facturaModalNumero">#0000</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="facturaModalBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cerrar
                </button>
                <button type="button" class="btn btn-primary" id="btnImprimirFactura" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

    <!-- Estilos -->
    <style>
        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            padding: 0.8rem 1.2rem;
            font-weight: 500;
        }
        .nav-tabs .nav-link.active {
            color: #764ba2;
            border-bottom: 3px solid #764ba2;
            background: transparent;
        }
        .nav-tabs .nav-link:hover {
            border-color: transparent;
        }
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
    </style>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico
            const facturasPorMes = @json($facturasPorMes);
            if (facturasPorMes.length > 0) {
                const ctx = document.getElementById('facturacionChart').getContext('2d');
                const meses = facturasPorMes.map(item => {
                    const date = new Date(item.mes + '-01');
                    return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
                });
                const totales = facturasPorMes.map(item => parseFloat(item.total));
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: meses,
                        datasets: [{
                            label: 'Total facturado (USD)',
                            data: totales,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: value => '$' + value.toFixed(2) }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: context => '$' + context.parsed.y.toFixed(2)
                                }
                            }
                        }
                    }
                });
            } else {
                const canvas = document.getElementById('facturacionChart');
                const parent = canvas.parentNode;
                canvas.style.display = 'none';
                const msg = document.createElement('p');
                msg.className = 'text-muted text-center';
                msg.textContent = 'No hay facturas registradas para mostrar.';
                parent.appendChild(msg);
            }

            // Modal de factura
            document.querySelectorAll('.btn-ver-factura').forEach(button => {
                button.addEventListener('click', function() {
                    const factura = JSON.parse(this.dataset.factura);
                    const body = document.getElementById('facturaModalBody');
                    console.log('Factura cargada en el modal:', factura); // Depuración
                    let html = `
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>N° Factura:</strong> ${factura.numero_factura}</p>
                                <p><strong>Fecha:</strong> ${new Date(factura.fecha_factura).toLocaleDateString('es-ES')}</p>
                                <p><strong>Fecha pago:</strong> ${factura.fecha_pago ? new Date(factura.fecha_pago).toLocaleDateString('es-ES') : 'Pendiente'}</p>
                                <p><strong>Producto:</strong> ${factura.producto ? factura.producto.nombre : 'N/A'}</p>
                                <p><strong>Monto:</strong> ${factura.monto}</p>
                                <p><strong>Moneda:</strong> ${factura.moneda}</p>
                                <p><strong>Estado:</strong> <span class="badge bg-${factura.estado_pago_color}">${factura.estado_pago_label}</span></p>
                                <p><strong>Observaciones:</strong> ${factura.observaciones || 'Ninguna'}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Archivos adjuntos</h6>
                                ${factura.archivos && factura.archivos.length > 0 ? 
                                    factura.archivos.map(a => `
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas ${a.icono} me-2"></i>
                                            <span class="me-2">${a.nombre_original}</span>
                                            <a href="${a.url}" target="_blank" class="btn btn-sm btn-outline-primary me-1">Ver</a>
                                            <a href="${a.url}" download class="btn btn-sm btn-outline-success">Descargar</a>
                                        </div>
                                    `).join('') 
                                    : '<p class="text-muted">Sin archivos</p>'
                                }
                            </div>
                        </div>
                    `;
                    body.innerHTML = html;
                });
            });
        });
    </script>
</x-layout>