<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo ProductionWeek - Gestiona las semanas de producción avícola
 * 
 * Este modelo maneja:
 * - Definición de períodos semanales para agrupar producción
 * - Control de fechas de inicio y fin de cada semana
 * - Estados de las semanas (activa, inactiva)
 * - Relación con registros de producción
 * - Cálculo automático de duración de semanas
 */
class ProductionWeek extends Model
{
    // TRAITS: Funcionalidades adicionales del modelo
    use HasFactory, SoftDeletes; // HasFactory para testing, SoftDeletes para borrado lógico

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_production_weeks';

    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'nombre',                    // Nombre de la semana (ej: "Semana 1", "Semana 2")
        'fecha_inicio',             // Fecha de inicio de la semana de producción
        'fecha_fin',                // Fecha de fin de la semana de producción
        'descripcion',              // Descripción adicional de la semana
        'estado'                    // Estado de la semana: 'activa', 'inactiva'
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'fecha_inicio' => 'date',   // Convierte a objeto Carbon (fecha)
        'fecha_fin' => 'date'       // Convierte a objeto Carbon (fecha)
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'fecha_inicio',             // Fecha de inicio de la semana
        'fecha_fin',                // Fecha de fin de la semana
        'created_at',               // Fecha de creación del registro
        'updated_at',               // Fecha de última actualización
        'deleted_at'                // Fecha de borrado lógico (SoftDelete)
    ];

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra solo semanas activas
     * Uso: ProductionWeek::activa()->get()
     */
    public function scopeActiva($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * SCOPE: Filtra solo semanas inactivas
     * Uso: ProductionWeek::inactiva()->get()
     */
    public function scopeInactiva($query)
    {
        return $query->where('estado', 'inactiva');
    }

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Una semana puede tener muchas producciones
     * Relaciona las semanas con los registros de producción usando el nombre de la semana
     * Uso: $semana->productions (obtiene todas las producciones de esa semana)
     */
    public function productions()
    {
        return $this->hasMany(Production::class, 'semana_produccion', 'nombre');
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Retorna el nombre de la semana para mostrar
     * Uso: $semana->nombre_display
     * 
     * @return string Nombre de la semana
     */
    public function getNombreDisplayAttribute()
    {
        return $this->nombre;
    }

    /**
     * ACCESSOR: Formatea la fecha de inicio en formato dd/mm/yyyy
     * Uso: $semana->fecha_inicio_formatted
     * 
     * @return string Fecha formateada o cadena vacía si no hay fecha
     */
    public function getFechaInicioFormattedAttribute()
    {
        return $this->fecha_inicio ? $this->fecha_inicio->format('d/m/Y') : '';
    }

    /**
     * ACCESSOR: Formatea la fecha de fin en formato dd/mm/yyyy
     * Uso: $semana->fecha_fin_formatted
     * 
     * @return string Fecha formateada o cadena vacía si no hay fecha
     */
    public function getFechaFinFormattedAttribute()
    {
        return $this->fecha_fin ? $this->fecha_fin->format('d/m/Y') : '';
    }

    /**
     * ACCESSOR: Calcula la duración de la semana en días
     * Incluye tanto la fecha de inicio como la de fin en el conteo
     * Uso: $semana->duracion
     * 
     * @return int Duración de la semana en días (0 si no hay fechas)
     */
    public function getDuracionAttribute()
    {
        // Verifica que ambas fechas estén disponibles
        if ($this->fecha_inicio && $this->fecha_fin) {
            // Calcula la diferencia en días y suma 1 para incluir ambas fechas
            return $this->fecha_inicio->diffInDays($this->fecha_fin) + 1;
        }
        return 0;
    }
}
