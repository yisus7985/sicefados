<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'avicontrol_inventory_products';

    protected $fillable = [
        'name',
        'category',
        'description',
        'unit_measure',
        'unit_price',
        'supplier',
        'expiration_date',
        'minimum_stock',
        'current_stock',
        'status',
        'code'
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'unit_price' => 'decimal:2',
        'minimum_stock' => 'integer',
        'current_stock' => 'integer',
    ];

    // Categorías disponibles
    const CATEGORIES = [
        'alimentos' => 'Alimentos (Concentrados)',
        'medicamentos' => 'Medicamentos',
        'biologicos' => 'Biológicos (Vacunas)',
        'desinfectantes' => 'Desinfectantes',
        'embalajes' => 'Embalajes (Cubetas, Cajas)',
        'equipos' => 'Equipos Menores',
        'otros' => 'Otros Insumos'
    ];

    // Estados disponibles
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_EXPIRED = 'expired';

    // Relaciones
    public function movements()
    {
        return $this->hasMany(InventoryMovement::class, 'product_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_stock');
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiration_date', '<=', now()->addDays($days))
                    ->where('expiration_date', '>', now());
    }

    // Métodos
    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function isExpiringSoon($days = 30)
    {
        return $this->expiration_date && 
               $this->expiration_date->diffInDays(now(), false) <= $days &&
               $this->expiration_date->isFuture();
    }

    public function isExpired()
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getTotalValueAttribute()
    {
        return $this->current_stock * $this->unit_price;
    }

    // Generar código único
    public static function generateCode()
    {
        $prefix = 'INV';
        $lastProduct = self::orderBy('id', 'desc')->first();
        $nextNumber = $lastProduct ? intval(substr($lastProduct->code, 3)) + 1 : 1;
        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
} 