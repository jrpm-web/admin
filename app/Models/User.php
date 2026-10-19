<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
//use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'password',
        'cedula',
        'telefono',
        'cargo',
        'departamento',
        'foto',
        'estado',
        'rol',
        'ultimo_acceso',
        'ultima_actividad',
        'ip_ultimo_acceso',
        'user_agent_ultimo_acceso',
        'preferencias',
        'tema',
        'idioma',
        'creado_por',
        'actualizado_por'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'ultimo_acceso' => 'datetime',
        'ultima_actividad' => 'datetime',
        'preferencias' => 'array',
    ];

    /**
     * Registrar acceso del usuario
     */
    public function registrarAcceso()
    {
        $this->update([
            'ultimo_acceso' => now(),
            'ip_ultimo_acceso' => request()->ip(),
            'user_agent_ultimo_acceso' => request()->userAgent(),
        ]);
    }

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return trim($this->name . ' ' . $this->apellidos);
    }

    /**
     * Obtener iniciales
     */
    public function getInicialesAttribute()
    {
        $iniciales = strtoupper(substr($this->name, 0, 1));
        if ($this->apellidos) {
            $iniciales .= strtoupper(substr($this->apellidos, 0, 1));
        }
        return $iniciales;
    }

    /**
     * Verificar si es administrador
     */
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    /**
     * Verificar si es técnico
     */
    public function isTecnico()
    {
        return $this->rol === 'tecnico';
    }

    /**
     * Verificar si está activo
     */
    public function isActive()
    {
        return $this->estado === 'activo';
    }
}