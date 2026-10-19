<x-layout>
    <x-slot:title>RZN-OFFERINGS | Nueva Factura</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            <div>
                                <h4 class="mb-0 fw-bold">Nueva Factura</h4>
                                <p class="opacity-75 mb-0 small">Registra una nueva factura en el sistema</p>
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

                        <form action="{{ route('facturas.store') }}" method="POST" id="facturaForm" enctype="multipart/form-data">
                            @csrf

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
                                               value="{{ old('numero_factura') }}"
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
                                        <select name="proveedor_id" id="proveedor_id" class="form-select border-start-0 @error('proveedor_id') is-invalid @enderror" required>
                                            <option value="">Seleccione un proveedor...</option>
                                            @foreach($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->proveedor ?? $proveedor->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('proveedor_id')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- NUEVO: Producto/Servicio -->
                                <div class="col-md-6">
    <label class="form-label fw-semibold required-field">
        <i class="fas fa-box me-1 text-primary"></i> Producto/Servicio
    </label>
    <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
            <i class="fas fa-box text-secondary"></i>
        </span>
        <select name="producto_id" id="producto_id" class="form-select border-start-0 @error('producto_id') is-invalid @enderror" required>
            <option value="">-- Seleccione un producto --</option>
        </select>
        <!-- Botón "+" -->
        <button type="button" class="btn btn-outline-primary" id="btnNuevoProducto" title="Agregar nuevo producto">
            <i class="fas fa-plus-circle"></i>
        </button>
    </div>
    @error('producto_id')
        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
    @enderror
    <small class="text-muted" id="productoInfo">Seleccione un proveedor para ver sus productos</small>
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
                                               value="{{ old('monto') }}"
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
                                        <select name="moneda" id="moneda" class="form-select border-start-0 @error('moneda') is-invalid @enderror" required onchange="toggleTasa(this.value)">
                                            @foreach($monedas as $key => $label)
                                                <option value="{{ $key }}" {{ old('moneda') == $key ? 'selected' : 'Bs' }}>
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
                                               value="{{ old('tasa_referencia') }}"
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
                                               value="{{ old('fecha_factura', date('Y-m-d')) }}"
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
                                               value="{{ old('fecha_pago') }}">
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
                                                <option value="{{ $key }}" {{ old('estado_pago') == $key ? 'selected' : 'pendiente' }}>
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
                                               placeholder="0.00">
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
                                                  rows="2" 
                                                  placeholder="Observaciones adicionales..."
                                                  maxlength="500">{{ old('observaciones') }}</textarea>
                                    </div>
                                    @error('observaciones')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Archivo de Factura -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-file-upload me-1 text-primary"></i>
                                        Archivo de Factura
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-file text-secondary"></i>
                                        </span>
                                        <input type="file" 
                                               name="archivo_factura" 
                                               id="archivo_factura"
                                               class="form-control border-start-0 @error('archivo_factura') is-invalid @enderror" 
                                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                               onchange="previsualizarArchivo(this)">
                                    </div>
                                    @error('archivo_factura')
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Formatos permitidos: PDF, JPG, JPEG, PNG, DOC, DOCX (Máx. 5MB)
                                    </small>
                                    
                                    <!-- Previsualización del archivo -->
                                    <div id="previsualizacionArchivo" class="mt-2" style="display: none;">
                                        <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border">
                                            <div id="iconoArchivo" class="fs-2 text-primary">
                                                <i class="fas fa-file-pdf"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p id="nombreArchivo" class="mb-0 fw-semibold">nombre-archivo.pdf</p>
                                                <p id="tamanoArchivo" class="mb-0 text-muted small">2.5 MB</p>
                                            </div>
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarArchivo()">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
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
                                    <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Registrar Factura
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
        .form-control:focus { border-color: #764ba2; box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.15); }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4) !important; }
        .card { animation: fadeInUp 0.5s ease; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
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
            
            // Recalcular USD
            calcularUSD(document.getElementById('monto').value);
        }

        // ============================================================
        // CARGAR PRODUCTOS POR PROVEEDOR
        // ============================================================
        document.getElementById('proveedor_id').addEventListener('change', function() {
            const proveedorId = this.value;
            const productSelect = document.getElementById('producto_id');
            const productoInfo = document.getElementById('productoInfo');

            // Limpiar select de productos
            productSelect.innerHTML = '<option value="">-- Seleccione un producto --</option>';

            if (!proveedorId) {
                productoInfo.textContent = 'Seleccione un proveedor para ver sus productos';
                productoInfo.className = 'text-muted';
                return;
            }

            // Mostrar carga
            productoInfo.textContent = 'Cargando productos...';
            productoInfo.className = 'text-info';

            // Llamada AJAX
            fetch(`/productos/por-proveedor/${proveedorId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    productSelect.innerHTML = '<option value="">-- No hay productos para este proveedor --</option>';
                    productoInfo.textContent = 'Este proveedor no tiene productos registrados';
                    productoInfo.className = 'text-muted';
                    return;
                }

                data.forEach(producto => {
                    const option = document.createElement('option');
                    option.value = producto.id;
                    const tipoPago = producto.tipo_pago ? ` (${producto.tipo_pago})` : '';
                    option.textContent = producto.nombre + tipoPago;
                    productSelect.appendChild(option);
                });

                productoInfo.textContent = `${data.length} productos disponibles`;
                productoInfo.className = 'text-success';
            })
            .catch(error => {
                console.error('Error al cargar productos:', error);
                productoInfo.textContent = 'Error al cargar productos. Intente de nuevo.';
                productoInfo.className = 'text-danger';
            });
        });

        // ============================================================
        // PREVISUALIZACIÓN DE ARCHIVO
        // ============================================================
        function previsualizarArchivo(input) {
            const previewDiv = document.getElementById('previsualizacionArchivo');
            const nombreArchivo = document.getElementById('nombreArchivo');
            const tamanoArchivo = document.getElementById('tamanoArchivo');
            const iconoArchivo = document.getElementById('iconoArchivo');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validar tamaño (5MB = 5 * 1024 * 1024 bytes)
                if (file.size > 5 * 1024 * 1024) {
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es 5MB.');
                    input.value = '';
                    previewDiv.style.display = 'none';
                    return;
                }
                
                // Validar tipo de archivo
                const tiposPermitidos = ['application/pdf', 'image/jpeg', 'image/png', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!tiposPermitidos.includes(file.type)) {
                    alert('Tipo de archivo no permitido. Por favor, sube un PDF, imagen o documento de Word.');
                    input.value = '';
                    previewDiv.style.display = 'none';
                    return;
                }
                
                // Mostrar información del archivo
                nombreArchivo.textContent = file.name;
                const tamañoEnMB = (file.size / 1024 / 1024).toFixed(2);
                tamanoArchivo.textContent = tamañoEnMB + ' MB';
                
                // Cambiar ícono según el tipo de archivo
                const extension = file.name.split('.').pop().toLowerCase();
                let icono = 'fa-file';
                let color = 'text-primary';
                
                switch(extension) {
                    case 'pdf':
                        icono = 'fa-file-pdf';
                        color = 'text-danger';
                        break;
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        icono = 'fa-file-image';
                        color = 'text-success';
                        break;
                    case 'doc':
                    case 'docx':
                        icono = 'fa-file-word';
                        color = 'text-primary';
                        break;
                    default:
                        icono = 'fa-file';
                        color = 'text-secondary';
                }
                
                iconoArchivo.innerHTML = `<i class="fas ${icono} ${color}"></i>`;
                
                // Mostrar previsualización
                previewDiv.style.display = 'block';
            } else {
                previewDiv.style.display = 'none';
            }
        }

        // ============================================================
        // ELIMINAR ARCHIVO SELECCIONADO
        // ============================================================
        function eliminarArchivo() {
            const input = document.getElementById('archivo_factura');
            input.value = '';
            document.getElementById('previsualizacionArchivo').style.display = 'none';
        }

        // ============================================================
        // INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar tasa
            const moneda = document.getElementById('moneda').value;
            toggleTasa(moneda);
            
            // Calcular USD inicial
            calcularUSD(document.getElementById('monto').value);
            
            // Si hay un error y el usuario seleccionó un archivo, mostrar previsualización
            const archivoInput = document.getElementById('archivo_factura');
            if (archivoInput.files && archivoInput.files[0]) {
                previsualizarArchivo(archivoInput);
            }
            
            // Si viene con proveedor_id en la URL, cargar productos automáticamente
            const urlParams = new URLSearchParams(window.location.search);
            const proveedorIdParam = urlParams.get('proveedor_id');
            if (proveedorIdParam) {
                const proveedorSelect = document.getElementById('proveedor_id');
                proveedorSelect.value = proveedorIdParam;
                // Disparar el evento change para cargar productos
                proveedorSelect.dispatchEvent(new Event('change'));
            }
            
            // Validación antes de enviar el formulario
            document.getElementById('facturaForm').addEventListener('submit', function(e) {
                const archivoInput = document.getElementById('archivo_factura');
                if (archivoInput.files && archivoInput.files[0]) {
                    const file = archivoInput.files[0];
                    if (file.size > 5 * 1024 * 1024) {
                        e.preventDefault();
                        alert('El archivo es demasiado grande. El tamaño máximo permitido es 5MB.');
                        return false;
                    }
                }
            });
        });
        // ============================================================
// 1. ABRIR MODAL CON PROVEEDOR PRESELECCIONADO
// ============================================================
document.getElementById('btnNuevoProducto').addEventListener('click', function() {
    const proveedorId = document.getElementById('proveedor_id').value;
    if (!proveedorId) {
        alert('Primero selecciona un proveedor');
        return;
    }
    // Establecer el proveedor en el formulario del modal
    const modalForm = document.getElementById('formNuevoProducto');
    const proveedorField = modalForm.querySelector('select[name="proveedor_id"]');
    if (proveedorField) {
        proveedorField.value = proveedorId;
    }
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('modalNuevoProducto'));
    modal.show();
});

// ============================================================
// 2. ENVÍO DEL FORMULARIO DEL MODAL VÍA AJAX
// ============================================================
document.getElementById('formNuevoProducto').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoProducto'));
            modal.hide();

            // Recargar productos del proveedor actual
            const proveedorId = document.getElementById('proveedor_id').value;
            if (proveedorId) {
                // Disparar el evento change del proveedor para recargar productos
                document.getElementById('proveedor_id').dispatchEvent(new Event('change'));
                // Seleccionar el producto recién creado
                setTimeout(() => {
                    document.getElementById('producto_id').value = data.producto_id;
                }, 300);
            }
            // Mostrar notificación (opcional)
            alert('Producto creado exitosamente');
        } else {
            // Mostrar errores de validación (puedes mejorarlo mostrándolos en el modal)
            const errors = data.errors || ['Error al crear el producto'];
            alert('Errores:\n' + errors.join('\n'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de red, por favor intenta nuevamente.');
    });
});
    </script>
    <!-- Modal Nuevo Producto -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title fw-bold">Nuevo Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoProducto" action="{{ route('productos.store') }}" method="POST">
                    @include('inventario._form') <!-- Aquí se hereda el formulario -->
                    <div class="mt-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-layout>