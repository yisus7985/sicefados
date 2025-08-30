<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo ProfitabilityAnalysis - Gestiona el análisis de rentabilidad de la producción avícola
 * 
 * Este modelo maneja:
 * - Cálculo de ganancias brutas y netas
 * - Análisis de márgenes de rentabilidad
 * - Seguimiento de ingresos y gastos adicionales
 * - Cálculo de rentabilidad por unidad producida
 * - Estados del análisis (borrador, confirmado, archivado)
 */
class ProfitabilityAnalysis extends Model
{
    // TRAIT: SoftDeletes para borrado lógico (mantiene registros en BD)
    use SoftDeletes;

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_profitability_analysis';
    
    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'production_cost_id',        // ID del costo de producción asociado
        'analysis_period',           // Período de análisis: 'daily', 'weekly', 'monthly', 'batch', 'custom'
        'period_start',              // Fecha de inicio del período analizado
        'period_end',                // Fecha de fin del período analizado
        'total_production_cost',     // Costo total de producción
        'total_revenue',             // Ingresos totales por ventas
        'other_income',              // Otros ingresos adicionales
        'other_expenses',            // Otros gastos adicionales
        'gross_profit',              // Ganancia bruta (ingresos - costos de producción)
        'gross_margin_percentage',   // Porcentaje de margen bruto
        'net_profit',                // Ganancia neta (bruta + otros ingresos - otros gastos)
        'net_margin_percentage',     // Porcentaje de margen neto
        'profit_per_unit',           // Ganancia por unidad producida
        'unit_type',                 // Tipo de unidad: 'egg', 'kg_meat', 'bird'
        'total_units_produced',      // Total de unidades producidas en el período
        'average_selling_price',     // Precio promedio de venta por unidad
        'analysis_date',             // Fecha en que se realizó el análisis
        'notes',                     // Notas y observaciones del análisis
        'status'                     // Estado del análisis: 'draft', 'confirmed', 'archived'
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'period_start',              // Fecha de inicio del período
        'period_end',                // Fecha de fin del período
        'analysis_date',             // Fecha del análisis
        'created_at',                // Fecha de creación del registro
        'updated_at',                // Fecha de última actualización
        'deleted_at'                 // Fecha de borrado lógico (SoftDelete)
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'period_start' => 'date',                // Convierte a objeto Carbon (fecha)
        'period_end' => 'date',                  // Convierte a objeto Carbon (fecha)
        'analysis_date' => 'date',               // Convierte a objeto Carbon (fecha)
        'total_production_cost' => 'decimal:2',  // Convierte a decimal con 2 decimales
        'total_revenue' => 'decimal:2',          // Convierte a decimal con 2 decimales
        'other_income' => 'decimal:2',           // Convierte a decimal con 2 decimales
        'other_expenses' => 'decimal:2',         // Convierte a decimal con 2 decimales
        'gross_profit' => 'decimal:2',           // Convierte a decimal con 2 decimales
        'gross_margin_percentage' => 'decimal:2', // Convierte a decimal con 2 decimales
        'net_profit' => 'decimal:2',             // Convierte a decimal con 2 decimales
        'net_margin_percentage' => 'decimal:2',  // Convierte a decimal con 2 decimales
        'profit_per_unit' => 'decimal:2',        // Convierte a decimal con 2 decimales
        'total_units_produced' => 'decimal:4',   // Convierte a decimal con 4 decimales
        'average_selling_price' => 'decimal:2'   // Convierte a decimal con 2 decimales
    ];

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un análisis de rentabilidad pertenece a un costo de producción
     * Retorna el costo de producción asociado a este análisis
     */
    public function productionCost()
    {
        return $this->belongsTo(ProductionCost::class, 'production_cost_id');
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Retorna el nombre legible del período de análisis
     * Uso: $analysis->analysis_period_name
     * 
     * @return string Nombre legible del período de análisis
     */
    public function getAnalysisPeriodNameAttribute()
    {
        $periods = [
            'daily' => 'Diario',         // Análisis diario
            'weekly' => 'Semanal',       // Análisis semanal
            'monthly' => 'Mensual',      // Análisis mensual
            'batch' => 'Por Lote',       // Análisis por lote específico
            'custom' => 'Personalizado'  // Análisis con período personalizado
        ];
        
        return $periods[$this->analysis_period] ?? $this->analysis_period;
    }

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de unidad
     * Uso: $analysis->unit_type_name
     * 
     * @return string Nombre legible del tipo de unidad
     */
    public function getUnitTypeNameAttribute()
    {
        $types = [
            'egg' => 'Por Huevo',        // Rentabilidad calculada por huevo
            'kg_meat' => 'Por Kg de Carne', // Rentabilidad calculada por kg de carne
            'bird' => 'Por Ave'           // Rentabilidad calculada por ave
        ];
        
        return $types[$this->unit_type] ?? $this->unit_type;
    }

    /**
     * ACCESSOR: Retorna el nombre legible del estado del análisis
     * Uso: $analysis->status_name
     * 
     * @return string Nombre legible del estado
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'draft' => 'Borrador',       // Análisis en proceso de elaboración
            'confirmed' => 'Confirmado', // Análisis validado y confirmado
            'archived' => 'Archivado'    // Análisis archivado para consulta histórica
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // ===== MÉTODOS: FUNCIONALIDADES ESPECÍFICAS =====

    /**
     * MÉTODO: Calcula la ganancia bruta
     * Diferencia entre ingresos totales y costos de producción
     * Uso: $analysis->calculateGrossProfit()
     * 
     * @return float Ganancia bruta calculada
     */
    public function calculateGrossProfit()
    {
        $this->gross_profit = $this->total_revenue - $this->total_production_cost;
        return $this->gross_profit;
    }

    /**
     * MÉTODO: Calcula el porcentaje de margen bruto
     * Porcentaje de ganancia bruta respecto a los ingresos totales
     * Uso: $analysis->calculateGrossMarginPercentage()
     * 
     * @return float Porcentaje de margen bruto
     */
    public function calculateGrossMarginPercentage()
    {
        if ($this->total_revenue > 0) {
            $this->gross_margin_percentage = ($this->gross_profit / $this->total_revenue) * 100;
        } else {
            $this->gross_margin_percentage = 0;
        }
        
        return $this->gross_margin_percentage;
    }

    /**
     * MÉTODO: Calcula la ganancia neta
     * Ganancia bruta más otros ingresos menos otros gastos
     * Uso: $analysis->calculateNetProfit()
     * 
     * @return float Ganancia neta calculada
     */
    public function calculateNetProfit()
    {
        $this->net_profit = $this->gross_profit + $this->other_income - $this->other_expenses;
        return $this->net_profit;
    }

    /**
     * MÉTODO: Calcula el porcentaje de margen neto
     * Porcentaje de ganancia neta respecto a los ingresos totales
     * Uso: $analysis->calculateNetMarginPercentage()
     * 
     * @return float Porcentaje de margen neto
     */
    public function calculateNetMarginPercentage()
    {
        if ($this->total_revenue > 0) {
            $this->net_margin_percentage = ($this->net_profit / $this->total_revenue) * 100;
        } else {
            $this->net_margin_percentage = 0;
        }
        
        return $this->net_margin_percentage;
    }

    /**
     * MÉTODO: Calcula la ganancia por unidad producida
     * Ganancia neta dividida entre el total de unidades producidas
     * Uso: $analysis->calculateProfitPerUnit()
     * 
     * @return float Ganancia por unidad
     */
    public function calculateProfitPerUnit()
    {
        if ($this->total_units_produced > 0) {
            $this->profit_per_unit = $this->net_profit / $this->total_units_produced;
        } else {
            $this->profit_per_unit = 0;
        }
        
        return $this->profit_per_unit;
    }

    /**
     * MÉTODO: Calcula todas las métricas de rentabilidad
     * Ejecuta todos los cálculos en secuencia
     * Uso: $analysis->calculateAllMetrics()
     * 
     * @return $this Instancia del modelo para encadenamiento
     */
    public function calculateAllMetrics()
    {
        $this->calculateGrossProfit();
        $this->calculateGrossMarginPercentage();
        $this->calculateNetProfit();
        $this->calculateNetMarginPercentage();
        $this->calculateProfitPerUnit();
        
        return $this;
    }

    /**
     * ACCESSOR: Calcula la duración del período analizado en días
     * Uso: $analysis->period_duration
     * 
     * @return int|null Duración del período en días o null si no hay fechas
     */
    public function getPeriodDurationAttribute()
    {
        if ($this->period_start && $this->period_end) {
            return $this->period_start->diffInDays($this->period_end) + 1;
        }
        
        return null;
    }

    /**
     * ACCESSOR: Determina el estado de rentabilidad del análisis
     * Uso: $analysis->profitability_status
     * 
     * @return string Estado de rentabilidad: 'profitable', 'loss', 'break_even'
     */
    public function getProfitabilityStatusAttribute()
    {
        if ($this->net_profit > 0) {
            return 'profitable';      // Con ganancias
        } elseif ($this->net_profit < 0) {
            return 'loss';            // Con pérdidas
        } else {
            return 'break_even';      // Punto de equilibrio
        }
    }

    /**
     * ACCESSOR: Retorna el nombre legible del estado de rentabilidad
     * Uso: $analysis->profitability_status_name
     * 
     * @return string Nombre legible del estado de rentabilidad
     */
    public function getProfitabilityStatusNameAttribute()
    {
        $statuses = [
            'profitable' => 'Rentable',           // Genera ganancias
            'loss' => 'Pérdida',                  // Genera pérdidas
            'break_even' => 'Punto de Equilibrio' // No gana ni pierde
        ];
        
        return $statuses[$this->profitability_status] ?? 'Desconocido';
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra por rango de fechas del período
     * Uso: ProfitabilityAnalysis::byPeriod('2024-01-01', '2024-01-31')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate Fecha de inicio (YYYY-MM-DD)
     * @param string $endDate Fecha de fin (YYYY-MM-DD)
     */
    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('period_start', [$startDate, $endDate])
                    ->orWhereBetween('period_end', [$startDate, $endDate]);
    }

    /**
     * SCOPE: Filtra por período de análisis específico
     * Uso: ProfitabilityAnalysis::byAnalysisPeriod('monthly')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $period Período de análisis
     */
    public function scopeByAnalysisPeriod($query, $period)
    {
        return $query->where('analysis_period', $period);
    }

    /**
     * SCOPE: Filtra solo análisis rentables (con ganancias)
     * Uso: ProfitabilityAnalysis::profitable()->get()
     */
    public function scopeProfitable($query)
    {
        return $query->where('net_profit', '>', 0);
    }

    /**
     * SCOPE: Filtra solo análisis con pérdidas
     * Uso: ProfitabilityAnalysis::loss()->get()
     */
    public function scopeLoss($query)
    {
        return $query->where('net_profit', '<', 0);
    }

    /**
     * SCOPE: Filtra solo análisis confirmados
     * Uso: ProfitabilityAnalysis::confirmed()->get()
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * SCOPE: Filtra por tipo de unidad específico
     * Uso: ProfitabilityAnalysis::byUnitType('egg')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $unitType Tipo de unidad
     */
    public function scopeByUnitType($query, $unitType)
    {
        return $query->where('unit_type', $unitType);
    }

    // ===== MÉTODOS DE BOOT: FUNCIONALIDADES AUTOMÁTICAS =====

    /**
     * MÉTODO BOOT: Se ejecuta automáticamente al guardar análisis
     * Calcula automáticamente todas las métricas antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * EVENTO: Antes de guardar un análisis
         * Calcula automáticamente todas las métricas de rentabilidad
         */
        static::saving(function ($analysis) {
            $analysis->calculateAllMetrics();
        });
    }
} 