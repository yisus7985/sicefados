<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostComponent extends Model
{
    use SoftDeletes;

    protected $table = 'avicontrol_cost_components';
    
    protected $fillable = [
        'production_cost_id',
        'component_type',
        'component_name',
        'quantity',
        'unit_price',
        'total_cost',
        'unit_measure',
        'description',
        'date_applied',
        'supplier',
        'invoice_number',
        'notes'
    ];

    protected $dates = [
        'date_applied',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'date_applied' => 'date',
        'quantity' => 'decimal:4',
        'unit_price' => 'decimal:2',
        'total_cost' => 'decimal:2'
    ];

    // Relaciones
    public function productionCost()
    {
        return $this->belongsTo(ProductionCost::class, 'production_cost_id');
    }

    // Accessors
    public function getComponentTypeNameAttribute()
    {
        $types = [
            'concentrate' => 'Concentrado',
            'water' => 'Agua',
            'energy' => 'Energía',
            'medication' => 'Medicamentos',
            'biological' => 'Biológicos',
            'packaging' => 'Embalajes',
            'labor' => 'Mano de Obra',
            'maintenance' => 'Mantenimiento',
            'other' => 'Otros'
        ];
        
        return $types[$this->component_type] ?? $this->component_type;
    }

    public function getUnitMeasureNameAttribute()
    {
        $measures = [
            'kg' => 'Kilogramos',
            'l' => 'Litros',
            'kwh' => 'Kilovatios-hora',
            'units' => 'Unidades',
            'hours' => 'Horas',
            'days' => 'Días',
            'pieces' => 'Piezas'
        ];
        
        return $measures[$this->unit_measure] ?? $this->unit_measure;
    }

    // Métodos de cálculo
    public function calculateTotalCost()
    {
        $this->total_cost = $this->quantity * $this->unit_price;
        return $this->total_cost;
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('component_type', $type);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_applied', [$startDate, $endDate]);
    }

    public function scopeBySupplier($query, $supplier)
    {
        return $query->where('supplier', 'like', '%' . $supplier . '%');
    }

    // Boot method para calcular automáticamente el costo total
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($component) {
            $component->calculateTotalCost();
        });
    }
} 