<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo InventoryMovement - Gestiona los movimientos de inventario
 * 
 * Este modelo maneja:
 * - Entradas y salidas de productos del inventario
 * - Ajustes de stock por inventarios físicos
 * - Seguimiento de precios y valores de movimientos
 * - Actualización automática del stock de productos
 * - Trazabilidad de todos los movimientos de inventario
 */
class InventoryMovement extends Model
{
    // TRAITS: Funcionalidades adicionales del modelo
    use HasFactory, SoftDeletes; // HasFactory para testing, SoftDeletes para borrado lógico

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_inventory_movements';

    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'product_id',                // ID del producto que se mueve
        'movement_type',             // Tipo de movimiento: 'entry', 'exit', 'adjustment'
        'quantity',                  // Cantidad movida (positiva para entradas, negativa para salidas)
        'unit_price',                // Precio unitario del producto en el momento del movimiento
        'total_value',               // Valor total del movimiento (cantidad × precio unitario)
        'reference',                 // Referencia del movimiento (compra, consumo, venta, etc.)
        'notes',                     // Notas y observaciones del movimiento
        'movement_date',             // Fecha y hora del movimiento
        'user_id'                    // ID del usuario que realizó el movimiento
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'movement_date' => 'datetime',    // Convierte a objeto Carbon (fecha y hora)
        'quantity' => 'integer',          // Convierte a número entero
        'unit_price' => 'decimal:2',      // Convierte a decimal con 2 decimales
        'total_value' => 'decimal:2',     // Convierte a decimal con 2 decimales
    ];

    // ===== CONSTANTES: VALORES PREDEFINIDOS =====

    /**
     * TIPOS DE MOVIMIENTO: Clasificación principal de los movimientos
     * Determina si el stock aumenta, disminuye o se ajusta
     */
    const TYPE_ENTRY = 'entry';           // Entrada: aumenta el stock (compra, producción)
    const TYPE_EXIT = 'exit';             // Salida: disminuye el stock (consumo, venta)
    const TYPE_ADJUSTMENT = 'adjustment'; // Ajuste: establece un stock específico (inventario físico)

    /**
     * REFERENCIAS DE MOVIMIENTO: Origen o destino específico del movimiento
     * Proporciona contexto adicional sobre por qué ocurrió el movimiento
     */
    const REFERENCE_PURCHASE = 'purchase';       // Compra de proveedores
    const REFERENCE_PRODUCTION = 'production';   // Producción interna
    const REFERENCE_CONSUMPTION = 'consumption'; // Consumo en galpones
    const REFERENCE_SALE = 'sale';               // Venta a clientes
    const REFERENCE_DISCARD = 'discard';         // Descarte por vencimiento o daño
    const REFERENCE_ADJUSTMENT = 'adjustment';   // Ajuste por inventario físico

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un movimiento pertenece a un producto específico
     * Retorna el producto que se movió en el inventario
     */
    public function product()
    {
        return $this->belongsTo(InventoryProduct::class, 'product_id');
    }

    /**
     * RELACIÓN: Un movimiento fue realizado por un usuario específico
     * Retorna el usuario que registró el movimiento
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra solo movimientos de entrada
     * Uso: InventoryMovement::entries()->get()
     */
    public function scopeEntries($query)
    {
        return $query->where('movement_type', self::TYPE_ENTRY);
    }

    /**
     * SCOPE: Filtra solo movimientos de salida
     * Uso: InventoryMovement::exits()->get()
     */
    public function scopeExits($query)
    {
        return $query->where('movement_type', self::TYPE_EXIT);
    }

    /**
     * SCOPE: Filtra por rango de fechas
     * Uso: InventoryMovement::byDateRange('2024-01-01', '2024-01-31')->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate Fecha de inicio (YYYY-MM-DD)
     * @param string $endDate Fecha de fin (YYYY-MM-DD)
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }

    /**
     * SCOPE: Filtra por producto específico
     * Uso: InventoryMovement::byProduct(10)->get()
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $productId ID del producto
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // ===== ACCESSORS: MÉTODOS QUE CALCULAN VALORES DERIVADOS =====

    /**
     * ACCESSOR: Retorna el nombre legible del tipo de movimiento
     * Uso: $movement->movement_type_name
     * 
     * @return string Nombre legible del tipo de movimiento
     */
    public function getMovementTypeNameAttribute()
    {
        $types = [
            self::TYPE_ENTRY => 'Entrada',        // Aumenta el stock
            self::TYPE_EXIT => 'Salida',          // Disminuye el stock
            self::TYPE_ADJUSTMENT => 'Ajuste'     // Establece stock específico
        ];
        return $types[$this->movement_type] ?? $this->movement_type;
    }

    /**
     * ACCESSOR: Retorna el nombre legible de la referencia del movimiento
     * Uso: $movement->reference_name
     * 
     * @return string Nombre legible de la referencia
     */
    public function getReferenceNameAttribute()
    {
        $references = [
            self::REFERENCE_PURCHASE => 'Compra',       // Adquisición de proveedores
            self::REFERENCE_PRODUCTION => 'Producción', // Producción interna
            self::REFERENCE_CONSUMPTION => 'Consumo',   // Uso en galpones
            self::REFERENCE_SALE => 'Venta',            // Comercialización
            self::REFERENCE_DISCARD => 'Descarte',      // Eliminación por vencimiento/daño
            self::REFERENCE_ADJUSTMENT => 'Ajuste'      // Corrección por inventario físico
        ];
        return $references[$this->reference] ?? $this->reference;
    }

    // ===== MÉTODOS: FUNCIONALIDADES ESPECÍFICAS =====

    /**
     * MÉTODO: Verifica si el movimiento es una entrada
     * Uso: $movement->isEntry()
     * 
     * @return bool True si es un movimiento de entrada
     */
    public function isEntry()
    {
        return $this->movement_type === self::TYPE_ENTRY;
    }

    /**
     * MÉTODO: Verifica si el movimiento es una salida
     * Uso: $movement->isExit()
     * 
     * @return bool True si es un movimiento de salida
     */
    public function isExit()
    {
        return $this->movement_type === self::TYPE_EXIT;
    }

    /**
     * MÉTODO: Verifica si el movimiento es un ajuste
     * Uso: $movement->isAdjustment()
     * 
     * @return bool True si es un movimiento de ajuste
     */
    public function isAdjustment()
    {
        return $this->movement_type === self::TYPE_ADJUSTMENT;
    }

    // ===== MÉTODOS DE BOOT: FUNCIONALIDADES AUTOMÁTICAS =====

    /**
     * MÉTODO BOOT: Se ejecuta automáticamente al crear/actualizar movimientos
     * Maneja la lógica automática de cálculos y actualizaciones de stock
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * EVENTO: Antes de crear un movimiento
         * Calcula automáticamente el valor total si no se proporciona
         */
        static::creating(function ($movement) {
            if (!$movement->total_value) {
                $movement->total_value = $movement->quantity * $movement->unit_price;
            }
        });

        /**
         * EVENTO: Después de crear un movimiento
         * Actualiza automáticamente el stock del producto asociado
         */
        static::created(function ($movement) {
            // Obtiene el producto asociado al movimiento
            $product = $movement->product;
            
            if ($product) {
                // Actualiza el stock según el tipo de movimiento
                if ($movement->isEntry()) {
                    // ENTRADA: Aumenta el stock
                    $product->current_stock += $movement->quantity;
                } elseif ($movement->isExit()) {
                    // SALIDA: Disminuye el stock
                    $product->current_stock -= $movement->quantity;
                } elseif ($movement->isAdjustment()) {
                    // AJUSTE: Establece el stock a un valor específico
                    $product->current_stock = $movement->quantity;
                }
                
                // Guarda los cambios en el producto
                $product->save();
            }
        });
    }
} 