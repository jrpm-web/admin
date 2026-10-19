<?php
// app/Models/Detalles/DetalleTelefonia.php

namespace App\Models\Detalles;

use Illuminate\Database\Eloquent\Model;
use App\Models\Producto;

class DetalleTelefonia extends Model
{
    protected $table = 'detalles_telefonia';
    
    protected $fillable = [
        'producto_id',
        'numero_telefonico',
        'numero_sim',
        'operador',
        'plan_datos',
        'imei',
        'pin',
        'puk',
        'fecha_activacion',
        'fecha_vencimiento',
        'estado_telefonia'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}