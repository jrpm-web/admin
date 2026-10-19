<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'code', 'name', 'email', 'phone', 'address', 'tax_id', 'status'
    ];
    
    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    // Accessor para nombre completo
    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }
}