<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo CostComponent - Gestiona los componentes individuales de costos de producción
 * 
 * Este modelo maneja:
 * - Desglose detallado de cada componente de costo
 * - Cálculo automático de costos totales por componente
 * - Seguimiento de proveedores y facturas
 * - Diferentes tipos de unidades de medida
 * - Relación con costos de producción principales
 */
class CostComponent extends Model
{
    // TRAIT: SoftDeletes para borrado lógico (mantiene registros en BD)
    use SoftDeletes;

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_cost_components';
    
    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'production_cost_id',        // ID del costo de producción principal
        'component_type',            // Tipo de componente: 'concentrate', 'water', 'energy', etc.
        'component_name',            // Nombre específico del componente
        'quantity',                  // Cantidad del componente utilizado
        'unit_price',                // Precio por unidad del componente
        'total_cost',                // Costo total calculado automáticamente
        'unit_measure',              // Unidad de medida: 'kg', 'l', 'kwh', 'units', etc.
        'description',               // Descripción detallada del componente
        'date_applied',              // Fecha en que se aplicó el componente
        'supplier',                  // Proveedor del componente
        'invoice_number',            // Número de factura del proveedor
        'notes'                      // Notas adicionales del componente
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'date_applied',              // Fecha de aplicación del componente
        'created_at',                // Fecha de creación del registro
        'updated_at',                // Fecha de última actualización
        'deleted_at'                 // Fecha de borrado lógico (SoftDelete)
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'date_applied' => 'date',    // Convierte a objeto Carbon (fecha)
        'quantity' => 'decimal:4',   // Convierte a decimal con 4 decimales
        'unit_price' => 'decimal:2', // Convierte a decimal con 2 decimales
        'total_cost' => 'decimal:2'  // Convierte a decimal con 2 decimales
    ];

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un componente de costo pertenece a un costo de producción principal
     * Retorna el costo de producción al que pertenece este componente
     */
    public function productionCost()
    {
        return $this->belongsTo(ProductionCost::class, 'production_cost_id');
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de componente
     * Uso: $component->component_type_name
     * 
     * @return string Nombre legible del tipo de componente
     */
    public function getComponentTypeNameAttribute()
    {
        $types = [
            'concentrate' => 'Concentrado',      // Alimentos concentrados para aves
            'water' => 'Agua',                   // Consumo de agua
            'energy' => 'Energía',               // Consumo de energía eléctrica
            'medication' => 'Medicamentos',      // Medicamentos veterinarios
            'biological' => 'Biológicos',        // Vacunas y productos biológicos
            'packaging' => 'Embalajes',          // Materiales de empaque
            'labor' => 'Mano de Obra',           // Costos de personal
            'maintenance' => 'Mantenimiento',    // Mantenimiento de instalaciones
            'other' => 'Otros'                   // Otros costos no categorizados
        ];
        
        return $types[$this->component_type] ?? $this->component_type;
    }

    /**
     * ACCESSOR: Retorna el nombre legible de la unidad de medida
     * Uso: $component->unit_measure_name
     * 
     * @return string Nombre legible de la unidad de medida
     */
    public function getUnitMeasureNameAttribute()
    {
        $measures = [
            'kg' => 'Kilogramos',                // Peso en kilogramos
            'l' => 'Litros',                     // Volumen en litros
            'kwh' => 'Kilovatios-hora',          // Consumo de energía
            'units' => 'Unidades',               // Cantidad en unidades
            'hours' => 'Horas',                  // Tiempo en horas
            'days' => 'Días',                    // Tiempo en días
            'pieces' => 'Piezas'                 // Cantidad en piezas
        ];
        
        return $measures[$this->component_type] ?? $this->unit_measure;
    }

    // ===== MÉTODOS: FUNCIONALIDADES ESPECÍFICAS =====

    /**
     * MÉTODO: Calcula el costo total del componente
     * Multiplica la cantidad por el precio unitario
     * Uso: $component->calculateTotalCost()
     * 
     * @return float Costo total calculado
     */
    public function calculateTotalCost()
    {
        $this->total_cost = $this->quantity * $this->unit_price;
        return $this->total_cost;
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra por tipo de componente específico
     * Uso: CostComponent::byType('concentrate')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type Tipo de componente
     */
    public function scopeByType($query, $type)
    {
        return $query->where('component_type', $type);
    }

    /**
     * SCOPE: Filtra por rango de fechas de aplicación
     * Uso: CostComponent::byDateRange('2024-01-01', '2024-01-31')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate Fecha de inicio (YYYY-MM-DD)
     * @param string $endDate Fecha de fin (YYYY-MM-DD)
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date_applied', [$startDate, $endDate]);
    }

    /**
     * SCOPE: Filtra por proveedor específico
     * Uso: CostComponent::bySupplier('Proveedor ABC')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $supplier Nombre del proveedor
     */
    public function scopeBySupplier($query, $supplier)
    {
        return $query->where('supplier', 'like', '%' . $supplier . '%');
    }

    // ===== MÉTODOS DE BOOT: FUNCIONALIDADES AUTOMÁTICAS =====

    /**
     * MÉTODO BOOT: Se ejecuta automáticamente al guardar componentes
     * Calcula automáticamente el costo total antes de guardar
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * EVENTO: Antes de guardar un componente
         * Calcula automáticamente el costo total
         */
        static::saving(function ($component) {
            $component->calculateTotalCost();
        });
    }
} 