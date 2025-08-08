<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfitabilityAnalysis extends Model
{
    use SoftDeletes;

    protected $table = 'avicontrol_profitability_analysis';
    
    protected $fillable = [
        'production_cost_id',
        'analysis_period',
        'period_start',
        'period_end',
        'total_production_cost',
        'total_revenue',
        'other_income',
        'other_expenses',
        'gross_profit',
        'gross_margin_percentage',
        'net_profit',
        'net_margin_percentage',
        'profit_per_unit',
        'unit_type',
        'total_units_produced',
        'average_selling_price',
        'analysis_date',
        'notes',
        'status' // 'draft', 'confirmed', 'archived'
    ];

    protected $dates = [
        'period_start',
        'period_end',
        'analysis_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'analysis_date' => 'date',
        'total_production_cost' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'other_income' => 'decimal:2',
        'other_expenses' => 'decimal:2',
        'gross_profit' => 'decimal:2',
        'gross_margin_percentage' => 'decimal:2',
        'net_profit' => 'decimal:2',
        'net_margin_percentage' => 'decimal:2',
        'profit_per_unit' => 'decimal:2',
        'total_units_produced' => 'decimal:4',
        'average_selling_price' => 'decimal:2'
    ];

    // Relaciones
    public function productionCost()
    {
        return $this->belongsTo(ProductionCost::class, 'production_cost_id');
    }

    // Accessors
    public function getAnalysisPeriodNameAttribute()
    {
        $periods = [
            'daily' => 'Diario',
            'weekly' => 'Semanal',
            'monthly' => 'Mensual',
            'batch' => 'Por Lote',
            'custom' => 'Personalizado'
        ];
        
        return $periods[$this->analysis_period] ?? $this->analysis_period;
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
            'archived' => 'Archivado'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Métodos de cálculo
    public function calculateGrossProfit()
    {
        $this->gross_profit = $this->total_revenue - $this->total_production_cost;
        return $this->gross_profit;
    }

    public function calculateGrossMarginPercentage()
    {
        if ($this->total_revenue > 0) {
            $this->gross_margin_percentage = ($this->gross_profit / $this->total_revenue) * 100;
        } else {
            $this->gross_margin_percentage = 0;
        }
        
        return $this->gross_margin_percentage;
    }

    public function calculateNetProfit()
    {
        $this->net_profit = $this->gross_profit + $this->other_income - $this->other_expenses;
        return $this->net_profit;
    }

    public function calculateNetMarginPercentage()
    {
        if ($this->total_revenue > 0) {
            $this->net_margin_percentage = ($this->net_profit / $this->total_revenue) * 100;
        } else {
            $this->net_margin_percentage = 0;
        }
        
        return $this->net_margin_percentage;
    }

    public function calculateProfitPerUnit()
    {
        if ($this->total_units_produced > 0) {
            $this->profit_per_unit = $this->net_profit / $this->total_units_produced;
        } else {
            $this->profit_per_unit = 0;
        }
        
        return $this->profit_per_unit;
    }

    public function calculateAllMetrics()
    {
        $this->calculateGrossProfit();
        $this->calculateGrossMarginPercentage();
        $this->calculateNetProfit();
        $this->calculateNetMarginPercentage();
        $this->calculateProfitPerUnit();
        
        return $this;
    }

    public function getPeriodDurationAttribute()
    {
        if ($this->period_start && $this->period_end) {
            return $this->period_start->diffInDays($this->period_end) + 1;
        }
        
        return null;
    }

    public function getProfitabilityStatusAttribute()
    {
        if ($this->net_profit > 0) {
            return 'profitable';
        } elseif ($this->net_profit < 0) {
            return 'loss';
        } else {
            return 'break_even';
        }
    }

    public function getProfitabilityStatusNameAttribute()
    {
        $statuses = [
            'profitable' => 'Rentable',
            'loss' => 'Pérdida',
            'break_even' => 'Punto de Equilibrio'
        ];
        
        return $statuses[$this->profitability_status] ?? 'Desconocido';
    }

    // Scopes
    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('period_start', [$startDate, $endDate])
                    ->orWhereBetween('period_end', [$startDate, $endDate]);
    }

    public function scopeByAnalysisPeriod($query, $period)
    {
        return $query->where('analysis_period', $period);
    }

    public function scopeProfitable($query)
    {
        return $query->where('net_profit', '>', 0);
    }

    public function scopeLoss($query)
    {
        return $query->where('net_profit', '<', 0);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeByUnitType($query, $unitType)
    {
        return $query->where('unit_type', $unitType);
    }

    // Boot method para calcular automáticamente las métricas
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($analysis) {
            $analysis->calculateAllMetrics();
        });
    }
} 