<x-layout>
    <x-slot:title>VRZ-CATEGORIAS | Sistema de Gestión</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                
                                <div>
                                    <h4 class="mb-0 fw-bold">Gestión de Categorías</h4>
                                    <p class="opacity-75 mb-0 small">Administra las categorías del sistema</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <!-- Buscador -->
                                <div class="input-group" style="max-width: 250px;">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="searchCategoria" 
                                           class="form-control border-start-0" 
                                           placeholder="Buscar categoría..."
                                           onkeyup="searchCategorias(this.value)">
                                </div>
                                <a href="{{ route('categorias.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus me-1"></i>
                                    Nueva Categoría
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

                        @if($categorias->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-tags text-muted" style="font-size: 64px;"></i>
                                <h5 class="mt-3 text-muted">No hay categorías registradas</h5>
                                <p class="text-muted">Comienza creando tu primera categoría</p>
                                <a href="{{ route('categorias.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i> Crear Categoría
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="categoriasTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Categoría</th>
                                            <th>Descripción</th>
                                            <th>Productos</th>
                                            <th>Estado</th>
                                            <th>Creado por</th>
                                            <th>Fecha</th>
                                            <th style="width: 150px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categoriasTableBody">
                                        @foreach($categorias as $categoria)
                                            <tr id="categoria-row-{{ $categoria->id }}">
                                                <td>#{{ $categoria->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 36px; height: 36px; font-size: 14px; font-weight: 600;">
                                                            {{ strtoupper(substr($categoria->nombre_categoria, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold small">{{ $categoria->nombre_categoria }}</div>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-tag me-1"></i>
                                                                ID: {{ $categoria->id }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ Str::limit($categoria->descripcion ?? 'Sin descripción', 50) }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-box me-1"></i>
                                                        {{ $categoria->productos_count ?? $categoria->productos->count() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $estado = $categoria->activo ? 'Activa' : 'Inactiva';
                                                        $color = $categoria->activo ? 'success' : 'secondary';
                                                    @endphp
                                                    <span class="badge bg-{{ $color }}">
                                                        <i class="fas fa-{{ $categoria->activo ? 'circle' : 'times-circle' }} me-1" style="font-size: 8px;"></i>
                                                        {{ $estado }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 24px; height: 24px; font-size: 10px; font-weight: 600;">
                                                            {{ $categoria->creador ? strtoupper(substr($categoria->creador->name, 0, 1)) : 'S' }}
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ $categoria->creador_nombre ?? 'Sistema' }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                        {{ $categoria->created_at->format('d/m/Y') }}
                                                        <br>
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $categoria->created_at->format('H:i') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        @if($categoria->activo)
                                                            <button class="btn btn-outline-warning" 
                                                                    onclick="toggleCategoriaStatus({{ $categoria->id }})" 
                                                                    title="Desactivar">
                                                                <i class="fas fa-pause"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-outline-success" 
                                                                    onclick="toggleCategoriaStatus({{ $categoria->id }})" 
                                                                    title="Activar">
                                                                <i class="fas fa-play"></i>
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('categorias.edit', $categoria) }}" 
                                                           class="btn btn-outline-primary" 
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if($categoria->productos->count() == 0)
                                                            <button class="btn btn-outline-danger" 
                                                                    onclick="deleteCategoria({{ $categoria->id }})" 
                                                                    title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-outline-secondary" 
                                                                    title="No se puede eliminar (tiene productos)" 
                                                                    disabled>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
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
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-tags me-1"></i>
                                Total: <strong>{{ $categorias->total() }}</strong> categorías
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Activas: <strong>{{ $categorias->where('activo', true)->count() }}</strong>
                                &nbsp;|&nbsp;
                                <i class="fas fa-times-circle text-secondary me-1"></i>
                                Inactivas: <strong>{{ $categorias->where('activo', false)->count() }}</strong>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                @if(!$categorias->isEmpty())
                    <div class="mt-4">
                        {{ $categorias->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* ============================================================
           ESTILOS DE LA VISTA - MISMO ESTILO QUE USUARIOS
           ============================================================ */
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .rounded-4 { 
            border-radius: 1rem !important; 
        }
        
        .table > :not(caption) > * > * {
            padding: 0.75rem 0.75rem;
        }
        
        .btn-group .btn {
            transition: all 0.2s ease;
            padding: 0.25rem 0.5rem;
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
        
        .dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        
        .dark-mode .alert {
            background: #1a1a2e !important;
            border-color: #2d2d44 !important;
            color: #e2e8f0 !important;
        }
        
        .dark-mode .input-group-text {
            background: #2d2d44 !important;
            border-color: #3d3d54 !important;
            color: #94a3b8 !important;
        }
        
        .dark-mode .form-control {
            background: #2d2d44 !important;
            color: #e2e8f0 !important;
            border-color: #3d3d54 !important;
        }
        
        .dark-mode .form-control:focus {
            background: #2d2d44 !important;
            border-color: #667eea !important;
        }
        
        .dark-mode .bg-light {
            background: #0f172a !important;
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
    </style>

    <script>
        // ============================================================
        // TOGGLE ESTADO DE CATEGORÍA
        // ============================================================
        function toggleCategoriaStatus(categoriaId) {
            if (!confirm('¿Estás seguro de cambiar el estado de esta categoría?')) return;
            
            fetch(`/categorias/${categoriaId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(data.message || 'Error al cambiar el estado', 'error');
                }
            })
            .catch(error => {
                showToast('❌ Error al cambiar el estado', 'error');
                console.error('Error:', error);
            });
        }

        // ============================================================
        // ELIMINAR CATEGORÍA
        // ============================================================
        function deleteCategoria(categoriaId) {
            if (!confirm('⚠️ ¿Estás seguro de eliminar esta categoría? Esta acción no se puede deshacer.')) return;
            
            fetch(`/categorias/${categoriaId}`, {
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
                    document.getElementById(`categoria-row-${categoriaId}`).remove();
                } else {
                    showToast(data.message || 'Error al eliminar', 'error');
                }
            })
            .catch(error => {
                showToast('❌ Error al eliminar la categoría', 'error');
                console.error('Error:', error);
            });
        }

        // ============================================================
        // BUSCAR CATEGORÍAS
        // ============================================================
        function searchCategorias(query) {
            if (query.length < 2) {
                document.querySelectorAll('#categoriasTableBody tr').forEach(row => {
                    row.style.display = '';
                });
                return;
            }
            
            fetch(`/categorias/search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const rows = document.querySelectorAll('#categoriasTableBody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(query.toLowerCase()) ? '' : 'none';
                    });
                }
            })
            .catch(error => {
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
            
            if (!toast || !toastMessage || !toastIcon) return;
            
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
            console.log('✅ Gestión de categorías cargada correctamente');
            console.log('📊 Total categorías:', document.querySelectorAll('#categoriasTableBody tr').length);
            
            // Cerrar alertas automáticamente después de 5 segundos
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    const closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) closeBtn.click();
                }, 5000);
            });
        });
    </script>
</x-layout>