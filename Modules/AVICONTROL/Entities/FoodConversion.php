<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\AVICONTROL\Entities\PoultryFacility;

class FoodConversion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'avicontrol_food_conversion';

    protected $fillable = [
        'galpon_id',
        'fecha_inicio',
        'fecha_fin',
        'periodo_tipo', // diario, semanal, mensual, acumulado
        'total_alimento_consumido',
        'total_producto_obtenido', // kg de huevo o kg de ganancia de peso
        'conversion_alimenticia',
        'tipo_produccion', // huevo, carne
        'observaciones',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'total_alimento_consumido' => 'decimal:2',
        'total_producto_obtenido' => 'decimal:2',
        'conversion_alimenticia' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Tipos de período
    const PERIODO_DIARIO = 'diario';
    const PERIODO_SEMANAL = 'semanal';
    const PERIODO_MENSUAL = 'mensual';
    const PERIODO_ACUMULADO = 'acumulado';

    // Tipos de producción
    const PRODUCCION_HUEVO = 'huevo';
    const PRODUCCION_CARNE = 'carne';

    // Estados
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';

    // Relaciones
    public function galpon()
    {
        return $this->belongsTo(PoultryFacility::class, 'galpon_id');
    }

    public function consumos()
    {
        return $this->hasMany(FoodConsumption::class, 'galpon_id', 'galpon_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('estado', self::STATUS_ACTIVE);
    }

    public function scopeByPeriodo($query, $periodo)
    {
        return $query->where('periodo_tipo', $periodo);
    }

    public function scopeByGalpon($query, $galponId)
    {
        return $query->where('galpon_id', $galponId);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('fecha_inicio', [$startDate, $endDate]);
    }

    public function scopeByTipoProduccion($query, $tipo)
    {
        return $query->where('tipo_produccion', $tipo);
    }

    // Métodos
    public function calcularConversionAlimenticia()
    {
        if ($this->total_producto_obtenido > 0 && $this->total_alimento_consumido > 0) {
            $this->conversion_alimenticia = $this->total_alimento_consumido / $this->total_producto_obtenido;
            $this->save();
        }
    }

    public function getConversionAlimenticiaFormateadaAttribute()
    {
        return number_format($this->conversion_alimenticia, 2);
    }

    public function getEficienciaAttribute()
    {
        // Una conversión más baja indica mayor eficiencia
        if ($this->conversion_alimenticia > 0) {
            // Calcular eficiencia como porcentaje (ejemplo: conversión de 2.0 = 50% eficiencia)
            $eficienciaBase = 1.5; // Conversión ideal
            $eficiencia = ($eficienciaBase / $this->conversion_alimenticia) * 100;
            return min(100, max(0, $eficiencia));
        }
        return 0;
    }

    public function getNivelEficienciaAttribute()
    {
        $eficiencia = $this->getEficienciaAttribute();
        
        if ($eficiencia >= 90) return 'Excelente';
        if ($eficiencia >= 80) return 'Buena';
        if ($eficiencia >= 70) return 'Regular';
        if ($eficiencia >= 60) return 'Baja';
        return 'Muy Baja';
    }

    public function getColorEficienciaAttribute()
    {
        $eficiencia = $this->getEficienciaAttribute();
        
        if ($eficiencia >= 90) return 'success';
        if ($eficiencia >= 80) return 'info';
        if ($eficiencia >= 70) return 'warning';
        return 'danger';
    }

    // Calcular conversión alimenticia desde consumos y producción
    public static function calcularDesdeConsumos($galponId, $fechaInicio, $fechaFin, $tipoProduccion)
    {
        // Obtener total de alimento consumido
        $totalAlimento = FoodConsumption::where('galpon_id', $galponId)
            ->whereBetween('fecha_registro', [$fechaInicio, $fechaFin])
            ->where('estado', FoodConsumption::STATUS_ACTIVE)
            ->sum('cantidad_kg');

        // Obtener total de producto obtenido (esto dependería de la integración con producción)
        $totalProducto = 0; // Por ahora 0, se integraría con el módulo de producción

        if ($totalProducto > 0 && $totalAlimento > 0) {
            $conversion = $totalAlimento / $totalProducto;
            
            return self::create([
                'galpon_id' => $galponId,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFin,
                'periodo_tipo' => self::PERIODO_ACUMULADO,
                'total_alimento_consumido' => $totalAlimento,
                'total_producto_obtenido' => $totalProducto,
                'conversion_alimenticia' => $conversion,
                'tipo_produccion' => $tipoProduccion,
                'estado' => self::STATUS_ACTIVE
            ]);
        }

        return null;
    }

    // Generar reporte de conversión alimenticia
    public static function generarReporte($galponId = null, $fechaInicio = null, $fechaFin = null, $periodo = self::PERIODO_ACUMULADO)
    {
        $query = self::active();

        if ($galponId) {
            $query->byGalpon($galponId);
        }

        if ($fechaInicio && $fechaFin) {
            $query->byDateRange($fechaInicio, $fechaFin);
        }

        if ($periodo) {
            $query->byPeriodo($periodo);
        }

        return $query->orderBy('fecha_inicio', 'desc')->get();
    }
}
