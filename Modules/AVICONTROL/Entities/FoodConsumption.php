<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodConsumption extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'avicontrol_food_consumption';

    protected $fillable = [
        'fecha_registro',
        'galpon_id',
        'producto_id',
        'cantidad_kg',
        'cantidad_bultos',
        'peso_por_bulto',
        'consumo_promedio_por_ave',
        'numero_aves',
        'observaciones',
        'responsable',
        'estado'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'cantidad_kg' => 'decimal:2',
        'cantidad_bultos' => 'integer',
        'peso_por_bulto' => 'decimal:2',
        'consumo_promedio_por_ave' => 'decimal:4',
        'numero_aves' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Estados disponibles
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PENDING = 'pending';

    // Relaciones
    public function galpon()
    {
        return $this->belongsTo(Galpon::class, 'galpon_id');
    }

    public function producto()
    {
        return $this->belongsTo(InventoryProduct::class, 'producto_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('estado', self::STATUS_ACTIVE);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('fecha_registro', [$startDate, $endDate]);
    }

    public function scopeByGalpon($query, $galponId)
    {
        return $query->where('galpon_id', $galponId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('producto_id', $productId);
    }

    // Métodos
    public function getTotalConsumoAttribute()
    {
        if ($this->cantidad_kg) {
            return $this->cantidad_kg;
        }
        
        if ($this->cantidad_bultos && $this->peso_por_bulto) {
            return $this->cantidad_bultos * $this->peso_por_bulto;
        }
        
        return 0;
    }

    public function getConsumoPorAveAttribute()
    {
        if ($this->numero_aves > 0 && $this->getTotalConsumoAttribute() > 0) {
            return ($this->getTotalConsumoAttribute() * 1000) / $this->numero_aves; // Convertir a gramos por ave
        }
        return 0;
    }

    public function getCostoTotalAttribute()
    {
        if ($this->producto && $this->getTotalConsumoAttribute() > 0) {
            return $this->getTotalConsumoAttribute() * $this->producto->unit_price;
        }
        return 0;
    }

    // Calcular consumo promedio por ave por día
    public function calcularConsumoPromedio()
    {
        if ($this->numero_aves > 0 && $this->getTotalConsumoAttribute() > 0) {
            $this->consumo_promedio_por_ave = ($this->getTotalConsumoAttribute() * 1000) / $this->numero_aves;
            $this->save();
        }
    }

    // Validar que el galpón tenga aves
    public function validarAvesEnGalpon()
    {
        if ($this->galpon) {
            // Aquí se podría integrar con el módulo de control de aves
            // Por ahora usamos el campo numero_aves
            return $this->numero_aves > 0;
        }
        return false;
    }

    // Actualizar inventario automáticamente
    public function actualizarInventario()
    {
        if ($this->producto && $this->estado === self::STATUS_ACTIVE) {
            $consumoTotal = $this->getTotalConsumoAttribute();
            $this->producto->current_stock -= $consumoTotal;
            $this->producto->save();
        }
    }

    // Revertir actualización de inventario (para cancelaciones)
    public function revertirInventario()
    {
        if ($this->producto && $this->estado === self::STATUS_CANCELLED) {
            $consumoTotal = $this->getTotalConsumoAttribute();
            $this->producto->current_stock += $consumoTotal;
            $this->producto->save();
        }
    }
}
