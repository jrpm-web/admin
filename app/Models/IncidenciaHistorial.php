<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidenciaHistorial extends Model
{
    protected $table = 'incidencia_historial';

    protected $fillable = [
        'incidencia_id', 'user_id', 'accion', 'descripcion',
        'valores_anteriores', 'valores_nuevos', 'metadata', 'ocurrido_en',
    ];

    protected $casts = [
        'valores_anteriores' => 'array',
        'valores_nuevos'     => 'array',
        'metadata'           => 'array',
        'ocurrido_en'        => 'datetime',
    ];

    public function incidencia() { return $this->belongsTo(Incidencia::class); }
    public function user()       { return $this->belongsTo(User::class); }
}