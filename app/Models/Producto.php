<?php
// app/Models/Producto.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'precio_compra',
        'precio_venta',
        'stock',
        'stock_minimo',
        'categoria_id',
        'proveedor_id',
        'unidad_medida',
        'ubicacion',
        'activo',
        // Nuevos campos
        'tipo_pago',
        'frecuencia_pago',
        'dia_facturacion',
        'proxima_facturacion',
        'notificar_antes',
        'dias_notificacion'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'notificar_antes' => 'boolean',
        'dia_facturacion' => 'integer',
        'dias_notificacion' => 'integer',
        'proxima_facturacion' => 'date'
    ];

    // ============================================================
    // CONSTANTES
    // ============================================================
    
    const TIPO_PAGO = [
        'unico' => 'Pago Único',
        'recurrente' => 'Pago Recurrente (Suscripción)'
    ];

    const FRECUENCIA_PAGO = [
        'mensual' => 'Mensual',
        'trimestral' => 'Trimestral',
        'semestral' => 'Semestral',
        'anual' => 'Anual'
    ];

    const DIAS_MES = [
        1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5',
        6 => '6', 7 => '7', 8 => '8', 9 => '9', 10 => '10',
        11 => '11', 12 => '12', 13 => '13', 14 => '14', 15 => '15',
        16 => '16', 17 => '17', 18 => '18', 19 => '19', 20 => '20',
        21 => '21', 22 => '22', 23 => '23', 24 => '24', 25 => '25',
        26 => '26', 27 => '27', 28 => '28', 29 => '29', 30 => '30',
        31 => '31'
    ];

    // ============================================================
    // RELACIONES
    // ============================================================

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    public function estatusHistorial(): HasMany
    {
        return $this->hasMany(EstatusProducto::class)->orderBy('fecha_cambio', 'desc');
    }

    public function estatusActual(): HasOne
    {
        return $this->hasOne(EstatusProducto::class)
                    ->latest('fecha_cambio')
                    ->latest('id');
    }

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class)->orderBy('fecha_asignacion', 'desc');
    }

    public function asignacionActiva(): HasOne
    {
        return $this->hasOne(Asignacion::class)
                    ->where('estado', 'activa')
                    ->latest('fecha_asignacion');
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getTipoPagoLabelAttribute()
    {
        return self::TIPO_PAGO[$this->tipo_pago] ?? $this->tipo_pago;
    }

    public function getFrecuenciaPagoLabelAttribute()
    {
        return self::FRECUENCIA_PAGO[$this->frecuencia_pago] ?? $this->frecuencia_pago;
    }

    public function getDiasParaFacturarAttribute()
    {
        if (!$this->proxima_facturacion) {
            return null;
        }
        return now()->diffInDays($this->proxima_facturacion, false);
    }

    public function getEstadoFacturacionAttribute()
    {
        if ($this->tipo_pago === 'unico') {
            return 'no_aplica';
        }

        if (!$this->proxima_facturacion) {
            return 'sin_configurar';
        }

        $dias = $this->dias_para_facturar;
        
        if ($dias === null) return 'sin_datos';
        
        if ($dias < 0) {
            return 'vencido';
        } elseif ($dias == 0) {
            return 'hoy';
        } elseif ($dias <= $this->dias_notificacion) {
            return 'proximo';
        }
        
        return 'normal';
    }

    public function getEstadoFacturacionColorAttribute()
    {
        return [
            'no_aplica' => 'secondary',
            'sin_configurar' => 'secondary',
            'sin_datos' => 'secondary',
            'vencido' => 'danger',
            'hoy' => 'warning',
            'proximo' => 'info',
            'normal' => 'success'
        ][$this->estado_facturacion] ?? 'secondary';
    }

    public function getEstadoFacturacionLabelAttribute()
    {
        return [
            'no_aplica' => 'No aplica',
            'sin_configurar' => 'Sin configurar',
            'sin_datos' => 'Sin datos',
            'vencido' => '🔴 Vencido',
            'hoy' => '🟡 Hoy',
            'proximo' => '🔵 Próximo',
            'normal' => '✅ Normal'
        ][$this->estado_facturacion] ?? 'Desconocido';
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeRecurrentes($query)
    {
        return $query->where('tipo_pago', 'recurrente');
    }

    public function scopePorVencer($query, $dias = 5)
    {
        return $query->where('tipo_pago', 'recurrente')
                     ->whereNotNull('proxima_facturacion')
                     ->whereDate('proxima_facturacion', '<=', now()->addDays($dias))
                     ->whereDate('proxima_facturacion', '>=', now());
    }

    public function scopeVencidos($query)
    {
        return $query->where('tipo_pago', 'recurrente')
                     ->whereNotNull('proxima_facturacion')
                     ->whereDate('proxima_facturacion', '<', now());
    }

    public function scopeSinFacturar($query)
    {
        return $query->where('tipo_pago', 'recurrente')
                     ->whereNotNull('proxima_facturacion')
                     ->whereDoesntHave('facturas', function($q) {
                         $q->whereDate('fecha_factura', '>=', now()->subDays(30));
                     });
    }

    // ============================================================
    // HELPERS
    // ============================================================

    public function calcularProximaFacturacion()
    {
        if ($this->tipo_pago !== 'recurrente' || !$this->dia_facturacion) {
            return null;
        }

        $fecha = now();
        $dia = $this->dia_facturacion;
        
        // Si el día es 31 y el mes tiene menos días, usar el último día del mes
        $ultimoDiaMes = $fecha->copy()->endOfMonth()->day;
        $diaFacturacion = min($dia, $ultimoDiaMes);
        
        $proxima = $fecha->copy()->day($diaFacturacion);
        
        // Si la fecha ya pasó este mes, mover al próximo mes
        if ($proxima->isPast()) {
            $proxima->addMonth();
            // Ajustar día si el mes siguiente tiene menos días
            $ultimoDiaMesSiguiente = $proxima->copy()->endOfMonth()->day;
            $proxima->day(min($dia, $ultimoDiaMesSiguiente));
        }
        
        // Aplicar frecuencia
        switch ($this->frecuencia_pago) {
            case 'mensual':
                // Ya está en el mes correcto
                break;
            case 'trimestral':
                $proxima->addMonths(3);
                break;
            case 'semestral':
                $proxima->addMonths(6);
                break;
            case 'anual':
                $proxima->addYear();
                break;
        }
        
        return $proxima;
    }

    public function actualizarProximaFacturacion()
    {
        if ($this->tipo_pago !== 'recurrente') {
            $this->proxima_facturacion = null;
            $this->save();
            return null;
        }

        $proxima = $this->calcularProximaFacturacion();
        $this->proxima_facturacion = $proxima;
        $this->save();
        
        return $proxima;
    }

    public function registrarFactura($factura)
    {
        // Al registrar una factura para este producto/servicio
        if ($this->tipo_pago === 'recurrente') {
            // Calcular la próxima fecha de facturación
            $this->actualizarProximaFacturacion();
        }
    }

    public function necesitaAlerta()
    {
        if ($this->tipo_pago !== 'recurrente') {
            return false;
        }

        if (!$this->proxima_facturacion) {
            return false;
        }

        $dias = $this->dias_para_facturar;
        
        if ($dias === null) return false;
        
        // Alerta si está vencido o está próximo a vencer
        return $dias < 0 || $dias <= $this->dias_notificacion;
    }

    // ============================================================
    // MÉTODOS EXISTENTES
    // ============================================================

    public function getEstatusAttribute()
    {
        return $this->estatusActual->estatus ?? EstatusProducto::ESTATUS_DISPONIBLE;
    }

    public function getEstatusLabelAttribute()
    {
        return EstatusProducto::ESTATUSES[$this->estatus] ?? 'Disponible';
    }

    public function getEstatusColorAttribute()
    {
        return EstatusProducto::ESTATUS_COLORS[$this->estatus] ?? 'success';
    }

    public function getEstatusBadgeAttribute()
    {
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $this->estatus_color,
            $this->estatus_label
        );
    }

    public function isAsignado()
    {
        return in_array($this->estatus, [
            EstatusProducto::ESTATUS_ASIGNADO,
            EstatusProducto::ESTATUS_PRESTAMO
        ]);
    }

    public function isDisponible()
    {
        return $this->estatus === EstatusProducto::ESTATUS_DISPONIBLE;
    }

    public function getAsignacionActivaAttribute()
    {
        return $this->asignacionActiva;
    }

    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'stock_minimo');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('nombre', 'LIKE', '%' . $search . '%')
                     ->orWhere('codigo', 'LIKE', '%' . $search . '%')
                     ->orWhere('descripcion', 'LIKE', '%' . $search . '%');
    }

    public function scopeByCategory($query, $categoriaId)
    {
        return $query->where('categoria_id', $categoriaId);
    }

    public function scopeBySupplier($query, $proveedorId)
    {
        return $query->where('proveedor_id', $proveedorId);
    }

    public function hasStock()
    {
        return $this->stock > 0;
    }

    public function isLowStock()
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function getInventoryValueAttribute()
    {
        return $this->stock * $this->precio_compra;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->precio_compra == 0) return 0;
        return (($this->precio_venta - $this->precio_compra) / $this->precio_compra) * 100;
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucwords(trim($value));
    }

    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper(trim($value));
    }

    public function getNombreAttribute($value)
    {
        return ucwords($value);
    }

    public function getCodigoAttribute($value)
    {
        return strtoupper($value);
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return ['class' => 'danger', 'label' => 'Sin stock'];
        } elseif ($this->isLowStock()) {
            return ['class' => 'warning', 'label' => 'Stock bajo'];
        } else {
            return ['class' => 'success', 'label' => 'Disponible'];
        }
    }
}