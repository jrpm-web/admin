<x-layout>
    <x-slot:title>RZN-OFFERINGS | Nuevo Producto</x-slot:title>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <!-- Header -->
                    <div class="card-header bg-gradient-primary text-white p-4 border-0">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h4 class="mb-0 fw-bold">Nuevo Producto</h4>
                                    <p class="opacity-75 mb-0 small">
                                        Campos marcados con <span class="text-warning fw-bold">*</span> son obligatorios
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('productos.index') }}" class="btn btn-light btn-sm px-4 py-2">
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

                        <form id="productoForm" action="{{ route('productos.store') }}" method="POST" novalidate>
                            @csrf
                            
                            <!-- ============================================================ -->
                            <!-- SECCIÓN 1: INFORMACIÓN PRINCIPAL -->
                            <!-- ============================================================ -->
                            <div class="mb-5">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-info-circle me-2 text-primary"></i>
                                    Información Principal
                                </h5>
                                
                                <div class="row g-4">
                                    <!-- Nombre del Producto -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-tag me-1 text-primary"></i>
                                            Nombre del Producto
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-box text-secondary"></i>
                                            </span>
                                            <input type="text" 
                                                   id="nombre"
                                                   name="nombre" 
                                                   class="form-control border-start-0 @error('nombre') is-invalid @enderror" 
                                                   placeholder="Ej: Laptop HP Pavilion" 
                                                   value="{{ old('nombre') }}"
                                                   required 
                                                   maxlength="100"
                                                   oninput="validateField(this); updateCounter(this, 'nombreCounter')">
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-asterisk text-danger"></i> Máximo 100 caracteres
                                                </small>
                                                <br>
                                                <small class="text-danger error-message" id="nombreError" style="display: none;">
                                                    <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                                                </small>
                                            </div>
                                            <small class="text-muted">
                                                <span id="nombreCounter">0</span>/100
                                            </small>
                                        </div>
                                        @error('nombre')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Código del Producto -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-barcode me-1 text-primary"></i>
                                            Código del Producto
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-barcode text-secondary"></i>
                                            </span>
                                            <input type="text" 
                                                   id="codigo"
                                                   name="codigo" 
                                                   class="form-control border-start-0 @error('codigo') is-invalid @enderror" 
                                                   placeholder="Ej: PROD-001" 
                                                   value="{{ old('codigo') }}"
                                                   required
                                                   maxlength="50"
                                                   oninput="validateField(this); updateCounter(this, 'codigoCounter')">
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-asterisk text-danger"></i> Máximo 50 caracteres
                                                </small>
                                                <br>
                                                <small class="text-danger error-message" id="codigoError" style="display: none;">
                                                    <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                                                </small>
                                            </div>
                                            <small class="text-muted">
                                                <span id="codigoCounter">0</span>/50
                                            </small>
                                        </div>
                                        @error('codigo')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Descripción -->
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-file-alt me-1 text-primary"></i>
                                            Descripción
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-file-alt text-secondary"></i>
                                            </span>
                                            <textarea id="descripcion"
                                                      name="descripcion" 
                                                      class="form-control border-start-0 @error('descripcion') is-invalid @enderror" 
                                                      rows="2" 
                                                      placeholder="Breve descripción del producto..."
                                                      maxlength="500"
                                                      oninput="updateCounter(this, 'descCounter')">{{ old('descripcion') }}</textarea>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <small class="text-muted">Máximo 500 caracteres</small>
                                            <small class="text-muted">
                                                <span id="descCounter">0</span>/500
                                            </small>
                                        </div>
                                        @error('descripcion')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================================ -->
                            <!-- SECCIÓN 2: CATEGORÍA Y PROVEEDOR -->
                            <!-- ============================================================ -->
                            <div class="mb-5">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-diagram-3 me-2 text-primary"></i>
                                    Categoría y Proveedor
                                </h5>
                                
                                <div class="row g-4">
                                    <!-- Categoría -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-folder me-1 text-primary"></i>
                                            Categoría
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-folder text-secondary"></i>
                                            </span>
                                            <select class="form-select border-start-0 @error('categoria_id') is-invalid @enderror" 
                                                    id="categoria_id"
                                                    name="categoria_id" 
                                                    required
                                                    onchange="validateSelect(this); cargarAtributosDinamicos(this.value)">
                                                <option value="">Seleccione una categoría...</option>
                                                @foreach($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}" 
                                                        {{ old('categoria_id', $categoriaIdSeleccionada ?? '') == $categoria->id ? 'selected' : '' }}>
                                                        {{ $categoria->nombre_categoria }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                                </small>
                                                <br>
                                                <small class="text-danger error-message" id="categoriaError" style="display: none;">
                                                    <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                                                </small>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Selecciona una categoría
                                            </small>
                                        </div>
                                        @error('categoria_id')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
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
                                            <select class="form-select border-start-0 @error('proveedor_id') is-invalid @enderror" 
                                                id="proveedor_id"
                                                name="proveedor_id" 
                                                required
                                                onchange="validateSelect(this)">
                                            <option value="">Seleccione un proveedor...</option>
                                            @foreach($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}" 
                                                    {{ old('proveedor_id', request('proveedor_id')) == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->proveedor ?? $proveedor->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="d-flex justify-content-between mt-1">
                                            <div>
                                                <small class="text-muted">
                                                    <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                                </small>
                                                <br>
                                                <small class="text-danger error-message" id="proveedorError" style="display: none;">
                                                    <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                                                </small>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Selecciona un proveedor
                                            </small>
                                        </div>
                                        @error('proveedor_id')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================================ -->
                            <!-- SECCIÓN 3: CONFIGURACIÓN DE PAGO (NUEVA) -->
                            <!-- ============================================================ -->
                            <div class="mb-5">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-credit-card me-2 text-primary"></i>
                                    Configuración de Pago
                                </h5>
                                
                                <div class="row g-4">
                                    <!-- Tipo de Pago -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-tag me-1 text-primary"></i>
                                            Tipo de Pago
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-tag text-secondary"></i>
                                            </span>
                                            <select name="tipo_pago" 
                                                    id="tipoPago" 
                                                    class="form-select border-start-0 @error('tipo_pago') is-invalid @enderror" 
                                                    onchange="togglePagoRecurrente()"
                                                    required>
                                                <option value="unico" {{ old('tipo_pago', $producto->tipo_pago ?? 'unico') == 'unico' ? 'selected' : '' }}>
                                                    Pago Único
                                                </option>
                                                <option value="recurrente" {{ old('tipo_pago', $producto->tipo_pago ?? '') == 'recurrente' ? 'selected' : '' }}>
                                                    Pago Recurrente (Suscripción)
                                                </option>
                                            </select>
                                        </div>
                                        @error('tipo_pago')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                            <br>
                                            <i class="fas fa-info-circle me-1"></i>
                                            Selecciona si es un pago único o una suscripción recurrente
                                        </small>
                                    </div>

                                    <!-- Frecuencia de Pago (solo para recurrentes) -->
                                    <div class="col-md-6" id="frecuenciaContainer" style="{{ old('tipo_pago', $producto->tipo_pago ?? 'unico') == 'recurrente' ? '' : 'display: none;' }}">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-clock me-1 text-primary"></i>
                                            Frecuencia de Pago
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-clock text-secondary"></i>
                                            </span>
                                            <select name="frecuencia_pago" 
                                                    class="form-select border-start-0 @error('frecuencia_pago') is-invalid @enderror">
                                                <option value="">Seleccione una frecuencia...</option>
                                                @foreach(\App\Models\Producto::FRECUENCIA_PAGO as $key => $label)
                                                    <option value="{{ $key }}" {{ old('frecuencia_pago', $producto->frecuencia_pago ?? '') == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('frecuencia_pago')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio para pagos recurrentes
                                        </small>
                                    </div>

                                    <!-- Día de Facturación (solo para recurrentes) -->
                                    <div class="col-md-4" id="diaContainer" style="{{ old('tipo_pago', $producto->tipo_pago ?? 'unico') == 'recurrente' ? '' : 'display: none;' }}">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-calendar-day me-1 text-primary"></i>
                                            Día de Facturación
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-calendar-day text-secondary"></i>
                                            </span>
                                            <select name="dia_facturacion" 
                                                    class="form-select border-start-0 @error('dia_facturacion') is-invalid @enderror">
                                                <option value="">Seleccione un día...</option>
                                                @foreach(\App\Models\Producto::DIAS_MES as $dia => $label)
                                                    <option value="{{ $dia }}" {{ old('dia_facturacion', $producto->dia_facturacion ?? '') == $dia ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('dia_facturacion')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio para pagos recurrentes
                                        </small>
                                    </div>

                                    <!-- Días de Notificación (solo para recurrentes) -->
                                    <div class="col-md-4" id="notificacionContainer" style="{{ old('tipo_pago', $producto->tipo_pago ?? 'unico') == 'recurrente' ? '' : 'display: none;' }}">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-bell me-1 text-primary"></i>
                                            Días de Notificación
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-bell text-secondary"></i>
                                            </span>
                                            <input type="number" 
                                                   name="dias_notificacion" 
                                                   class="form-control border-start-0 @error('dias_notificacion') is-invalid @enderror"
                                                   value="{{ old('dias_notificacion', $producto->dias_notificacion ?? 5) }}"
                                                   min="1"
                                                   max="30">
                                        </div>
                                        @error('dias_notificacion')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Días antes de la facturación para generar alerta
                                        </small>
                                    </div>

                                    <!-- Notificar Antes (solo para recurrentes) -->
                                    <div class="col-md-4" id="notificarContainer" style="{{ old('tipo_pago', $producto->tipo_pago ?? 'unico') == 'recurrente' ? '' : 'display: none;' }}">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-toggle-on me-1 text-primary"></i>
                                            Notificar Antes
                                        </label>
                                        <div class="mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="notificar_antes" 
                                                       id="notificarAntes" 
                                                       value="1"
                                                       {{ old('notificar_antes', $producto->notificar_antes ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="notificarAntes">
                                                    Activar notificaciones
                                                </label>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Recibir alertas antes de la fecha de facturación
                                        </small>
                                    </div>

                                    <!-- Próxima Facturación (solo para recurrentes) -->
                                    <div class="col-12" id="proximaContainer" style="{{ old('tipo_pago', $producto->tipo_pago ?? 'unico') == 'recurrente' ? '' : 'display: none;' }}">
                                        <div class="alert alert-info border-0 shadow-sm">
                                            <div class="d-flex align-items-center gap-3">
                                                <i class="fas fa-calendar-check fs-4 text-primary"></i>
                                                <div>
                                                    <strong>Próxima fecha de facturación:</strong>
                                                    @if(isset($producto) && $producto->proxima_facturacion)
                                                        <span class="fw-bold">{{ $producto->proxima_facturacion->format('d/m/Y') }}</span>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-clock me-1"></i>
                                                            Faltan {{ $producto->dias_para_facturar ?? 0 }} días
                                                        </small>
                                                    @else
                                                        <span class="text-muted">Se calculará automáticamente al guardar</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================================ -->
                            <!-- SECCIÓN 4: PRECIOS Y STOCK -->
                            <!-- ============================================================ -->
                            <div class="mb-5">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-cash-stack me-2 text-primary"></i>
                                    Precios y Stock
                                </h5>
                                
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-arrow-down-circle me-1 text-primary"></i>
                                            Precio Compra
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">$</span>
                                            <input type="number" 
                                                   id="precio_compra"
                                                   class="form-control @error('precio_compra') is-invalid @enderror" 
                                                   name="precio_compra" 
                                                   value="{{ old('precio_compra') }}" 
                                                   step="0.01" min="0" 
                                                   placeholder="0.00"
                                                   required
                                                   oninput="validateField(this)">
                                        </div>
                                        @error('precio_compra')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                        </small>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-arrow-up-circle me-1 text-primary"></i>
                                            Precio Venta
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">$</span>
                                            <input type="number" 
                                                   id="precio_venta"
                                                   class="form-control @error('precio_venta') is-invalid @enderror" 
                                                   name="precio_venta" 
                                                   value="{{ old('precio_venta') }}" 
                                                   step="0.01" min="0" 
                                                   placeholder="0.00"
                                                   required
                                                   oninput="validateField(this)">
                                        </div>
                                        @error('precio_venta')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                        </small>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-boxes me-1 text-primary"></i>
                                            Stock
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-boxes text-secondary"></i>
                                            </span>
                                            <input type="number" 
                                                   id="stock"
                                                   class="form-control @error('stock') is-invalid @enderror" 
                                                   name="stock" 
                                                   value="{{ old('stock', 0) }}" 
                                                   min="0" placeholder="0" 
                                                   required
                                                   oninput="validateField(this)">
                                        </div>
                                        @error('stock')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                        </small>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-exclamation-triangle me-1 text-primary"></i>
                                            Stock Mínimo
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-exclamation-triangle text-secondary"></i>
                                            </span>
                                            <input type="number" 
                                                   id="stock_minimo"
                                                   class="form-control @error('stock_minimo') is-invalid @enderror" 
                                                   name="stock_minimo" 
                                                   value="{{ old('stock_minimo', 0) }}" 
                                                   min="0" placeholder="0" 
                                                   required
                                                   oninput="validateField(this)">
                                        </div>
                                        @error('stock_minimo')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================================ -->
                            <!-- SECCIÓN 5: ESTATUS Y ASIGNACIÓN -->
                            <!-- ============================================================ -->
                            <div class="mb-5">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-clipboard-check me-2 text-primary"></i>
                                    Estatus y Asignación
                                </h5>
                                
                                <div class="row g-4">
                                    <!-- Estatus -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold required-field">
                                            <i class="fas fa-circle me-1 text-primary"></i>
                                            Estatus del Producto
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-circle text-secondary"></i>
                                            </span>
                                            <select id="estatus"
                                                    name="estatus" 
                                                    class="form-select border-start-0 @error('estatus') is-invalid @enderror"
                                                    onchange="toggleAsignacionFields(this.value); validateSelect(this)"
                                                    required>
                                                <option value="disponible" {{ old('estatus', 'disponible') == 'disponible' ? 'selected' : '' }}>
                                                    <i class="fas fa-check-circle"></i> Disponible
                                                </option>
                                                <option value="asignado" {{ old('estatus') == 'asignado' ? 'selected' : '' }}>
                                                    <i class="fas fa-user-check"></i> Asignado
                                                </option>
                                                <option value="mantenimiento" {{ old('estatus') == 'mantenimiento' ? 'selected' : '' }}>
                                                    <i class="fas fa-tools"></i> En Mantenimiento
                                                </option>
                                                <option value="reparacion" {{ old('estatus') == 'reparacion' ? 'selected' : '' }}>
                                                    <i class="fas fa-hammer"></i> En Reparación
                                                </option>
                                                <option value="prestamo" {{ old('estatus') == 'prestamo' ? 'selected' : '' }}>
                                                    <i class="fas fa-exchange-alt"></i> En Préstamo
                                                </option>
                                                <option value="baja" {{ old('estatus') == 'baja' ? 'selected' : '' }}>
                                                    <i class="fas fa-times-circle"></i> Dado de Baja
                                                </option>
                                            </select>
                                        </div>
                                        @error('estatus')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-asterisk text-danger"></i> Campo obligatorio
                                        </small>
                                    </div>

                                    <!-- Fecha de Asignación -->
                                    <div class="col-md-6" id="fechaAsignacionContainer" style="{{ old('estatus') == 'asignado' || old('estatus') == 'prestamo' ? '' : 'display: none;' }}">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-calendar-day me-1 text-primary"></i>
                                            Fecha de Asignación
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-calendar text-secondary"></i>
                                            </span>
                                            <input type="date" 
                                                   name="fecha_asignacion" 
                                                   class="form-control border-start-0 @error('fecha_asignacion') is-invalid @enderror" 
                                                   value="{{ old('fecha_asignacion', date('Y-m-d')) }}">
                                        </div>
                                        @error('fecha_asignacion')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Fecha en que se asignó el producto
                                        </small>
                                    </div>
                                </div>

                                <!-- Campos de Asignación -->
                                <div id="asignacionFields" style="{{ old('estatus') == 'asignado' || old('estatus') == 'prestamo' ? '' : 'display: none;' }}">
                                    <div class="row g-4 mt-2">
                                        <div class="col-12">
                                            <div class="alert alert-info border-0 shadow-sm">
                                                <i class="fas fa-user-badge me-2"></i>
                                                <strong>Información de Asignación</strong>
                                                <br>
                                                <small>Completa los datos de la persona a quien se asigna el producto</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold required-field">
                                                <i class="fas fa-user me-1 text-primary"></i>
                                                Asignado a
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-user text-secondary"></i>
                                                </span>
                                                <input type="text" 
                                                       id="asignado_a"
                                                       name="asignado_a" 
                                                       class="form-control border-start-0 @error('asignado_a') is-invalid @enderror" 
                                                       placeholder="Nombre completo del usuario"
                                                       value="{{ old('asignado_a') }}"
                                                       maxlength="100"
                                                       oninput="validateField(this); updateCounter(this, 'asignadoCounter')">
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <div>
                                                    <small class="text-muted">Máximo 100 caracteres</small>
                                                    <br>
                                                    <small class="text-danger error-message" id="asignadoError" style="display: none;">
                                                        <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                                                    </small>
                                                </div>
                                                <small class="text-muted">
                                                    <span id="asignadoCounter">0</span>/100
                                                </small>
                                            </div>
                                            @error('asignado_a')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold required-field">
                                                <i class="fas fa-envelope me-1 text-primary"></i>
                                                Correo del Usuario
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-envelope text-secondary"></i>
                                                </span>
                                                <input type="email" 
                                                       id="email_usuario"
                                                       name="email_usuario" 
                                                       class="form-control border-start-0 @error('email_usuario') is-invalid @enderror" 
                                                       placeholder="usuario@empresa.com"
                                                       value="{{ old('email_usuario') }}"
                                                       maxlength="255"
                                                       oninput="validateField(this); validateEmail(this); updateCounter(this, 'emailUsuarioCounter')">
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <div>
                                                    <small class="text-muted">Máximo 255 caracteres</small>
                                                    <br>
                                                    <small class="text-danger error-message" id="emailUsuarioError" style="display: none;">
                                                        <i class="fas fa-exclamation-circle"></i> Este campo es obligatorio
                                                    </small>
                                                    <small class="text-danger error-message" id="emailUsuarioFormatError" style="display: none;">
                                                        <i class="fas fa-exclamation-circle"></i> Ingresa un correo válido
                                                    </small>
                                                </div>
                                                <small class="text-muted">
                                                    <span id="emailUsuarioCounter">0</span>/255
                                                </small>
                                            </div>
                                            @error('email_usuario')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-phone me-1 text-primary"></i>
                                                Teléfono del Usuario
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-phone text-secondary"></i>
                                                </span>
                                                <input type="tel" 
                                                       name="telefono_usuario" 
                                                       class="form-control border-start-0 @error('telefono_usuario') is-invalid @enderror" 
                                                       placeholder="+34 123 456 789"
                                                       value="{{ old('telefono_usuario') }}"
                                                       maxlength="50"
                                                       oninput="updateCounter(this, 'telefonoUsuarioCounter')">
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="text-muted">Máximo 50 caracteres</small>
                                                <small class="text-muted">
                                                    <span id="telefonoUsuarioCounter">0</span>/50
                                                </small>
                                            </div>
                                            @error('telefono_usuario')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                <i class="fas fa-sticky-note me-1 text-primary"></i>
                                                Notas de Asignación
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-sticky-note text-secondary"></i>
                                                </span>
                                                <textarea name="notas_asignacion" 
                                                          class="form-control border-start-0 @error('notas_asignacion') is-invalid @enderror" 
                                                          rows="2" 
                                                          placeholder="Observaciones sobre la asignación..."
                                                          maxlength="500"
                                                          oninput="updateCounter(this, 'notasCounter')">{{ old('notas_asignacion') }}</textarea>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="text-muted">Máximo 500 caracteres</small>
                                                <small class="text-muted">
                                                    <span id="notasCounter">0</span>/500
                                                </small>
                                            </div>
                                            @error('notas_asignacion')
                                                <div class="invalid-feedback d-block">
                                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================================ -->
                            <!-- SECCIÓN 6: INFORMACIÓN ADICIONAL -->
                            <!-- ============================================================ -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-geo-alt me-2 text-primary"></i>
                                    Información Adicional
                                </h5>
                                
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-ruler me-1 text-primary"></i>
                                            Unidad de Medida
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-ruler text-secondary"></i>
                                            </span>
                                            <select class="form-select border-start-0 @error('unidad_medida') is-invalid @enderror" 
                                                    name="unidad_medida">
                                                <option value="">Seleccione una unidad...</option>
                                                <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                                <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                                                <option value="g" {{ old('unidad_medida') == 'g' ? 'selected' : '' }}>Gramo</option>
                                                <option value="l" {{ old('unidad_medida') == 'l' ? 'selected' : '' }}>Litro</option>
                                                <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>Mililitro</option>
                                                <option value="m" {{ old('unidad_medida') == 'm' ? 'selected' : '' }}>Metro</option>
                                            </select>
                                        </div>
                                        @error('unidad_medida')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-map-pin me-1 text-primary"></i>
                                            Ubicación
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-map-pin text-secondary"></i>
                                            </span>
                                            <input type="text" 
                                                   name="ubicacion" 
                                                   class="form-control border-start-0 @error('ubicacion') is-invalid @enderror" 
                                                   placeholder="Ej: Estante A, Pasillo 3"
                                                   value="{{ old('ubicacion') }}"
                                                   maxlength="255"
                                                   oninput="updateCounter(this, 'ubicacionCounter')">
                                        </div>
                                        <div class="d-flex justify-content-end mt-1">
                                            <small class="text-muted">
                                                <span id="ubicacionCounter">0</span>/255
                                            </small>
                                        </div>
                                        @error('ubicacion')
                                            <div class="invalid-feedback d-block">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- ============================================================ -->
                            <!-- SECCIÓN 7: ESTADO DEL PRODUCTO -->
                            <!-- ============================================================ -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-3 text-secondary fw-bold">
                                    <i class="fas fa-toggle-on me-2 text-primary"></i>
                                    Estado del Producto
                                </h5>
                                
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="activo" value="1" 
                                                   id="activo" {{ old('activo', true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="activo">
                                                <i class="fas fa-check-circle text-success me-1"></i> 
                                                Producto Activo
                                            </label>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Desactive para ocultar el producto en el inventario
                                            </small>
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
                                    <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary px-4">
                                        <i class="fas fa-times me-1"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Registrar Producto
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
           ESTILOS UNIFICADOS CON EL SISTEMA
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
        
        .error-message {
            font-size: 0.875rem;
        }
        
        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }
        
        .invalid-feedback.d-block {
            display: block !important;
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
        
        .dark-mode .text-muted {
            color: #94a3b8 !important;
        }
        
        .dark-mode .alert-info {
            background: #1a2d3d !important;
            border-color: #0d6efd !important;
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
        
        /* Spinner para el botón */
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }
    </style>

    <script>
        // ============================================================
        // 1. TOGGLE PAGO RECURRENTE
        // ============================================================
        function togglePagoRecurrente() {
            const tipoPago = document.getElementById('tipoPago').value;
            const isRecurrente = tipoPago === 'recurrente';
            
            const containers = [
                'frecuenciaContainer',
                'diaContainer',
                'notificacionContainer',
                'notificarContainer',
                'proximaContainer'
            ];
            
            containers.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.style.display = isRecurrente ? 'block' : 'none';
                }
            });
            
            // Marcar/desmarcar campos requeridos
            const frecuenciaPago = document.querySelector('select[name="frecuencia_pago"]');
            const diaFacturacion = document.querySelector('select[name="dia_facturacion"]');
            
            if (isRecurrente) {
                if (frecuenciaPago) frecuenciaPago.setAttribute('required', 'required');
                if (diaFacturacion) diaFacturacion.setAttribute('required', 'required');
            } else {
                if (frecuenciaPago) frecuenciaPago.removeAttribute('required');
                if (diaFacturacion) diaFacturacion.removeAttribute('required');
            }
        }

        // ============================================================
        // 2. TOGGLE ASIGNACIÓN
        // ============================================================
        function toggleAsignacionFields(estatus) {
            const asignacionFields = document.getElementById('asignacionFields');
            const fechaContainer = document.getElementById('fechaAsignacionContainer');
            
            if (estatus === 'asignado' || estatus === 'prestamo') {
                asignacionFields.style.display = 'block';
                fechaContainer.style.display = 'block';
                document.getElementById('asignado_a').setAttribute('required', 'required');
                document.getElementById('email_usuario').setAttribute('required', 'required');
            } else {
                asignacionFields.style.display = 'none';
                fechaContainer.style.display = 'none';
                document.getElementById('asignado_a').removeAttribute('required');
                document.getElementById('email_usuario').removeAttribute('required');
                document.getElementById('asignado_a').classList.remove('is-valid', 'is-invalid');
                document.getElementById('email_usuario').classList.remove('is-valid', 'is-invalid');
            }
        }

        // ============================================================
        // 3. CONTADORES
        // ============================================================
        function updateCounter(input, counterId) {
            const counter = document.getElementById(counterId);
            if (!counter) return;
            
            const currentLength = input.value.length;
            counter.textContent = currentLength;
            
            const maxLength = parseInt(input.getAttribute('maxlength'));
            if (maxLength) {
                if (currentLength > maxLength * 0.9) {
                    counter.style.color = '#dc3545';
                } else if (currentLength > maxLength * 0.7) {
                    counter.style.color = '#f59e0b';
                } else {
                    counter.style.color = '#6c757d';
                }
            }
        }

        // ============================================================
        // 4. VALIDACIÓN DE CAMPOS
        // ============================================================
        function validateField(input) {
            if (!input.hasAttribute('required')) return true;

            const errorMessage = document.getElementById(input.id + 'Error');
            const value = input.value.trim();
            
            input.classList.remove('is-valid', 'is-invalid', 'shake');
            
            if (value === '') {
                input.classList.add('is-invalid');
                if (errorMessage) errorMessage.style.display = 'block';
                return false;
            } else {
                input.classList.add('is-valid');
                if (errorMessage) errorMessage.style.display = 'none';
                return true;
            }
        }

        function validateSelect(select) {
            if (!select.hasAttribute('required')) return true;

            const errorElement = document.getElementById(select.id + 'Error');
            
            select.classList.remove('is-valid', 'is-invalid');
            
            if (select.value === '') {
                select.classList.add('is-invalid');
                if (errorElement) errorElement.style.display = 'block';
                return false;
            } else {
                select.classList.remove('is-invalid');
                select.classList.add('is-valid');
                if (errorElement) errorElement.style.display = 'none';
                return true;
            }
        }

        function validateEmail(input) {
            const emailError = document.getElementById('emailUsuarioFormatError');
            const value = input.value.trim();
            
            if (value === '') {
                if (emailError) emailError.style.display = 'none';
                return true;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                input.classList.add('is-invalid');
                if (emailError) emailError.style.display = 'block';
                return false;
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                if (emailError) emailError.style.display = 'none';
                return true;
            }
        }

        // ============================================================
        // 5. ATRIBUTOS DINÁMICOS POR CATEGORÍA
        // ============================================================
        function cargarAtributosDinamicos(categoriaId) {
            const container = document.getElementById('atributosContainer');
            const contenido = document.getElementById('atributosDinamicos');
            
            if (!categoriaId) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'block';
            contenido.innerHTML = `
                <div class="text-center py-3">
                    <i class="fas fa-spinner fa-spin me-2 text-primary"></i>
                    Cargando atributos...
                </div>
            `;

            // Simular carga de atributos (conexión con tu API)
            setTimeout(() => {
                // Ejemplo de atributos dinámicos
                const atributos = [
                    { id: 1, nombre: 'Número Telefónico', tipo: 'text', required: true },
                    { id: 2, nombre: 'Número de SIM', tipo: 'text', required: false },
                    { id: 3, nombre: 'Operador', tipo: 'select', options: ['Movistar', 'Claro', 'Digitel'], required: true },
                ];
                
                renderizarAtributos(atributos);
            }, 500);
        }

        function renderizarAtributos(atributos) {
            const contenido = document.getElementById('atributosDinamicos');
            
            if (!atributos || atributos.length === 0) {
                contenido.innerHTML = `
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="fas fa-info-circle me-2"></i>
                        Esta categoría no tiene atributos personalizados
                    </div>
                `;
                return;
            }

            let html = '<div class="row g-4">';
            
            atributos.forEach((atributo, index) => {
                const required = atributo.required ? 'required' : '';
                const requiredStar = atributo.required ? '<span class="text-danger">*</span>' : '';
                
                html += `
                    <div class="col-md-6">
                        <label class="form-label fw-semibold ${atributo.required ? 'required-field' : ''}">
                            <i class="fas fa-pencil-square me-1 text-primary"></i>
                            ${atributo.nombre} ${requiredStar}
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-pencil-square text-secondary"></i>
                            </span>
                            ${generarCampoAtributo(atributo, index)}
                        </div>
                        <small class="text-muted mt-1 d-block">
                            ${atributo.required ? '<i class="fas fa-asterisk text-danger me-1"></i>Campo obligatorio' : 'Campo opcional'}
                        </small>
                    </div>
                `;
            });
            
            html += '</div>';
            contenido.innerHTML = html;
        }

        function generarCampoAtributo(atributo, index) {
            const name = `atributos[${atributo.id}]`;
            const required = atributo.required ? 'required' : '';
            
            switch(atributo.tipo) {
                case 'text':
                case 'number':
                case 'email':
                case 'date':
                    return `
                        <input type="${atributo.tipo}" 
                               name="${name}" 
                               class="form-control border-start-0" 
                               placeholder="Ingrese ${atributo.nombre.toLowerCase()}"
                               ${required}>
                    `;
                    
                case 'select':
                    const opciones = atributo.options || [];
                    return `
                        <select name="${name}" class="form-select border-start-0" ${required}>
                            <option value="">Seleccione...</option>
                            ${opciones.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                        </select>
                    `;
                    
                case 'textarea':
                    return `
                        <textarea name="${name}" 
                                  class="form-control border-start-0" 
                                  rows="2" 
                                  placeholder="Ingrese ${atributo.nombre.toLowerCase()}"
                                  ${required}></textarea>
                    `;
                    
                default:
                    return `
                        <input type="text" 
                               name="${name}" 
                               class="form-control border-start-0" 
                               placeholder="Ingrese ${atributo.nombre.toLowerCase()}"
                               ${required}>
                    `;
            }
        }

        // ============================================================
        // 6. ENVÍO DEL FORMULARIO
        // ============================================================
        document.getElementById('productoForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                let fieldIsValid = true;
                
                if (field.tagName === 'SELECT') {
                    fieldIsValid = validateSelect(field);
                } else {
                    fieldIsValid = validateField(field);
                }
                
                if (!fieldIsValid) {
                    isValid = false;
                    field.classList.add('shake');
                    setTimeout(() => field.classList.remove('shake'), 500);
                }
                
                if (field.type === 'email' && field.value.trim() !== '') {
                    if (!validateEmail(field)) {
                        isValid = false;
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                const firstError = this.querySelector('.is-invalid');
                if (firstError) {
                    firstError.focus();
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                return;
            }
            
            // Deshabilitar botón
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Registrando...';
        });

        // ============================================================
        // 7. INICIALIZACIÓN
        // ============================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar contadores
            document.querySelectorAll('input[maxlength], textarea[maxlength]').forEach(input => {
                const counterId = input.id + 'Counter';
                const counter = document.getElementById(counterId);
                if (counter) {
                    counter.textContent = input.value.length;
                }
            });
            
            // Inicializar campos de asignación
            const estatusSelect = document.getElementById('estatus');
            if (estatusSelect) {
                toggleAsignacionFields(estatusSelect.value);
            }

            // Inicializar pago recurrente
            togglePagoRecurrente();

            // Validar campos con errores de Laravel
            document.querySelectorAll('.is-invalid').forEach(input => {
                const errorMessage = document.getElementById(input.id + 'Error');
                if (errorMessage && input.hasAttribute('required')) {
                    errorMessage.style.display = 'block';
                }
            });

            // Cargar atributos si hay categoría seleccionada
            const categoriaSelect = document.getElementById('categoria_id');
            if (categoriaSelect && categoriaSelect.value) {
                cargarAtributosDinamicos(categoriaSelect.value);
            }

            console.log('✅ Formulario de productos optimizado cargado correctamente');
        });
    </script>
</x-layout>