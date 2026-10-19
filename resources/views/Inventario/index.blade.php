<x-layout>
    <x-slot:title>RZN-OFFERINGS | Inventario de Productos</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                
                                <div>
                                    <h4 class="mb-0 fw-bold">Inventario de Productos</h4>
                                    <p class="opacity-75 mb-0 small">Gestiona todos los productos del sistema</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <!-- Filtro por Categoría -->
                                <div class="input-group" style="max-width: 200px;">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-filter text-muted"></i>
                                    </span>
                                    <select id="filtroCategoria" 
                                            class="form-control border-start-0"
                                            onchange="filtrarPorCategoria(this.value)">
                                        <option value="">Todas las categorías</option>
                                        @foreach($categorias ?? [] as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nombre_categoria }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <!-- Buscador -->
                                <div class="input-group" style="max-width: 250px;">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="searchProducto" 
                                           class="form-control border-start-0" 
                                           placeholder="Buscar producto..."
                                           onkeyup="searchProductos(this.value)">
                                </div>
                                
                                <a href="{{ route('productos.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus me-1"></i>
                                    Nuevo Producto
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Mensajes -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm m-3" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm m-3" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($productos->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-boxes text-muted" style="font-size: 64px;"></i>
                                <h5 class="mt-3 text-muted">No hay productos registrados</h5>
                                <p class="text-muted">Comienza creando tu primer producto</p>
                                <a href="{{ route('productos.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i> Crear Producto
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="productosTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th style="width: 40px;">
                                                <i class="fas fa-chevron-right text-muted"></i>
                                            </th>
                                            <th>Producto / Detalles</th>
                                            <th>Código</th>
                                            <th>Categoría</th>
                                            <th>Stock</th>
                                            <th>Estatus</th>
                                            <th style="width: 120px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productosTableBody">
                                        @foreach($productos as $producto)
                                            @php
                                                $detalles = $producto->detalles;
                                                $categoria = $producto->categoria;
                                            @endphp
                                            <tr id="producto-row-{{ $producto->id }}" 
                                                class="producto-row"
                                                data-categoria="{{ $categoria->id ?? '' }}">
                                                <td>#{{ $producto->id }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-link text-muted p-0" 
                                                            onclick="toggleDetalles({{ $producto->id }})"
                                                            id="toggle-btn-{{ $producto->id }}">
                                                        <i class="fas fa-chevron-right" id="chevron-{{ $producto->id }}"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 36px; height: 36px; font-size: 14px; font-weight: 600; flex-shrink: 0;">
                                                            {{ strtoupper(substr($producto->nombre, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold small">{{ $producto->nombre }}</div>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-tag me-1"></i>
                                                                {{ $categoria->nombre_categoria ?? 'Sin categoría' }}
                                                                @if($producto->ubicacion)
                                                                    <span class="mx-1">|</span>
                                                                    <i class="fas fa-map-pin me-1"></i>
                                                                    {{ $producto->ubicacion }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-barcode me-1"></i>
                                                        {{ $producto->codigo }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge" style="background: {{ $categoria->color ?? '#6c757d' }}; color: white;">
                                                        <i class="{{ $categoria->icono ?? 'fas fa-folder' }} me-1"></i>
                                                        {{ $categoria->nombre_categoria ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $stockClass = $producto->stock <= $producto->stock_minimo ? 'danger' : ($producto->stock <= $producto->stock_minimo * 2 ? 'warning' : 'success');
                                                    @endphp
                                                    <span class="badge bg-{{ $stockClass }}">
                                                        <i class="fas fa-boxes me-1"></i>
                                                        {{ $producto->stock }}
                                                    </span>
                                                    @if($producto->stock <= $producto->stock_minimo)
                                                        <span class="badge bg-danger ms-1" title="Stock mínimo: {{ $producto->stock_minimo }}">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $estatusConfig = [
                                                            'disponible' => ['color' => 'success', 'icon' => 'fa-circle', 'label' => 'Disponible'],
                                                            'asignado' => ['color' => 'primary', 'icon' => 'fa-user-check', 'label' => 'Asignado'],
                                                            'mantenimiento' => ['color' => 'warning', 'icon' => 'fa-tools', 'label' => 'Mantenimiento'],
                                                            'reparacion' => ['color' => 'danger', 'icon' => 'fa-hammer', 'label' => 'Reparación'],
                                                            'prestamo' => ['color' => 'info', 'icon' => 'fa-exchange-alt', 'label' => 'Préstamo'],
                                                            'baja' => ['color' => 'secondary', 'icon' => 'fa-times-circle', 'label' => 'Dado de Baja'],
                                                        ];
                                                        $estatus = $estatusConfig[$producto->estatus] ?? ['color' => 'secondary', 'icon' => 'fa-circle', 'label' => $producto->estatus];
                                                    @endphp
                                                    <span class="badge bg-{{ $estatus['color'] }}">
                                                        <i class="fas {{ $estatus['icon'] }} me-1" style="font-size: 8px;"></i>
                                                        {{ $estatus['label'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('productos.show', $producto) }}" 
                                                           class="btn btn-outline-info" 
                                                           title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('productos.edit', $producto) }}" 
                                                           class="btn btn-outline-primary" 
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-outline-danger" 
                                                                onclick="deleteProducto({{ $producto->id }})" 
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <!-- Fila de Detalles Expandible -->
                                            <tr id="detalles-row-{{ $producto->id }}" class="detalles-row" style="display: none;">
                                                <td colspan="8">
                                                    <div class="p-3 bg-light rounded-3 mx-2 my-1">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <h6 class="fw-bold text-secondary">
                                                                    <i class="fas fa-info-circle me-1 text-primary"></i>
                                                                    Información General
                                                                </h6>
                                                                <div class="row g-2">
                                                                    <div class="col-6">
                                                                        <small class="text-muted">Código</small>
                                                                        <div class="fw-semibold">{{ $producto->codigo }}</div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted">Categoría</small>
                                                                        <div class="fw-semibold">{{ $categoria->nombre_categoria ?? 'N/A' }}</div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted">Proveedor</small>
                                                                        <div class="fw-semibold">{{ $producto->proveedor->proveedor ?? $producto->proveedor->proveedor ?? 'N/A' }}</div>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="text-muted">Ubicación</small>
                                                                        <div class="fw-semibold">{{ $producto->ubicacion ?? 'No definida' }}</div>
                                                                    </div>
                                                                    @if($producto->descripcion)
                                                                        <div class="col-12">
                                                                            <small class="text-muted">Descripción</small>
                                                                            <div class="fw-semibold">{{ $producto->descripcion }}</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <h6 class="fw-bold text-secondary">
                                                                    <i class="fas fa-sliders-h me-1 text-primary"></i>
                                                                    Atributos Específicos
                                                                </h6>
                                                                @if($detalles)
                                                                    <div class="row g-2">
                                                                        @foreach($detalles->getAttributes() as $key => $value)
                                                                            @if(!in_array($key, ['id', 'producto_id', 'created_at', 'updated_at', 'deleted_at']) && $value)
                                                                                <div class="col-6">
                                                                                    <small class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}</small>
                                                                                    <div class="fw-semibold">{{ $value }}</div>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="text-muted small">
                                                                        <i class="fas fa-info-circle me-1"></i>
                                                                        No hay atributos específicos para este producto
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="mt-2 pt-2 border-top">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                                    Creado: {{ $producto->created_at->format('d/m/Y H:i') }}
                                                                    @if($producto->updated_at)
                                                                        <span class="mx-2">|</span>
                                                                        <i class="fas fa-edit me-1"></i>
                                                                        Actualizado: {{ $producto->updated_at->format('d/m/Y H:i') }}
                                                                    @endif
                                                                </small>
                                                                <a href="{{ route('productos.show', $producto) }}" class="btn btn-sm btn-primary">
                                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                                    Ver completo
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-0 py-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <small class="text-muted">
                                <i class="fas fa-boxes me-1"></i>
                                Total: <strong>{{ $productos->total() }}</strong> productos
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Disponibles: <strong>{{ $productos->where('estatus', 'disponible')->count() }}</strong>
                                &nbsp;|&nbsp;
                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                Stock bajo: <strong>{{ $productos->filter(fn($p) => $p->stock <= $p->stock_minimo)->count() }}</strong>
                                &nbsp;|&nbsp;
                                <i class="fas fa-box text-info me-1"></i>
                                Categorías: <strong>{{ $productos->groupBy('categoria_id')->count() }}</strong>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                @if(!$productos->isEmpty())
                    <div class="mt-4">
                        {{ $productos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* ============================================================
           ESTILOS DE LA VISTA
           ============================================================ */
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .rounded-4 { 
            border-radius: 1rem !important; 
        }
        
        .table > :not(caption) > * > * {
            padding: 0.6rem 0.6rem;
        }
        
        .btn-group .btn {
            transition: all 0.2s ease;
            padding: 0.2rem 0.4rem;
        }
        
        .btn-group .btn:hover {
            transform: translateY(-2px);
        }
        
        .card {
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Fila de detalles */
        .detalles-row td {
            padding: 0.5rem !important;
            background: #f8f9fa !important;
        }
        
        .detalles-row .bg-light {
            background: #ffffff !important;
            border: 1px solid #e9ecef;
            border-radius: 0.5rem !important;
        }
        
        /* Animación de chevron */
        .chevron-rotate {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
        }
        
        .chevron-normal {
            transform: rotate(0deg);
            transition: transform 0.3s ease;
        }
        
        /* Badge de stock bajo */
        .badge.bg-danger {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        /* Modo oscuro */
        .dark-mode .card {
            background: #1a1a2e !important;
            color: #e2e8f0 !important;
        }
        
        .dark-mode .card-header {
            background: linear-gradient(135deg, #1e293b, #0f172a) !important;
        }
        
        .dark-mode .card-footer {
            background: #0f172a !important;
            border-color: #2d2d44 !important;
        }
        
        .dark-mode .table-light {
            background: #2d2d44 !important;
        }
        
        .dark-mode .table-light th {
            color: #e2e8f0 !important;
        }
        
        .dark-mode .table td {
            color: #e2e8f0 !important;
            border-color: #2d2d44 !important;
        }
        
        .dark-mode .detalles-row td {
            background: #1a1a2e !important;
        }
        
        .dark-mode .detalles-row .bg-light {
            background: #2d2d44 !important;
            border-color: #3d3d54 !important;
            color: #e2e8f0 !important;
        }
        
        .dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        
        .dark-mode .badge.bg-light {
            background: #2d2d44 !important;
            color: #e2e8f0 !important;
        }
        
        .dark-mode .input-group-text {
            background: #2d2d44 !important;
            border-color: #3d3d54 !important;
            color: #94a3b8 !important;
        }
        
        .dark-mode .form-control,
        .dark-mode .form-select {
            background: #2d2d44 !important;
            color: #e2e8f0 !important;
            border-color: #3d3d54 !important;
        }
        
        .dark-mode .form-control:focus,
        .dark-mode .form-select:focus {
            background: #2d2d44 !important;
            border-color: #667eea !important;
        }
        
        .dark-mode .btn-light {
            background: #2d2d44 !important;
            color: #e2e8f0 !important;
            border-color: #3d3d54 !important;
        }
        
        .dark-mode .btn-light:hover {
            background: #3d3d54 !important;
        }
        
        /* Estilos para la paginación */
        .pagination .page-link {
            color: #667eea;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .pagination .page-link:hover {
            background: #f0f4ff;
            border-color: #667eea;
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }
        
        .dark-mode .pagination .page-link {
            background: #2d2d44;
            color: #e2e8f0;
            border-color: #3d3d54;
        }
        
        .dark-mode .pagination .page-link:hover {
            background: #3d3d54;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.8rem;
            }
            
            .btn-group .btn {
                padding: 0.15rem 0.3rem;
                font-size: 0.7rem;
            }
            
            .badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }
            
            .detalles-row .row {
                flex-direction: column;
            }
            
            .detalles-row .col-md-6 {
                width: 100%;
            }
        }
    </style>

    <script>
        // ============================================================
        // TOGGLE DETALLES DEL PRODUCTO
        // ============================================================
        function toggleDetalles(productoId) {
            const detallesRow = document.getElementById(`detalles-row-${productoId}`);
            const chevron = document.getElementById(`chevron-${productoId}`);
            
            if (detallesRow.style.display === 'none') {
                detallesRow.style.display = 'table-row';
                chevron.className = 'fas fa-chevron-down chevron-rotate';
            } else {
                detallesRow.style.display = 'none';
                chevron.className = 'fas fa-chevron-right chevron-normal';
            }
        }

        // ============================================================
        // FILTRAR POR CATEGORÍA
        // ============================================================
        function filtrarPorCategoria(categoriaId) {
            const rows = document.querySelectorAll('#productosTableBody tr.producto-row');
            
            rows.forEach(row => {
                const categoria = row.dataset.categoria;
                if (categoriaId === '' || categoria === categoriaId) {
                    row.style.display = '';
                    // Ocultar detalles si están abiertos
                    const id = row.id.replace('producto-row-', '');
                    const detallesRow = document.getElementById(`detalles-row-${id}`);
                    if (detallesRow) {
                        detallesRow.style.display = 'none';
                        const chevron = document.getElementById(`chevron-${id}`);
                        if (chevron) {
                            chevron.className = 'fas fa-chevron-right chevron-normal';
                        }
                    }
                } else {
                    row.style.display = 'none';
                    // Ocultar detalles
                    const id = row.id.replace('producto-row-', '');
                    const detallesRow = document.getElementById(`detalles-row-${id}`);
                    if (detallesRow) {
                        detallesRow.style.display = 'none';
                        const chevron = document.getElementById(`chevron-${id}`);
                        if (chevron) {
                            chevron.className = 'fas fa-chevron-right chevron-normal';
                        }
                    }
                }
            });
        }

        // ============================================================
        // BUSCAR PRODUCTOS
        // ============================================================
        function searchProductos(query) {
            const rows = document.querySelectorAll('#productosTableBody tr.producto-row');
            const term = query.toLowerCase().trim();
            
            if (term.length < 2) {
                rows.forEach(row => {
                    row.style.display = '';
                    // Ocultar detalles si están abiertos
                    const id = row.id.replace('producto-row-', '');
                    const detallesRow = document.getElementById(`detalles-row-${id}`);
                    if (detallesRow) {
                        detallesRow.style.display = 'none';
                        const chevron = document.getElementById(`chevron-${id}`);
                        if (chevron) {
                            chevron.className = 'fas fa-chevron-right chevron-normal';
                        }
                    }
                });
                return;
            }
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    // Ocultar detalles
                    const id = row.id.replace('producto-row-', '');
                    const detallesRow = document.getElementById(`detalles-row-${id}`);
                    if (detallesRow) {
                        detallesRow.style.display = 'none';
                        const chevron = document.getElementById(`chevron-${id}`);
                        if (chevron) {
                            chevron.className = 'fas fa-chevron-right chevron-normal';
                        }
                    }
                }
            });
        }

        // ============================================================
        // ELIMINAR PRODUCTO
        // ============================================================
        function deleteProducto(id) {
            if (!confirm('⚠️ ¿Estás seguro de eliminar este producto?')) return;
            
            fetch(`/productos/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    const row = document.getElementById(`producto-row-${id}`);
                    const detallesRow = document.getElementById(`detalles-row-${id}`);
                    if (row) row.remove();
                    if (detallesRow) detallesRow.remove();
                } else {
                    showToast(data.message || 'Error al eliminar', 'error');
                }
            })
            .catch(error => {
                showToast('❌ Error al eliminar el producto', 'error');
                console.error('Error:', error);
            });
        }

        // ============================================================
        // TOAST MEJORADO
        // ============================================================
        function showToast(message, type = 'success') {
            const toast = document.getElementById('customToast');
            const toastMessage = document.getElementById('customToastMessage');
            const toastIcon = document.getElementById('customToastIcon');
            
            if (!toast) return;
            
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
            console.log('✅ Inventario de productos cargado correctamente');
            console.log('📊 Total productos:', document.querySelectorAll('#productosTableBody tr.producto-row').length);
            
            // Cerrar alertas automáticamente
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    const closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) closeBtn.click();
                }, 5000);
            });
        });
    </script>
</x-layout>