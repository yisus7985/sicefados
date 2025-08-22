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
        // Nuevas columnas
        'fecha',
        'tipo',
        'cantidad',
        'valor_unidad',
        'valor_total',
        'destino',
        'observaciones',
        'firma_recibido',
        'semana_produccion',
        'firma_lider',
        'estado',
        
        // Columnas antiguas
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
        'valor_unidad' => 'decimal:2',
        'valor_total' => 'decimal:2'
    ];

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeByTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
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

    // Métodos estáticos para estadísticas
    public static function getTotalBySemana($semana)
    {
        return self::where('semana_produccion', $semana)
            ->where('estado', 'activo')
            ->sum('cantidad');
    }

    public static function getValorTotalBySemana($semana)
    {
        return self::where('semana_produccion', $semana)
            ->where('estado', 'activo')
            ->sum('valor_total');
    }

    public static function getTotalByTipo($tipo, $semana = null)
    {
        $query = self::where('tipo', $tipo)->where('estado', 'activo');
        
        if ($semana) {
            $query->where('semana_produccion', $semana);
        }
        
        return $query->sum('cantidad');
    }

    public static function getEstadisticasGenerales($semana = null)
    {
        $query = self::where('estado', 'activo');
        
        if ($semana) {
            $query->where('semana_produccion', $semana);
        }

        return [
            'total_huevos' => $query->sum('cantidad'),
            'valor_total' => $query->sum('valor_total'),
            'promedio_por_dia' => $query->distinct('fecha')->count(),
            'tipos_disponibles' => $query->distinct('tipo')->pluck('tipo')->toArray()
        ];
    }
}