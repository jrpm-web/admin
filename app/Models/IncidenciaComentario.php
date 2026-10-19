<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidenciaComentario extends Model
{
    protected $fillable = [
        'incidencia_id', 'user_id', 'comentario', 'es_interno', 'adjuntos',
    ];

    protected $casts = [
        'adjuntos'   => 'array',
        'es_interno' => 'boolean',
    ];
}
