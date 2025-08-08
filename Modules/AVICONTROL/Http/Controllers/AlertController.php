<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Modules\AVICONTROL\Entities\ProductionCost;
use Carbon\Carbon;

class AlertController extends Controller
{
    /**
     * Display a listing of alerts
     */
    public function index()
    {
        // Obtener alertas de producción
        $productionAlerts = $this->getProductionAlerts();
        
        // Obtener alertas de inventario
        $inventoryAlerts = $this->getInventoryAlerts();
        
        // Combinar todas las alertas
        $allAlerts = array_merge($productionAlerts, $inventoryAlerts);
        
        // Ordenar por fecha de creación (más recientes primero)
        usort($allAlerts, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });
        
        return view('avicontrol::admin.alerts.index', compact('allAlerts', 'productionAlerts', 'inventoryAlerts'));
    }

    /**
     * Obtener alertas de producción (RF-020)
     */
    private function getProductionAlerts()
    {
        $alerts = [];
        
        // Alertas por caída en porcentaje de postura
        $birds = Bird::where('bird_type', 'laying_hens')->get();
        foreach ($birds as $bird) {
            // Usar datos reales si están disponibles, sino simular
            $currentLayingRate = $bird->laying_rate ?? $this->getCurrentLayingRate($bird->id);
            $expectedLayingRate = $this->getExpectedLayingRate($bird->age_weeks);
            $batchName = $bird->batch_name ?? $bird->batch_code;
            
            if ($currentLayingRate && $currentLayingRate < ($expectedLayingRate * 0.8)) { // 20% menos del esperado
                $alerts[] = [
                    'type' => 'production',
                    'category' => 'laying_rate',
                    'severity' => 'high',
                    'title' => 'Caída en porcentaje de postura',
                    'message' => "El lote {$batchName} muestra una caída del " . 
                                round((($expectedLayingRate - $currentLayingRate) / $expectedLayingRate) * 100, 1) . 
                                "% en el porcentaje de postura esperado.",
                    'data' => [
                        'bird_id' => $bird->id,
                        'current_rate' => $currentLayingRate,
                        'expected_rate' => $expectedLayingRate,
                        'batch_name' => $batchName
                    ],
                    'created_at' => Carbon::now(),
                    'icon' => 'fas fa-egg',
                    'color' => 'warning'
                ];
            }
        }
        
        // Alertas por mortalidad alta
        $birdsWithHighMortality = Bird::where('mortality_rate', '>', 0.05)->get(); // Más del 5%
        foreach ($birdsWithHighMortality as $bird) {
            $batchName = $bird->batch_name ?? $bird->batch_code;
            $alerts[] = [
                'type' => 'production',
                'category' => 'mortality',
                'severity' => 'critical',
                'title' => 'Mortalidad alta detectada',
                'message' => "El lote {$batchName} presenta una mortalidad del " . 
                            round($bird->mortality_rate * 100, 1) . "%, superior al umbral establecido.",
                'data' => [
                    'bird_id' => $bird->id,
                    'mortality_rate' => $bird->mortality_rate,
                    'batch_name' => $batchName
                ],
                'created_at' => Carbon::now(),
                'icon' => 'fas fa-skull-crossbones',
                'color' => 'danger'
            ];
        }
        
        // Alertas por consumo de alimento anormal
        $birdsWithAbnormalFeed = Bird::where('feed_consumption', '>', 150)->orWhere('feed_consumption', '<', 80)->get();
        foreach ($birdsWithAbnormalFeed as $bird) {
            $batchName = $bird->batch_name ?? $bird->batch_code;
            $alerts[] = [
                'type' => 'production',
                'category' => 'feed_consumption',
                'severity' => 'medium',
                'title' => 'Consumo de alimento anormal',
                'message' => "El lote {$batchName} presenta un consumo de " . 
                            $bird->feed_consumption . "g/ave/día, fuera del rango normal (80-150g).",
                'data' => [
                    'bird_id' => $bird->id,
                    'feed_consumption' => $bird->feed_consumption,
                    'batch_name' => $batchName
                ],
                'created_at' => Carbon::now(),
                'icon' => 'fas fa-wheat-awn',
                'color' => 'warning'
            ];
        }
        
        return $alerts;
    }

    /**
     * Obtener alertas de inventario (RF-021)
     */
    private function getInventoryAlerts()
    {
        $alerts = [];
        
        // Alertas por stock bajo
        $lowStockProducts = InventoryProduct::where('current_stock', '<=', DB::raw('minimum_stock'))->get();
        foreach ($lowStockProducts as $product) {
            $alerts[] = [
                'type' => 'inventory',
                'category' => 'low_stock',
                'severity' => 'medium',
                'title' => 'Stock mínimo alcanzado',
                'message' => "El producto {$product->name} ha alcanzado su nivel mínimo de stock ({$product->minimum_stock} unidades). Stock actual: {$product->current_stock} unidades.",
                'data' => [
                    'product_id' => $product->id,
                    'current_quantity' => $product->current_stock,
                    'minimum_stock' => $product->minimum_stock,
                    'product_name' => $product->name
                ],
                'created_at' => Carbon::now(),
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'warning'
            ];
        }
        
        // Alertas por productos próximos a vencer
        $expiringProducts = InventoryProduct::where('expiration_date', '<=', Carbon::now()->addDays(30))
                                          ->where('expiration_date', '>', Carbon::now())
                                          ->get();
        foreach ($expiringProducts as $product) {
            $daysUntilExpiration = Carbon::now()->diffInDays($product->expiration_date);
            $alerts[] = [
                'type' => 'inventory',
                'category' => 'expiring_soon',
                'severity' => 'high',
                'title' => 'Producto próximo a vencer',
                'message' => "El producto {$product->name} vence en {$daysUntilExpiration} días ({$product->expiration_date->format('d/m/Y')}).",
                'data' => [
                    'product_id' => $product->id,
                    'expiration_date' => $product->expiration_date,
                    'days_until_expiration' => $daysUntilExpiration,
                    'product_name' => $product->name
                ],
                'created_at' => Carbon::now(),
                'icon' => 'fas fa-clock',
                'color' => 'danger'
            ];
        }
        
        // Alertas por productos vencidos
        $expiredProducts = InventoryProduct::where('expiration_date', '<', Carbon::now())->get();
        foreach ($expiredProducts as $product) {
            $alerts[] = [
                'type' => 'inventory',
                'category' => 'expired',
                'severity' => 'critical',
                'title' => 'Producto vencido',
                'message' => "El producto {$product->name} ha vencido el {$product->expiration_date->format('d/m/Y')}. Se recomienda su eliminación.",
                'data' => [
                    'product_id' => $product->id,
                    'expiration_date' => $product->expiration_date,
                    'product_name' => $product->name
                ],
                'created_at' => Carbon::now(),
                'icon' => 'fas fa-times-circle',
                'color' => 'danger'
            ];
        }
        
        return $alerts;
    }

    /**
     * Obtener tasa de postura actual (simulado)
     */
    private function getCurrentLayingRate($birdId)
    {
        // En un sistema real, esto vendría de una tabla de producción diaria
        return rand(60, 95); // Simulación: 60-95%
    }

    /**
     * Obtener tasa de postura esperada según la edad
     */
    private function getExpectedLayingRate($ageWeeks)
    {
        // Curva de postura típica según la edad
        if ($ageWeeks < 20) return 0;
        if ($ageWeeks >= 20 && $ageWeeks <= 25) return 50 + ($ageWeeks - 20) * 5;
        if ($ageWeeks > 25 && $ageWeeks <= 40) return 85;
        if ($ageWeeks > 40 && $ageWeeks <= 60) return 80;
        return 70; // Después de 60 semanas
    }

    /**
     * Marcar alerta como leída
     */
    public function markAsRead(Request $request)
    {
        try {
            $data = $request->json()->all();
            
            if (isset($data['mark_all']) && $data['mark_all']) {
                // Marcar todas las alertas como leídas
                // En un sistema real, esto actualizaría una tabla de alertas
                return response()->json([
                    'success' => true,
                    'message' => 'Todas las alertas marcadas como leídas'
                ]);
            } else {
                // Marcar una alerta específica como leída
                $alertIndex = $data['alert_index'] ?? null;
                $alertType = $data['alert_type'] ?? null;
                $alertCategory = $data['alert_category'] ?? null;
                
                if ($alertIndex !== null) {
                    // En un sistema real, esto actualizaría una tabla de alertas
                    // Por ahora, solo simulamos el éxito
                    return response()->json([
                        'success' => true,
                        'message' => 'Alerta marcada como leída',
                        'alert_index' => $alertIndex
                    ]);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Datos insuficientes'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas de alertas
     */
    public function getStats()
    {
        $productionAlerts = $this->getProductionAlerts();
        $inventoryAlerts = $this->getInventoryAlerts();
        
        $stats = [
            'total_alerts' => count($productionAlerts) + count($inventoryAlerts),
            'production_alerts' => count($productionAlerts),
            'inventory_alerts' => count($inventoryAlerts),
            'critical_alerts' => count(array_filter(array_merge($productionAlerts, $inventoryAlerts), function($alert) {
                return $alert['severity'] === 'critical';
            })),
            'high_alerts' => count(array_filter(array_merge($productionAlerts, $inventoryAlerts), function($alert) {
                return $alert['severity'] === 'high';
            })),
            'medium_alerts' => count(array_filter(array_merge($productionAlerts, $inventoryAlerts), function($alert) {
                return $alert['severity'] === 'medium';
            }))
        ];
        
        return response()->json($stats);
    }

    /**
     * Obtener alertas para el dashboard (welcome)
     */
    public function getDashboardAlerts()
    {
        $productionAlerts = $this->getProductionAlerts();
        $inventoryAlerts = $this->getInventoryAlerts();
        $allAlerts = array_merge($productionAlerts, $inventoryAlerts);
        
        // Ordenar por severidad y fecha, tomar solo las primeras 3
        usort($allAlerts, function($a, $b) {
            $severityOrder = ['critical' => 3, 'high' => 2, 'medium' => 1, 'low' => 0];
            $aSeverity = $severityOrder[$a['severity']] ?? 0;
            $bSeverity = $severityOrder[$b['severity']] ?? 0;
            
            if ($aSeverity !== $bSeverity) {
                return $bSeverity - $aSeverity;
            }
            
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });
        
        return array_slice($allAlerts, 0, 3);
    }
} 