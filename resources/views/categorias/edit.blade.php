{{-- resources/views/categorias/edit.blade.php --}}

<x-layout>
    <x-slot:title>VRZ-CATEGORIAS | Editar Categoría</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            
                            <div>
                                <h4 class="mb-0 fw-bold">Editar Categoría</h4>
                                <p class="opacity-75 mb-0 small">Actualiza la información de la categoría</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        <!-- Alertas de errores -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="fas fa-exclamation-triangle fs-4"></i>
                                    <div>
                                        <strong class="d-block">Por favor, corrige los siguientes errores:</strong>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('categorias.update', $categoria) }}" method="POST" id="categoriaForm" novalidate>
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-4">
                                <!-- Nombre de la Categoría -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-tag me-1 text-primary"></i>
                                        Nombre de la Categoría
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-tags text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0 @error('nombre_categoria') is-invalid @enderror" 
                                               id="nombre_categoria" 
                                               name="nombre_categoria" 
                                               placeholder="Ej: Electrónica, Ropa, Alimentos..."
                                               value="{{ old('nombre_categoria', $categoria->nombre_categoria) }}"
                                               maxlength="100"
                                               required
                                               autofocus
                                               oninput="updateCounter(this, 'nombreCount', 100)">
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <div>
                                            <small class="text-muted">
                                                <i class="bi bi-asterisk text-danger"></i> Máximo 100 caracteres
                                            </small>
                                            <br>
                                            <small class="text-danger error-message" id="nombreError" style="display: none;">
                                                <i class="bi bi-exclamation-circle"></i> Este campo es obligatorio
                                            </small>
                                        </div>
                                        <small class="text-muted">
                                            <span id="nombreCount">{{ strlen($categoria->nombre_categoria) }}</span>/100
                                        </small>
                                    </div>
                                    @error('nombre_categoria')
                                        <div class="invalid-feedback d-block">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Descripción de la Categoría -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-file-alt me-1 text-primary"></i>
                                        Descripción
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-file-alt text-secondary"></i>
                                        </span>
                                        <textarea class="form-control border-start-0 @error('descripcion') is-invalid @enderror" 
                                                  id="descripcion" 
                                                  name="descripcion" 
                                                  rows="3"
                                                  placeholder="Breve descripción de la categoría..."
                                                  maxlength="255"
                                                  oninput="updateCounter(this, 'descripcionCount', 255)">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">Máximo 255 caracteres</small>
                                        <small class="text-muted">
                                            <span id="descripcionCount">{{ strlen($categoria->descripcion ?? '') }}</span>/255
                                        </small>
                                    </div>
                                    @error('descripcion')
                                        <div class="invalid-feedback d-block">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Estado -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-circle me-1 text-primary"></i>
                                        Estado
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-circle text-secondary"></i>
                                        </span>
                                        <select name="activo" class="form-select border-start-0 @error('activo') is-invalid @enderror">
                                            <option value="1" {{ $categoria->activo ? 'selected' : '' }}>Activa</option>
                                            <option value="0" {{ !$categoria->activo ? 'selected' : '' }}>Inactiva</option>
                                        </select>
                                    </div>
                                    @error('activo')
                                        <div class="invalid-feedback d-block">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Información de Auditoría -->
                                <div class="col-12">
                                    <div class="alert alert-info border-0 bg-info bg-opacity-10 p-3 rounded-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="fas fa-shield-alt text-info fs-4"></i>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-info">Información de Auditoría</h6>
                                                <p class="small text-muted mb-0">
                                                    <i class="fas fa-user me-1"></i>
                                                    Creada por: <strong>{{ $categoria->creador_nombre ?? 'Sistema' }}</strong>
                                                    @if($categoria->created_at)
                                                        el {{ $categoria->created_at->format('d/m/Y H:i') }}
                                                    @endif
                                                </p>
                                                @if($categoria->actualizador)
                                                    <p class="small text-muted mb-0">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Última actualización por: <strong>{{ $categoria->actualizador_nombre }}</strong>
                                                        el {{ $categoria->updated_at->format('d/m/Y H:i') }}
                                                    </p>
                                                @endif
                                                <p class="small text-muted mb-0">
                                                    <i class="fas fa-boxes me-1"></i>
                                                    Productos asociados: <strong>{{ $categoria->productos->count() }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Datos protegidos con encriptación
                                    </small>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Actualizar Categoría
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ============================================================
           ESTILOS DEL FORMULARIO - MISMO ESTILO QUE USUARIOS
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
        
        .form-control {
            border: 1px solid #dee2e6;
            padding: 0.6rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.15);
        }
        
        .form-select {
            border: 1px solid #dee2e6;
            padding: 0.6rem 0.75rem;
            transition: all 0.3s ease;
        }
        
        .form-select:focus {
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
        
        .btn-primary:disabled {
            opacity: 0.7;
            transform: none;
        }
        
        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .error-message {
            font-size: 0.875rem;
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
        
        .dark-mode .input-group-text {
            background: #2d2d44 !important;
            border-color: #3d3d54 !important;
            color: #94a3b8 !important;
        }
        
        .dark-mode .alert-info {
            background: #1a2d3d !important;
        }
        
        .dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }
            
            .d-flex.flex-wrap.gap-2 {
                flex-direction: column;
                width: 100%;
            }
            
            .d-flex.flex-wrap.gap-2 .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <script>
        // ============================================================
        // ACTUALIZAR CONTADOR DE CARACTERES
        // ============================================================
        function updateCounter(input, counterId, maxLength) {
            const counter = document.getElementById(counterId);
            if (!counter) return;
            
            const currentLength = input.value.length;
            counter.textContent = currentLength;
            
            // Cambiar color según el límite
            if (currentLength > maxLength * 0.9) {
                counter.style.color = '#dc3545';
            } else if (currentLength > maxLength * 0.7) {
                counter.style.color = '#f59e0b';
            } else {
                counter.style.color = '#6c757d';
            }
        }

        // ============================================================
        // VALIDAR CAMPO OBLIGATORIO
        // ============================================================
        function validateField(input) {
            const errorMessage = document.getElementById(input.id + 'Error');
            const value = input.value.trim();
            
            input.classList.remove('is-valid', 'is-invalid', 'shake');
            
            if (value === '') {
                input.classList.add('is-invalid');
                if (errorMessage) {
                    errorMessage.style.display = 'block';
                }
                return false;
            } else {
                input.classList.add('is-valid');
                if (errorMessage) {
                    errorMessage.style.display = 'none';
                }
                return true;
            }
        }

        // ============================================================
        // INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Validación en tiempo real para el nombre
            const nombreInput = document.getElementById('nombre_categoria');
            if (nombreInput) {
                nombreInput.addEventListener('blur', function() {
                    validateField(this);
                });
                
                nombreInput.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                        const errorMessage = document.getElementById('nombreError');
                        if (errorMessage) {
                            errorMessage.style.display = 'none';
                        }
                    }
                });
            }

            // ============================================================
            // ENVÍO DEL FORMULARIO
            // ============================================================
            const form = document.getElementById('categoriaForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Validar campos requeridos
                    const nombreInput = document.getElementById('nombre_categoria');
                    let isValid = true;
                    
                    if (!validateField(nombreInput)) {
                        isValid = false;
                        nombreInput.classList.add('shake');
                        setTimeout(() => nombreInput.classList.remove('shake'), 500);
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        nombreInput.focus();
                        nombreInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return;
                    }
                    
                    // Deshabilitar botón para evitar doble envío
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Actualizando...';
                    }
                });
            }

            console.log('✅ Formulario de edición de categorías cargado correctamente');
            console.log('📝 Categoría:', '{{ $categoria->nombre_categoria }}');
        });
    </script>
</x-layout>