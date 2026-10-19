<?php
// app/Models/Alerta.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'proveedor_id',
        'mensaje',
        'tipo',
        'prioridad',
        'fecha_limite',
        'observaciones',
        'leida',
        'activa',
        'creado_por'
    ];

    protected $casts = [
        'fecha_limite' => 'date',
        'leida' => 'boolean',
        'activa' => 'boolean',
    ];

    // ============================================================
    // RELACIONES
    // ============================================================
    
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    public function scopePorProveedor($query, $proveedorId)
    {
        return $query->where('proveedor_id', $proveedorId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeVencidas($query)
    {
        return $query->where('fecha_limite', '<', now())
                     ->where('leida', false)
                     ->where('activa', true);
    }

    // ============================================================
    // ACCESORS
    // ============================================================
    
    public function getPrioridadColorAttribute()
    {
        return [
            'baja' => 'success',
            'media' => 'info',
            'alta' => 'warning',
            'critica' => 'danger'
        ][$this->prioridad] ?? 'secondary';
    }

    public function getTipoLabelAttribute()
    {
        return [
            'recordatorio' => '📌 Recordatorio',
            'vencimiento' => '⏰ Vencimiento',
            'alerta' => '⚠️ Alerta'
        ][$this->tipo] ?? $this->tipo;
    }

    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_limite) return null;
        return now()->diffInDays($this->fecha_limite, false);
    }

    public function getEstadoAttribute()
    {
        if (!$this->activa) return 'Inactiva';
        if ($this->leida) return 'Leída';
        if ($this->fecha_limite && $this->fecha_limite->isPast()) return 'Vencida';
        return 'Pendiente';
    }
}