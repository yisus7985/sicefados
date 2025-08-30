<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Modelo ProductionCost - Gestiona los costos de producción avícola
 * 
 * Este modelo maneja:
 * - Desglose detallado de costos por galpón y lote
 * - Cálculo de costos por unidad (huevo, kg de carne, ave)
 * - Seguimiento de costos por período o por lote
 * - Análisis de rentabilidad de la producción
 * - Estados de los registros de costos
 */
class ProductionCost extends Model
{
    // TRAIT: SoftDeletes para borrado lógico (mantiene registros en BD)
    use SoftDeletes;

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_production_costs';
    
    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'poultry_facility_id',      // ID de la instalación avícola (galpón)
        'bird_id',                  // ID del lote de aves
        'batch_code',               // Código único del lote de producción
        'period_start',             // Fecha de inicio del período de costos
        'period_end',               // Fecha de fin del período de costos
        'cost_type',                // Tipo de costo: 'batch' (por lote) o 'period' (por período)
        'concentrate_cost',         // Costo de concentrados/alimentos
        'water_cost',               // Costo de agua
        'energy_cost',              // Costo de energía eléctrica
        'medication_cost',          // Costo de medicamentos
        'biological_cost',          // Costo de productos biológicos (vacunas)
        'packaging_cost',           // Costo de empaques y embalajes
        'labor_cost',               // Costo de mano de obra
        'maintenance_cost',         // Costo de mantenimiento de instalaciones
        'other_costs',              // Otros costos no categorizados
        'total_cost',               // Costo total calculado automáticamente
        'cost_per_unit',            // Costo por unidad producida
        'unit_type',                // Tipo de unidad: 'egg', 'kg_meat', 'bird'
        'notes',                    // Notas y observaciones adicionales
        'status'                    // Estado del registro: 'draft', 'confirmed', 'cancelled'
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'period_start',             // Fecha de inicio del período
        'period_end',               // Fecha de fin del período
        'created_at',               // Fecha de creación del registro
        'updated_at',               // Fecha de última actualización
        'deleted_at'                // Fecha de borrado lógico (SoftDelete)
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'period_start' => 'date',           // Convierte a objeto Carbon (fecha)
        'period_end' => 'date',             // Convierte a objeto Carbon (fecha)
        'concentrate_cost' => 'decimal:2',  // Convierte a decimal con 2 decimales
        'water_cost' => 'decimal:2',        // Convierte a decimal con 2 decimales
        'energy_cost' => 'decimal:2',       // Convierte a decimal con 2 decimales
        'medication_cost' => 'decimal:2',   // Convierte a decimal con 2 decimales
        'biological_cost' => 'decimal:2',   // Convierte a decimal con 2 decimales
        'packaging_cost' => 'decimal:2',    // Convierte a decimal con 2 decimales
        'labor_cost' => 'decimal:2',        // Convierte a decimal con 2 decimales
        'maintenance_cost' => 'decimal:2',  // Convierte a decimal con 2 decimales
        'other_costs' => 'decimal:2',       // Convierte a decimal con 2 decimales
        'total_cost' => 'decimal:2',        // Convierte a decimal con 2 decimales
        'cost_per_unit' => 'decimal:2'      // Convierte a decimal con 2 decimales
    ];

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un costo de producción pertenece a una instalación avícola
     * Retorna la instalación (galpón) donde se generaron los costos
     */
    public function poultryFacility()
    {
        return $this->belongsTo(PoultryFacility::class, 'poultry_facility_id');
    }

    /**
     * RELACIÓN: Un costo de producción está asociado a un lote de aves
     * Retorna el lote de aves que generó los costos
     */
    public function bird()
    {
        return $this->belongsTo(Bird::class, 'bird_id');
    }

    /**
     * RELACIÓN: Un costo de producción puede tener muchos componentes de costo
     * Retorna los componentes individuales que conforman el costo total
     */
    public function costComponents()
    {
        return $this->hasMany(CostComponent::class, 'production_cost_id');
    }

    /**
     * RELACIÓN: Un costo de producción puede tener un análisis de rentabilidad
     * Retorna el análisis de rentabilidad asociado (si existe la tabla)
     */
    public function profitabilityAnalysis()
    {
        // Verifica si la tabla existe antes de intentar la relación
        if (\Schema::hasTable('avicontrol_profitability_analysis')) {
            return $this->hasOne(ProfitabilityAnalysis::class, 'production_cost_id');
        }
        return null;
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de costo
     * Uso: $productionCost->cost_type_name
     * 
     * @return string Nombre legible del tipo de costo
     */
    public function getCostTypeNameAttribute()
    {
        $types = [
            'batch' => 'Por Lote',      // Costo calculado por lote específico
            'period' => 'Por Período'    // Costo calculado por período de tiempo
        ];
        
        return $types[$this->cost_type] ?? $this->cost_type;
    }

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de unidad
     * Uso: $productionCost->unit_type_name
     * 
     * @return string Nombre legible del tipo de unidad
     */
    public function getUnitTypeNameAttribute()
    {
        $types = [
            'egg' => 'Por Huevo',        // Costo calculado por huevo individual
            'kg_meat' => 'Por Kg de Carne', // Costo calculado por kilogramo de carne
            'bird' => 'Por Ave'           // Costo calculado por ave individual
        ];
        
        return $types[$this->unit_type] ?? $this->unit_type;
    }

    /**
     * ACCESSOR: Calcula la duración del período en días
     * Uso: $productionCost->period_duration
     * 
     * @return int Duración del período en días
     */
    public function getPeriodDurationAttribute()
    {
        if ($this->period_start && $this->period_end) {
            return $this->period_start->diffInDays($this->period_end) + 1;
        }
        return 0;
    }

    /**
     * ACCESSOR: Calcula el costo promedio por día
     * Uso: $productionCost->cost_per_day
     * 
     * @return float Costo promedio por día
     */
    public function getCostPerDayAttribute()
    {
        $duration = $this->getPeriodDurationAttribute();
        return $duration > 0 ? $this->total_cost / $duration : 0;
    }

    // ===== MÉTODOS: FUNCIONALIDADES ESPECÍFICAS =====

    /**
     * MÉTODO: Calcula el costo total sumando todos los componentes
     * Se ejecuta automáticamente antes de guardar el modelo
     */
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

    /**
     * MÉTODO: Calcula el costo por unidad basado en la producción
     * Requiere que se proporcione la cantidad de unidades producidas
     * 
     * @param int $unitsProduced Cantidad de unidades producidas
     * @return float Costo por unidad
     */
    public function calculateCostPerUnit($unitsProduced)
    {
        if ($unitsProduced > 0) {
            $this->cost_per_unit = $this->total_cost / $unitsProduced;
            return $this->cost_per_unit;
        }
        return 0;
    }

    /**
     * MÉTODO: Verifica si el costo está en estado borrador
     * Uso: $productionCost->isDraft()
     * 
     * @return bool True si el costo está en borrador
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * MÉTODO: Verifica si el costo está confirmado
     * Uso: $productionCost->isConfirmed()
     * 
     * @return bool True si el costo está confirmado
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * MÉTODO: Verifica si el costo está cancelado
     * Uso: $productionCost->isCancelled()
     * 
     * @return bool True si el costo está cancelado
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * MÉTODO: Obtiene el costo más alto entre todos los componentes
     * Útil para identificar el componente de mayor impacto en los costos
     * 
     * @return array Array con el componente y su costo
     */
    public function getHighestCostComponent()
    {
        $costs = [
            'Concentrados' => $this->concentrate_cost,
            'Agua' => $this->water_cost,
            'Energía' => $this->energy_cost,
            'Medicamentos' => $this->medication_cost,
            'Biológicos' => $this->biological_cost,
            'Empaques' => $this->packaging_cost,
            'Mano de Obra' => $this->labor_cost,
            'Mantenimiento' => $this->maintenance_cost,
            'Otros' => $this->other_costs
        ];

        $highest = max($costs);
        $component = array_search($highest, $costs);

        return [
            'component' => $component,
            'cost' => $highest,
            'percentage' => $this->total_cost > 0 ? ($highest / $this->total_cost) * 100 : 0
        ];
    }

    /**
     * MÉTODO: Obtiene un resumen de costos por categoría
     * Útil para análisis y reportes
     * 
     * @return array Array con resumen de costos por categoría
     */
    public function getCostSummary()
    {
        return [
            'alimentacion' => $this->concentrate_cost,           // Costos de alimentación
            'servicios_basicos' => $this->water_cost + $this->energy_cost, // Agua y energía
            'salud' => $this->medication_cost + $this->biological_cost,    // Medicamentos y vacunas
            'operacion' => $this->labor_cost + $this->maintenance_cost,    // Mano de obra y mantenimiento
            'otros' => $this->packaging_cost + $this->other_costs,        // Empaques y otros
            'total' => $this->total_cost                          // Total general
        ];
    }
} 