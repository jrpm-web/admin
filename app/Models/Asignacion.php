<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asignacion extends Model
{
    protected $table = 'asignaciones';

    protected $fillable = [
        'producto_id',
        'asignado_a',
        'email_usuario',
        'telefono_usuario',
        'fecha_asignacion',
        'fecha_devolucion',
        'notas',
        'tipo',
        'estado',
        'usuario_registro_id'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'fecha_devolucion' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Constantes de tipo
    const TIPO_ASIGNACION = 'asignacion';
    const TIPO_PRESTAMO = 'prestamo';

    const TIPOS = [
        self::TIPO_ASIGNACION => 'Asignación',
        self::TIPO_PRESTAMO => 'Préstamo',
    ];

    // Constantes de estado
    const ESTADO_ACTIVA = 'activa';
    const ESTADO_DEVUELTA = 'devuelta';
    const ESTADO_VENCIDA = 'vencida';

    const ESTADOS = [
        self::ESTADO_ACTIVA => 'Activa',
        self::ESTADO_DEVUELTA => 'Devuelta',
        self::ESTADO_VENCIDA => 'Vencida',
    ];

    const ESTADOS_COLORS = [
        self::ESTADO_ACTIVA => 'success',
        self::ESTADO_DEVUELTA => 'info',
        self::ESTADO_VENCIDA => 'danger',
    ];

    /**
     * Relación con el producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Relación con el usuario que registró la asignación.
     */
    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_registro_id');
    }

    /**
     * Verificar si la asignación está activa.
     */
    public function isActiva(): bool
    {
        return $this->estado === self::ESTADO_ACTIVA;
    }

    /**
     * Verificar si la asignación está vencida.
     */
    public function isVencida(): bool
    {
        return $this->estado === self::ESTADO_VENCIDA;
    }

    /**
     * Verificar si la asignación es un préstamo.
     */
    public function isPrestamo(): bool
    {
        return $this->tipo === self::TIPO_PRESTAMO;
    }

    /**
     * Obtener el badge del estado.
     */
    public function getEstadoBadgeAttribute(): string
    {
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            self::ESTADOS_COLORS[$this->estado] ?? 'secondary',
            self::ESTADOS[$this->estado] ?? $this->estado
        );
    }

    /**
     * Scope para asignaciones activas.
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', self::ESTADO_ACTIVA);
    }

    /**
     * Scope para asignaciones por tipo.
     */
    public function scopeByTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Scope para asignaciones vencidas.
     */
    public function scopeVencidas($query)
    {
        return $query->where('estado', self::ESTADO_VENCIDA);
    }

    /**
     * Scope para asignaciones por usuario.
     */
    public function scopeByUsuario($query, $nombre)
    {
        return $query->where('asignado_a', 'LIKE', '%' . $nombre . '%');
    }
}