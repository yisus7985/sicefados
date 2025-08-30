<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo FoodConsumption - Gestiona el consumo de alimentos en galpones
 * 
 * Este modelo maneja:
 * - Registro de consumo diario de alimentos por galpón
 * - Control de inventario de productos alimenticios
 * - Cálculo de consumo promedio por ave
 * - Seguimiento de responsabilidades en el consumo
 * - Estados de los registros de consumo
 */
class FoodConsumption extends Model
{
    // TRAITS: Funcionalidades adicionales del modelo
    use HasFactory, SoftDeletes; // HasFactory para testing, SoftDeletes para borrado lógico

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_food_consumption';

    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'fecha_registro',           // Fecha en que se registró el consumo
        'galpon_id',                // ID del galpón donde se consumió el alimento
        'producto_id',              // ID del producto alimenticio consumido
        'cantidad_kg',              // Cantidad consumida en kilogramos
        'cantidad_bultos',          // Cantidad consumida en bultos/sacos
        'peso_por_bulto',           // Peso de cada bulto en kilogramos
        'consumo_promedio_por_ave', // Consumo promedio por ave en gramos
        'numero_aves',              // Número total de aves en el galpón
        'observaciones',            // Observaciones adicionales del consumo
        'responsable',              // Persona responsable del registro
        'estado'                    // Estado del registro: 'active', 'cancelled', 'pending'
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'fecha_registro' => 'date',             // Convierte a objeto Carbon (fecha)
        'cantidad_kg' => 'decimal:2',          // Convierte a decimal con 2 decimales
        'cantidad_bultos' => 'integer',        // Convierte a número entero
        'peso_por_bulto' => 'decimal:2',       // Convierte a decimal con 2 decimales
        'consumo_promedio_por_ave' => 'decimal:4', // Convierte a decimal con 4 decimales
        'numero_aves' => 'integer',            // Convierte a número entero
        'created_at' => 'datetime',            // Convierte a objeto Carbon (fecha y hora)
        'updated_at' => 'datetime',            // Convierte a objeto Carbon (fecha y hora)
        'deleted_at' => 'datetime'             // Convierte a objeto Carbon (fecha y hora)
    ];

    // ===== CONSTANTES: VALORES PREDEFINIDOS =====

    /**
     * ESTADOS DISPONIBLES: Estados posibles para un registro de consumo
     * Se usan para controlar el flujo de trabajo y validaciones
     */
    const STATUS_ACTIVE = 'active';        // Registro activo y válido
    const STATUS_CANCELLED = 'cancelled';  // Registro cancelado
    const STATUS_PENDING = 'pending';      // Registro pendiente de confirmación

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un consumo pertenece a un galpón específico
     * Retorna el galpón donde se consumió el alimento
     */
    public function galpon()
    {
        return $this->belongsTo(Galpon::class, 'galpon_id');
    }

    /**
     * RELACIÓN: Un consumo está asociado a un producto específico
     * Retorna el producto alimenticio que se consumió
     */
    public function producto()
    {
        return $this->belongsTo(InventoryProduct::class, 'producto_id');
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra solo registros activos
     * Uso: FoodConsumption::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('estado', self::STATUS_ACTIVE);
    }

    /**
     * SCOPE: Filtra por rango de fechas
     * Uso: FoodConsumption::byDateRange('2024-01-01', '2024-01-31')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate Fecha de inicio (YYYY-MM-DD)
     * @param string $endDate Fecha de fin (YYYY-MM-DD)
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('fecha_registro', [$startDate, $endDate]);
    }

    /**
     * SCOPE: Filtra por galpón específico
     * Uso: FoodConsumption::byGalpon(5)->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $galponId ID del galpón
     */
    public function scopeByGalpon($query, $galponId)
    {
        return $query->where('galpon_id', $galponId);
    }

    /**
     * SCOPE: Filtra por producto específico
     * Uso: FoodConsumption::byProduct(10)->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $productId ID del producto
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('producto_id', $productId);
    }

    // ===== MÉTODOS: FUNCIONALIDADES ESPECÍFICAS =====

    /**
     * MÉTODO: Calcula el consumo total en kilogramos
     * Prioriza cantidad_kg si está disponible, sino calcula desde bultos
     * Uso: $consumo->total_consumo
     * 
     * @return float Consumo total en kilogramos
     */
    public function getTotalConsumoAttribute()
    {
        // Si hay cantidad en kg directamente, la retorna
        if ($this->cantidad_kg) {
            return $this->cantidad_kg;
        }
        
        // Si no hay kg pero hay bultos, calcula el total
        if ($this->cantidad_bultos && $this->peso_por_bulto) {
            return $this->cantidad_bultos * $this->peso_por_bulto;
        }
        
        // Si no hay datos suficientes, retorna 0
        return 0;
    }

    /**
     * MÉTODO: Calcula el consumo promedio por ave en gramos
     * Convierte el consumo total a gramos y divide por el número de aves
     * Uso: $consumo->consumo_por_ave
     * 
     * @return float Consumo promedio por ave en gramos
     */
    public function getConsumoPorAveAttribute()
    {
        // Verifica que haya aves y consumo para hacer el cálculo
        if ($this->numero_aves > 0 && $this->getTotalConsumoAttribute() > 0) {
            // Convierte kg a gramos (× 1000) y divide por número de aves
            return ($this->getTotalConsumoAttribute() * 1000) / $this->numero_aves;
        }
        return 0;
    }

    /**
     * MÉTODO: Calcula el costo total del consumo
     * Multiplica el consumo total por el precio unitario del producto
     * Uso: $consumo->costo_total
     * 
     * @return float Costo total del consumo
     */
    public function getCostoTotalAttribute()
    {
        // Verifica que haya un producto asociado y precio
        if ($this->producto && $this->producto->precio_unitario) {
            return $this->getTotalConsumoAttribute() * $this->producto->precio_unitario;
        }
        return 0;
    }

    /**
     * MÉTODO: Calcula el costo por ave
     * Divide el costo total entre el número de aves
     * Uso: $consumo->costo_por_ave
     * 
     * @return float Costo promedio por ave
     */
    public function getCostoPorAveAttribute()
    {
        // Verifica que haya aves para hacer el cálculo
        if ($this->numero_aves > 0) {
            return $this->getCostoTotalAttribute() / $this->numero_aves;
        }
        return 0;
    }

    /**
     * MÉTODO: Calcula y actualiza el consumo promedio por ave
     * Calcula el consumo promedio y lo guarda en el campo correspondiente
     * Uso: $consumo->calcularConsumoPromedio()
     * 
     * @return float Consumo promedio por ave calculado
     */
    public function calcularConsumoPromedio()
    {
        $consumoPromedio = $this->getConsumoPorAveAttribute();
        
        // Actualiza el campo en la base de datos
        $this->update([
            'consumo_promedio_por_ave' => $consumoPromedio
        ]);
        
        return $consumoPromedio;
    }

    /**
     * MÉTODO: Actualiza el inventario del producto
     * Reduce la cantidad disponible del producto en inventario
     * Uso: $consumo->actualizarInventario()
     * 
     * @return bool True si se actualizó correctamente
     */
    public function actualizarInventario()
    {
        try {
            if ($this->producto) {
                $cantidadConsumida = $this->getTotalConsumoAttribute();
                $this->producto->decrement('stock_available', $cantidadConsumida);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Error actualizando inventario: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * MÉTODO: Revierte el inventario del producto
     * Restaura la cantidad consumida al inventario
     * Uso: $consumo->revertirInventario()
     * 
     * @return bool True si se revirtió correctamente
     */
    public function revertirInventario()
    {
        try {
            if ($this->producto) {
                $cantidadConsumida = $this->getTotalConsumoAttribute();
                $this->producto->increment('stock_available', $cantidadConsumida);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            \Log::error('Error revirtiendo inventario: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * MÉTODO: Verifica si el consumo está en estado activo
     * Uso: $consumo->isActive()
     * 
     * @return bool True si el consumo está activo
     */
    public function isActive()
    {
        return $this->estado === self::STATUS_ACTIVE;
    }

    /**
     * MÉTODO: Verifica si el consumo está pendiente
     * Uso: $consumo->isPending()
     * 
     * @return bool True si el consumo está pendiente
     */
    public function isPending()
    {
        return $this->estado === self::STATUS_PENDING;
    }

    /**
     * MÉTODO: Verifica si el consumo está cancelado
     * Uso: $consumo->isCancelled()
     * 
     * @return bool True si el consumo está cancelado
     */
    public function isCancelled()
    {
        return $this->estado === self::STATUS_CANCELLED;
    }
}
