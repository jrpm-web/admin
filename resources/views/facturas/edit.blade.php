<x-layout>
    <x-slot:title>RZN-OFFERINGS | Editar Factura</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                
                                <div>
                                    <h4 class="mb-0 fw-bold">Editar Factura</h4>
                                    <p class="opacity-75 mb-0 small">
                                        Actualiza la información de la factura 
                                        <span class="fw-bold">#{{ $factura->numero_factura }}</span>
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('facturas.index') }}" class="btn btn-light btn-sm px-4 py-2">
                                <i class="fas fa-arrow-left me-1"></i> Volver
                            </a>
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

                        <form action="{{ route('facturas.update', $factura->id) }}" method="POST" id="facturaForm">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Número de Factura -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-hashtag me-1 text-primary"></i>
                                        Número de Factura
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-hashtag text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               name="numero_factura" 
                                               class="form-control border-start-0 @error('numero_factura') is-invalid @enderror" 
                                               placeholder="FAC-001"
                                               value="{{ old('numero_factura', $factura->numero_factura) }}"
                                               required
                                               maxlength="50">
                                    </div>
                                    @error('numero_factura')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Proveedor -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-building me-1 text-primary"></i>
                                        Proveedor
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-building text-secondary"></i>
                                        </span>
                                        <select name="proveedor_id" class="form-select border-start-0 @error('proveedor_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un proveedor...</option>
                                            @foreach($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}" 
                                                    {{ old('proveedor_id', $factura->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->proveedor }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('proveedor_id')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Monto -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-dollar-sign me-1 text-primary"></i>
                                        Monto
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-dollar-sign text-secondary"></i>
                                        </span>
                                        <input type="number" 
                                               name="monto" 
                                               id="monto"
                                               class="form-control border-start-0 @error('monto') is-invalid @enderror" 
                                               placeholder="0.00"
                                               value="{{ old('monto', $factura->monto) }}"
                                               step="0.01"
                                               min="0.01"
                                               required
                                               oninput="calcularUSD(this.value)">
                                    </div>
                                    @error('monto')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Moneda -->
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-coins me-1 text-primary"></i>
                                        Moneda
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-coins text-secondary"></i>
                                        </span>
                                        <select name="moneda" 
                                                id="moneda" 
                                                class="form-select border-start-0 @error('moneda') is-invalid @enderror" 
                                                required 
                                                onchange="toggleTasa(this.value); calcularUSD(document.getElementById('monto').value)">
                                            @foreach($monedas as $key => $label)
                                                <option value="{{ $key }}" 
                                                    {{ old('moneda', $factura->moneda) == $key ? 'selected' : '' }}>
                                                    {{ $label }} ({{ $key }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('moneda')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tasa de Referencia -->
                                <div class="col-md-4" id="tasaContainer">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-exchange-alt me-1 text-primary"></i>
                                        Tasa de Referencia (Bs)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-exchange-alt text-secondary"></i>
                                        </span>
                                        <input type="number" 
                                               name="tasa_referencia" 
                                               id="tasa_referencia"
                                               class="form-control border-start-0 @error('tasa_referencia') is-invalid @enderror" 
                                               placeholder="Tasa del día"
                                               value="{{ old('tasa_referencia', $factura->tasa_referencia) }}"
                                               step="0.01"
                                               min="0"
                                               oninput="calcularUSD(document.getElementById('monto').value)">
                                    </div>
                                    @error('tasa_referencia')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Obligatorio para montos en Bs o RUB</small>
                                </div>

                                <!-- Fecha Factura -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-calendar-day me-1 text-primary"></i>
                                        Fecha de Factura
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-calendar text-secondary"></i>
                                        </span>
                                        <input type="date" 
                                               name="fecha_factura" 
                                               class="form-control border-start-0 @error('fecha_factura') is-invalid @enderror" 
                                               value="{{ old('fecha_factura', $factura->fecha_factura ? $factura->fecha_factura->format('Y-m-d') : date('Y-m-d')) }}"
                                               required>
                                    </div>
                                    @error('fecha_factura')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fecha Pago -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-calendar-check me-1 text-primary"></i>
                                        Fecha de Pago
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-calendar-check text-secondary"></i>
                                        </span>
                                        <input type="date" 
                                               name="fecha_pago" 
                                               class="form-control border-start-0 @error('fecha_pago') is-invalid @enderror" 
                                               value="{{ old('fecha_pago', $factura->fecha_pago ? $factura->fecha_pago->format('Y-m-d') : '') }}">
                                    </div>
                                    @error('fecha_pago')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Fecha en que se realizó el pago</small>
                                </div>

                                <!-- Estado de Pago -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold required-field">
                                        <i class="fas fa-circle me-1 text-primary"></i>
                                        Estado de Pago
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-circle text-secondary"></i>
                                        </span>
                                        <select name="estado_pago" class="form-select border-start-0 @error('estado_pago') is-invalid @enderror" required>
                                            @foreach($estadosPago as $key => $label)
                                                <option value="{{ $key }}" 
                                                    {{ old('estado_pago', $factura->estado_pago) == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('estado_pago')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Monto en USD (Calculado automáticamente) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">
                                        <i class="fas fa-dollar-sign me-1 text-primary"></i>
                                        Monto en USD (Equivalente)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-dollar-sign text-secondary"></i>
                                        </span>
                                        <input type="text" 
                                               id="monto_usd"
                                               class="form-control border-start-0 bg-light" 
                                               readonly
                                               value="{{ old('monto_usd', $factura->monto_usd ? number_format($factura->monto_usd, 2) : '0.00') }}">
                                    </div>
                                    <small class="text-muted">Se calcula automáticamente según la moneda y tasa</small>
                                </div>

                                <!-- Observaciones -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-sticky-note me-1 text-primary"></i>
                                        Observaciones
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-sticky-note text-secondary"></i>
                                        </span>
                                        <textarea name="observaciones" 
                                                  class="form-control border-start-0 @error('observaciones') is-invalid @enderror" 
                                                  rows="3" 
                                                  placeholder="Observaciones adicionales..."
                                                  maxlength="500">{{ old('observaciones', $factura->observaciones) }}</textarea>
                                    </div>
                                    @error('observaciones')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                    <div class="d-flex justify-content-end mt-1">
                                        <small class="text-muted" id="obsCounter">{{ strlen(old('observaciones', $factura->observaciones ?? '')) }}</small>
                                        <small class="text-muted">/500</small>
                                    </div>
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
                                                    Creada por: <strong>{{ $factura->creador->name ?? 'Sistema' }}</strong>
                                                    @if($factura->created_at)
                                                        el {{ $factura->created_at->format('d/m/Y H:i') }}
                                                    @endif
                                                </p>
                                                @if($factura->updated_at && $factura->updated_at != $factura->created_at)
                                                    <p class="small text-muted mb-0">
                                                        <i class="fas fa-edit me-1"></i>
                                                        Última actualización: {{ $factura->updated_at->format('d/m/Y H:i') }}
                                                        por <strong>{{ $factura->actualizador->name ?? 'Sistema' }}</strong>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div>
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Datos protegidos con encriptación
                                    </small>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Actualizar Factura
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
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .rounded-4 { border-radius: 1rem !important; }
        .required-field::after {
            content: " *";
            color: #dc3545;
            font-weight: bold;
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
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important;
        }
        .btn-primary:disabled {
            opacity: 0.7;
            transform: none;
        }
        .card {
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

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
        .dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        .dark-mode .alert-info {
            background: #1a2d3d !important;
        }
        .dark-mode .bg-light {
            background: #2d2d44 !important;
        }

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
        // CALCULAR MONTO EN USD
        // ============================================================
        function calcularUSD(monto) {
            const moneda = document.getElementById('moneda').value;
            const tasa = parseFloat(document.getElementById('tasa_referencia').value) || 0;
            const montoNum = parseFloat(monto) || 0;
            
            let montoUsd = 0;
            
            if (moneda === 'USD') {
                montoUsd = montoNum;
            } else if (moneda === 'Bs' && tasa > 0) {
                montoUsd = montoNum / tasa;
            } else if (moneda === 'RUB' && tasa > 0) {
                montoUsd = montoNum / tasa;
            }
            
            document.getElementById('monto_usd').value = montoUsd.toFixed(2);
        }

        // ============================================================
        // TOGGLE TASA DE REFERENCIA
        // ============================================================
        function toggleTasa(moneda) {
            const tasaContainer = document.getElementById('tasaContainer');
            const tasaInput = document.getElementById('tasa_referencia');
            
            if (moneda === 'USD') {
                tasaContainer.style.display = 'none';
                tasaInput.removeAttribute('required');
            } else {
                tasaContainer.style.display = 'block';
                tasaInput.setAttribute('required', 'required');
            }
        }

        // ============================================================
        // CONTADOR DE OBSERVACIONES
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            const obsTextarea = document.querySelector('textarea[name="observaciones"]');
            const obsCounter = document.getElementById('obsCounter');
            
            if (obsTextarea && obsCounter) {
                obsTextarea.addEventListener('input', function() {
                    const length = this.value.length;
                    obsCounter.textContent = length;
                    obsCounter.style.color = length > 450 ? '#dc3545' : length > 400 ? '#f59e0b' : '#6c757d';
                });
            }

            // Inicializar tasa y cálculo
            const moneda = document.getElementById('moneda').value;
            toggleTasa(moneda);
            calcularUSD(document.getElementById('monto').value);

            // Prevenir envío doble
            const form = document.getElementById('facturaForm');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form) {
                form.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Actualizando...';
                });
            }
        });
    </script>
</x-layout>