<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;
    
    protected $table = 'invoices';
    
    protected $fillable = [
        'numero_factura',
        'proveedor',
        'supplier_id',
        'monto',
        'mes',
        'fecha_factura'
    ];
    
    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_factura' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Relación con el proveedor
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
    /**
     * Accessor para el nombre del mes
     */
    public function getMonthNameAttribute()
    {
        if (!$this->fecha_factura) return $this->mes;
        return $this->fecha_factura->translatedFormat('F Y');
    }
    
    /**
     * Scope para filtrar por año
     */
    public function scopeForYear($query, $year)
    {
        return $query->whereYear('fecha_factura', $year);
    }
    
    /**
     * Scope para filtrar por mes
     */
    public function scopeForMonth($query, $month)
    {
        return $query->whereMonth('fecha_factura', $month);
    }
}