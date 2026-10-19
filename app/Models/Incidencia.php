<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incidencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'incidencias';

    protected $fillable = [
        'titulo', 'descripcion', 'categoria',
        'prioridad', 'estado','progreso',
        'user_id', 'asignado_a', 'asignado_por',
        'visibilidad',
        'fecha_reporte', 'fecha_asignacion', 'fecha_cierre', 'fecha_limite',
        'solucion', 'adjuntos', 'tags', 'vistas',
    ];

    protected $casts = [
        'adjuntos'         => 'array',
        'tags'             => 'array',
        'fecha_reporte'    => 'datetime',
        'fecha_asignacion' => 'datetime',
        'fecha_cierre'     => 'datetime',
        'fecha_limite'     => 'datetime',
    ];

    // ============================================================
    // RELACIONES
    // ============================================================

    /** Dueño de la incidencia */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Usuario asignado a resolverla */
    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_a');
    }

    /** Usuario que realizó la asignación */
    public function asignador()
    {
        return $this->belongsTo(User::class, 'asignado_por');
    }

    /** Usuarios con los que se ha compartido (pivot) */
    public function compartidaCon()
    {
        return $this->belongsToMany(User::class, 'incidencia_usuario')
                    ->withPivot(['permiso', 'compartido_por', 'compartido_en', 'expira_en'])
                    ->withTimestamps();
    }

    /** Comentarios */
    public function comentarios()
    {
        return $this->hasMany(IncidenciaComentario::class, 'incidencia_id')
                    ->orderBy('created_at', 'asc');
    }

    /** Historial de cambios */
    public function historial()
    {
        return $this->hasMany(IncidenciaHistorial::class, 'incidencia_id')
                    ->orderBy('ocurrido_en', 'desc');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeAbiertas($query)
    {
        return $query->whereIn('estado', ['abierta', 'en_proceso', 'en_revision']);
    }

    public function scopeFinalizadas($query)
    {
        return $query->whereIn('estado', ['finalizada', 'cerrada']);
    }

    public function scopeAsignadasA($query, $userId)
    {
        return $query->where('asignado_a', $userId);
    }

    /**
     * Incidencias visibles para un usuario según su rol.
     * - Super admin: todas
     * - Padre/Admin: las suyas + las que asignó + las que le compartieron + públicas
     * - Usuario normal: las suyas + las que le compartieron + públicas
     */
    public function scopeVisiblesPara($query, User $user)
    {
        if ($user->hasRole('super-admin')) {
            return $query; // ve todas
        }

        if ($user->hasRole('padre') || $user->hasRole('admin')) {
            return $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)          // las que él creó
                  ->orWhere('asignado_a', $user->id)     // las que le asignaron
                  ->orWhere('asignado_por', $user->id)   // las que él asignó
                  ->orWhere('visibilidad', 'publica')
                  ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
            });
        }

        // Usuario normal
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('asignado_a', $user->id)
              ->orWhere('visibilidad', 'publica')
              ->orWhereHas('compartidaCon', fn($q2) => $q2->where('user_id', $user->id));
        });
    }

    // ============================================================
    // HELPERS DE PERMISOS
    // ============================================================

    public function esDueño(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function esAsignado(User $user): bool
    {
        return $this->asignado_a === $user->id;
    }

    public function estaCompartidaCon(User $user): bool
    {
        return $this->compartidaCon()->where('user_id', $user->id)->exists();
    }

    public function puedeVer(User $user): bool
    {
        if ($user->hasRole('super-admin')) return true;
        if ($this->esDueño($user) || $this->esAsignado($user)) return true;
        if ($this->visibilidad === 'publica') return true;
        if ($this->estaCompartidaCon($user)) return true;
        return false;
    }

    public function puedeEditar(User $user): bool
    {
        if ($user->hasRole('super-admin')) return true;
        if ($this->esDueño($user)) return true;

        // ¿Tiene permiso de "editar" vía compartida?
        $pivot = $this->compartidaCon()->where('user_id', $user->id)->first();
        if ($pivot && $pivot->pivot->permiso === 'editar') return true;

        return false;
    }

    public function puedeAsignar(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'padre', 'admin'])
            || $this->esDueño($user);
    }

    public function puedeCompartir(User $user): bool
    {
        return $user->hasAnyRole(['super-admin', 'padre', 'admin'])
            || $this->esDueño($user);
    }

    // ============================================================
    // ACCESORES
    // ============================================================

    public function getBadgeEstadoAttribute(): string
    {
        return match ($this->estado) {
            'abierta'     => 'bg-danger',
            'en_proceso'  => 'bg-warning text-dark',
            'en_revision' => 'bg-info text-dark',
            'finalizada'  => 'bg-success',
            'cerrada'     => 'bg-secondary',
            default       => 'bg-secondary',
        };
    }

    public function getBadgePrioridadAttribute(): string
    {
        return match ($this->prioridad) {
            'critica' => 'bg-danger',
            'alta'    => 'bg-warning text-dark',
            'media'   => 'bg-info text-dark',
            'baja'    => 'bg-secondary',
            default   => 'bg-secondary',
        };
    }
}