<?php
// app/Models/Proveedor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'categoria_id',
        'proveedor',
        'asesor',
        'telefono_contacto',
        'correo',
        'ciudad',
        'descripcion_corta',
        'fecha_facturacion',
        'dias_alertar',
        'monitoreo_activo'
    ];

    protected $table = 'proveedores';

    /**
     * Los atributos que deben ser ocultados para arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'fecha_facturacion' => 'date',
        'monitoreo_activo' => 'boolean',
        'dias_alertar' => 'integer'
    ];

    /**
     * Reglas de validación para el modelo.
     */
    public static $rules = [
       
        'categoria_id' => 'required|exists:categorias,id',
        'proveedor' => 'required|string|max:100',
        'asesor' => 'required|string|max:100',
        'telefono_contacto' => 'nullable|string|max:50',
        'correo' => 'required|email|unique:proveedores,correo',
        'descripcion_corta' => 'nullable|string|max:255',
        'fecha_facturacion' => 'nullable|date',
        'dias_alertar' => 'nullable|integer|min:1|max:90',
        'monitoreo_activo' => 'boolean'
    ];

    // ============ RELACIONES ============
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    /**
     * Relación: Un proveedor tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Relación: Un proveedor tiene muchas facturas
     */
    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }

    /**
     * Relación: Un proveedor tiene muchas alertas
     */
    public function alertas()
    {
        return $this->hasMany(Alerta::class);
    }

    // ============ SCOPES ============

    /**
     * Scope para búsqueda por nombre, asesor o correo.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('proveedor', 'LIKE', '%' . $search . '%')
                     ->orWhere('asesor', 'LIKE', '%' . $search . '%')
                     ->orWhere('correo', 'LIKE', '%' . $search . '%');
    }

    /**
     * Scope para filtrar por proveedor activo (con productos).
     */
    public function scopeWithProducts($query)
    {
        return $query->has('productos');
    }

    /**
     * Scope para filtrar proveedores con monitoreo activo
     */
    public function scopeMonitoreoActivo($query)
    {
        return $query->where('monitoreo_activo', true)
                     ->whereNotNull('fecha_facturacion');
    }

    /**
     * Scope para filtrar proveedores sin fecha de facturación
     */
    public function scopeSinFechaFacturacion($query)
    {
        return $query->whereNull('fecha_facturacion');
    }

    /**
     * Scope para ordenar por nombre.
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('proveedor', $direction);
    }

    /**
     * Scope para ordenar por días sin facturar
     */
    public function scopeOrderByDiasSinFacturar($query)
    {
        return $query->orderByRaw('
            CASE 
                WHEN fecha_facturacion IS NOT NULL THEN DATEDIFF(NOW(), fecha_facturacion)
                ELSE 0
            END DESC
        ');
    }

    // ============ ACCESORS ============

    /**
     * Accesor para obtener el nombre del proveedor (alias de proveedor).
     */
    public function getNombreProveedorAttribute()
    {
        return $this->proveedor;
    }

    /**
     * Accesor para obtener el proveedor formateado.
     */
    public function getProveedorAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Accesor para obtener el nombre completo del proveedor con contacto.
     */
    public function getNombreCompletoAttribute()
    {
        return $this->proveedor . ' - ' . $this->asesor;
    }

    /**
     * Accesor para obtener días sin facturar
     */
    public function getDiasSinFacturarAttribute()
{
    if (!$this->fecha_facturacion) {
        return null;
    }

    $ultimaFactura = $this->facturas()
        ->orderBy('fecha_factura', 'desc')
        ->first();

    if ($ultimaFactura) {
        // Calcular días entre la última factura y hoy
        $dias = $ultimaFactura->fecha_factura->diffInDays(now(), false);
        // Redondear hacia abajo (floor) para números negativos y positivos
        return floor($dias);
    }

    // Si no hay facturas, calcular días desde la fecha de facturación
    $dias = $this->fecha_facturacion->diffInDays(now(), false);
    return floor($dias);
}

/**
 * Accesor para obtener días sin facturar formateados
 */
public function getDiasSinFacturarFormateadoAttribute()
{
    $dias = $this->dias_sin_facturar;
    if ($dias === null) {
        return 'Sin datos';
    }
    
    if ($dias < 0) {
        return 'En ' . abs($dias) . ' días';
    }
    
    if ($dias == 0) {
        return 'Hoy';
    }
    
    return $dias . ' días';
}

/**
 * Accesor para obtener próxima fecha esperada
 */
public function getProximaFechaEsperadaAttribute()
{
    $ultimaFactura = $this->facturas()
        ->orderBy('fecha_factura', 'desc')
        ->first();

    if ($ultimaFactura) {
        return $ultimaFactura->fecha_factura->copy()->addMonth();
    }

    if ($this->fecha_facturacion) {
        return $this->fecha_facturacion->copy()->addMonth();
    }

    return null;
}

/**
 * Accesor para obtener días hasta la próxima fecha esperada (redondeado hacia abajo)
 */
public function getDiasHastaProximaFacturaAttribute()
{
    $proximaFecha = $this->proxima_fecha_esperada;
    if (!$proximaFecha) {
        return null;
    }
    
    $dias = now()->diffInDays($proximaFecha, false);
    return floor($dias);
}

    /**
     * Accesor para obtener estado del monitoreo
     */
    public function getEstadoMonitoreoAttribute()
    {
        if (!$this->monitoreo_activo) {
            return 'inactivo';
        }

        if (!$this->fecha_facturacion) {
            return 'sin_configurar';
        }

        $dias = $this->dias_sin_facturar;
        if ($dias === null) {
            return 'sin_datos';
        }

        if ($dias > 30) {
            return 'critico';
        } elseif ($dias > 15) {
            return 'atencion';
        } elseif ($dias > $this->dias_alertar) {
            return 'alerta';
        }

        return 'normal';
    }

    /**
     * Accesor para obtener color del estado
     */
    public function getEstadoColorAttribute()
    {
        return [
            'normal' => 'success',
            'alerta' => 'warning',
            'atencion' => 'orange',
            'critico' => 'danger',
            'sin_configurar' => 'secondary',
            'sin_datos' => 'info',
            'inactivo' => 'secondary'
        ][$this->estado_monitoreo] ?? 'secondary';
    }

    /**
     * Accesor para obtener icono del estado
     */
    public function getEstadoIconoAttribute()
    {
        return [
            'normal' => 'fa-check-circle',
            'alerta' => 'fa-clock',
            'atencion' => 'fa-exclamation-triangle',
            'critico' => 'fa-exclamation-circle',
            'sin_configurar' => 'fa-cog',
            'sin_datos' => 'fa-info-circle',
            'inactivo' => 'fa-pause-circle'
        ][$this->estado_monitoreo] ?? 'fa-circle';
    }

    /**
     * Accesor para obtener etiqueta del estado
     */
    public function getEstadoLabelAttribute()
    {
        return [
            'normal' => '✅ Normal',
            'alerta' => '⚡ Alerta',
            'atencion' => '⚠️ Atención',
            'critico' => '🚨 Crítico',
            'sin_configurar' => '⚙️ Sin configurar',
            'sin_datos' => '📊 Sin datos',
            'inactivo' => '⏸️ Inactivo'
        ][$this->estado_monitoreo] ?? '❓ Desconocido';
    }

    /**
     * Accesor para obtener promedio mensual de facturas
     */
    public function getPromedioMensualAttribute()
    {
        $facturasUltimos6Meses = $this->facturas()
            ->where('fecha_factura', '>=', now()->subMonths(6))
            ->count();
        
        return round($facturasUltimos6Meses / 6, 1);
    }

    // ============ MUTADORES ============

    /**
     * Mutador para capitalizar el nombre del proveedor.
     */
    public function setProveedorAttribute($value)
    {
        $this->attributes['proveedor'] = ucwords(trim($value));
    }

    /**
     * Mutador para capitalizar el nombre del asesor.
     */
    public function setAsesorAttribute($value)
    {
        $this->attributes['asesor'] = ucwords(trim($value));
    }

    /**
     * Mutador para convertir correo a minúsculas.
     */
    public function setCorreoAttribute($value)
    {
        $this->attributes['correo'] = strtolower(trim($value));
    }

    /**
     * Mutador para asegurar que días_alertar tenga un valor predeterminado
     */
    public function setDiasAlertarAttribute($value)
    {
        $this->attributes['dias_alertar'] = $value ?: 5;
    }

    // ============ HELPERS ============

    /**
     * Verificar si el proveedor tiene productos asociados.
     */
    public function hasProducts()
    {
        return $this->productos()->exists();
    }

    /**
     * Verificar si el proveedor tiene facturas asociadas.
     */
    public function hasFacturas()
    {
        return $this->facturas()->exists();
    }

    /**
     * Verificar si el proveedor tiene monitoreo activo
     */
    public function tieneMonitoreoActivo()
    {
        return $this->monitoreo_activo && $this->fecha_facturacion;
    }

    /**
     * Obtener el conteo de productos del proveedor.
     */
    public function getProductCountAttribute()
    {
        return $this->productos()->count();
    }

    /**
     * Obtener el conteo de facturas del proveedor.
     */
    public function getFacturaCountAttribute()
    {
        return $this->facturas()->count();
    }

    /**
     * Obtener el total facturado del proveedor.
     */
    public function getTotalFacturadoAttribute()
    {
        return $this->facturas()->sum('monto');
    }

    /**
     * Obtener el total facturado en USD del proveedor.
     */
    public function getTotalFacturadoUsdAttribute()
    {
        return $this->facturas()->sum('monto_usd');
    }

    /**
     * Obtener alertas activas no leídas del proveedor
     */
    public function getAlertasPendientesAttribute()
    {
        return $this->alertas()
            ->where('activa', true)
            ->where('leida', false)
            ->count();
    }

    /**
     * Obtener última factura del proveedor
     */
    public function getUltimaFacturaAttribute()
    {
        return $this->facturas()
            ->orderBy('fecha_factura', 'desc')
            ->first();
    }

    /**
     * Verificar si necesita generar alerta
     */
    public function necesitaAlerta()
    {
        if (!$this->tieneMonitoreoActivo()) {
            return false;
        }

        $dias = $this->dias_sin_facturar;
        if ($dias === null) {
            return false;
        }

        return $dias > $this->dias_alertar;
    }
}