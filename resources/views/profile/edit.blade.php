<x-layout>
    <x-slot:title>VRZ-CATEGORIAS| Sistema de Gestión</x-slot:title>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('status'))
                <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Por favor, corrige los siguientes errores:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Card del Perfil -->
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white p-4 border-0">
                    <div class="d-flex align-items-center gap-3">
                        
                        <div>
                            <h4 class="mb-0 fw-bold">Mi Perfil</h4>
                            <p class="opacity-75 mb-0 small">Gestiona tu información personal</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <!-- ============================================ -->
                    <!-- SECCIÓN 1: INFORMACIÓN DEL PERFIL -->
                    <!-- ============================================ -->
                    <div class="mb-5">
                        <h5 class="border-bottom pb-3 text-secondary fw-bold">
                            <i class="fas fa-id-card me-2 text-primary"></i>
                            Información Personal
                        </h5>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <!-- Avatar -->
                                <div class="col-12 text-center mb-3">
                                    <div class="position-relative d-inline-block">
                                        <div class="bg-gradient-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 100px; height: 100px; font-size: 40px; font-weight: 600;">
                                            {{ Auth::user()->iniciales ?? strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0" 
                                                style="width: 32px; height: 32px;" onclick="document.getElementById('foto_input').click()">
                                            <i class="fas fa-camera"></i>
                                        </button>
                                        <input type="file" id="foto_input" name="foto" class="d-none" accept="image/*">
                                    </div>
                                    <h5 class="mt-3 fw-bold">{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</h5>
                                    <span class="badge bg-primary">{{ Auth::user()->rol ?? 'Usuario' }}</span>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i>
                                        {{ Auth::user()->estado ?? 'Activo' }}
                                    </small>
                                </div>

                                <!-- Nombre -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-user me-1 text-primary"></i>
                                        Nombre
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               name="name" 
                                               class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                               value="{{ old('name', Auth::user()->name) }}" 
                                               required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Apellidos -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user-tag me-1 text-primary"></i>
                                        Apellidos
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user-tag text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               name="apellidos" 
                                               class="form-control border-start-0 @error('apellidos') is-invalid @enderror" 
                                               value="{{ old('apellidos', Auth::user()->apellidos) }}">
                                        @error('apellidos')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        Correo Electrónico
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-secondary"></i>
                                        </span>
                                        <input type="email" 
                                               name="email" 
                                               class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                               value="{{ old('email', Auth::user()->email) }}" 
                                               required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Si cambias tu email, deberás verificarlo nuevamente
                                    </small>
                                </div>

                                <!-- Teléfono -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-phone me-1 text-primary"></i>
                                        Teléfono
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-phone text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               name="telefono" 
                                               class="form-control border-start-0 @error('telefono') is-invalid @enderror" 
                                               value="{{ old('telefono', Auth::user()->telefono) }}">
                                        @error('telefono')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Cédula -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-id-card me-1 text-primary"></i>
                                        Cédula / RIF
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-id-card text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               name="cedula" 
                                               class="form-control border-start-0 @error('cedula') is-invalid @enderror" 
                                               value="{{ old('cedula', Auth::user()->cedula) }}">
                                        @error('cedula')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- ============================================ -->
                            <!-- SECCIÓN 2: INFORMACIÓN LABORAL -->
                            <!-- ============================================ -->
                            <div class="row g-3">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-briefcase me-2 text-primary"></i>
                                    Información Laboral
                                </h5>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-briefcase me-1 text-primary"></i>
                                        Cargo
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-briefcase text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               name="cargo" 
                                               class="form-control border-start-0 @error('cargo') is-invalid @enderror" 
                                               value="{{ old('cargo', Auth::user()->cargo) }}">
                                        @error('cargo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-building me-1 text-primary"></i>
                                        Departamento
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-building text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               name="departamento" 
                                               class="form-control border-start-0 @error('departamento') is-invalid @enderror" 
                                               value="{{ old('departamento', Auth::user()->departamento) }}">
                                        @error('departamento')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- ============================================ -->
                            <!-- SECCIÓN 3: CAMBIAR CONTRASEÑA -->
                            <!-- ============================================ -->
                            <div class="row g-3">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-lock me-2 text-primary"></i>
                                    Cambiar Contraseña
                                    <small class="text-muted fw-normal">(opcional)</small>
                                </h5>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-key me-1 text-primary"></i>
                                        Nueva Contraseña
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-key text-secondary"></i>
                                        </span>
                                        <input type="password" 
                                               name="password" 
                                               class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                               placeholder="Dejar en blanco para no cambiar">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Mínimo 8 caracteres
                                    </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-check-circle me-1 text-primary"></i>
                                        Confirmar Contraseña
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-check-circle text-secondary"></i>
                                        </span>
                                        <input type="password" 
                                               name="password_confirmation" 
                                               class="form-control border-start-0" 
                                               placeholder="Confirmar nueva contraseña">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- ============================================ -->
                            <!-- SECCIÓN 4: BOTONES -->
                            <!-- ============================================ -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Datos protegidos con encriptación
                                    </small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Volver
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                        <i class="fas fa-save me-1"></i>
                                        Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ============================================ -->
                    <!-- SECCIÓN 5: ELIMINAR CUENTA -->
                    <!-- ============================================ -->
                    <div class="mt-5 pt-4 border-top">
                        <div class="alert alert-danger border-0 shadow-sm">
                            <div class="d-flex align-items-start gap-3">
                                <i class="fas fa-exclamation-triangle fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">⚠️ Zona Peligrosa</h6>
                                    <p class="mb-2 small">
                                        Eliminar tu cuenta es una acción irreversible. 
                                        Todos tus datos y configuraciones serán eliminados permanentemente.
                                    </p>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash me-1"></i>
                                        Eliminar Cuenta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL ELIMINAR CUENTA -->
<!-- ============================================ -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Eliminar Cuenta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-slash text-danger" style="font-size: 48px;"></i>
                    </div>
                    <p class="text-center text-muted">
                        ¿Estás seguro de que deseas eliminar tu cuenta?
                    </p>
                    <div class="alert alert-warning border-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Esta acción es irreversible.</strong> 
                        Todos tus datos, configuraciones y asignaciones serán eliminados permanentemente.
                    </div>
                    <div class="form-group mt-3">
                        <label for="delete_password" class="form-label fw-semibold">
                            <i class="fas fa-lock me-1"></i>
                            Ingresa tu contraseña para confirmar
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-lock text-secondary"></i>
                            </span>
                            <input type="password" 
                                   name="password" 
                                   id="delete_password"
                                   class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                   placeholder="••••••••" 
                                   required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Debes ingresar tu contraseña actual para confirmar
                        </small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Sí, eliminar mi cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* ============================================================
       ESTILOS DEL PERFIL
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
    
    .form-control:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.15);
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #6c757d;
        min-width: 42px;
        justify-content: center;
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
    
    .btn-outline-secondary:hover {
        background: #f8f9fa;
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
    
    /* Estilos para el modal */
    .modal-content {
        animation: modalFadeIn 0.3s ease;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
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
    
    .dark-mode .border-bottom {
        border-color: #2d2d44 !important;
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
    
    .dark-mode .input-group-text {
        background: #2d2d44 !important;
        border-color: #3d3d54 !important;
        color: #94a3b8 !important;
    }
    
    .dark-mode .text-secondary {
        color: #94a3b8 !important;
    }
    
    .dark-mode .text-muted {
        color: #94a3b8 !important;
    }
    
    .dark-mode .alert {
        background: #1a1a2e !important;
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
</style>

<script>
    // ============================================================
    // CONFIRMAR ELIMINACIÓN DE CUENTA
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.querySelector('#deleteAccountModal form');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                const confirmDelete = confirm('⚠️ ¿Estás seguro de eliminar tu cuenta? Esta acción no se puede deshacer.');
                if (!confirmDelete) {
                    e.preventDefault();
                }
            });
        }

        // ============================================================
        // PREVISUALIZACIÓN DE AVATAR
        // ============================================================
        const fotoInput = document.getElementById('foto_input');
        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const avatar = document.querySelector('.bg-gradient-primary.rounded-circle');
                        if (avatar) {
                            avatar.style.backgroundImage = `url(${event.target.result})`;
                            avatar.style.backgroundSize = 'cover';
                            avatar.style.backgroundPosition = 'center';
                            avatar.textContent = '';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // ============================================================
        // TOAST PARA NOTIFICACIONES
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

        console.log('✅ Perfil cargado correctamente');
        console.log('👤 Usuario:', '{{ Auth::user()->email }}');
    });
</script>
</x-layout>