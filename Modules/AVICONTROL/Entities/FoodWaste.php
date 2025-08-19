<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodWaste extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'avicontrol_food_waste';

    protected $fillable = [
        'fecha_registro',
        'galpon_id',
        'producto_id',
        'cantidad_perdida',
        'causa_merma',
        'responsable',
        'observaciones',
        'costo_perdida',
        'estado'
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'cantidad_perdida' => 'decimal:2',
        'costo_perdida' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Causas de merma disponibles
    const CAUSAS_MERMA = [
        'derrame' => 'Derrame durante el transporte',
        'contaminacion' => 'Contaminación del alimento',
        'roedores' => 'Daño por roedores',
        'empaque_roto' => 'Empaque roto o dañado',
        'humedad' => 'Daño por humedad',
        'plagas' => 'Daño por plagas',
        'vencimiento' => 'Vencimiento del producto',
        'manejo_incorrecto' => 'Manejo incorrecto',
        'otros' => 'Otras causas'
    ];

    // Estados
    const STATUS_ACTIVE = 'active';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_PENDING = 'pending';

    // Relaciones
    public function galpon()
    {
        return $this->belongsTo(Galpon::class, 'galpon_id');
    }

    public function producto()
    {
        return $this->belongsTo(InventoryProduct::class, 'producto_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('estado', self::STATUS_ACTIVE);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('fecha_registro', [$startDate, $endDate]);
    }

    public function scopeByGalpon($query, $galponId)
    {
        return $query->where('galpon_id', $galponId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('producto_id', $productId);
    }

    public function scopeByCausa($query, $causa)
    {
        return $query->where('causa_merma', $causa);
    }

    // Métodos
    public function getCausaMermaNombreAttribute()
    {
        return self::CAUSAS_MERMA[$this->causa_merma] ?? $this->causa_merma;
    }

    public function getCostoPerdidaAttribute()
    {
        if ($this->producto && $this->cantidad_perdida > 0) {
            return $this->cantidad_perdida * $this->producto->unit_price;
        }
        return $this->costo_perdida ?? 0;
    }

    // Calcular porcentaje de mermas sobre el total suministrado
    public function calcularPorcentajeMerma($periodoInicio = null, $periodoFin = null)
    {
        $query = FoodConsumption::where('galpon_id', $this->galpon_id)
            ->where('producto_id', $this->producto_id)
            ->where('estado', FoodConsumption::STATUS_ACTIVE);

        if ($periodoInicio && $periodoFin) {
            $query->whereBetween('fecha_registro', [$periodoInicio, $periodoFin]);
        }

        $totalSuministrado = $query->sum('cantidad_kg');

        if ($totalSuministrado > 0) {
            return ($this->cantidad_perdida / $totalSuministrado) * 100;
        }

        return 0;
    }

    // Actualizar inventario automáticamente
    public function actualizarInventario()
    {
        if ($this->producto && $this->estado === self::STATUS_ACTIVE) {
            $this->producto->current_stock -= $this->cantidad_perdida;
            $this->producto->save();
        }
    }

    // Revertir actualización de inventario (para cancelaciones)
    public function revertirInventario()
    {
        if ($this->producto && $this->estado === self::STATUS_CANCELLED) {
            $this->producto->current_stock += $this->cantidad_perdida;
            $this->producto->save();
        }
    }

    // Generar reporte de mermas
    public static function generarReporteMermas($galponId = null, $fechaInicio = null, $fechaFin = null, $causa = null)
    {
        $query = self::active();

        if ($galponId) {
            $query->byGalpon($galponId);
        }

        if ($fechaInicio && $fechaFin) {
            $query->byDateRange($fechaInicio, $fechaFin);
        }

        if ($causa) {
            $query->byCausa($causa);
        }

        return $query->with(['galpon', 'producto'])
            ->orderBy('fecha_registro', 'desc')
            ->get();
    }

    // Calcular estadísticas de mermas
    public static function calcularEstadisticasMermas($galponId = null, $fechaInicio = null, $fechaFin = null)
    {
        $query = self::active();

        if ($galponId) {
            $query->byGalpon($galponId);
        }

        if ($fechaInicio && $fechaFin) {
            $query->byDateRange($fechaInicio, $fechaFin);
        }

        $totalMermas = $query->sum('cantidad_perdida');
        $totalCosto = $query->sum('costo_perdida');

        // Calcular mermas por causa
        $mermasPorCausa = $query->selectRaw('causa_merma, SUM(cantidad_perdida) as total_perdida, COUNT(*) as cantidad_registros')
            ->groupBy('causa_merma')
            ->get();

        return [
            'total_mermas' => $totalMermas,
            'total_costo' => $totalCosto,
            'mermas_por_causa' => $mermasPorCausa,
            'cantidad_registros' => $query->count()
        ];
    }
}
