<x-layout>
    <x-slot:title>VRZ-PROVEEDORES | Editar Proveedor</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h4 class="mb-0 fw-bold">Editar Proveedor</h4>
                                <p class="opacity-75 mb-0 small">Actualiza la información del proveedor</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-lg-5">
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

                        <form action="{{ route('proveedores.update', $proveedor->id) }}" method="POST" id="proveedorForm" novalidate>
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-4">
                                <!-- Nombre del Proveedor -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-building me-1 text-primary"></i>
                                        Nombre del Proveedor
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-building text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control border-start-0 @error('proveedor') is-invalid @enderror" 
                                               id="proveedor" 
                                               name="proveedor" 
                                               placeholder="Nombre del proveedor"
                                               value="{{ old('proveedor', $proveedor->proveedor) }}"
                                               maxlength="150"
                                               required
                                               oninput="updateCounter(this, 'proveedorCount', 150)">
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted"><i class="bi bi-asterisk text-danger"></i> Máximo 150 caracteres</small>
                                        <small class="text-muted"><span id="proveedorCount">{{ strlen($proveedor->proveedor) }}</span>/150</small>
                                    </div>
                                    @error('proveedor')
                                        <div class="invalid-feedback d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
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
                                               class="form-control border-start-0 @error('asesor') is-invalid @enderror" 
                                               id="asesor" 
                                               name="asesor" 
                                               placeholder="Persona de contacto"
                                               value="{{ old('asesor', $proveedor->asesor) }}"
                                               maxlength="100">
                                    </div>
                                    @error('asesor')
                                        <div class="invalid-feedback d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
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
                                               class="form-control border-start-0 @error('correo') is-invalid @enderror" 
                                               id="correo" 
                                               name="correo" 
                                               placeholder="proveedor@empresa.com"
                                               value="{{ old('correo', $proveedor->correo) }}"
                                               maxlength="100"
                                               required>
                                    </div>
                                    @error('correo')
                                        <div class="invalid-feedback d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
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
                                               class="form-control border-start-0 @error('telefono_contacto') is-invalid @enderror" 
                                               id="telefono_contacto" 
                                               name="telefono_contacto" 
                                               placeholder="+58 212-555-1212"
                                               value="{{ old('telefono_contacto', $proveedor->telefono_contacto) }}"
                                               maxlength="20">
                                    </div>
                                    @error('telefono_contacto')
                                        <div class="invalid-feedback d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- RIF -->
                                

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
                                               class="form-control border-start-0 @error('ciudad') is-invalid @enderror" 
                                               id="ciudad" 
                                               name="ciudad" 
                                               placeholder="Caracas"
                                               value="{{ old('ciudad', $proveedor->ciudad) }}"
                                               maxlength="100">
                                    </div>
                                    @error('ciudad')
                                        <div class="invalid-feedback d-block"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Dirección -->
                                
                                <!-- Información de Auditoría -->
                                <div class="col-12">
                                    <div class="alert alert-info border-0 bg-info bg-opacity-10 p-3 rounded-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="fas fa-shield-alt text-info fs-4"></i>
                                            <div>
                                                <h6 class="fw-bold mb-0 text-info">Información de Auditoría</h6>
                                                <p class="small text-muted mb-0">
                                                    <i class="fas fa-user me-1"></i>
                                                    Creado por: <strong>{{ $proveedor->creador->name ?? 'Sistema' }}</strong>
                                                    @if($proveedor->created_at)
                                                        el {{ $proveedor->created_at->format('d/m/Y H:i') }}
                                                    @endif
                                                </p>
                                                @if($proveedor->updated_at)
                                                    <p class="small text-muted mb-0">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Última actualización: {{ $proveedor->updated_at->format('d/m/Y H:i') }}
                                                    </p>
                                                @endif
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
                                        <i class="fas fa-save me-1"></i> Actualizar Proveedor
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
        .bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .rounded-4 { border-radius: 1rem !important; }
        .required-field::after { content: " *"; color: #dc3545; font-weight: bold; }
        .form-control { border: 1px solid #dee2e6; padding: 0.6rem 0.75rem; transition: all 0.3s ease; }
        .form-control:focus { border-color: #764ba2; box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.15); }
        .input-group-text { background-color: #f8f9fa; border: 1px solid #dee2e6; color: #6c757d; min-width: 42px; justify-content: center; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important; }
        .card { animation: fadeInUp 0.5s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        function updateCounter(input, counterId, maxLength) {
            const counter = document.getElementById(counterId);
            if (!counter) return;
            const currentLength = input.value.length;
            counter.textContent = currentLength;
            counter.style.color = currentLength > maxLength * 0.9 ? '#dc3545' : currentLength > maxLength * 0.7 ? '#f59e0b' : '#6c757d';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('proveedorForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form) {
                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Actualizando...';
                });
            }

            // Inicializar contadores
            document.querySelectorAll('input[maxlength]').forEach(input => {
                const counterId = input.id + 'Count';
                const counter = document.getElementById(counterId);
                if (counter) counter.textContent = input.value.length;
            });
        });
    </script>
</x-layout>