<!-- ============================================================ -->
<!-- SECCIÓN 1: INFORMACIÓN PRINCIPAL -->
<!-- ============================================================ -->
<div class="mb-5">
    <h5 class="border-bottom pb-3 text-secondary fw-bold">
        <i class="fas fa-info-circle me-2 text-primary"></i> Información Principal
    </h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold required-field">
                <i class="fas fa-tag me-1 text-primary"></i> Nombre del Producto
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-box text-secondary"></i></span>
                <input type="text" id="nombre" name="nombre" class="form-control border-start-0 @error('nombre') is-invalid @enderror" 
                       placeholder="Ej: Laptop HP Pavilion" value="{{ old('nombre') }}" required maxlength="100"
                       oninput="validateField(this); updateCounter(this, 'nombreCounter')">
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted"><i class="fas fa-asterisk text-danger"></i> Máximo 100 caracteres</small>
                <small class="text-muted"><span id="nombreCounter">0</span>/100</small>
            </div>
            @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold required-field">
                <i class="fas fa-barcode me-1 text-primary"></i> Código del Producto
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-barcode text-secondary"></i></span>
                <input type="text" id="codigo" name="codigo" class="form-control border-start-0 @error('codigo') is-invalid @enderror" 
                       placeholder="Ej: PROD-001" value="{{ old('codigo') }}" required maxlength="50"
                       oninput="validateField(this); updateCounter(this, 'codigoCounter')">
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted"><i class="fas fa-asterisk text-danger"></i> Máximo 50 caracteres</small>
                <small class="text-muted"><span id="codigoCounter">0</span>/50</small>
            </div>
            @error('codigo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold"><i class="fas fa-file-alt me-1 text-primary"></i> Descripción</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-file-alt text-secondary"></i></span>
                <textarea id="descripcion" name="descripcion" class="form-control border-start-0 @error('descripcion') is-invalid @enderror" 
                          rows="2" placeholder="Breve descripción del producto..." maxlength="500"
                          oninput="updateCounter(this, 'descCounter')">{{ old('descripcion') }}</textarea>
            </div>
            <div class="d-flex justify-content-between mt-1">
                <small class="text-muted">Máximo 500 caracteres</small>
                <small class="text-muted"><span id="descCounter">0</span>/500</small>
            </div>
            @error('descripcion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECCIÓN 2: CATEGORÍA Y PROVEEDOR -->
<!-- ============================================================ -->
<div class="mb-5">
    <h5 class="border-bottom pb-3 text-secondary fw-bold">
        <i class="fas fa-diagram-3 me-2 text-primary"></i> Categoría y Proveedor
    </h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold required-field"><i class="fas fa-folder me-1 text-primary"></i> Categoría</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-folder text-secondary"></i></span>
                <select class="form-select border-start-0 @error('categoria_id') is-invalid @enderror" name="categoria_id" required>
                    <option value="">Seleccione una categoría...</option>
                    @foreach($categorias ?? [] as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre_categoria }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('categoria_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold required-field"><i class="fas fa-building me-1 text-primary"></i> Proveedor</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-building text-secondary"></i></span>
                <select class="form-select border-start-0 @error('proveedor_id') is-invalid @enderror" name="proveedor_id" required>
                    <option value="">Seleccione un proveedor...</option>
                    @foreach($proveedores ?? [] as $proveedor)
                        <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                            {{ $proveedor->proveedor ?? $proveedor->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            @error('proveedor_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECCIÓN 3: CONFIGURACIÓN DE PAGO -->
<!-- ============================================================ -->
<div class="mb-5">
    <h5 class="border-bottom pb-3 text-secondary fw-bold">
        <i class="fas fa-credit-card me-2 text-primary"></i> Configuración de Pago
    </h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold required-field"><i class="fas fa-tag me-1 text-primary"></i> Tipo de Pago</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-tag text-secondary"></i></span>
                <select name="tipo_pago" id="tipoPago" class="form-select border-start-0 @error('tipo_pago') is-invalid @enderror" 
                        onchange="togglePagoRecurrente()" required>
                    <option value="unico" {{ old('tipo_pago', 'unico') == 'unico' ? 'selected' : '' }}>Pago Único</option>
                    <option value="recurrente" {{ old('tipo_pago') == 'recurrente' ? 'selected' : '' }}>Pago Recurrente (Suscripción)</option>
                </select>
            </div>
            @error('tipo_pago') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6" id="frecuenciaContainer" style="{{ old('tipo_pago', 'unico') == 'recurrente' ? '' : 'display: none;' }}">
            <label class="form-label fw-semibold required-field"><i class="fas fa-clock me-1 text-primary"></i> Frecuencia de Pago</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-clock text-secondary"></i></span>
                <select name="frecuencia_pago" class="form-select border-start-0 @error('frecuencia_pago') is-invalid @enderror">
                    <option value="">Seleccione una frecuencia...</option>
                    @foreach(\App\Models\Producto::FRECUENCIA_PAGO as $key => $label)
                        <option value="{{ $key }}" {{ old('frecuencia_pago') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @error('frecuencia_pago') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4" id="diaContainer" style="{{ old('tipo_pago', 'unico') == 'recurrente' ? '' : 'display: none;' }}">
            <label class="form-label fw-semibold required-field"><i class="fas fa-calendar-day me-1 text-primary"></i> Día de Facturación</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar-day text-secondary"></i></span>
                <select name="dia_facturacion" class="form-select border-start-0 @error('dia_facturacion') is-invalid @enderror">
                    <option value="">Seleccione un día...</option>
                    @foreach(\App\Models\Producto::DIAS_MES as $dia => $label)
                        <option value="{{ $dia }}" {{ old('dia_facturacion') == $dia ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @error('dia_facturacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4" id="notificacionContainer" style="{{ old('tipo_pago', 'unico') == 'recurrente' ? '' : 'display: none;' }}">
            <label class="form-label fw-semibold"><i class="fas fa-bell me-1 text-primary"></i> Días de Notificación</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-bell text-secondary"></i></span>
                <input type="number" name="dias_notificacion" class="form-control border-start-0 @error('dias_notificacion') is-invalid @enderror"
                       value="{{ old('dias_notificacion', 5) }}" min="1" max="30">
            </div>
            @error('dias_notificacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4" id="notificarContainer" style="{{ old('tipo_pago', 'unico') == 'recurrente' ? '' : 'display: none;' }}">
            <label class="form-label fw-semibold"><i class="fas fa-toggle-on me-1 text-primary"></i> Notificar Antes</label>
            <div class="mt-2">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="notificar_antes" id="notificarAntes" value="1" {{ old('notificar_antes', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="notificarAntes">Activar notificaciones</label>
                </div>
            </div>
        </div>
        <div class="col-12" id="proximaContainer" style="{{ old('tipo_pago', 'unico') == 'recurrente' ? '' : 'display: none;' }}">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-calendar-check fs-4 text-primary"></i>
                    <div>
                        <strong>Próxima fecha de facturación:</strong>
                        <span class="text-muted">Se calculará automáticamente al guardar</span>
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
    <h5 class="border-bottom pb-3 text-secondary fw-bold"><i class="fas fa-cash-stack me-2 text-primary"></i> Precios y Stock</h5>
    <div class="row g-4">
        <div class="col-md-3">
            <label class="form-label fw-semibold required-field"><i class="fas fa-arrow-down-circle me-1 text-primary"></i> Precio Compra</label>
            <div class="input-group">
                <span class="input-group-text bg-light">$</span>
                <input type="number" name="precio_compra" class="form-control @error('precio_compra') is-invalid @enderror" 
                       value="{{ old('precio_compra') }}" step="0.01" min="0" placeholder="0.00" required>
            </div>
            @error('precio_compra') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold required-field"><i class="fas fa-arrow-up-circle me-1 text-primary"></i> Precio Venta</label>
            <div class="input-group">
                <span class="input-group-text bg-light">$</span>
                <input type="number" name="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror" 
                       value="{{ old('precio_venta') }}" step="0.01" min="0" placeholder="0.00" required>
            </div>
            @error('precio_venta') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold required-field"><i class="fas fa-boxes me-1 text-primary"></i> Stock</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-boxes text-secondary"></i></span>
                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                       value="{{ old('stock', 0) }}" min="0" placeholder="0" required>
            </div>
            @error('stock') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold required-field"><i class="fas fa-exclamation-triangle me-1 text-primary"></i> Stock Mínimo</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fas fa-exclamation-triangle text-secondary"></i></span>
                <input type="number" name="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror" 
                       value="{{ old('stock_minimo', 0) }}" min="0" placeholder="0" required>
            </div>
            @error('stock_minimo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECCIÓN 5: ESTATUS Y ASIGNACIÓN -->
<!-- ============================================================ -->
<div class="mb-5">
    <h5 class="border-bottom pb-3 text-secondary fw-bold"><i class="fas fa-clipboard-check me-2 text-primary"></i> Estatus y Asignación</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold required-field"><i class="fas fa-circle me-1 text-primary"></i> Estatus del Producto</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-circle text-secondary"></i></span>
                <select name="estatus" class="form-select border-start-0 @error('estatus') is-invalid @enderror" required>
                    <option value="disponible" {{ old('estatus', 'disponible') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="asignado" {{ old('estatus') == 'asignado' ? 'selected' : '' }}>Asignado</option>
                    <option value="mantenimiento" {{ old('estatus') == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                    <option value="reparacion" {{ old('estatus') == 'reparacion' ? 'selected' : '' }}>En Reparación</option>
                    <option value="prestamo" {{ old('estatus') == 'prestamo' ? 'selected' : '' }}>En Préstamo</option>
                    <option value="baja" {{ old('estatus') == 'baja' ? 'selected' : '' }}>Dado de Baja</option>
                </select>
            </div>
            @error('estatus') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6" id="fechaAsignacionContainer" style="{{ old('estatus') == 'asignado' || old('estatus') == 'prestamo' ? '' : 'display: none;' }}">
            <label class="form-label fw-semibold"><i class="fas fa-calendar-day me-1 text-primary"></i> Fecha de Asignación</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-calendar text-secondary"></i></span>
                <input type="date" name="fecha_asignacion" class="form-control border-start-0 @error('fecha_asignacion') is-invalid @enderror" 
                       value="{{ old('fecha_asignacion', date('Y-m-d')) }}">
            </div>
            @error('fecha_asignacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
    <div id="asignacionFields" style="{{ old('estatus') == 'asignado' || old('estatus') == 'prestamo' ? '' : 'display: none;' }}">
        <div class="row g-4 mt-2">
            <div class="col-12"><div class="alert alert-info border-0 shadow-sm"><i class="fas fa-user-badge me-2"></i><strong>Información de Asignación</strong></div></div>
            <div class="col-md-6">
                <label class="form-label fw-semibold required-field"><i class="fas fa-user me-1 text-primary"></i> Asignado a</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-secondary"></i></span>
                    <input type="text" name="asignado_a" class="form-control border-start-0 @error('asignado_a') is-invalid @enderror" 
                           placeholder="Nombre completo del usuario" value="{{ old('asignado_a') }}" maxlength="100">
                </div>
                @error('asignado_a') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold required-field"><i class="fas fa-envelope me-1 text-primary"></i> Correo del Usuario</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-secondary"></i></span>
                    <input type="email" name="email_usuario" class="form-control border-start-0 @error('email_usuario') is-invalid @enderror" 
                           placeholder="usuario@empresa.com" value="{{ old('email_usuario') }}" maxlength="255">
                </div>
                @error('email_usuario') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold"><i class="fas fa-phone me-1 text-primary"></i> Teléfono del Usuario</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-phone text-secondary"></i></span>
                    <input type="tel" name="telefono_usuario" class="form-control border-start-0 @error('telefono_usuario') is-invalid @enderror" 
                           placeholder="+34 123 456 789" value="{{ old('telefono_usuario') }}" maxlength="50">
                </div>
                @error('telefono_usuario') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold"><i class="fas fa-sticky-note me-1 text-primary"></i> Notas de Asignación</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-sticky-note text-secondary"></i></span>
                    <textarea name="notas_asignacion" class="form-control border-start-0 @error('notas_asignacion') is-invalid @enderror" 
                              rows="2" placeholder="Observaciones sobre la asignación..." maxlength="500">{{ old('notas_asignacion') }}</textarea>
                </div>
                @error('notas_asignacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECCIÓN 6: INFORMACIÓN ADICIONAL -->
<!-- ============================================================ -->
<div class="mb-4">
    <h5 class="border-bottom pb-3 text-secondary fw-bold"><i class="fas fa-geo-alt me-2 text-primary"></i> Información Adicional</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="fas fa-ruler me-1 text-primary"></i> Unidad de Medida</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-ruler text-secondary"></i></span>
                <select class="form-select border-start-0 @error('unidad_medida') is-invalid @enderror" name="unidad_medida">
                    <option value="">Seleccione una unidad...</option>
                    <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                    <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo</option>
                    <option value="g" {{ old('unidad_medida') == 'g' ? 'selected' : '' }}>Gramo</option>
                    <option value="l" {{ old('unidad_medida') == 'l' ? 'selected' : '' }}>Litro</option>
                    <option value="ml" {{ old('unidad_medida') == 'ml' ? 'selected' : '' }}>Mililitro</option>
                    <option value="m" {{ old('unidad_medida') == 'm' ? 'selected' : '' }}>Metro</option>
                </select>
            </div>
            @error('unidad_medida') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold"><i class="fas fa-map-pin me-1 text-primary"></i> Ubicación</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-map-pin text-secondary"></i></span>
                <input type="text" name="ubicacion" class="form-control border-start-0 @error('ubicacion') is-invalid @enderror" 
                       placeholder="Ej: Estante A, Pasillo 3" value="{{ old('ubicacion') }}" maxlength="255">
            </div>
            @error('ubicacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SECCIÓN 7: ESTADO DEL PRODUCTO -->
<!-- ============================================================ -->
<div class="mb-4">
    <h5 class="border-bottom pb-3 text-secondary fw-bold"><i class="fas fa-toggle-on me-2 text-primary"></i> Estado del Producto</h5>
    <div class="row g-4">
        <div class="col-12">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo" {{ old('activo', true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="activo"><i class="fas fa-check-circle text-success me-1"></i> Producto Activo</label>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- SCRIPTS DE VALIDACIÓN (necesarios para el funcionamiento del formulario) -->
<!-- ============================================================ -->
<script>
    function togglePagoRecurrente() {
        const tipoPago = document.getElementById('tipoPago').value;
        const isRecurrente = tipoPago === 'recurrente';
        ['frecuenciaContainer', 'diaContainer', 'notificacionContainer', 'notificarContainer', 'proximaContainer'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = isRecurrente ? 'block' : 'none';
        });
        const frecuencia = document.querySelector('select[name="frecuencia_pago"]');
        const dia = document.querySelector('select[name="dia_facturacion"]');
        if (isRecurrente) {
            if (frecuencia) frecuencia.setAttribute('required', 'required');
            if (dia) dia.setAttribute('required', 'required');
        } else {
            if (frecuencia) frecuencia.removeAttribute('required');
            if (dia) dia.removeAttribute('required');
        }
    }

    function updateCounter(input, counterId) {
        const counter = document.getElementById(counterId);
        if (counter) {
            counter.textContent = input.value.length;
        }
    }

    function validateField(input) {
        // Puedes implementar validación adicional si lo deseas
    }
</script>