<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Galpon - Gestiona las instalaciones avícolas (galpones)
 * 
 * Este modelo maneja:
 * - Información física de los galpones (dimensiones, capacidad)
 * - Tipos de galpones (gallinas ponedoras, pollos de engorde)
 * - Estado y configuración de las instalaciones
 * - Relaciones con la producción y lotes de aves
 * - Cálculos automáticos de área, volumen y densidad
 */
class Galpon extends Model
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
        'name',                     // Nombre o identificador del galpón
        'tipo',                     // Tipo de galpón: 'gallinas_ponedoras' o 'pollos_engorde'
        'length',                    // Longitud del galpón en metros
        'width',                     // Ancho del galpón en metros
        'height',                    // Altura del galpón en metros
        'capacity',                  // Capacidad máxima de aves que puede albergar
        'description',               // Descripción detallada del galpón
        'status',                    // Estado del galpón: 'active', 'inactive', 'maintenance'
        'creation_date'             // Fecha de creación/construcción del galpón
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'creation_date' => 'date'   // Convierte a objeto Carbon (fecha)
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'creation_date',             // Fecha de creación del galpón
        'created_at',                // Fecha de creación del registro en BD
        'updated_at',                // Fecha de última actualización del registro
        'deleted_at'                 // Fecha de borrado lógico (SoftDelete)
    ];

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un galpón puede tener muchas producciones
     * Retorna todas las producciones registradas en este galpón
     */
    public function productions()
    {
        return $this->hasMany(Production::class, 'galpon_id');
    }

    /**
     * RELACIÓN: Un galpón puede tener muchas producciones diarias (a futuro)
     * Esta relación está preparada para futuras funcionalidades
     */
    public function produccionDiaria()
    {
        return $this->hasMany(ProduccionDiaria::class);
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra solo galpones de gallinas ponedoras
     * Uso: Galpon::gallinasPonedoras()->get()
     */
    public function scopeGallinasPonedoras($query)
    {
        return $query->where('tipo', 'gallinas_ponedoras');
    }

    /**
     * SCOPE: Filtra solo galpones de pollos de engorde
     * Uso: Galpon::pollosEngorde()->get()
     */
    public function scopePollosEngorde($query)
    {
        return $query->where('tipo', 'pollos_engorde');
    }

    /**
     * SCOPE: Filtra solo galpones activos
     * Uso: Galpon::activo()->get()
     */
    public function scopeActivo($query)
    {
        return $query->where('status', 'active');
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Calcula el área total del galpón (largo × ancho)
     * Uso: $galpon->area
     * 
     * @return float Área en metros cuadrados
     */
    public function getAreaAttribute()
    {
        return $this->length * $this->width;
    }

    /**
     * ACCESSOR: Calcula el volumen total del galpón (largo × ancho × alto)
     * Uso: $galpon->volumen
     * 
     * @return float Volumen en metros cúbicos
     */
    public function getVolumenAttribute()
    {
        return $this->length * $this->width * $this->height;
    }

    /**
     * ACCESSOR: Calcula la densidad de aves por metro cuadrado
     * Uso: $galpon->densidad
     * 
     * @return float Densidad de aves por m² (0 si no hay área)
     */
    public function getDensidadAttribute()
    {
        $area = $this->getAreaAttribute();
        return $area > 0 ? $this->capacity / $area : 0;
    }

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de galpón
     * Uso: $galpon->tipo_display
     * 
     * @return string Nombre legible del tipo de galpón
     */
    public function getTipoDisplayAttribute()
    {
        return $this->tipo === 'gallinas_ponedoras' ? 'Gallinas Ponederas' : 'Pollos de Engorde';
    }

    /**
     * ACCESSOR: Retorna el tipo de producción del galpón
     * Uso: $galpon->tipo_produccion
     * 
     * @return string Tipo de producción: 'huevos' o 'carne'
     */
    public function getTipoProduccionAttribute()
    {
        return $this->tipo === 'gallinas_ponedoras' ? 'huevos' : 'carne';
    }
}
