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
        'activo'
    ];

    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // ============================================================
    // RELACIONES EXISTENTES
    // ============================================================

    /**
     * Relación con categoría.
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación con proveedor.
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Relación con historial de estatus.
     */
    public function estatusHistorial(): HasMany
    {
        return $this->hasMany(EstatusProducto::class)->orderBy('fecha_cambio', 'desc');
    }

    /**
     * Relación con el estatus actual.
     */
    public function estatusActual(): HasOne
    {
        return $this->hasOne(EstatusProducto::class)
                    ->latest('fecha_cambio')
                    ->latest('id');
    }

    /**
     * Relación con asignaciones.
     */
    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class)->orderBy('fecha_asignacion', 'desc');
    }

    /**
     * Relación con la asignación activa.
     */
    public function asignacionActiva(): HasOne
    {
        return $this->hasOne(Asignacion::class)
                    ->where('estado', 'activa')
                    ->latest('fecha_asignacion');
    }

    // ============================================================
    // 🆕 RELACIÓN DINÁMICA PARA DETALLES
    // ============================================================

    /**
     * Obtener los detalles específicos del producto según su categoría.
     * Esta relación es dinámica y se resuelve en tiempo de ejecución.
     */
    public function detalles()
    {
        // Obtener la categoría del producto
        $categoria = $this->categoria;
        
        // Si no hay categoría o no tiene modelo de detalles definido
        if (!$categoria || !$categoria->modelo_detalle) {
            return null;
        }
        
        // Construir el nombre del modelo de detalles
        $modeloDetalle = "App\\Models\\Detalles\\" . $categoria->modelo_detalle;
        
        // Verificar si la clase existe
        if (!class_exists($modeloDetalle)) {
            return null;
        }
        
        // Retornar la relación hasOne con el modelo de detalles
        return $this->hasOne($modeloDetalle, 'producto_id');
    }

    /**
     * Obtener los detalles del producto (accesor mágico).
     * Útil para usar como $producto->detalles en la vista.
     */
    public function getDetallesAttribute()
    {
        $categoria = $this->categoria;
        
        if (!$categoria || !$categoria->modelo_detalle) {
            return null;
        }
        
        $modeloDetalle = "App\\Models\\Detalles\\" . $categoria->modelo_detalle;
        
        if (!class_exists($modeloDetalle)) {
            return null;
        }
        
        return $this->hasOne($modeloDetalle, 'producto_id')->first();
    }

    /**
     * Obtener los detalles del producto con sus atributos formateados.
     * Útil para mostrar en la vista de inventario.
     */
    public function getDetallesFormateadosAttribute()
    {
        $detalles = $this->detalles;
        
        if (!$detalles) {
            return [];
        }
        
        // Obtener todos los atributos del modelo de detalles
        $atributos = $detalles->getAttributes();
        
        // Filtrar atributos que no queremos mostrar
        $excluir = ['id', 'producto_id', 'created_at', 'updated_at', 'deleted_at'];
        
        $resultado = [];
        foreach ($atributos as $key => $value) {
            if (!in_array($key, $excluir) && $value !== null && $value !== '') {
                // Formatear el nombre del atributo
                $nombre = ucfirst(str_replace('_', ' ', $key));
                $resultado[$nombre] = $value;
            }
        }
        
        return $resultado;
    }

    // ============================================================
    // MÉTODOS EXISTENTES (sin cambios)
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

    // Scopes
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

    // Métodos
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

    // Mutadores
    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = ucwords(trim($value));
    }

    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper(trim($value));
    }

    // Accesores
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