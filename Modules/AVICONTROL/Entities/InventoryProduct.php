<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo InventoryProduct - Gestiona el inventario de productos avícolas
 * 
 * Este modelo maneja:
 * - Catálogo de productos (alimentos, medicamentos, biológicos)
 * - Control de stock y precios unitarios
 * - Seguimiento de fechas de vencimiento
 * - Alertas de stock bajo y productos por vencer
 * - Códigos únicos para identificación de productos
 */
class InventoryProduct extends Model
{
    // TRAITS: Funcionalidades adicionales del modelo
    use HasFactory, SoftDeletes; // HasFactory para testing, SoftDeletes para borrado lógico

    /**
     * TABLA: Nombre de la tabla en la base de datos
     * Se usa el prefijo 'avicontrol_' para evitar conflictos con otras tablas
     */
    protected $table = 'avicontrol_inventory_products';

    /**
     * FILLABLE: Campos que se pueden llenar masivamente (mass assignment)
     * Solo estos campos se pueden asignar directamente desde formularios
     */
    protected $fillable = [
        'name',                     // Nombre del producto
        'category',                 // Categoría del producto (alimentos, medicamentos, etc.)
        'description',              // Descripción detallada del producto
        'unit_measure',             // Unidad de medida (kg, litros, unidades, etc.)
        'unit_price',               // Precio por unidad
        'supplier',                 // Proveedor del producto
        'expiration_date',          // Fecha de vencimiento del producto
        'minimum_stock',            // Stock mínimo para alertas
        'current_stock',            // Stock actual disponible
        'status',                   // Estado del producto (activo, inactivo, vencido)
        'code'                      // Código único del producto
    ];

    /**
     * CASTS: Conversión automática de tipos de datos
     * Convierte automáticamente los campos de la BD al tipo especificado
     */
    protected $casts = [
        'expiration_date' => 'date',    // Convierte a objeto Carbon (fecha)
        'unit_price' => 'decimal:2',    // Convierte a decimal con 2 decimales
        'minimum_stock' => 'integer',   // Convierte a número entero
        'current_stock' => 'integer',   // Convierte a número entero
    ];

    // ===== CONSTANTES: VALORES PREDEFINIDOS =====

    /**
     * CATEGORÍAS DISPONIBLES: Tipos de productos en el inventario
     * Se usan para clasificar y filtrar productos
     */
    const CATEGORIES = [
        'alimentos' => 'Alimentos (Concentrados)',    // Concentrados y alimentos para aves
        'medicamentos' => 'Medicamentos',              // Medicamentos veterinarios
        'biologicos' => 'Biológicos (Vacunas)',       // Vacunas y productos biológicos
        'desinfectantes' => 'Desinfectantes',         // Productos de limpieza y desinfección
        'embalajes' => 'Embalajes (Cubetas, Cajas)',  // Materiales de empaque
        'equipos' => 'Equipos Menores',                // Herramientas y equipos pequeños
        'otros' => 'Otros Insumos'                     // Otros productos no categorizados
    ];

    /**
     * ESTADOS DISPONIBLES: Estados posibles para un producto
     * Se usan para controlar la disponibilidad y validez
     */
    const STATUS_ACTIVE = 'active';      // Producto activo y disponible
    const STATUS_INACTIVE = 'inactive';  // Producto inactivo (no disponible)
    const STATUS_EXPIRED = 'expired';    // Producto vencido

    // ===== RELACIONES CON OTROS MODELOS =====

    /**
     * RELACIÓN: Un producto puede tener muchos movimientos de inventario
     * Retorna todos los movimientos (entradas/salidas) del producto
     */
    public function movements()
    {
        return $this->hasMany(InventoryMovement::class, 'product_id');
    }

    // ===== SCOPES: CONSULTAS REUTILIZABLES =====

    /**
     * SCOPE: Filtra solo productos activos
     * Uso: InventoryProduct::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * SCOPE: Filtra productos con stock bajo
     * Uso: InventoryProduct::lowStock()->get()
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= minimum_stock');
    }

    /**
     * SCOPE: Filtra productos que vencen pronto
     * Uso: InventoryProduct::expiringSoon(15)->get() (15 días)
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $days Días antes del vencimiento para considerar "pronto"
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiration_date', '<=', now()->addDays($days))
                    ->where('expiration_date', '>', now());
    }

    // ===== MÉTODOS: FUNCIONALIDADES ESPECÍFICAS =====

    /**
     * MÉTODO: Verifica si el producto tiene stock bajo
     * Uso: $product->isLowStock()
     * 
     * @return bool True si el stock actual es menor o igual al mínimo
     */
    public function isLowStock()
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    /**
     * MÉTODO: Verifica si el producto vence pronto
     * Uso: $product->isExpiringSoon(15)
     * 
     * @param int $days Días antes del vencimiento
     * @return bool True si vence en los próximos días especificados
     */
    public function isExpiringSoon($days = 30)
    {
        // Verifica que haya fecha de vencimiento y que esté en el futuro
        return $this->expiration_date && 
               $this->expiration_date->diffInDays(now(), false) <= $days &&
               $this->expiration_date->isFuture();
    }

    /**
     * MÉTODO: Verifica si el producto está vencido
     * Uso: $product->isExpired()
     * 
     * @return bool True si la fecha de vencimiento ya pasó
     */
    public function isExpired()
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    /**
     * ACCESSOR: Retorna el nombre legible de la categoría
     * Uso: $product->category_name
     * 
     * @return string Nombre legible de la categoría
     */
    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * ACCESSOR: Calcula el valor total del stock actual
     * Uso: $product->total_value
     * 
     * @return float Valor total del stock (stock × precio unitario)
     */
    public function getTotalValueAttribute()
    {
        return $this->current_stock * $this->unit_price;
    }

    // ===== MÉTODOS ESTÁTICOS: FUNCIONES QUE NO REQUIEREN INSTANCIA =====

    /**
     * MÉTODO ESTÁTICO: Genera un código único para nuevos productos
     * Formato: INV + 6 dígitos secuenciales (ej: INV000001)
     * Uso: InventoryProduct::generateCode()
     * 
     * @return string Código único generado
     */
    public static function generateCode()
    {
        $prefix = 'INV'; // Prefijo para productos de inventario
        
        // Obtiene el último producto para determinar el siguiente número
        $lastProduct = self::orderBy('id', 'desc')->first();
        
        // Extrae el número del código del último producto y le suma 1
        $nextNumber = $lastProduct ? intval(substr($lastProduct->code, 3)) + 1 : 1;
        
        // Formatea el número con ceros a la izquierda para mantener 6 dígitos
        return $prefix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }
} 