<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_categoria',
        'descripcion',
        'icono',
        'color',
        'orden',
        'activo',
        'creado_por',
        'actualizado_por'
    ];

    /**
     * Nombre de la tabla.
     *
     * @var string
     */
    protected $table = 'categorias';

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
        'activo' => 'boolean',
        'orden' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // ============================================================
    // RELACIONES
    // ============================================================
        /**
     * Relación: Una categoría tiene muchos proveedores.
     */
    public function proveedores()
    {
        return $this->hasMany(Proveedor::class);
    }
    /**
     * Relación: Una categoría tiene muchos productos.
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    /**
     * Relación: Usuario que creó la categoría.
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    /**
     * Relación: Usuario que actualizó la categoría.
     */
    public function actualizador()
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    /**
     * Scope para búsqueda por nombre o descripción.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('nombre_categoria', 'LIKE', '%' . $search . '%')
                     ->orWhere('descripcion', 'LIKE', '%' . $search . '%');
    }

    /**
     * Scope para ordenar por nombre.
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('nombre_categoria', $direction);
    }

    /**
     * Scope para obtener solo categorías activas.
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para obtener solo categorías inactivas.
     */
    public function scopeInactivas($query)
    {
        return $query->where('activo', false);
    }

    /**
     * Scope para obtener categorías ordenadas.
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('orden')->orderBy('nombre_categoria');
    }

    /**
     * Scope para obtener solo categorías con productos.
     */
    public function scopeWithProducts($query)
    {
        return $query->has('productos');
    }

    /**
     * Scope para obtener categorías sin productos.
     */
    public function scopeWithoutProducts($query)
    {
        return $query->doesntHave('productos');
    }

    // ============================================================
    // HELPERS / ACCESORS & MUTATORS
    // ============================================================

    /**
     * Verificar si la categoría tiene productos asociados.
     */
    public function hasProducts()
    {
        return $this->productos()->exists();
    }

    /**
     * Obtener el conteo de productos de la categoría.
     */
    public function getProductCountAttribute()
    {
        return $this->productos()->count();
    }

    /**
     * Obtener el nombre del creador.
     */
    public function getCreadorNombreAttribute()
    {
        return $this->creador ? $this->creador->nombre_completo : 'Sistema';
    }

    /**
     * Obtener el nombre del actualizador.
     */
    public function getActualizadorNombreAttribute()
    {
        return $this->actualizador ? $this->actualizador->nombre_completo : 'Sistema';
    }

    /**
     * Obtener el estado de la categoría en formato badge.
     */
    public function getEstadoBadgeAttribute()
    {
        if ($this->activo) {
            return ['label' => 'Activa', 'color' => 'success', 'icon' => 'fa-check-circle'];
        }
        return ['label' => 'Inactiva', 'color' => 'secondary', 'icon' => 'fa-times-circle'];
    }

    /**
     * Verificar si la categoría está activa.
     */
    public function isActiva()
    {
        return $this->activo;
    }

    /**
     * Obtener el nombre completo de la categoría.
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombre_categoria;
    }

    /**
     * Obtener la URL para ver la categoría.
     */
    public function getUrlAttribute()
    {
        return route('categorias.show', $this);
    }

    /**
     * Obtener la URL para editar la categoría.
     */
    public function getEditUrlAttribute()
    {
        return route('categorias.edit', $this);
    }
    public function scopeWithProveedores($query)
    {
        return $query->has('proveedores');
    }

    public function scopeWithoutProveedores($query)
    {
        return $query->doesntHave('proveedores');
    }
    public function hasProveedores()
    {
        return $this->proveedores()->exists();
    }

    public function getProveedorCountAttribute()
    {
        return $this->proveedores()->count();
    }
    /**
     * Obtener la URL para eliminar la categoría.
     */
    public function getDeleteUrlAttribute()
    {
        return route('categorias.destroy', $this);
    }

    /**
     * Mutador para capitalizar el nombre al guardar.
     */
    public function setNombreCategoriaAttribute($value)
    {
        $this->attributes['nombre_categoria'] = ucfirst(trim($value));
    }

    /**
     * Accesor para obtener el nombre formateado.
     */
    public function getNombreCategoriaAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * Mutador para limitar la descripción.
     */
    public function setDescripcionAttribute($value)
    {
        $this->attributes['descripcion'] = $value ? trim($value) : null;
    }
}