<x-layout>
    <x-slot:title>VRZ-PROVEEDORES | Nuevo Proveedor</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h4 class="mb-0 fw-bold">Nuevo Proveedor</h4>
                                <p class="opacity-75 mb-0 small">Registra un nuevo proveedor en el sistema</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        {{-- Mensajes de error generales --}}
                        <div id="formErrors" class="alert alert-danger d-none" role="alert"></div>

                        <form id="proveedorForm">
                            @csrf

                            <div class="row g-4">
                                <!-- Nombre del Proveedor -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold  -field">
                                        <i class="fas fa-building me-1 text-primary"></i>
                                        Nombre del Proveedor
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-building text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0" 
                                               id="proveedor" 
                                               name="proveedor" 
                                               placeholder="Nombre del proveedor"
                                               value="{{ old('proveedor') }}"
                                               maxlength="150"
                                                
                                               oninput="updateCounter(this, 'proveedorCount', 150)">
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted"><i class="bi bi-asterisk text-danger"></i> Máximo 150 caracteres</small>
                                        <small class="text-muted"><span id="proveedorCount">0</span>/150</small>
                                    </div>
                                </div>

                                <!-- Nombre de Contacto -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user me-1 text-primary"></i>
                                        Nombre de Contacto
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0" 
                                               id="asesor" 
                                               name="asesor" 
                                               placeholder="Persona de contacto"
                                               value="{{ old('asesor') }}"
                                               maxlength="100">
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold  -field">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        Correo Electrónico
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-secondary"></i>
                                        </span>
                                        <input type="email" 
                                               class="form-control border-start-0" 
                                               id="correo" 
                                               name="correo" 
                                               placeholder="proveedor@empresa.com"
                                               value="{{ old('correo') }}"
                                               maxlength="100"
                                                >
                                    </div>
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
                                               class="form-control border-start-0" 
                                               id="telefono_contacto" 
                                               name="telefono_contacto" 
                                               placeholder="+58 212-555-1212"
                                               value="{{ old('telefono_contacto') }}"
                                               maxlength="20">
                                    </div>
                                </div>

                                <!-- Ciudad -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-city me-1 text-primary"></i>
                                        Ciudad
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-city text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0" 
                                               id="ciudad" 
                                               name="ciudad" 
                                               placeholder="Caracas"
                                               value="{{ old('ciudad') }}"
                                               maxlength="100">
                                    </div>
                                </div>

                                <!-- Categoría -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-tags me-1 text-primary"></i>
                                        Categoría
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-tags text-secondary"></i>
                                        </span>
                                        <select class="form-select border-start-0" 
                                                id="categoria_id" 
                                                name="categoria_id">
                                            <option value="">Sin categoría</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" 
                                                    {{ old('categoria_id', $proveedor->categoria_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nombre_categoria }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#crearCategoriaModal"
                                                title="Agregar nueva categoría">
                                            <i class="fas fa-plus-circle"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Clasifica al proveedor para una mejor organización
                                    </small>
                                </div>

                                <!-- Dirección -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                        Dirección
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-map-marker-alt text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0" 
                                               id="direccion" 
                                               name="direccion" 
                                               placeholder="Dirección completa del proveedor"
                                               value="{{ old('direccion') }}"
                                               maxlength="255">
                                    </div>
                                </div>

                                <!-- Información de Auditoría -->
                                <div class="col-12">
                                    <div class="alert alert-info border-0 bg-info bg-opacity-10 p-3 rounded-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="fas fa-shield-alt text-info fs-4"></i>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-info">Datos protegidos con encriptación</h6>
                                                <p class="small text-muted mb-0">
                                                    Este proveedor será registrado por: 
                                                    <strong>{{ Auth::user()->nombre_completo ?? Auth::user()->name }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Datos protegidos con encriptación
                                    </small>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Registrar Proveedor
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: Crear Categoría Rápida                                 -->
    <!-- ============================================================ -->
    <div class="modal fade" id="crearCategoriaModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form id="formCrearCategoriaRapida" action="{{ route('categorias.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-gradient-primary text-white border-0 rounded-top-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fas fa-plus-circle me-2"></i>
                            Nueva Categoría
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold  -field">
                                    <i class="fas fa-tag me-1 text-primary"></i>
                                    Nombre de la Categoría
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-tags text-secondary"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control border-start-0" 
                                           id="nombre_categoria" 
                                           name="nombre_categoria" 
                                           placeholder="Ej: Electrónica, Ropa, Alimentos..."
                                           value="{{ old('nombre_categoria') }}"
                                           maxlength="100"
                                            
                                           autofocus
                                           oninput="updateCounter(this, 'nombreCount', 100)">
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <div>
                                        <small class="text-muted">
                                            <i class="bi bi-asterisk text-danger"></i> Máximo 100 caracteres
                                        </small>
                                    </div>
                                    <small class="text-muted">
                                        <span id="nombreCount">0</span>/100
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-file-alt me-1 text-primary"></i>
                                    Descripción
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-file-alt text-secondary"></i>
                                    </span>
                                    <textarea class="form-control border-start-0" 
                                              id="descripcion" 
                                              name="descripcion" 
                                              rows="3"
                                              placeholder="Breve descripción de la categoría..."
                                              maxlength="255"
                                              oninput="updateCounter(this, 'descripcionCount', 255)">{{ old('descripcion') }}</textarea>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Máximo 255 caracteres</small>
                                    <small class="text-muted">
                                        <span id="descripcionCount">0</span>/255
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 justify-content-between">
                        <span class="text-muted small"><i class="fas fa-info-circle"></i> Campos con * son obligatorios</span>
                        <div>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" id="btnGuardarCategoriaRapida">
                                <i class="fas fa-save me-1"></i> Guardar Categoría
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL: Acciones rápidas después de crear proveedor           -->
    <!-- ============================================================ -->
    <div class="modal fade" id="quickActionsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-gradient-success text-white border-0 rounded-top-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-check-circle me-2"></i>
                        ¡Proveedor registrado!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Se llena dinámicamente desde JavaScript --}}
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cerrar
                    </button>
                    <a href="{{ route('proveedores.index') }}" class="btn btn-primary px-4">
                        <i class="fas fa-list me-1"></i> Ver Lista
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ESTILOS                                                      -->
    <!-- ============================================================ -->
    <style>
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-gradient-success { background: linear-gradient(135deg, #00b894 0%, #00cec9 100%); }
        .rounded-4 { border-radius: 1rem !important; }
        . -field::after { content: " *"; color: #dc3545; font-weight: bold; }
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            padding: 0.6rem 0.75rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
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
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important; }
        .btn-primary:disabled { opacity: 0.7; transform: none; }
        .card { animation: fadeInUp 0.5s ease; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .invalid-feedback { display: none; width: 100%; margin-top: 0.25rem; font-size: 0.875em; color: #dc3545; }
        .invalid-feedback.d-block { display: block !important; }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        .form-control.is-valid, .form-select.is-valid {
            border-color: #198754;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        .hover-card {
            transition: all 0.3s ease;
            border-radius: 1rem !important;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
            border-color: #667eea !important;
        }
        /* Modo oscuro (opcional) */
        .dark-mode .card { background: #1a1a2e !important; color: #e2e8f0 !important; }
        .dark-mode .card-header { background: linear-gradient(135deg, #1e293b, #0f172a) !important; }
        .dark-mode .form-control, .dark-mode .form-select {
            background: #2d2d44 !important;
            color: #e2e8f0 !important;
            border-color: #3d3d54 !important;
        }
        .dark-mode .input-group-text {
            background: #2d2d44 !important;
            border-color: #3d3d54 !important;
            color: #94a3b8 !important;
        }
        .dark-mode .text-muted { color: #94a3b8 !important; }
        .dark-mode .alert-info { background: #1a2d3d !important; border-color: #0d6efd !important; }
        .dark-mode .btn-outline-secondary { border-color: #3d3d54; color: #94a3b8; }
        .dark-mode .btn-outline-secondary:hover { background: #2d2d44; color: #e2e8f0; }
        .dark-mode .btn-light { background: #2d2d44; color: #e2e8f0; border-color: #3d3d54; }
        .dark-mode .btn-light:hover { background: #3d3d54; }
    </style>

    <!-- ============================================================ -->
    <!-- SCRIPTS                                                     -->
    <!-- ============================================================ -->
    <script>
        // Función global para contador de caracteres
        function updateCounter(input, counterId, maxLength) {
            const counter = document.getElementById(counterId);
            if (!counter) return;
            const currentLength = input.value.length;
            counter.textContent = currentLength;
            counter.style.color = currentLength > maxLength * 0.9 ? '#dc3545' : currentLength > maxLength * 0.7 ? '#f59e0b' : '#6c757d';
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar contadores
            document.querySelectorAll('input[maxlength], textarea[maxlength]').forEach(input => {
                const counterId = input.id + 'Count';
                const counter = document.getElementById(counterId);
                if (counter) counter.textContent = input.value.length;
            });

            // ============================================================
            // ENVÍO DEL FORMULARIO PRINCIPAL (PROVEEDOR) POR AJAX
            // ============================================================
            const form = document.getElementById('proveedorForm');
            const submitBtn = document.getElementById('submitBtn');
            const errorsContainer = document.getElementById('formErrors');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Limpiar errores anteriores
                clearErrors();
                errorsContainer.classList.add('d-none');
                errorsContainer.innerHTML = '';

                // Preloader en el botón
                const originalHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Registrando...
                `;

                try {
                    const formData = new FormData(form);
                    console.log(formData); // Depuración: Ver el contenido del FormData
                    const response = await fetch('{{ route("proveedores.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    // Verificar si la respuesta es JSON
                    const contentType = response.headers.get('content-type');
                    console.log('Content-Type de la respuesta:', contentType); // Depuración: Ver el tipo de contenido
                    if (!contentType || !contentType.includes('application/json')) {
                        const text = await response.text();
                        console.log('Content-Type de la respuesta:', text); // Depuración: Ver el tipo de contenido
                        console.error('Respuesta no JSON:', text);
                        throw new Error('El servidor devolvió una respuesta inesperada. Revisa los logs.');
                    }

                    const data = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && data.errors) {
                            showValidationErrors(data.errors);
                            showError('Por favor, corrige los errores en el formulario.');
                        } else {
                            throw new Error(data.message || 'Ocurrió un error al registrar el proveedor.');
                        }
                        return;
                    }

                    // Éxito: mostrar modal de acciones rápidas
                    showQuickActions(data.proveedor);

                    // Opcional: resetear formulario
                    // form.reset();

                } catch (error) {
                    showError(error.message || 'Error de red. Intenta nuevamente.');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                }
            });

            // ============================================================
            // FUNCIONES AUXILIARES PARA ERRORES Y MODAL
            // ============================================================

            function clearErrors() {
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback.d-block').forEach(el => el.remove());
            }

            function showValidationErrors(errors) {
                for (const [field, messages] of Object.entries(errors)) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback d-block';
                        feedback.innerHTML = `<i class="bi bi-exclamation-circle me-1"></i>${messages.join(', ')}`;
                        input.parentNode.appendChild(feedback);
                    }
                }
            }

            function showError(message) {
                errorsContainer.classList.remove('d-none');
                errorsContainer.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i> ${message}`;
                errorsContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            function showQuickActions(proveedor) {
                const modalElement = document.getElementById('quickActionsModal');
                const modalBody = modalElement.querySelector('.modal-body');
                const modalTitle = modalElement.querySelector('.modal-title');

                // Generar URL para ver detalles con un marcador de posición numérico
                const showUrl = '{{ route("proveedores.show", ["proveedore" => 999999]) }}'.replace('999999', proveedor.id);

                modalTitle.innerHTML = `<i class="fas fa-check-circle me-2"></i> ¡Proveedor registrado!`;
                modalBody.innerHTML = `
                    <div class="text-center mb-4">
                        <div class="display-1 text-success mb-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4 class="fw-bold">${proveedor.nombre || proveedor.proveedor || 'Proveedor'}</h4>
                        <p class="text-muted">El proveedor se ha creado exitosamente. ¿Qué deseas hacer ahora?</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('productos.create') }}?proveedor_id=${proveedor.id}"
                               class="card h-100 border-0 shadow-sm text-decoration-none hover-card">
                                <div class="card-body text-center py-4">
                                    <div class="display-4 text-primary mb-3">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Crear Producto</h5>
                                    <p class="text-muted small mb-0">Registra un nuevo producto para este proveedor</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('facturas.create') }}?proveedor_id=${proveedor.id}"
                               class="card h-100 border-0 shadow-sm text-decoration-none hover-card">
                                <div class="card-body text-center py-4">
                                    <div class="display-4 text-warning mb-3">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Crear Factura</h5>
                                    <p class="text-muted small mb-0">Registra una nueva factura para este proveedor</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 offset-md-3">
                            <a href="${showUrl}"
                               class="card h-100 border-0 shadow-sm text-decoration-none hover-card">
                                <div class="card-body text-center py-4">
                                    <div class="display-4 text-info mb-3">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Ver Detalles</h5>
                                    <p class="text-muted small mb-0">Consultar la información completa del proveedor</p>
                                </div>
                            </a>
                        </div>
                    </div>
                `;

                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: 'static',
                    keyboard: false
                });
                modal.show();
            }

            // ============================================================
            // CREACIÓN RÁPIDA DE CATEGORÍA (MODAL)
            // ============================================================
            const formRapido = document.getElementById('formCrearCategoriaRapida');
            if (formRapido) {
                formRapido.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    formData.append('_desde_proveedor', '1');

                    const submitBtn = document.getElementById('btnGuardarCategoriaRapida');
                    const originalHtml = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById('crearCategoriaModal'));
                            modal.hide();

                            const selectCategoria = document.getElementById('categoria_id');
                            const newOption = document.createElement('option');
                            newOption.value = data.categoria_id;
                            newOption.textContent = data.nombre_categoria;
                            selectCategoria.appendChild(newOption);
                            selectCategoria.value = data.categoria_id;

                            alert('Categoría creada exitosamente y seleccionada');
                        } else {
                            let errorMsg = 'Error al crear categoría: ';
                            if (data.errors) {
                                const firstError = Object.values(data.errors)[0];
                                errorMsg += firstError.join(', ');
                            } else {
                                errorMsg += data.message || 'Error desconocido';
                            }
                            alert(errorMsg);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error de red al crear categoría');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalHtml;
                    });
                });
            }
        });
    </script>
</x-layout>