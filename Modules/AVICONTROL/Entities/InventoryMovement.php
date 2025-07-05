<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryMovement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'avicontrol_inventory_movements';

    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'unit_price',
        'total_value',
        'reference',
        'notes',
        'movement_date',
        'user_id'
    ];

    protected $casts = [
        'movement_date' => 'datetime',
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    // Tipos de movimiento
    const TYPE_ENTRY = 'entry';      // Entrada (compra, producción)
    const TYPE_EXIT = 'exit';        // Salida (consumo, venta, descarte)
    const TYPE_ADJUSTMENT = 'adjustment'; // Ajuste de inventario

    // Referencias de movimiento
    const REFERENCE_PURCHASE = 'purchase';
    const REFERENCE_PRODUCTION = 'production';
    const REFERENCE_CONSUMPTION = 'consumption';
    const REFERENCE_SALE = 'sale';
    const REFERENCE_DISCARD = 'discard';
    const REFERENCE_ADJUSTMENT = 'adjustment';

    // Relaciones
    public function product()
    {
        return $this->belongsTo(InventoryProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Scopes
    public function scopeEntries($query)
    {
        return $query->where('movement_type', self::TYPE_ENTRY);
    }

    public function scopeExits($query)
    {
        return $query->where('movement_type', self::TYPE_EXIT);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Métodos
    public function getMovementTypeNameAttribute()
    {
        $types = [
            self::TYPE_ENTRY => 'Entrada',
            self::TYPE_EXIT => 'Salida',
            self::TYPE_ADJUSTMENT => 'Ajuste'
        ];
        return $types[$this->movement_type] ?? $this->movement_type;
    }

    public function getReferenceNameAttribute()
    {
        $references = [
            self::REFERENCE_PURCHASE => 'Compra',
            self::REFERENCE_PRODUCTION => 'Producción',
            self::REFERENCE_CONSUMPTION => 'Consumo',
            self::REFERENCE_SALE => 'Venta',
            self::REFERENCE_DISCARD => 'Descarte',
            self::REFERENCE_ADJUSTMENT => 'Ajuste'
        ];
        return $references[$this->reference] ?? $this->reference;
    }

    public function isEntry()
    {
        return $this->movement_type === self::TYPE_ENTRY;
    }

    public function isExit()
    {
        return $this->movement_type === self::TYPE_EXIT;
    }

    public function isAdjustment()
    {
        return $this->movement_type === self::TYPE_ADJUSTMENT;
    }

    // Boot method para calcular total_value automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movement) {
            if (!$movement->total_value) {
                $movement->total_value = $movement->quantity * $movement->unit_price;
            }
        });

        static::created(function ($movement) {
            // Actualizar stock del producto
            $product = $movement->product;
            if ($product) {
                if ($movement->isEntry()) {
                    $product->current_stock += $movement->quantity;
                } elseif ($movement->isExit()) {
                    $product->current_stock -= $movement->quantity;
                } elseif ($movement->isAdjustment()) {
                    $product->current_stock = $movement->quantity;
                }
                $product->save();
            }
        });
    }
} 