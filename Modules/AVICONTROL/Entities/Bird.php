<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Bird - Gestiona los lotes de aves en galpones
 * 
 * Este modelo maneja:
 * - Información de lotes de aves (pollitos, gallinas, pollos)
 * - Seguimiento de mortalidad y crecimiento
 * - Control de consumo de alimento y tasas de postura
 * - Estados de los lotes (activo, vendido, fallecido)
 * - Cálculos automáticos de edad, mortalidad y valor
 */
class Bird extends Model
{
    // TRAIT: SoftDeletes para borrado lógico (mantiene registros en BD)
    use SoftDeletes;

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_birds';
    
    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'poultry_facility_id',      // ID de la instalación avícola principal
        'galpon_id',                // ID del galpón específico (relación adicional)
        'batch_code',               // Código único del lote de aves
        'batch_name',               // Nombre descriptivo del lote
        'bird_type',                // Tipo de ave: 'laying_hens', 'broilers', 'chicks', 'breeders'
        'quantity',                 // Cantidad actual de aves en el lote
        'initial_quantity',         // Cantidad inicial de aves al ingresar
        'entry_date',               // Fecha de ingreso del lote al galpón
        'age_weeks',                // Edad inicial en semanas al ingresar
        'breed',                    // Raza o variedad de las aves
        'average_weight',           // Peso promedio por ave en kilogramos
        'status',                   // Estado del lote: 'active', 'sold', 'deceased', 'transferred'
        'mortality_rate',           // Tasa de mortalidad esperada (porcentaje)
        'feed_consumption',         // Consumo de alimento por ave por día (gramos)
        'laying_rate',              // Tasa de postura para gallinas ponedoras (porcentaje)
        'notes',                    // Notas y observaciones del lote
        'purchase_price',           // Precio de compra por ave
        'supplier'                  // Proveedor o granja de origen
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'entry_date',               // Fecha de ingreso del lote
        'created_at',               // Fecha de creación del registro
        'updated_at',               // Fecha de última actualización
        'deleted_at'                // Fecha de borrado lógico (SoftDelete)
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'entry_date' => 'date',             // Convierte a objeto Carbon (fecha)
        'average_weight' => 'decimal:2',    // Convierte a decimal con 2 decimales
        'purchase_price' => 'decimal:2',    // Convierte a decimal con 2 decimales
        'mortality_rate' => 'decimal:2',    // Convierte a decimal con 2 decimales
        'feed_consumption' => 'decimal:2',  // Convierte a decimal con 2 decimales
        'laying_rate' => 'decimal:2'        // Convierte a decimal con 2 decimales
    ];

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un lote de aves pertenece a una instalación avícola principal
     * Retorna la instalación avícola donde se encuentra el lote
     */
    public function poultryFacility()
    {
        return $this->belongsTo(PoultryFacility::class, 'poultry_facility_id');
    }

    /**
     * RELACIÓN: Un lote de aves pertenece a un galpón específico
     * Retorna el galpón donde se encuentra el lote
     */
    public function galpon()
    {
        return $this->belongsTo(PoultryFacility::class, 'galpon_id');
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de ave en español
     * Uso: $bird->bird_type_name
     * 
     * @return string Nombre legible del tipo de ave
     */
    public function getBirdTypeNameAttribute()
    {
        $types = [
            'laying_hens' => 'Gallinas Ponedoras',  // Aves para producción de huevos
            'broilers' => 'Pollos de Engorde',       // Aves para producción de carne
            'chicks' => 'Pollitos',                   // Aves jóvenes en crecimiento
            'breeders' => 'Reproductores'             // Aves para reproducción
        ];
        
        return $types[$this->bird_type] ?? $this->bird_type;
    }

    /**
     * ACCESSOR: Retorna el nombre legible del estado del lote
     * Uso: $bird->status_name
     * 
     * @return string Nombre legible del estado
     */
    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'Activo',        // Lote activo en producción
            'sold' => 'Vendido',         // Lote vendido/comercializado
            'deceased' => 'Fallecido',   // Lote perdido por mortalidad
            'transferred' => 'Transferido' // Lote transferido a otro galpón
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * ACCESSOR: Calcula la edad actual del lote en semanas
     * Suma la edad inicial con las semanas transcurridas desde el ingreso
     * Uso: $bird->current_age_weeks
     * 
     * @return int|null Edad actual en semanas o null si no hay datos
     */
    public function getCurrentAgeWeeksAttribute()
    {
        // Verifica que haya edad inicial y fecha de ingreso
        if (!$this->age_weeks || !$this->entry_date) {
            return null;
        }

        // Calcula las semanas transcurridas desde el ingreso
        $weeksFromEntry = $this->entry_date->diffInWeeks(now());
        return $this->age_weeks + $weeksFromEntry;
    }

    /**
     * ACCESSOR: Calcula el valor total del lote
     * Multiplica la cantidad actual por el precio de compra
     * Uso: $bird->total_value
     * 
     * @return float Valor total del lote
     */
    public function getTotalValueAttribute()
    {
        return $this->quantity * ($this->purchase_price ?? 0);
    }

    /**
     * ACCESSOR: Calcula la cantidad de aves que han fallecido
     * Diferencia entre cantidad inicial y cantidad actual
     * Uso: $bird->mortality
     * 
     * @return int Cantidad de aves fallecidas
     */
    public function getMortalityAttribute()
    {
        return $this->initial_quantity - $this->quantity;
    }

    /**
     * ACCESSOR: Calcula el porcentaje de mortalidad del lote
     * Porcentaje de aves fallecidas respecto al total inicial
     * Uso: $bird->mortality_percentage
     * 
     * @return float Porcentaje de mortalidad con 2 decimales
     */
    public function getMortalityPercentageAttribute()
    {
        // Evita división por cero
        if ($this->initial_quantity == 0) {
            return 0;
        }

        // Calcula el porcentaje de mortalidad
        return round(($this->mortality / $this->initial_quantity) * 100, 2);
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra solo lotes activos
     * Uso: Bird::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * SCOPE: Filtra por instalación avícola específica
     * Uso: Bird::byFacility(5)->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $facilityId ID de la instalación avícola
     */
    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('poultry_facility_id', $facilityId);
    }

    /**
     * SCOPE: Filtra por tipo de ave específico
     * Uso: Bird::byType('laying_hens')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type Tipo de ave
     */
    public function scopeByType($query, $type)
    {
        return $query->where('bird_type', $type);
    }
}
