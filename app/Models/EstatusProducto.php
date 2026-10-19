<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstatusProducto extends Model
{
    protected $table = 'estatus_productos';

    protected $fillable = [
        'producto_id',
        'estatus',
        'observaciones',
        'usuario_registro_id',
        'fecha_cambio'
    ];

    protected $casts = [
        'fecha_cambio' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes de estatus
    const ESTATUS_DISPONIBLE = 'disponible';
    const ESTATUS_ASIGNADO = 'asignado';
    const ESTATUS_MANTENIMIENTO = 'mantenimiento';
    const ESTATUS_REPARACION = 'reparacion';
    const ESTATUS_PRESTAMO = 'prestamo';
    const ESTATUS_BAJA = 'baja';

    const ESTATUSES = [
        self::ESTATUS_DISPONIBLE => 'Disponible',
        self::ESTATUS_ASIGNADO => 'Asignado',
        self::ESTATUS_MANTENIMIENTO => 'En Mantenimiento',
        self::ESTATUS_REPARACION => 'En Reparación',
        self::ESTATUS_PRESTAMO => 'En Préstamo',
        self::ESTATUS_BAJA => 'Dado de Baja',
    ];

    const ESTATUS_COLORS = [
        self::ESTATUS_DISPONIBLE => 'success',
        self::ESTATUS_ASIGNADO => 'warning',
        self::ESTATUS_MANTENIMIENTO => 'info',
        self::ESTATUS_REPARACION => 'danger',
        self::ESTATUS_PRESTAMO => 'primary',
        self::ESTATUS_BAJA => 'secondary',
    ];

    /**
     * Relación con el producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación con el usuario que registró el cambio.
     */
    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_registro_id');
    }

    /**
     * Obtener el badge HTML del estatus.
     */
    public function getBadgeAttribute(): string
    {
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            self::ESTATUS_COLORS[$this->estatus] ?? 'secondary',
            self::ESTATUSES[$this->estatus] ?? $this->estatus
        );
    }

    /**
     * Scope para filtrar por estatus.
     */
    public function scopeByEstatus($query, $estatus)
    {
        return $query->where('estatus', $estatus);
    }

    /**
     * Scope para obtener el último estatus de un producto.
     */
    public function scopeUltimo($query)
    {
        return $query->orderBy('fecha_cambio', 'desc')->orderBy('id', 'desc');
    }
}