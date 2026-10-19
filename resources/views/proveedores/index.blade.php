<x-layout>
    <x-slot:title>VRZ-PROVEEDORES | Sistema de Gestión</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h4 class="mb-0 fw-bold">Gestión de Proveedores</h4>
                                    <p class="opacity-75 mb-0 small">Administra los proveedores del sistema</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <div class="input-group" style="max-width: 250px;">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           id="searchProveedor" 
                                           class="form-control border-start-0" 
                                           placeholder="Buscar proveedor..."
                                           onkeyup="searchProveedores(this.value)">
                                </div>
                                <a href="{{ route('proveedores.create') }}" class="btn btn-light">
                                    <i class="fas fa-plus me-1"></i>
                                    Nuevo Proveedor
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
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

                        @if($proveedores->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-truck text-muted" style="font-size: 64px;"></i>
                                <h5 class="mt-3 text-muted">No hay proveedores registrados</h5>
                                <p class="text-muted">Comienza creando tu primer proveedor</p>
                                <a href="{{ route('proveedores.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-2"></i> Crear Proveedor
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="proveedoresTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>Proveedor</th>
                                            <th>Contacto</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th style="width: 120px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="proveedoresTableBody">
                                        @foreach($proveedores as $proveedor)
                                            <!-- NUEVO: agregamos data-id -->
                                            <tr id="proveedor-row-{{ $proveedor->id }}" data-id="{{ $proveedor->id }}">
                                                <td>#{{ $proveedor->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 36px; height: 36px; font-size: 14px; font-weight: 600;">
                                                            {{ strtoupper(substr($proveedor->proveedor, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold small">{{ $proveedor->proveedor }}</div>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-building me-1"></i>
                                                                {{ $proveedor->ciudad ?? 'Sin ciudad' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <small>{{ $proveedor->asesor ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $proveedor->correo ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <small>{{ $proveedor->telefono_contacto ?? 'N/A' }}</small>
                                                </td>

                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('proveedores.edit', $proveedor->id) }}" 
                                                           class="btn btn-outline-primary" 
                                                           title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm btn-delete-proveedor" 
                                                                data-id="{{ $proveedor->id }}" 
                                                                data-name="{{ $proveedor->proveedor }}"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
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
                                <i class="fas fa-truck me-1"></i>
                                Total: <strong id="totalProveedores">{{ $proveedores->total() }}</strong> proveedores
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Última actualización: {{ now()->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Paginación -->
                @if(!$proveedores->isEmpty())
                    <div class="mt-4" id="paginationContainer">
                        {{ $proveedores->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de eliminar al proveedor <strong id="deleteModalName"></strong>?
                    <p class="text-danger small mt-2">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar definitivamente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast para notificaciones -->
    <div id="customToast" class="custom-toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex align-items-center">
            <i id="customToastIcon" class="fas fa-check-circle text-success fs-5 me-2"></i>
            <span id="customToastMessage"></span>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .rounded-4 { border-radius: 1rem !important; }
        .table > :not(caption) > * > * { padding: 0.75rem 0.75rem; }
        .btn-group .btn { transition: all 0.2s ease; padding: 0.25rem 0.5rem; }
        .btn-group .btn:hover { transform: translateY(-2px); }
        .card { animation: fadeInUp 0.5s ease; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dark-mode .card { background: #1a1a2e !important; color: #e2e8f0 !important; }
        .dark-mode .card-header { background: linear-gradient(135deg, #1e293b, #0f172a) !important; }
        .dark-mode .card-footer { background: #0f172a !important; border-color: #2d2d44 !important; }
        .dark-mode .table-light { background: #2d2d44 !important; }
        .dark-mode .table-light th { color: #e2e8f0 !important; }
        .dark-mode .table td { color: #e2e8f0 !important; border-color: #2d2d44 !important; }
        .dark-mode .text-muted { color: #94a3b8 !important; }
        .dark-mode .input-group-text { background: #2d2d44 !important; border-color: #3d3d54 !important; color: #94a3b8 !important; }
        .dark-mode .form-control { background: #2d2d44 !important; color: #e2e8f0 !important; border-color: #3d3d54 !important; }
        .dark-mode .btn-light { background: #2d2d44 !important; color: #e2e8f0 !important; border-color: #3d3d54 !important; }
        .dark-mode .btn-light:hover { background: #3d3d54 !important; }

        /* NUEVO: cursor pointer en filas */
        #proveedoresTable tbody tr {
            cursor: pointer;
            transition: background-color 0.15s ease;
        }
        #proveedoresTable tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .custom-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #fff;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border-left: 5px solid #28a745;
            z-index: 9999;
            transform: translateY(80px);
            opacity: 0;
            transition: all 0.3s ease;
            max-width: 400px;
        }
        .custom-toast.show {
            transform: translateY(0);
            opacity: 1;
        }
        .custom-toast.error { border-left-color: #dc3545; }
        .custom-toast.info { border-left-color: #0dcaf0; }
        .custom-toast.warning { border-left-color: #ffc107; }
    </style>

    <script>
        // Función de búsqueda con actualización de tabla y paginación
        function searchProveedores(query) {
            const queryValue = query.trim();
            if (queryValue.length < 2) {
                fetchProveedores('');
                return;
            }
            fetchProveedores(queryValue);
        }

        function fetchProveedores(query = '') {
            const url = query ? `/proveedores/search?q=${encodeURIComponent(query)}` : '/proveedores?ajax=1';

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('proveedoresTableBody').innerHTML = data.html;
                    const totalSpan = document.getElementById('totalProveedores');
                    if (totalSpan) totalSpan.textContent = data.total;
                    const pagContainer = document.getElementById('paginationContainer');
                    if (pagContainer) {
                        pagContainer.innerHTML = data.pagination || '';
                    }
                    // Reasignar eventos a los botones de eliminar (los nuevos)
                    assignDeleteEvents();
                }
            })
            .catch(error => console.error('Error en búsqueda:', error));
        }

        function assignDeleteEvents() {
            document.querySelectorAll('.btn-delete-proveedor').forEach(button => {
                button.removeEventListener('click', handleDeleteClick);
                button.addEventListener('click', handleDeleteClick);
            });
        }

        function handleDeleteClick(e) {
            const button = e.currentTarget;
            const id = button.dataset.id;
            const name = button.dataset.name;

            document.getElementById('deleteModalName').textContent = name;
            const form = document.getElementById('deleteForm');
            form.action = `/proveedores/${id}`;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

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
            
            toastIcon.className = 'fas ' + (icons[type] || icons.success) + ' fs-5 me-2';
            toastMessage.textContent = message;
            toast.className = 'custom-toast show ' + type;
            
            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Asignar eventos iniciales a los botones de eliminar
            assignDeleteEvents();

            // NUEVO: Delegación de clic en la tabla para redirigir a show
            document.getElementById('proveedoresTable').addEventListener('click', function(e) {
                // Si el clic fue sobre un botón o un enlace, no hacemos nada
                if (e.target.closest('.btn') || e.target.closest('a')) {
                    return;
                }

                // Buscar la fila más cercana (tr)
                const row = e.target.closest('tr');
                if (!row) return;

                // Obtener el id del proveedor desde data-id o desde el id de la fila
                const id = row.dataset.id || row.id.replace('proveedor-row-', '');
                if (id) {
                    window.location.href = `/proveedores/${id}`;
                }
            });

            // Manejar el submit del formulario de eliminación (igual que antes)
            document.getElementById('deleteForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const url = form.action;
                const method = form.querySelector('input[name="_method"]').value;
                const token = form.querySelector('input[name="_token"]').value;

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                    modal.hide();

                    if (data.success) {
                        showToast(data.message, 'success');

                        const row = document.getElementById(`proveedor-row-${data.id}`);
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-20px)';
                            setTimeout(() => row.remove(), 300);
                        }

                        const totalSpan = document.getElementById('totalProveedores');
                        if (totalSpan) {
                            let current = parseInt(totalSpan.textContent);
                            if (!isNaN(current) && current > 0) {
                                totalSpan.textContent = current - 1;
                            }
                        }
                    } else {
                        showToast(data.message || 'Error al eliminar', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error de red o servidor', 'error');
                });
            });

            console.log('✅ Gestión de proveedores cargada correctamente');
        });
    </script>
</x-layout>