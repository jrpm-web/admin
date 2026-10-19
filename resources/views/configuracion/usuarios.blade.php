<x-layout>
    <x-slot:title>VRZ-Usuarios| Sistema de Gestión</x-slot:title>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white p-4 border-0">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3">
                            
                            <div>
                                <h4 class="mb-0 fw-bold">Gestión de Usuarios</h4>
                                <p class="opacity-75 mb-0 small">Administra los usuarios del sistema</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Buscador -->
                            <div class="input-group" style="max-width: 250px;">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       id="searchUser" 
                                       class="form-control border-start-0" 
                                       placeholder="Buscar usuario..."
                                       onkeyup="searchUsers(this.value)">
                            </div>
                            <button class="btn btn-light" onclick="openCreateModal()">
                                <i class="fas fa-user-plus me-1"></i>
                                Nuevo Usuario
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Cédula</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Último Acceso</th>
                                    <th style="width: 180px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                @forelse($users ?? [] as $user)
                                    <tr id="user-row-{{ $user->id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 36px; height: 36px; font-size: 13px; font-weight: 600;">
                                                    {{ $user->iniciales ?? strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small">{{ $user->nombre_completo ?? $user->name }}</div>
                                                    <div class="text-muted small">{{ $user->cargo ?? 'Sin cargo' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <small>{{ $user->email }}</small>
                                            @if($user->email_verified_at)
                                                <i class="fas fa-check-circle text-success ms-1" title="Verificado"></i>
                                            @endif
                                        </td>
                                        <td><small>{{ $user->cedula ?? 'N/A' }}</small></td>
                                        <td>
                                            <span class="badge bg-{{ $user->rol === 'admin' ? 'danger' : ($user->rol === 'tecnico' ? 'info' : ($user->rol === 'gerente' ? 'warning' : 'secondary')) }}">
                                                <i class="fas fa-{{ $user->rol === 'admin' ? 'user-shield' : ($user->rol === 'tecnico' ? 'user-cog' : 'user') }} me-1"></i>
                                                {{ ucfirst($user->rol ?? 'usuario') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $user->estado === 'activo' ? 'success' : ($user->estado === 'suspendido' ? 'warning' : 'danger') }}">
                                                <i class="fas fa-{{ $user->estado === 'activo' ? 'circle' : ($user->estado === 'suspendido' ? 'pause' : 'times') }} me-1" style="font-size: 8px;"></i>
                                                {{ ucfirst($user->estado ?? 'activo') }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $user->ultimo_acceso ? $user->ultimo_acceso->diffForHumans() : 'Nunca' }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($user->id !== auth()->id())
                                                    <button class="btn btn-outline-{{ $user->estado === 'activo' ? 'warning' : 'success' }}" 
                                                            onclick="toggleUserStatus({{ $user->id }})" 
                                                            title="{{ $user->estado === 'activo' ? 'Desactivar' : 'Activar' }}">
                                                        <i class="fas fa-{{ $user->estado === 'activo' ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                @endif
                                                <button class="btn btn-outline-primary" 
                                                        onclick="openEditModal({{ $user->id }})" 
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if($user->id !== auth()->id())
                                                    <button class="btn btn-outline-danger" 
                                                            onclick="deleteUser({{ $user->id }})" 
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="fas fa-users text-muted" style="font-size: 48px;"></i>
                                            <h5 class="mt-3 text-muted">No hay usuarios registrados</h5>
                                            <p class="text-muted">Haz clic en "Nuevo Usuario" para agregar el primero</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            Total: <strong>{{ count($users ?? []) }}</strong> usuarios
                        </small>
                        <small class="text-muted">
                            <i class="fas fa-user-check text-success me-1"></i>
                            Activos: <strong>{{ count($users->where('estado', 'activo') ?? []) }}</strong>
                            &nbsp;|&nbsp;
                            <i class="fas fa-user-slash text-danger me-1"></i>
                            Inactivos: <strong>{{ count($users->where('estado', 'inactivo') ?? []) }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL: CREAR/EDITAR USUARIO -->
<!-- ============================================ -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="userModalTitle">
                    <i class="fas fa-user-plus me-2"></i>
                    Nuevo Usuario
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm">
                @csrf
                <input type="hidden" id="user_id" name="user_id" value="">
                <input type="hidden" id="method" name="_method" value="POST">
                
                <div class="modal-body p-4">
                    <div id="formErrors" class="alert alert-danger d-none"></div>
                    
                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold required-field">
                                <i class="fas fa-user me-1 text-primary"></i>
                                Nombre *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="Nombre del usuario" 
                                   required>
                        </div>

                        <!-- Apellidos -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-tag me-1 text-primary"></i>
                                Apellidos
                            </label>
                            <input type="text" 
                                   id="apellidos" 
                                   name="apellidos" 
                                   class="form-control" 
                                   placeholder="Apellidos del usuario">
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold required-field">
                                <i class="fas fa-envelope me-1 text-primary"></i>
                                Email *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control" 
                                   placeholder="usuario@empresa.com" 
                                   required>
                        </div>

                        <!-- Cédula -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-id-card me-1 text-primary"></i>
                                Cédula/RIF
                            </label>
                            <input type="text" 
                                   id="cedula" 
                                   name="cedula" 
                                   class="form-control" 
                                   placeholder="V-12345678">
                        </div>

                        <!-- Teléfono -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-phone me-1 text-primary"></i>
                                Teléfono
                            </label>
                            <input type="text" 
                                   id="telefono" 
                                   name="telefono" 
                                   class="form-control" 
                                   placeholder="+58 412-1234567">
                        </div>

                        <!-- Cargo -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-briefcase me-1 text-primary"></i>
                                Cargo
                            </label>
                            <input type="text" 
                                   id="cargo" 
                                   name="cargo" 
                                   class="form-control" 
                                   placeholder="Ej: Gerente de TI">
                        </div>

                        <!-- Departamento -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-building me-1 text-primary"></i>
                                Departamento
                            </label>
                            <input type="text" 
                                   id="departamento" 
                                   name="departamento" 
                                   class="form-control" 
                                   placeholder="Ej: Tecnología">
                        </div>

                        <!-- Rol -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold required-field">
                                <i class="fas fa-user-tag me-1 text-primary"></i>
                                Rol *
                            </label>
                            <select id="rol" name="rol" class="form-select" required>
                                <option value="usuario">Usuario</option>
                                <option value="tecnico">Técnico</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="gerente">Gerente</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <!-- Contraseña (solo para nuevo usuario) -->
                        <div class="col-md-6" id="passwordFields">
                            <label class="form-label fw-semibold" id="passwordLabel">
                                <i class="fas fa-lock me-1 text-primary"></i>
                                Contraseña
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control" 
                                   placeholder="Mínimo 8 caracteres">
                            <small class="text-muted" id="passwordHelp">Obligatorio para nuevos usuarios</small>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="col-md-6" id="passwordConfirmFields">
                            <label class="form-label fw-semibold" id="passwordConfirmLabel">
                                <i class="fas fa-check-circle me-1 text-primary"></i>
                                Confirmar Contraseña
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   class="form-control" 
                                   placeholder="Confirmar contraseña">
                        </div>

                        <!-- Estado -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold required-field">
                                <i class="fas fa-circle me-1 text-primary"></i>
                                Estado *
                            </label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="suspendido">Suspendido</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-1"></i>
                        <span id="submitBtnText">Guardar Usuario</span>
                    </button>
                </div>
            </form>
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
    
    .required-field::after {
        content: " *";
        color: #dc3545;
        font-weight: bold;
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
    
    .form-control:focus, .form-select:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important;
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
    
    /* Estilos para modo oscuro */
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
    
    .dark-mode .modal-content {
        background: #1a1a2e !important;
        color: #e2e8f0 !important;
    }
    
    .dark-mode .modal-header {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
    }
    
    .dark-mode .modal-footer {
        border-color: #2d2d44 !important;
    }
    
    .dark-mode .form-control, .dark-mode .form-select {
        background: #2d2d44 !important;
        color: #e2e8f0 !important;
        border-color: #3d3d54 !important;
    }
    
    .dark-mode .form-control:focus, .dark-mode .form-select:focus {
        background: #2d2d44 !important;
        border-color: #667eea !important;
    }
    
    .dark-mode .input-group-text {
        background: #2d2d44 !important;
        border-color: #3d3d54 !important;
        color: #94a3b8 !important;
    }
    
    .dark-mode .text-muted {
        color: #94a3b8 !important;
    }
</style>

<script>
    // ============================================================
    // VARIABLES GLOBALES
    // ============================================================
    let isEditMode = false;
    let currentUserId = null;

    // ============================================================
    // ABRIR MODAL PARA NUEVO USUARIO
    // ============================================================
    function openCreateModal() {
        isEditMode = false;
        currentUserId = null;
        
        document.getElementById('userModalTitle').innerHTML = '<i class="fas fa-user-plus me-2"></i> Nuevo Usuario';
        document.getElementById('submitBtnText').textContent = 'Crear Usuario';
        document.getElementById('userForm').reset();
        document.getElementById('user_id').value = '';
        document.getElementById('method').value = 'POST';
        document.getElementById('formErrors').classList.add('d-none');
        
        // Mostrar campos de contraseña
        document.getElementById('passwordFields').style.display = 'block';
        document.getElementById('passwordConfirmFields').style.display = 'block';
        document.getElementById('password').required = true;
        document.getElementById('password_confirmation').required = true;
        document.getElementById('passwordHelp').textContent = 'Obligatorio para nuevos usuarios';
        document.getElementById('passwordLabel').classList.add('required-field');
        document.getElementById('passwordConfirmLabel').classList.add('required-field');
        
        new bootstrap.Modal(document.getElementById('userModal')).show();
    }

    // ============================================================
    // ABRIR MODAL PARA EDITAR USUARIO (CORREGIDO)
    // ============================================================
    function openEditModal(userId) {
    console.log('📝 Editando usuario ID:', userId);
    
    if (!userId) {
        showToast('❌ ID de usuario no válido', 'error');
        return;
    }
    
    isEditMode = true;
    currentUserId = userId;
    
    // Mostrar loading
    document.getElementById('userModalTitle').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Cargando...';
    
    // URL correcta
    const url = `/usuarios/${userId}/edit`;
    console.log('🌐 URL:', url);
    
    // Cargar datos del usuario
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        console.log('📥 Respuesta recibida:', response.status, response.statusText);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📦 Datos recibidos:', data);
        console.log('📦 Tipo de data:', typeof data);
        console.log('📦 data.success:', data.success);
        console.log('📦 data.user:', data.user);
        
        if (data.success && data.user) {
            const user = data.user;
            
            console.log('👤 Usuario encontrado:', user);
            console.log('📧 Email:', user.email);
            console.log('📛 Nombre:', user.name);
            console.log('🆔 ID:', user.id);
            
            // Llenar el modal
            document.getElementById('userModalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i> Editar Usuario';
            document.getElementById('submitBtnText').textContent = 'Actualizar Usuario';
            document.getElementById('user_id').value = user.id;
            document.getElementById('method').value = 'PUT';
            
            // Llenar campos
            document.getElementById('name').value = user.name || '';
            document.getElementById('apellidos').value = user.apellidos || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('cedula').value = user.cedula || '';
            document.getElementById('telefono').value = user.telefono || '';
            document.getElementById('cargo').value = user.cargo || '';
            document.getElementById('departamento').value = user.departamento || '';
            document.getElementById('rol').value = user.rol || 'usuario';
            document.getElementById('estado').value = user.estado || 'activo';
            
            // Configurar contraseña (opcional)
            document.getElementById('passwordFields').style.display = 'block';
            document.getElementById('passwordConfirmFields').style.display = 'block';
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            document.getElementById('passwordHelp').textContent = 'Dejar en blanco para no cambiar';
            document.getElementById('passwordLabel').classList.remove('required-field');
            document.getElementById('passwordConfirmLabel').classList.remove('required-field');
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
            
            document.getElementById('formErrors').classList.add('d-none');
            
            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('userModal'));
            modal.show();
        } else {
            showToast('❌ Error: ' + (data.message || 'Usuario no encontrado'), 'error');
            document.getElementById('userModalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i> Editar Usuario';
        }
    })
    .catch(error => {
        console.error('❌ Error detallado:', error);
        showToast('❌ Error al cargar los datos del usuario. Revisa la consola.', 'error');
        document.getElementById('userModalTitle').innerHTML = '<i class="fas fa-user-edit me-2"></i> Editar Usuario';
    });
}

    // ============================================================
    // ENVIAR FORMULARIO (AJAX) - CORREGIDO
    // ============================================================
    document.getElementById('userForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const method = document.getElementById('method').value;
        const userId = document.getElementById('user_id').value;
        
        let url = '/usuarios';
        if (method === 'PUT' && userId) {
            url = `/usuarios/${userId}`;
            formData.append('_method', 'PUT');
        }
        
        // Deshabilitar botón
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';
        submitBtn.disabled = true;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if (data.success) {
                // Cerrar modal
                bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                showToast(data.message, 'success');
                
                // Recargar tabla
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                // Mostrar errores
                const errorsDiv = document.getElementById('formErrors');
                errorsDiv.classList.remove('d-none');
                let errorHtml = '<ul class="mb-0">';
                Object.values(data.errors || {}).forEach(error => {
                    if (Array.isArray(error)) {
                        error.forEach(msg => {
                            errorHtml += `<li>${msg}</li>`;
                        });
                    } else {
                        errorHtml += `<li>${error}</li>`;
                    }
                });
                errorHtml += '</ul>';
                errorsDiv.innerHTML = errorHtml;
            }
        })
        .catch(error => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            showToast('❌ Error al procesar la solicitud', 'error');
            console.error('Error:', error);
        });
    });

    // ============================================================
    // CAMBIAR ESTADO DEL USUARIO
    // ============================================================
    function toggleUserStatus(userId) {
        if (!confirm('¿Estás seguro de cambiar el estado de este usuario?')) return;
        
        fetch(`/usuarios/${userId}/toggle-status`, {
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
            }
        })
        .catch(error => {
            showToast('❌ Error al cambiar el estado', 'error');
            console.error('Error:', error);
        });
    }

    // ============================================================
    // ELIMINAR USUARIO
    // ============================================================
    function deleteUser(userId) {
        if (!confirm('⚠️ ¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')) return;
        
        fetch(`/usuarios/${userId}`, {
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
                document.getElementById(`user-row-${userId}`).remove();
            }
        })
        .catch(error => {
            showToast('❌ Error al eliminar el usuario', 'error');
            console.error('Error:', error);
        });
    }

    // ============================================================
    // BUSCAR USUARIOS
    // ============================================================
    function searchUsers(query) {
        if (query.length < 2) {
            document.querySelectorAll('#usersTableBody tr').forEach(row => {
                row.style.display = '';
            });
            return;
        }
        
        fetch(`/usuarios/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const rows = document.querySelectorAll('#usersTableBody tr');
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
    // TOAST
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
        console.log('✅ Gestión de usuarios cargada correctamente');
        console.log('👤 Usuarios totales:', document.querySelectorAll('#usersTableBody tr:not(:empty)').length);
    });
</script>
</x-layout>