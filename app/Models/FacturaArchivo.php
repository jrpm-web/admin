<?php
// app/Models/FacturaArchivo.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FacturaArchivo extends Model
{
    use HasFactory;

    protected $table = 'factura_archivos';

    protected $fillable = [
        'factura_id',
        'nombre_original',
        'nombre_guardado',
        'ruta',
        'tipo_archivo',
        'tamaño',
        'mime_type',
        'categoria',
        'descripcion',
        
        'subido_por'
    ];

    protected $casts = [
        'tamaño' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Tipos de categoría
    const CATEGORIAS = [
        'factura' => 'Factura',
        'soporte' => 'Documento Soporte',
        'anexo' => 'Anexo',
        'comprobante' => 'Comprobante de Pago',
        'otro' => 'Otro'
    ];

    // Tipos de archivo permitidos
    const TIPOS_PERMITIDOS = [
        'pdf' => 'PDF',
        'jpg' => 'Imagen JPG',
        'jpeg' => 'Imagen JPEG',
        'png' => 'Imagen PNG',
        'doc' => 'Documento Word',
        'docx' => 'Documento Word',
        'xls' => 'Excel',
        'xlsx' => 'Excel',
        'zip' => 'Comprimido'
    ];

    // ============ RELACIONES ============
    
    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    // ============ ACCESORS ============
    
    public function getUrlAttribute()
    {
        return Storage::url($this->ruta);
    }

    public function getTamañoFormateadoAttribute()
    {
        $bytes = $this->tamaño;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTipoArchivoLabelAttribute()
    {
        return self::TIPOS_PERMITIDOS[$this->tipo_archivo] ?? $this->tipo_archivo;
    }

    public function getCategoriaLabelAttribute()
    {
        return self::CATEGORIAS[$this->categoria] ?? $this->categoria;
    }

    public function getIconoAttribute()
    {
        $iconos = [
            'pdf' => 'fa-file-pdf text-danger',
            'jpg' => 'fa-file-image text-success',
            'jpeg' => 'fa-file-image text-success',
            'png' => 'fa-file-image text-success',
            'doc' => 'fa-file-word text-primary',
            'docx' => 'fa-file-word text-primary',
            'xls' => 'fa-file-excel text-success',
            'xlsx' => 'fa-file-excel text-success',
            'zip' => 'fa-file-archive text-warning'
        ];
        return $iconos[$this->tipo_archivo] ?? 'fa-file text-secondary';
    }

    // ============ SCOPES ============
    
    public function scopePorFactura($query, $facturaId)
    {
        return $query->where('factura_id', $facturaId);
    }

    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo_archivo', $tipo);
    }

    public function scopeFacturas($query)
    {
        return $query->where('categoria', 'factura');
    }

    public function scopeSoportes($query)
    {
        return $query->where('categoria', 'soporte');
    }

    // ============ HELPERS ============
    
    public function eliminarArchivoFisico()
    {
        if (Storage::exists($this->ruta)) {
            return Storage::delete($this->ruta);
        }
        return false;
    }

    public function esImagen()
    {
        return in_array($this->tipo_archivo, ['jpg', 'jpeg', 'png']);
    }

    public function esDocumento()
    {
        return in_array($this->tipo_archivo, ['pdf', 'doc', 'docx']);
    }

    public function esExcel()
    {
        return in_array($this->tipo_archivo, ['xls', 'xlsx']);
    }

    // Sobrescribir el método delete para eliminar también el archivo físico
    public function delete()
    {
        $this->eliminarArchivoFisico();
        return parent::delete();
    }
    // app/Models/Proveedor.php

public function getProductosRecurrentesAttribute()
{
    return $this->productos->where('tipo_pago', 'recurrente');
}

public function getProximaFacturaRecurrenteAttribute()
{
    return $this->productosRecurrentes
                ->pluck('proxima_facturacion')
                ->filter()
                ->min(); // la fecha más próxima
}

public function getDiasHastaProximaRecurrenteAttribute()
{
    $proxima = $this->proxima_factura_recurrente;
    if (!$proxima) return null;
    return now()->diffInDays($proxima, false);
}

public function getEstadoMonitoreoAttribute()
{
    $recurrentes = $this->productosRecurrentes;
    if ($recurrentes->isEmpty()) {
        return 'sin_productos_recurrentes';
    }
    $dias = $this->dias_hasta_proxima_recurrente;
    if ($dias === null) return 'sin_fecha';
    if ($dias < 0) return 'vencido';
    if ($dias <= 5) return 'por_vencer'; // alerta
    return 'normal';
}
}