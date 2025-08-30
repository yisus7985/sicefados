<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo PoultryFacility - Gestiona las instalaciones avícolas (galpones)
 * 
 * Este modelo maneja:
 * - Información física de las instalaciones (dimensiones, capacidad)
 * - Tipos de instalaciones (gallinas ponedoras, pollos de engorde)
 * - Control de ocupación y capacidad disponible
 * - Relaciones con lotes de aves y producción
 * - Cálculos automáticos de área, volumen y densidad
 */
class PoultryFacility extends Model
{
    // TRAIT: SoftDeletes para borrado lógico (mantiene registros en BD)
    use SoftDeletes;

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_poultry_facilities';
    
    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'name',                     // Nombre o identificador de la instalación
        'tipo',                     // Tipo de instalación: 'gallinas_ponedoras' o 'pollos_engorde'
        'length',                    // Longitud de la instalación en metros
        'width',                     // Ancho de la instalación en metros
        'height',                    // Altura de la instalación en metros
        'capacity',                  // Capacidad máxima de aves que puede albergar
        'description',               // Descripción detallada de la instalación
        'status',                    // Estado de la instalación: 'active', 'inactive', 'maintenance'
        'creation_date'             // Fecha de creación/construcción de la instalación
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'creation_date',             // Fecha de creación de la instalación
        'created_at',                // Fecha de creación del registro en BD
        'updated_at',                // Fecha de última actualización del registro
        'deleted_at'                 // Fecha de borrado lógico (SoftDelete)
    ];

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Una instalación puede tener muchas producciones diarias
     * Retorna todas las producciones diarias registradas en esta instalación
     */
    public function dailyProduction()
    {
        return $this->hasMany(DailyProduction::class);
    }

    /**
     * RELACIÓN: Una instalación puede tener muchos lotes de aves
     * Retorna todos los lotes de aves alojados en esta instalación
     */
    public function birds()
    {
        return $this->hasMany(Bird::class, 'poultry_facility_id');
    }

    /**
     * RELACIÓN: Una instalación puede tener muchos lotes de aves activos
     * Retorna solo los lotes de aves con estado 'active'
     */
    public function activeBirds()
    {
        return $this->hasMany(Bird::class, 'poultry_facility_id')->where('status', 'active');
    }

    /**
     * RELACIÓN: Una instalación puede tener muchos lotes de aves (campo alternativo)
     * Retorna lotes de aves usando el campo 'galpon_id' como alternativa
     */
    public function aves()
    {
        return $this->hasMany(Bird::class, 'galpon_id');
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Calcula el área total de la instalación (largo × ancho)
     * Uso: $facility->area
     * 
     * @return float Área en metros cuadrados
     */
    public function getAreaAttribute()
    {
        return $this->length * $this->width;
    }

    /**
     * ACCESSOR: Calcula el volumen total de la instalación (largo × ancho × alto)
     * Uso: $facility->volume
     * 
     * @return float Volumen en metros cúbicos
     */
    public function getVolumeAttribute()
    {
        return $this->length * $this->width * $this->height;
    }

    /**
     * ACCESSOR: Calcula la densidad de aves por metro cuadrado
     * Uso: $facility->density
     * 
     * @return float Densidad de aves por m² (0 si no hay área)
     */
    public function getDensityAttribute()
    {
        $area = $this->getAreaAttribute();
        return $area > 0 ? $this->capacity / $area : 0;
    }

    /**
     * ACCESSOR: Calcula el total de aves activas en la instalación
     * Uso: $facility->total_active_birds
     * 
     * @return int Total de aves activas
     */
    public function getTotalActiveBirdsAttribute()
    {
        return $this->activeBirds()->sum('quantity');
    }

    /**
     * ACCESSOR: Calcula el porcentaje de ocupación de la instalación
     * Uso: $facility->occupancy_percentage
     * 
     * @return float Porcentaje de ocupación con 2 decimales
     */
    public function getOccupancyPercentageAttribute()
    {
        // Evita división por cero
        if ($this->capacity == 0) {
            return 0;
        }
        
        // Calcula el porcentaje de ocupación
        return round(($this->total_active_birds / $this->capacity) * 100, 2);
    }

    /**
     * ACCESSOR: Calcula la capacidad disponible en la instalación
     * Uso: $facility->available_capacity
     * 
     * @return int Capacidad disponible (total - ocupada)
     */
    public function getAvailableCapacityAttribute()
    {
        return $this->capacity - $this->total_active_birds;
    }

    // ===== MÉTODOS: FUNCIONALIDADES ESPECÍFICAS =====

    /**
     * MÉTODO: Verifica si la instalación puede alojar una cantidad específica de aves
     * Uso: $facility->canAccommodate(100)
     * 
     * @param int $quantity Cantidad de aves a verificar
     * @return bool True si puede alojar la cantidad especificada
     */
    public function canAccommodate($quantity)
    {
        return $this->available_capacity >= $quantity;
    }

    /**
     * MÉTODO: Verifica si la instalación es para producción de huevos
     * Uso: $facility->isEggProduction()
     * 
     * @return bool True si es para gallinas ponedoras
     */
    public function isEggProduction()
    {
        return $this->tipo === 'gallinas_ponedoras';
    }

    /**
     * MÉTODO: Verifica si la instalación es para producción de carne
     * Uso: $facility->isMeatProduction()
     * 
     * @return bool True si es para pollos de engorde
     */
    public function isMeatProduction()
    {
        return $this->tipo === 'pollos_engorde';
    }

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de producción
     * Uso: $facility->tipo_produccion_text
     * 
     * @return string Nombre legible del tipo de producción
     */
    public function getTipoProduccionTextAttribute()
    {
        return $this->tipo === 'gallinas_ponedoras' ? 'Gallinas Ponedoras' : 'Pollos de Engorde';
    }

    /**
     * ACCESSOR: Retorna el nombre corto del tipo de producción
     * Uso: $facility->tipo_produccion_short
     * 
     * @return string Nombre corto del tipo de producción
     */
    public function getTipoProduccionShortAttribute()
    {
        return $this->tipo === 'gallinas_ponedoras' ? 'Huevos' : 'Carne';
    }
}
