<?php
// app/Models/Factura.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_factura',
        'proveedor_id',
        'producto_id',
        'monto',
        'moneda',
        'tasa_referencia',
        'monto_usd',
        'mes',
        'fecha_factura',
        'fecha_pago',
        'estado_pago',
        'observaciones',
        'creado_por',
        'actualizado_por'
    ];

    protected $casts = [
        'fecha_factura' => 'date',
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
        'tasa_referencia' => 'decimal:2',
        'monto_usd' => 'decimal:2',
    ];

    // Monedas disponibles
    const MONEDAS = [
        'Bs' => 'Bolívares',
        'USD' => 'Dólares',
        'RUB' => 'Rublos Rusos'
    ];

    // Estados de pago
    const ESTADOS_PAGO = [
        'pendiente' => 'Pendiente',
        'pagado' => 'Pagado',
        'parcial' => 'Parcial',
        'vencido' => 'Vencido'
    ];

    // ============ RELACIONES ============
    
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function actualizador()
    {
        return $this->belongsTo(User::class, 'actualizado_por');
    }

    // NUEVA RELACIÓN CON ARCHIVOS
    public function archivos()
    {
        return $this->hasMany(FacturaArchivo::class)->orderBy('created_at', 'desc');
    }

    // Relación específica para el archivo principal de la factura
    public function archivoPrincipal()
    {
        return $this->hasOne(FacturaArchivo::class)
                    ->where('categoria', 'factura')
                    ->latest();
    }

    // ============ SCOPES ============
    
    public function scopePendientes($query)
    {
        return $query->where('estado_pago', 'pendiente');
    }

    public function scopePagadas($query)
    {
        return $query->where('estado_pago', 'pagado');
    }

    public function scopeParciales($query)
    {
        return $query->where('estado_pago', 'parcial');
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado_pago', 'vencido');
    }

    public function scopePorMoneda($query, $moneda)
    {
        return $query->where('moneda', $moneda);
    }

    public function scopePorProveedor($query, $proveedorId)
    {
        return $query->where('proveedor_id', $proveedorId);
    }

    public function scopePorMes($query, $mes)
    {
        return $query->where('mes', $mes);
    }

    public function scopeRangoFechas($query, $inicio, $fin)
    {
        return $query->whereBetween('fecha_factura', [$inicio, $fin]);
    }

    // ============ ACCESORS ============
    
    public function getMontoFormateadoAttribute()
    {
        return number_format($this->monto, 2, ',', '.');
    }

    public function getMontoUsdFormateadoAttribute()
    {
        if ($this->monto_usd) {
            return number_format($this->monto_usd, 2, ',', '.');
        }
        return null;
    }

    public function getTasaReferenciaFormateadaAttribute()
    {
        if ($this->tasa_referencia) {
            return number_format($this->tasa_referencia, 2, ',', '.');
        }
        return null;
    }

    public function getEstadoPagoLabelAttribute()
    {
        return self::ESTADOS_PAGO[$this->estado_pago] ?? $this->estado_pago;
    }

    public function getEstadoPagoColorAttribute()
    {
        $colores = [
            'pendiente' => 'warning',
            'pagado' => 'success',
            'parcial' => 'info',
            'vencido' => 'danger'
        ];
        return $colores[$this->estado_pago] ?? 'secondary';
    }

    public function getMonedaLabelAttribute()
    {
        return self::MONEDAS[$this->moneda] ?? $this->moneda;
    }

    public function getMonedaSimboloAttribute()
    {
        $simbolos = [
            'Bs' => 'Bs.',
            'USD' => '$',
            'RUB' => '₽'
        ];
        return $simbolos[$this->moneda] ?? $this->moneda;
    }

    // ============ HELPERS ============
    
    public function calcularMontoUSD()
    {
        if ($this->moneda === 'USD') {
            return $this->monto;
        }
        
        if ($this->moneda === 'Bs' && $this->tasa_referencia) {
            return $this->monto / $this->tasa_referencia;
        }
        
        if ($this->moneda === 'RUB' && $this->tasa_referencia) {
            return $this->monto / $this->tasa_referencia;
        }
        
        return null;
    }

    public function estaPagada()
    {
        return $this->estado_pago === 'pagado';
    }

    public function estaVencida()
    {
        if ($this->estado_pago === 'pagado') {
            return false;
        }
        
        if ($this->fecha_pago && $this->fecha_pago < now()) {
            return true;
        }
        
        return false;
    }

    public function estaPendiente()
    {
        return $this->estado_pago === 'pendiente';
    }

    public function estaParcial()
    {
        return $this->estado_pago === 'parcial';
    }

    // NUEVO: Verificar si tiene archivos
    public function tieneArchivos()
    {
        return $this->archivos()->exists();
    }

    // NUEVO: Obtener archivos por categoría
    public function getArchivosPorCategoria($categoria)
    {
        return $this->archivos()->where('categoria', $categoria)->get();
    }

    // NUEVO: Contar archivos por categoría
    public function contarArchivosPorCategoria($categoria)
    {
        return $this->archivos()->where('categoria', $categoria)->count();
    }
}