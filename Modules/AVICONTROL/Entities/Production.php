<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

/**
 * Modelo Production - Gestiona la producción de huevos y carne en galpones
 * 
 * Este modelo maneja:
 * - Registro diario de producción por galpón
 * - Control de tipos de huevos (A, AA, B, C, D)
 * - Seguimiento de producción de carne
 * - Cálculo automático de valores y pesos totales
 * - Estadísticas de producción por período
 */
class Production extends Model
{
    // TRAITS: Funcionalidades adicionales del modelo
    use HasFactory, SoftDeletes; // HasFactory para testing, SoftDeletes para borrado lógico

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_productions';

    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        // ===== CAMPOS BÁSICOS DE PRODUCCIÓN =====
        'fecha',                    // Fecha de registro de la producción
        'tipo',                     // Tipo de huevo (A, AA, B, C, D) o calidad de carne
        'tipo_produccion',          // Tipo de producción: 'huevos' o 'carne'
        'galpon_id',                // ID del galpón donde se registró la producción
        'cantidad',                 // Cantidad total producida (huevos o kg de carne)
        'mortalidad_aves',          // Número de aves que murieron en el período
        'peso_promedio',            // Peso promedio por unidad (huevo o ave)
        'peso_total',               // Peso total de toda la producción
        'fecha_sacrificio',         // Fecha de sacrificio (solo para producción de carne)
        'responsable_sacrificio',   // Persona responsable del sacrificio
        'huevos_rotos',             // Cantidad de huevos rotos (solo para huevos)
        'huevos_sucios',            // Cantidad de huevos sucios (solo para huevos)
        'valor_unidad',             // Valor por unidad individual
        'valor_total',              // Valor total de toda la producción
        'destino',                  // Destino de la producción (venta, consumo, etc.)
        'observaciones',            // Observaciones adicionales del registro
        'firma_recibido',           // Firma de quien recibió la producción
        'semana_produccion',        // Semana de producción para agrupación
        'firma_lider',              // Firma del líder de producción
        'estado',                   // Estado del registro: 'activo', 'cancelado', 'pendiente'
        
        // ===== CAMPOS ANTIGUOS (MANTENER PARA COMPATIBILIDAD) =====
        // Estos campos se mantienen para no romper funcionalidades existentes
        'poultry_facility_id',      // ID de la instalación avícola (campo antiguo)
        'bird_id',                  // ID del lote de aves (campo antiguo)
        'batch_code',               // Código del lote (campo antiguo)
        'production_date',          // Fecha de producción (campo antiguo)
        'egg_type_a',               // Cantidad de huevos tipo A (campo antiguo)
        'egg_type_aa',              // Cantidad de huevos tipo AA (campo antiguo)
        'egg_type_b',               // Cantidad de huevos tipo B (campo antiguo)
        'egg_type_c',               // Cantidad de huevos tipo C (campo antiguo)
        'egg_type_d',               // Cantidad de huevos tipo D (campo antiguo)
        'egg_count_total',          // Total de huevos (campo antiguo)
        'egg_weight_total',         // Peso total de huevos (campo antiguo)
        'egg_weight_average',       // Peso promedio de huevos (campo antiguo)
        'unit_value',               // Valor por unidad (campo antiguo)
        'total_value',              // Valor total (campo antiguo)
        'destination',              // Destino (campo antiguo)
        'week_number',              // Número de semana (campo antiguo)
        'observations',             // Observaciones (campo antiguo)
        'received_by',              // Recibido por (campo antiguo)
        'status',                   // Estado (campo antiguo)
        'confirmed_by',             // Confirmado por (campo antiguo)
        'confirmed_at'              // Fecha de confirmación (campo antiguo)
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'fecha' => 'date',                    // Convierte a objeto Carbon (fecha)
        'fecha_sacrificio' => 'date',         // Convierte a objeto Carbon (fecha)
        'valor_unidad' => 'decimal:2',        // Convierte a decimal con 2 decimales
        'valor_total' => 'decimal:2',         // Convierte a decimal con 2 decimales
        'peso_promedio' => 'decimal:2',       // Convierte a decimal con 2 decimales
        'peso_total' => 'decimal:2'           // Convierte a decimal con 2 decimales
    ];

    /**
     * DATES: Campos que se tratan como fechas
     * Laravel automáticamente convierte estos campos a objetos Carbon
     */
    protected $dates = [
        'fecha',                    // Fecha de producción
        'created_at',               // Fecha de creación del registro
        'updated_at',               // Fecha de última actualización
        'deleted_at'                // Fecha de borrado lógico (SoftDelete)
    ];

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un registro de producción pertenece a un galpón
     * Retorna el galpón asociado a esta producción
     */
    public function galpon()
    {
        return $this->belongsTo(Galpon::class, 'galpon_id');
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra solo registros activos
     * Uso: Production::activo()->get()
     */
    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    /**
     * SCOPE: Filtra por tipo de huevo o calidad
     * Uso: Production::byTipo('A')->get()
     */
    public function scopeByTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * SCOPE: Filtra por tipo de producción (huevos o carne)
     * Uso: Production::byTipoProduccion('huevos')->get()
     */
    public function scopeByTipoProduccion($query, $tipoProduccion)
    {
        return $query->where('tipo_produccion', $tipoProduccion);
    }

    /**
     * SCOPE: Filtra solo producción de huevos
     * Uso: Production::huevos()->get()
     */
    public function scopeHuevos($query)
    {
        return $query->where('tipo_produccion', 'huevos');
    }

    /**
     * SCOPE: Filtra solo producción de carne
     * Uso: Production::carne()->get()
     */
    public function scopeCarne($query)
    {
        return $query->where('tipo_produccion', 'carne');
    }

    /**
     * SCOPE: Filtra por galpón específico
     * Uso: Production::byGalpon(5)->get()
     */
    public function scopeByGalpon($query, $galponId)
    {
        return $query->where('galpon_id', $galponId);
    }

    /**
     * SCOPE: Filtra por rango de fechas
     * Uso: Production::byDateRange('2024-01-01', '2024-01-31')->get()
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('fecha', [$startDate, $endDate]);
    }

    /**
     * SCOPE: Filtra por semana de producción
     * Uso: Production::bySemana('Semana 1')->get()
     */
    public function scopeBySemana($query, $semana)
    {
        return $query->where('semana_produccion', $semana);
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Formatea la fecha en formato dd/mm/yyyy
     * Uso: $production->formatted_fecha
     */
    public function getFormattedFechaAttribute()
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : '';
    }

    /**
     * ACCESSOR: Formatea el valor por unidad con formato de moneda
     * Uso: $production->formatted_valor_unidad
     */
    public function getFormattedValorUnidadAttribute()
    {
        return '$' . number_format($this->valor_unidad, 0, ',', '.');
    }

    /**
     * ACCESSOR: Formatea el valor total con formato de moneda
     * Uso: $production->formatted_valor_total
     */
    public function getFormattedValorTotalAttribute()
    {
        return '$' . number_format($this->valor_total, 0, ',', '.');
    }

    /**
     * ACCESSOR: Formatea el peso promedio con unidades
     * Uso: $production->formatted_peso_promedio
     */
    public function getFormattedPesoPromedioAttribute()
    {
        return $this->peso_promedio ? number_format($this->peso_promedio, 2, ',', '.') . ' kg' : '-';
    }

    /**
     * ACCESSOR: Formatea el peso total con unidades
     * Uso: $production->formatted_peso_total
     */
    public function getFormattedPesoTotalAttribute()
    {
        return $this->peso_total ? number_format($this->peso_total, 2, ',', '.') . ' kg' : '-';
    }

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de producción
     * Uso: $production->tipo_produccion_display
     */
    public function getTipoProduccionDisplayAttribute()
    {
        return $this->tipo_produccion === 'huevos' ? 'Huevos' : 'Carne';
    }

    /**
     * ACCESSOR: Calcula la cantidad de huevos buenos (total - rotos - sucios)
     * Uso: $production->cantidad_huevos_buenos
     */
    public function getCantidadHuevosBuenosAttribute()
    {
        if ($this->tipo_produccion === 'huevos') {
            return $this->cantidad - $this->huevos_rotos - $this->huevos_sucios;
        }
        return $this->cantidad;
    }

    // ===== MUTATORS: MÉTODOS QUE SE EJECUTAN AL ASIGNAR VALORES =====

    /**
     * MUTATOR: Calcula automáticamente el valor total al asignar cantidad y valor_unitario
     * Se ejecuta automáticamente cuando se asigna valor_total
     */
    public function setValorTotalAttribute($value)
    {
        // Si no se proporciona valor_total, lo calcula automáticamente
        if (empty($value) && $this->cantidad && $this->valor_unidad) {
            $this->attributes['valor_total'] = $this->cantidad * $this->valor_unidad;
        } else {
            $this->attributes['valor_total'] = $value;
        }
    }

    /**
     * MUTATOR: Calcula automáticamente el peso total al asignar cantidad y peso_promedio
     * Se ejecuta automáticamente cuando se asigna peso_total
     */
    public function setPesoTotalAttribute($value)
    {
        // Si no se proporciona peso_total, lo calcula automáticamente
        if (empty($value) && $this->cantidad && $this->peso_promedio) {
            $this->attributes['peso_total'] = $this->cantidad * $this->peso_promedio;
        } else {
            $this->attributes['peso_total'] = $value;
        }
    }

    // ===== MÉTODOS ESTÁTICOS: FUNCIONES QUE NO REQUIEREN INSTANCIA =====

    /**
     * MÉTODO ESTÁTICO: Obtiene el total de producción por semana
     * Uso: Production::getTotalBySemana('Semana 1', 'huevos')
     * 
     * @param string $semana Nombre de la semana
     * @param string|null $tipoProduccion Tipo de producción (opcional)
     * @return int Total de cantidad producida
     */
    public static function getTotalBySemana($semana, $tipoProduccion = null)
    {
        $query = self::where('semana_produccion', $semana)->where('estado', 'activo');
        
        // Filtra por tipo de producción si se especifica
        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }
        
        return $query->sum('cantidad');
    }

    /**
     * MÉTODO ESTÁTICO: Obtiene el valor total de producción por semana
     * Uso: Production::getValorTotalBySemana('Semana 1', 'huevos')
     * 
     * @param string $semana Nombre de la semana
     * @param string|null $tipoProduccion Tipo de producción (opcional)
     * @return float Valor total de la producción
     */
    public static function getValorTotalBySemana($semana, $tipoProduccion = null)
    {
        $query = self::where('semana_produccion', $semana)->where('estado', 'activo');
        
        // Filtra por tipo de producción si se especifica
        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }
        
        return $query->sum('valor_total');
    }

    /**
     * MÉTODO ESTÁTICO: Obtiene el total de producción por tipo de huevo/calidad
     * Uso: Production::getTotalByTipo('A', 'Semana 1', 'huevos')
     * 
     * @param string $tipo Tipo de huevo o calidad
     * @param string|null $semana Semana específica (opcional)
     * @param string|null $tipoProduccion Tipo de producción (opcional)
     * @return int Total de cantidad producida
     */
    public static function getTotalByTipo($tipo, $semana = null, $tipoProduccion = null)
    {
        $query = self::where('tipo', $tipo)->where('estado', 'activo');
        
        // Filtra por semana si se especifica
        if ($semana) {
            $query->where('semana_produccion', $semana);
        }
        
        // Filtra por tipo de producción si se especifica
        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }
        
        return $query->sum('cantidad');
    }

    /**
     * MÉTODO ESTÁTICO: Obtiene estadísticas generales de producción
     * Uso: Production::getEstadisticasGenerales('Semana 1', 'huevos')
     * 
     * @param string|null $semana Semana específica (opcional)
     * @param string|null $tipoProduccion Tipo de producción (opcional)
     * @return array Array con estadísticas de producción
     */
    public static function getEstadisticasGenerales($semana = null, $tipoProduccion = null)
    {
        $query = self::where('estado', 'activo');
        
        // Filtra por semana si se especifica
        if ($semana) {
            $query->where('semana_produccion', $semana);
        }

        // Filtra por tipo de producción si se especifica
        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }

        // Retorna array con todas las estadísticas calculadas
        return [
            'total_huevos' => $query->sum('cantidad'),           // Total de unidades producidas
            'valor_total' => $query->sum('valor_total'),         // Valor total de la producción
            'promedio_por_dia' => $query->distinct('fecha')->count(), // Días con producción
            'tipos_disponibles' => $query->distinct('tipo')->pluck('tipo')->toArray(), // Tipos disponibles
            'peso_total' => $query->sum('peso_total'),           // Peso total de la producción
            'huevos_rotos' => $query->sum('huevos_rotos'),       // Total de huevos rotos
            'huevos_sucios' => $query->sum('huevos_sucios')      // Total de huevos sucios
        ];
    }
}