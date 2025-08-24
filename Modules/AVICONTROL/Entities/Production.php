<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Production extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'avicontrol_productions';

    protected $fillable = [
        // Campos básicos
        'fecha',
        'tipo',
        'tipo_produccion',
        'galpon_id',
        'cantidad',
        'mortalidad_aves',
        'peso_promedio',
        'peso_total',
        'fecha_sacrificio',
        'responsable_sacrificio',
        'huevos_rotos',
        'huevos_sucios',
        'valor_unidad',
        'valor_total',
        'destino',
        'observaciones',
        'firma_recibido',
        'semana_produccion',
        'firma_lider',
        'estado',
        
        // Columnas antiguas (mantener para compatibilidad)
        'poultry_facility_id',
        'bird_id',
        'batch_code',
        'production_date',
        'egg_type_a',
        'egg_type_aa',
        'egg_type_b',
        'egg_type_c',
        'egg_type_d',
        'egg_count_total',
        'egg_weight_total',
        'egg_weight_average',
        'unit_value',
        'total_value',
        'destination',
        'week_number',
        'observations',
        'received_by',
        'status',
        'confirmed_by',
        'confirmed_at'
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_sacrificio' => 'date',
        'valor_unidad' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'peso_promedio' => 'decimal:2',
        'peso_total' => 'decimal:2'
    ];

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relaciones
    public function galpon()
    {
        return $this->belongsTo(Galpon::class, 'galpon_id');
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeByTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeByTipoProduccion($query, $tipoProduccion)
    {
        return $query->where('tipo_produccion', $tipoProduccion);
    }

    public function scopeHuevos($query)
    {
        return $query->where('tipo_produccion', 'huevos');
    }

    public function scopeCarne($query)
    {
        return $query->where('tipo_produccion', 'carne');
    }

    public function scopeByGalpon($query, $galponId)
    {
        return $query->where('galpon_id', $galponId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('fecha', [$startDate, $endDate]);
    }

    public function scopeBySemana($query, $semana)
    {
        return $query->where('semana_produccion', $semana);
    }

    // Accessors
    public function getFormattedFechaAttribute()
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : '';
    }

    public function getFormattedValorUnidadAttribute()
    {
        return '$' . number_format($this->valor_unidad, 0, ',', '.');
    }

    public function getFormattedValorTotalAttribute()
    {
        return '$' . number_format($this->valor_total, 0, ',', '.');
    }

    public function getFormattedPesoPromedioAttribute()
    {
        return $this->peso_promedio ? number_format($this->peso_promedio, 2, ',', '.') . ' kg' : '-';
    }

    public function getFormattedPesoTotalAttribute()
    {
        return $this->peso_total ? number_format($this->peso_total, 2, ',', '.') . ' kg' : '-';
    }

    public function getTipoProduccionDisplayAttribute()
    {
        return $this->tipo_produccion === 'huevos' ? 'Huevos' : 'Carne';
    }

    public function getCantidadHuevosBuenosAttribute()
    {
        if ($this->tipo_produccion === 'huevos') {
            return $this->cantidad - $this->huevos_rotos - $this->huevos_sucios;
        }
        return $this->cantidad;
    }

    // Mutators
    public function setValorTotalAttribute($value)
    {
        // Calcular automáticamente el valor total si no se proporciona
        if (empty($value) && $this->cantidad && $this->valor_unidad) {
            $this->attributes['valor_total'] = $this->cantidad * $this->valor_unidad;
        } else {
            $this->attributes['valor_total'] = $value;
        }
    }

    public function setPesoTotalAttribute($value)
    {
        // Calcular automáticamente el peso total si no se proporciona
        if (empty($value) && $this->cantidad && $this->peso_promedio) {
            $this->attributes['peso_total'] = $this->cantidad * $this->peso_promedio;
        } else {
            $this->attributes['peso_total'] = $value;
        }
    }

    // Métodos estáticos para estadísticas
    public static function getTotalBySemana($semana, $tipoProduccion = null)
    {
        $query = self::where('semana_produccion', $semana)->where('estado', 'activo');
        
        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }
        
        return $query->sum('cantidad');
    }

    public static function getValorTotalBySemana($semana, $tipoProduccion = null)
    {
        $query = self::where('semana_produccion', $semana)->where('estado', 'activo');
        
        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }
        
        return $query->sum('valor_total');
    }

    public static function getTotalByTipo($tipo, $semana = null, $tipoProduccion = null)
    {
        $query = self::where('tipo', $tipo)->where('estado', 'activo');
        
        if ($semana) {
            $query->where('semana_produccion', $semana);
        }
        
        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }
        
        return $query->sum('cantidad');
    }

    public static function getEstadisticasGenerales($semana = null, $tipoProduccion = null)
    {
        $query = self::where('estado', 'activo');
        
        if ($semana) {
            $query->where('semana_produccion', $semana);
        }

        if ($tipoProduccion) {
            $query->where('tipo_produccion', $tipoProduccion);
        }

        return [
            'total_huevos' => $query->sum('cantidad'),
            'valor_total' => $query->sum('valor_total'),
            'promedio_por_dia' => $query->distinct('fecha')->count(),
            'tipos_disponibles' => $query->distinct('tipo')->pluck('tipo')->toArray(),
            'peso_total' => $query->sum('peso_total'),
            'huevos_rotos' => $query->sum('huevos_rotos'),
            'huevos_sucios' => $query->sum('huevos_sucios')
        ];
    }
}