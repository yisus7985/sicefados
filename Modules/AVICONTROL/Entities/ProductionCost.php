<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ProductionCost extends Model
{
    use SoftDeletes;

    protected $table = 'avicontrol_production_costs';
    
    protected $fillable = [
        'poultry_facility_id',
        'bird_id',
        'batch_code',
        'period_start',
        'period_end',
        'cost_type', // 'batch', 'period'
        'concentrate_cost',
        'water_cost',
        'energy_cost',
        'medication_cost',
        'biological_cost',
        'packaging_cost',
        'labor_cost',
        'maintenance_cost',
        'other_costs',
        'total_cost',
        'cost_per_unit',
        'unit_type', // 'egg', 'kg_meat', 'bird'
        'notes',
        'status' // 'draft', 'confirmed', 'cancelled'
    ];

    protected $dates = [
        'period_start',
        'period_end',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'concentrate_cost' => 'decimal:2',
        'water_cost' => 'decimal:2',
        'energy_cost' => 'decimal:2',
        'medication_cost' => 'decimal:2',
        'biological_cost' => 'decimal:2',
        'packaging_cost' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'maintenance_cost' => 'decimal:2',
        'other_costs' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'cost_per_unit' => 'decimal:2'
    ];

    // Relaciones
    public function poultryFacility()
    {
        return $this->belongsTo(PoultryFacility::class, 'poultry_facility_id');
    }

    public function bird()
    {
        return $this->belongsTo(Bird::class, 'bird_id');
    }

    public function costComponents()
    {
        return $this->hasMany(CostComponent::class, 'production_cost_id');
    }

    public function profitabilityAnalysis()
    {
        return $this->hasOne(ProfitabilityAnalysis::class, 'production_cost_id');
    }

    // Accessors
    public function getCostTypeNameAttribute()
    {
        $types = [
            'batch' => 'Por Lote',
            'period' => 'Por Período'
        ];
        
        return $types[$this->cost_type] ?? $this->cost_type;
    }

    public function getUnitTypeNameAttribute()
    {
        $types = [
            'egg' => 'Por Huevo',
            'kg_meat' => 'Por Kg de Carne',
            'bird' => 'Por Ave'
        ];
        
        return $types[$this->unit_type] ?? $this->unit_type;
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'draft' => 'Borrador',
            'confirmed' => 'Confirmado',
            'cancelled' => 'Cancelado'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Métodos de cálculo
    public function calculateTotalCost()
    {
        $this->total_cost = 
            $this->concentrate_cost +
            $this->water_cost +
            $this->energy_cost +
            $this->medication_cost +
            $this->biological_cost +
            $this->packaging_cost +
            $this->labor_cost +
            $this->maintenance_cost +
            $this->other_costs;
        
        return $this->total_cost;
    }

    public function calculateCostPerUnit($quantity = null)
    {
        if ($quantity && $quantity > 0) {
            $this->cost_per_unit = $this->total_cost / $quantity;
        }
        
        return $this->cost_per_unit;
    }

    public function getPeriodDurationAttribute()
    {
        if ($this->period_start && $this->period_end) {
            return $this->period_start->diffInDays($this->period_end) + 1;
        }
        
        return null;
    }

    // Scopes
    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('poultry_facility_id', $facilityId);
    }

    public function scopeByBatch($query, $batchCode)
    {
        return $query->where('batch_code', $batchCode);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('period_start', [$startDate, $endDate])
                    ->orWhereBetween('period_end', [$startDate, $endDate]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('cost_type', $type);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
} 