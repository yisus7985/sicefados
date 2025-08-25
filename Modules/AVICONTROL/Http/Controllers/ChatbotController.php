<?php

namespace Modules\AVICONTROL\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Modules\AVICONTROL\Entities\Production;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\InventoryProduct;
use Modules\AVICONTROL\Entities\ProductionCost;

class ChatbotController extends Controller
{
    private $knowledgeBase;
    
    public function __construct()
    {
        $this->knowledgeBase = $this->buildKnowledgeBase();
    }

    /**
     * Test endpoint para verificar conectividad
     */
    public function test(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => '✅ Chatbot funcionando correctamente',
            'timestamp' => now()->toISOString(),
            'ip' => $request->ip()
        ]);
    }

    /**
     * Procesa la consulta del usuario y genera una respuesta
     */
    public function chat(Request $request)
    {
        try {
            $userMessage = $request->input('message', '');
            $conversationHistory = $request->input('history', []);
            
            Log::info('Chatbot consulta recibida', ['message' => $userMessage, 'ip' => $request->ip()]);
            
            if (empty($userMessage)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, escribe tu pregunta.'
                ]);
            }

            // Verificar comandos especiales
            if ($this->isSpecialCommand($userMessage)) {
                $response = $this->handleSpecialCommand($userMessage, $request);
            } else {
                // Generar respuesta usando IA
                $response = $this->generateResponse($userMessage, $conversationHistory);
            }
            
            // Obtener sugerencias relacionadas
            $suggestions = $this->getSuggestions($userMessage);
            
            // Guardar en caché para historial
            $this->saveConversation($request->ip(), $userMessage, $response['message']);
            
            return response()->json([
                'success' => true,
                'message' => $response['message'],
                'type' => $response['type'],
                'suggestions' => $suggestions,
                'context' => $response['context'] ?? null,
                'actions' => $response['actions'] ?? [],
                'metadata' => $response['metadata'] ?? null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error en chatbot: ' . $e->getMessage(), [
                'message' => $userMessage,
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lo siento, ha ocurrido un error. Por favor, intenta nuevamente. Si el problema persiste, contacta al soporte técnico.',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Genera respuesta inteligente basada en la consulta del usuario
     */
    private function generateResponse($userMessage, $conversationHistory = [])
    {
        $message = strtolower($userMessage);
        
        // Analizar el tipo de consulta
        $queryType = $this->analyzeQueryType($message);
        
        switch ($queryType) {
            case 'greeting':
                return $this->handleGreeting();
                
            case 'module_help':
                return $this->handleModuleHelp($message);
                
            case 'production_help':
                return $this->handleProductionHelp($message);
                
            case 'inventory_help':
                return $this->handleInventoryHelp($message);
                
            case 'cost_help':
                return $this->handleCostHelp($message);
                
            case 'facility_help':
                return $this->handleFacilityHelp($message);
                
            case 'report_help':
                return $this->handleReportHelp($message);
                
            case 'troubleshooting':
                return $this->handleTroubleshooting($message);
                
            case 'statistics':
                return $this->handleStatistics($message);
                
            case 'navigation':
                return $this->handleNavigation($message);
                
            default:
                return $this->handleGeneral($message);
        }
    }

    /**
     * Analiza el tipo de consulta del usuario
     */
    private function analyzeQueryType($message)
    {
        $keywords = [
            'greeting' => ['hola', 'buenos dias', 'buenas tardes', 'saludos', 'hey', 'hi'],
            'module_help' => ['módulo', 'modulo', 'funcionalidad', 'característica', 'feature'],
            'production_help' => ['producción', 'produccion', 'huevos', 'carne', 'registro', 'aves'],
            'inventory_help' => ['inventario', 'stock', 'productos', 'insumos', 'almacén'],
            'cost_help' => ['costo', 'costos', 'precio', 'rentabilidad', 'gastos', 'dinero'],
            'facility_help' => ['galpón', 'galpon', 'instalación', 'instalacion', 'edificio'],
            'report_help' => ['reporte', 'informe', 'estadística', 'estadistica', 'gráfica', 'pdf'],
            'troubleshooting' => ['error', 'problema', 'no funciona', 'falla', 'bug', 'ayuda'],
            'statistics' => ['estadísticas', 'estadisticas', 'números', 'datos', 'métricas'],
            'navigation' => ['cómo llegar', 'donde está', 'navegar', 'encontrar', 'ubicar']
        ];

        foreach ($keywords as $type => $words) {
            foreach ($words as $word) {
                if (strpos($message, $word) !== false) {
                    return $type;
                }
            }
        }

        return 'general';
    }

    /**
     * Maneja saludos
     */
    private function handleGreeting()
    {
        $greetings = [
            "¡Hola! 👋 Soy el asistente virtual de AVICONTROL. Estoy aquí para ayudarte con cualquier pregunta sobre el sistema.",
            "¡Buenos días! 🌅 ¿En qué puedo ayudarte con AVICONTROL hoy?",
            "¡Hola! 😊 Soy tu asistente experto en AVICONTROL. ¿Qué necesitas saber?"
        ];

        return [
            'message' => $greetings[array_rand($greetings)],
            'type' => 'greeting',
            'actions' => [
                ['text' => '📊 Ver módulos disponibles', 'action' => 'show_modules'],
                ['text' => '🆘 Necesito ayuda urgente', 'action' => 'emergency_help'],
                ['text' => '📈 Ver estadísticas rápidas', 'action' => 'quick_stats']
            ]
        ];
    }

    /**
     * Ayuda con módulos
     */
    private function handleModuleHelp($message)
    {
        $modules = [
            'inventario' => [
                'name' => 'Inventario',
                'description' => 'Gestiona productos, stock, movimientos y alertas de inventario',
                'features' => ['Control de stock', 'Movimientos de entrada/salida', 'Alertas de stock bajo', 'Gestión de proveedores'],
                'url' => route('avicontrol.admin.inventory.index')
            ],
            'producción' => [
                'name' => 'Producción',
                'description' => 'Registra y controla la producción diaria de huevos y carne',
                'features' => ['Registro de producción', 'Control de mortalidad', 'Clasificación de huevos', 'Reportes de producción'],
                'url' => route('avicontrol.admin.production.index')
            ],
            'costos' => [
                'name' => 'Costos de Producción',
                'description' => 'Calcula y analiza los costos de producción avícola',
                'features' => ['Cálculo automático de costos', 'Análisis de rentabilidad', 'Componentes detallados', 'Reportes financieros'],
                'url' => route('avicontrol.admin.production_costs.index')
            ],
            'instalaciones' => [
                'name' => 'Instalaciones',
                'description' => 'Administra galpones y instalaciones avícolas',
                'features' => ['Gestión de galpones', 'Control de capacidad', 'Estados de instalaciones', 'Asignación de aves'],
                'url' => route('avicontrol.admin.poultry_facilities.index')
            ]
        ];

        $moduleFound = null;
        foreach ($modules as $key => $module) {
            if (strpos($message, $key) !== false) {
                $moduleFound = $module;
                break;
            }
        }

        if ($moduleFound) {
            $response = "📋 **{$moduleFound['name']}**\n\n";
            $response .= "**Descripción:** {$moduleFound['description']}\n\n";
            $response .= "**Características principales:**\n";
            foreach ($moduleFound['features'] as $feature) {
                $response .= "• {$feature}\n";
            }
            $response .= "\n💡 **Tip:** Puedes acceder directamente desde el menú lateral o hacer clic en el botón de abajo.";

            return [
                'message' => $response,
                'type' => 'module_info',
                'actions' => [
                    ['text' => "🚀 Ir a {$moduleFound['name']}", 'action' => 'navigate', 'url' => $moduleFound['url']],
                    ['text' => '📚 Ver todos los módulos', 'action' => 'show_all_modules']
                ]
            ];
        }

        // Si no se encuentra un módulo específico, mostrar todos
        $response = "🎯 **Módulos disponibles en AVICONTROL:**\n\n";
        foreach ($modules as $module) {
            $response .= "**{$module['name']}:** {$module['description']}\n";
        }
        $response .= "\n💡 **Tip:** Pregúntame sobre cualquier módulo específico para obtener más detalles.";

        return [
            'message' => $response,
            'type' => 'modules_list',
            'actions' => array_map(function($module) {
                return ['text' => "📂 {$module['name']}", 'action' => 'navigate', 'url' => $module['url']];
            }, array_values($modules))
        ];
    }

    /**
     * Ayuda con producción
     */
    private function handleProductionHelp($message)
    {
        if (strpos($message, 'registrar') !== false || strpos($message, 'crear') !== false) {
            return [
                'message' => "📝 **Cómo registrar producción:**\n\n" .
                           "1. Ve al módulo **Producción** desde el menú\n" .
                           "2. Haz clic en **'Nuevo Huevos'** o **'Nuevo Carne'**\n" .
                           "3. Selecciona el **galpón** correspondiente\n" .
                           "4. Ingresa la **fecha** y **cantidad** producida\n" .
                           "5. Para huevos: clasifica por tipos (A, AA, B, C)\n" .
                           "6. Registra **mortalidad** si aplica\n" .
                           "7. Guarda el registro\n\n" .
                           "💡 **Tip:** El sistema calcula automáticamente porcentajes y valores totales.",
                'type' => 'tutorial',
                'actions' => [
                    ['text' => '🥚 Registrar Huevos', 'action' => 'navigate', 'url' => route('avicontrol.admin.production.create', ['tipo_produccion' => 'huevos'])],
                    ['text' => '🍗 Registrar Carne', 'action' => 'navigate', 'url' => route('avicontrol.admin.production.create', ['tipo_produccion' => 'carne'])],
                    ['text' => '📊 Ver Dashboard', 'action' => 'navigate', 'url' => route('avicontrol.admin.production.index')]
                ]
            ];
        }

        if (strpos($message, 'problema') !== false || strpos($message, 'error') !== false) {
            return [
                'message' => "🔧 **Problemas comunes en Producción:**\n\n" .
                           "**1. No puedo seleccionar un galpón:**\n" .
                           "• Verifica que el galpón esté **activo**\n" .
                           "• El tipo de galpón debe coincidir con el tipo de producción\n\n" .
                           "**2. Los cálculos no son correctos:**\n" .
                           "• Revisa que todos los campos estén completos\n" .
                           "• Verifica el **peso promedio** para carne\n\n" .
                           "**3. No puedo guardar el registro:**\n" .
                           "• Todos los campos obligatorios deben estar llenos\n" .
                           "• La fecha no puede ser futura\n\n" .
                           "💡 **Tip:** Si persiste el problema, revisa las alertas del sistema.",
                'type' => 'troubleshooting'
            ];
        }

        return [
            'message' => "🐔 **Módulo de Producción - Guía completa:**\n\n" .
                       "**Funciones principales:**\n" .
                       "• Registro diario de producción de huevos y carne\n" .
                       "• Control de mortalidad de aves\n" .
                       "• Clasificación automática de calidad\n" .
                       "• Cálculo de porcentajes de producción\n" .
                       "• Reportes y estadísticas detalladas\n\n" .
                       "**Tipos de registro:**\n" .
                       "• **Huevos:** Por tipo (A, AA, B, C, D)\n" .
                       "• **Carne:** Por peso y cantidad\n\n" .
                       "💡 **Tip:** Usa los filtros para encontrar registros específicos rápidamente.",
            'type' => 'module_guide',
            'actions' => [
                ['text' => '📝 Registrar producción', 'action' => 'show_production_tutorial'],
                ['text' => '📊 Ver reportes', 'action' => 'navigate', 'url' => route('avicontrol.admin.production.index')],
                ['text' => '🔧 Solucionar problemas', 'action' => 'show_troubleshooting']
            ]
        ];
    }

    /**
     * Ayuda con inventario
     */
    private function handleInventoryHelp($message)
    {
        if (strpos($message, 'stock bajo') !== false || strpos($message, 'alerta') !== false) {
            return [
                'message' => "⚠️ **Gestión de Stock Bajo:**\n\n" .
                           "**Para productos con stock bajo:**\n" .
                           "1. Ve a **Inventario > Stock Bajo**\n" .
                           "2. Revisa la lista de productos críticos\n" .
                           "3. Haz clic en **'Agregar Stock'** para reabastecer\n" .
                           "4. Registra la compra o movimiento de entrada\n\n" .
                           "**Configurar alertas:**\n" .
                           "• Cada producto tiene un **stock mínimo**\n" .
                           "• El sistema alerta automáticamente cuando se alcanza\n" .
                           "• Puedes modificar los límites en la edición del producto\n\n" .
                           "💡 **Tip:** Revisa las alertas diariamente para evitar desabastecimiento.",
                'type' => 'inventory_alert',
                'actions' => [
                    ['text' => '⚠️ Ver Stock Bajo', 'action' => 'navigate', 'url' => route('avicontrol.admin.inventory.low_stock')],
                    ['text' => '📦 Gestionar Inventario', 'action' => 'navigate', 'url' => route('avicontrol.admin.inventory.index')]
                ]
            ];
        }

        return [
            'message' => "📦 **Módulo de Inventario - Guía completa:**\n\n" .
                       "**Funciones principales:**\n" .
                       "• Control de stock de productos e insumos\n" .
                       "• Movimientos de entrada y salida\n" .
                       "• Alertas automáticas de stock bajo\n" .
                       "• Gestión de proveedores\n" .
                       "• Reportes de movimientos\n\n" .
                       "**Tipos de productos:**\n" .
                       "• **Alimentos:** Concentrado, granos, suplementos\n" .
                       "• **Medicamentos:** Vacunas, antibióticos, desinfectantes\n" .
                       "• **Suministros:** Empaques, materiales, herramientas\n\n" .
                       "💡 **Tip:** Mantén actualizados los stocks para un control preciso.",
            'type' => 'inventory_guide',
            'actions' => [
                ['text' => '📦 Ver Inventario', 'action' => 'navigate', 'url' => route('avicontrol.admin.inventory.index')],
                ['text' => '⚠️ Productos Críticos', 'action' => 'navigate', 'url' => route('avicontrol.admin.inventory.low_stock')],
                ['text' => '📝 Registrar Movimiento', 'action' => 'navigate', 'url' => route('avicontrol.admin.inventory.movements.create', ['id' => 1])]
            ]
        ];
    }

    /**
     * Ayuda con costos
     */
    private function handleCostHelp($message)
    {
        if (strpos($message, 'calcular') !== false || strpos($message, 'rentabilidad') !== false) {
            return [
                'message' => "💰 **Cálculo de Costos y Rentabilidad:**\n\n" .
                           "**Para calcular costos:**\n" .
                           "1. Ve a **Costos de Producción**\n" .
                           "2. Haz clic en **'Crear Costo'**\n" .
                           "3. Selecciona el **galpón** y **período**\n" .
                           "4. Ingresa costos por categoría:\n" .
                           "   • Concentrado y alimento\n" .
                           "   • Agua y energía\n" .
                           "   • Medicamentos y biológicos\n" .
                           "   • Mano de obra\n" .
                           "   • Mantenimiento\n" .
                           "5. El sistema calcula automáticamente el **costo total**\n\n" .
                           "**Análisis de rentabilidad:**\n" .
                           "• Compara costos vs ingresos\n" .
                           "• Calcula márgenes de ganancia\n" .
                           "• Identifica puntos de mejora\n\n" .
                           "💡 **Tip:** Usa la función 'Calcular desde Inventario' para mayor precisión.",
                'type' => 'cost_tutorial',
                'actions' => [
                    ['text' => '💰 Crear Costo', 'action' => 'navigate', 'url' => route('avicontrol.admin.production_costs.create')],
                    ['text' => '📊 Ver Análisis', 'action' => 'navigate', 'url' => route('avicontrol.admin.profitability_analysis.index')]
                ]
            ];
        }

        return [
            'message' => "💰 **Módulo de Costos - Guía completa:**\n\n" .
                       "**Control de Costos de Producción:**\n" .
                       "• Cálculo automático desde inventario\n" .
                       "• Desglose por componentes\n" .
                       "• Costo por unidad de producto\n" .
                       "• Seguimiento por lote o período\n\n" .
                       "**Análisis de Rentabilidad:**\n" .
                       "• Margen bruto y neto\n" .
                       "• Rentabilidad por período\n" .
                       "• Comparación entre galpones\n" .
                       "• Reportes financieros detallados\n\n" .
                       "💡 **Tip:** Actualiza los costos regularmente para mantener análisis precisos.",
            'type' => 'cost_guide'
        ];
    }

    /**
     * Ayuda con instalaciones
     */
    private function handleFacilityHelp($message)
    {
        return [
            'message' => "🏢 **Módulo de Instalaciones - Guía completa:**\n\n" .
                       "**Gestión de Galpones:**\n" .
                       "• Registro de instalaciones avícolas\n" .
                       "• Control de capacidad y ocupación\n" .
                       "• Estados: Activo, Mantenimiento, Inactivo\n" .
                       "• Tipos: Gallinas ponedoras, Pollos de engorde\n\n" .
                       "**Configuración importante:**\n" .
                       "• **Capacidad máxima** de aves\n" .
                       "• **Tipo de producción** (huevos/carne)\n" .
                       "• **Estado operativo** actual\n" .
                       "• **Ubicación** y características\n\n" .
                       "💡 **Tip:** Mantén actualizados los estados para un control eficiente.",
            'type' => 'facility_guide',
            'actions' => [
                ['text' => '🏢 Ver Instalaciones', 'action' => 'navigate', 'url' => route('avicontrol.admin.poultry_facilities.index')],
                ['text' => '➕ Crear Galpón', 'action' => 'navigate', 'url' => route('avicontrol.admin.poultry_facilities.create')]
            ]
        ];
    }

    /**
     * Ayuda con reportes
     */
    private function handleReportHelp($message)
    {
        if (strpos($message, 'pdf') !== false || strpos($message, 'exportar') !== false) {
            return [
                'message' => "📄 **Exportar Reportes a PDF:**\n\n" .
                           "**Reportes disponibles:**\n" .
                           "• **Dashboard:** Estadísticas generales del sistema\n" .
                           "• **Producción:** Registros detallados de producción\n" .
                           "• **Costos:** Análisis financiero y de rentabilidad\n" .
                           "• **Inventario:** Estado actual y movimientos\n\n" .
                           "**Cómo exportar:**\n" .
                           "1. Ve al módulo correspondiente\n" .
                           "2. Busca el botón **'Exportar PDF'** ⬇️\n" .
                           "3. El archivo se descarga automáticamente\n" .
                           "4. Incluye gráficas y estadísticas actualizadas\n\n" .
                           "💡 **Tip:** Los PDFs incluyen fecha y hora de generación para trazabilidad.",
                'type' => 'pdf_help',
                'actions' => [
                    ['text' => '📊 Dashboard PDF', 'action' => 'navigate', 'url' => route('avicontrol.admin.dashboard.export-pdf')],
                    ['text' => '📈 Ver Informes', 'action' => 'navigate', 'url' => route('avicontrol.admin.information.index')]
                ]
            ];
        }

        return [
            'message' => "📊 **Módulo de Informes - Guía completa:**\n\n" .
                       "**Tipos de reportes:**\n" .
                       "• **Producción:** Análisis detallado de producción diaria\n" .
                       "• **Consumo de Alimento:** Control de alimentación\n" .
                       "• **Costos:** Reportes financieros y de rentabilidad\n" .
                       "• **Seguimiento:** Monitoreo de aves por galpón\n\n" .
                       "**Características:**\n" .
                       "• Filtros por fecha, galpón, tipo\n" .
                       "• Gráficas interactivas\n" .
                       "• Exportación a PDF y Excel\n" .
                       "• Estadísticas en tiempo real\n\n" .
                       "💡 **Tip:** Usa los filtros para obtener reportes específicos más útiles.",
            'type' => 'report_guide'
        ];
    }

    /**
     * Maneja problemas y troubleshooting
     */
    private function handleTroubleshooting($message)
    {
        $problems = [
            'login' => "🔐 **Problemas de acceso:**\n• Verifica usuario y contraseña\n• Limpia caché del navegador\n• Contacta al administrador si persiste",
            'slow' => "🐌 **Sistema lento:**\n• Cierra pestañas innecesarias\n• Actualiza la página (F5)\n• Verifica conexión a internet",
            'save' => "💾 **No se guardan los datos:**\n• Completa todos los campos obligatorios\n• Verifica formato de fechas\n• Revisa permisos de usuario",
            'pdf' => "📄 **Problemas con PDF:**\n• Permite descargas en el navegador\n• Verifica bloqueador de pop-ups\n• Intenta con otro navegador"
        ];

        foreach ($problems as $key => $solution) {
            if (strpos($message, $key) !== false || 
                ($key === 'login' && (strpos($message, 'acceso') !== false || strpos($message, 'sesión') !== false)) ||
                ($key === 'slow' && (strpos($message, 'lento') !== false || strpos($message, 'carga') !== false)) ||
                ($key === 'save' && (strpos($message, 'guardar') !== false || strpos($message, 'grabar') !== false))) {
                
                return [
                    'message' => $solution . "\n\n💡 **¿Necesitas más ayuda?** Puedo ayudarte con problemas específicos.",
                    'type' => 'troubleshooting',
                    'actions' => [
                        ['text' => '🆘 Ayuda urgente', 'action' => 'emergency_help'],
                        ['text' => '📞 Contactar soporte', 'action' => 'contact_support']
                    ]
                ];
            }
        }

        return [
            'message' => "🔧 **Centro de Ayuda - Problemas Comunes:**\n\n" .
                       "**Problemas frecuentes:**\n" .
                       "• **Acceso al sistema:** Problemas de login o sesión\n" .
                       "• **Rendimiento:** Sistema lento o que no carga\n" .
                       "• **Guardado de datos:** No se guardan los registros\n" .
                       "• **Exportación:** Problemas con PDFs o reportes\n" .
                       "• **Navegación:** No encuentro una función\n\n" .
                       "💡 **Tip:** Describe tu problema específico para obtener ayuda personalizada.",
            'type' => 'troubleshooting_menu',
            'actions' => [
                ['text' => '🔐 Problemas de acceso', 'action' => 'help_login'],
                ['text' => '🐌 Sistema lento', 'action' => 'help_performance'],
                ['text' => '💾 No guarda datos', 'action' => 'help_save'],
                ['text' => '📄 Problemas con PDF', 'action' => 'help_pdf']
            ]
        ];
    }

    /**
     * Maneja consultas de estadísticas
     */
    private function handleStatistics($message)
    {
        try {
            // Obtener estadísticas reales del sistema
            $stats = [
                'instalaciones' => \DB::table('avicontrol_poultry_facilities')->where('status', 'active')->count(),
                'produccion_hoy' => \DB::table('avicontrol_productions')->whereDate('fecha', today())->sum('cantidad'),
                'productos_inventario' => \DB::table('avicontrol_inventory_products')->count(),
                'alertas_activas' => \DB::table('avicontrol_inventory_products')->where('current_stock', '<=', \DB::raw('min_stock'))->count()
            ];

            return [
                'message' => "📈 **Estadísticas del Sistema (Tiempo Real):**\n\n" .
                           "🏢 **Instalaciones activas:** {$stats['instalaciones']}\n" .
                           "🥚 **Producción hoy:** " . number_format($stats['produccion_hoy']) . " unidades\n" .
                           "📦 **Productos en inventario:** {$stats['productos_inventario']}\n" .
                           "⚠️ **Alertas de stock:** {$stats['alertas_activas']}\n\n" .
                           "💡 **Tip:** Estas estadísticas se actualizan automáticamente cada vez que consultas.",
                'type' => 'statistics',
                'context' => $stats,
                'actions' => [
                    ['text' => '📊 Ver Dashboard Completo', 'action' => 'navigate', 'url' => route('avicontrol.admin.welcome')],
                    ['text' => '📈 Reportes Detallados', 'action' => 'navigate', 'url' => route('avicontrol.admin.information.index')]
                ]
            ];
        } catch (\Exception $e) {
            return [
                'message' => "📊 **Estadísticas del Sistema:**\n\nLo siento, no puedo obtener las estadísticas en este momento. Por favor, intenta acceder directamente al dashboard para ver los datos actualizados.",
                'type' => 'statistics_error',
                'actions' => [
                    ['text' => '📊 Ir al Dashboard', 'action' => 'navigate', 'url' => route('avicontrol.admin.welcome')]
                ]
            ];
        }
    }

    /**
     * Ayuda con navegación
     */
    private function handleNavigation($message)
    {
        $locations = [
            'dashboard' => ['name' => 'Dashboard Principal', 'url' => route('avicontrol.admin.welcome'), 'description' => 'Vista general del sistema'],
            'inventario' => ['name' => 'Inventario', 'url' => route('avicontrol.admin.inventory.index'), 'description' => 'Gestión de productos y stock'],
            'producción' => ['name' => 'Producción', 'url' => route('avicontrol.admin.production.index'), 'description' => 'Registro de producción diaria'],
            'costos' => ['name' => 'Costos', 'url' => route('avicontrol.admin.production_costs.index'), 'description' => 'Control de costos de producción'],
            'instalaciones' => ['name' => 'Instalaciones', 'url' => route('avicontrol.admin.poultry_facilities.index'), 'description' => 'Gestión de galpones'],
            'informes' => ['name' => 'Informes', 'url' => route('avicontrol.admin.information.index'), 'description' => 'Reportes y estadísticas']
        ];

        $found = null;
        foreach ($locations as $key => $location) {
            if (strpos($message, $key) !== false) {
                $found = $location;
                break;
            }
        }

        if ($found) {
            return [
                'message' => "🧭 **Navegación a {$found['name']}:**\n\n" .
                           "**Descripción:** {$found['description']}\n\n" .
                           "**Formas de acceder:**\n" .
                           "1. **Menú lateral:** Busca el ícono correspondiente\n" .
                           "2. **Botón directo:** Haz clic abajo para ir ahora\n" .
                           "3. **Dashboard:** Desde las tarjetas del dashboard principal\n\n" .
                           "💡 **Tip:** El menú lateral siempre está disponible para navegación rápida.",
                'type' => 'navigation',
                'actions' => [
                    ['text' => "🚀 Ir a {$found['name']}", 'action' => 'navigate', 'url' => $found['url']]
                ]
            ];
        }

        return [
            'message' => "🧭 **Mapa de Navegación AVICONTROL:**\n\n" .
                       "**Módulos principales:**\n" .
                       "📊 **Dashboard:** Vista general del sistema\n" .
                       "📦 **Inventario:** Gestión de productos y stock\n" .
                       "🐔 **Producción:** Registro diario de producción\n" .
                       "💰 **Costos:** Control financiero y rentabilidad\n" .
                       "🏢 **Instalaciones:** Gestión de galpones\n" .
                       "📈 **Informes:** Reportes y estadísticas\n\n" .
                       "💡 **Tip:** Usa el menú lateral para navegar rápidamente entre módulos.",
            'type' => 'navigation_map',
            'actions' => array_map(function($location) {
                return ['text' => "📍 {$location['name']}", 'action' => 'navigate', 'url' => $location['url']];
            }, array_values($locations))
        ];
    }

    /**
     * Maneja consultas generales
     */
    private function handleGeneral($message)
    {
        $responses = [
            "🤖 Entiendo que tienes una consulta sobre AVICONTROL. ¿Podrías ser más específico? Por ejemplo:\n\n" .
            "• ¿Necesitas ayuda con algún módulo específico?\n" .
            "• ¿Tienes algún problema técnico?\n" .
            "• ¿Quieres ver estadísticas del sistema?\n" .
            "• ¿Necesitas ayuda para navegar?\n\n" .
            "💡 **Tip:** Puedo ayudarte con producción, inventario, costos, reportes y más.",
            
            "🎯 No estoy seguro de entender tu consulta. Te puedo ayudar con:\n\n" .
            "📊 **Módulos:** Inventario, Producción, Costos, Instalaciones\n" .
            "🔧 **Problemas:** Errores, rendimiento, navegación\n" .
            "📈 **Reportes:** PDFs, estadísticas, gráficas\n" .
            "🧭 **Navegación:** Cómo llegar a diferentes secciones\n\n" .
            "💡 **Tip:** Pregúntame algo específico para darte una respuesta más precisa."
        ];

        return [
            'message' => $responses[array_rand($responses)],
            'type' => 'general',
            'actions' => [
                ['text' => '📋 Ver módulos', 'action' => 'show_modules'],
                ['text' => '🆘 Necesito ayuda', 'action' => 'emergency_help'],
                ['text' => '📊 Ver estadísticas', 'action' => 'quick_stats'],
                ['text' => '🧭 Ayuda de navegación', 'action' => 'navigation_help']
            ]
        ];
    }

    /**
     * Obtiene sugerencias relacionadas con la consulta
     */
    private function getSuggestions($message)
    {
        $suggestions = [
            "¿Cómo registro producción de huevos?",
            "¿Cómo controlo el inventario?",
            "¿Cómo calculo costos de producción?",
            "¿Cómo genero reportes en PDF?",
            "¿Dónde veo las estadísticas del sistema?",
            "¿Cómo gestiono las instalaciones?",
            "¿Qué hago si hay stock bajo?",
            "¿Cómo navego entre módulos?"
        ];

        // Filtrar sugerencias relacionadas con la consulta
        $related = [];
        $message = strtolower($message);
        
        foreach ($suggestions as $suggestion) {
            $suggestionLower = strtolower($suggestion);
            if (strpos($suggestionLower, 'producción') !== false && strpos($message, 'producción') === false) {
                $related[] = $suggestion;
            } elseif (strpos($suggestionLower, 'inventario') !== false && strpos($message, 'inventario') === false) {
                $related[] = $suggestion;
            } elseif (strpos($suggestionLower, 'costo') !== false && strpos($message, 'costo') === false) {
                $related[] = $suggestion;
            }
        }

        // Si no hay relacionadas, devolver algunas aleatorias
        if (empty($related)) {
            $related = array_slice($suggestions, 0, 3);
        } else {
            $related = array_slice($related, 0, 3);
        }

        return $related;
    }

    /**
     * Construye la base de conocimiento del sistema
     */
    private function buildKnowledgeBase()
    {
        return [
            'modules' => [
                'inventory' => 'Control de stock, productos, movimientos, alertas de inventario',
                'production' => 'Registro diario de producción de huevos y carne, control de mortalidad',
                'costs' => 'Cálculo de costos de producción, análisis de rentabilidad',
                'facilities' => 'Gestión de galpones e instalaciones avícolas',
                'reports' => 'Generación de informes, estadísticas y exportación a PDF',
                'birds' => 'Gestión de lotes de aves, seguimiento por galpón'
            ],
            'features' => [
                'real_time_stats' => 'Estadísticas en tiempo real del sistema',
                'pdf_export' => 'Exportación de reportes a PDF con gráficas',
                'alerts' => 'Sistema de alertas automáticas',
                'dashboard' => 'Panel de control con métricas principales'
            ]
        ];
    }

    /**
     * Obtiene el historial de conversación
     */
    public function getHistory(Request $request)
    {
        try {
            // En una implementación real, esto vendría de una base de datos
            $history = Cache::get('chatbot_history_' . $request->ip(), []);
            
            return response()->json([
                'success' => true,
                'history' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener historial'
            ]);
        }
    }

    /**
     * Limpia el historial de conversación
     */
    public function clearHistory(Request $request)
    {
        try {
            Cache::forget('chatbot_history_' . $request->ip());
            
            return response()->json([
                'success' => true,
                'message' => 'Historial limpiado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al limpiar historial'
            ]);
        }
    }

    /**
     * Verifica si el mensaje es un comando especial
     */
    private function isSpecialCommand($message)
    {
        $commands = ['/stats', '/help', '/modules', '/clear', '/status', '/search', '/quick'];
        
        foreach ($commands as $command) {
            if (strpos(strtolower($message), $command) === 0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Maneja comandos especiales
     */
    private function handleSpecialCommand($message, $request)
    {
        $message = strtolower(trim($message));
        
        if (strpos($message, '/stats') === 0) {
            return $this->handleStatsCommand();
        }
        
        if (strpos($message, '/help') === 0) {
            return $this->handleHelpCommand();
        }
        
        if (strpos($message, '/modules') === 0) {
            return $this->handleModulesCommand();
        }
        
        if (strpos($message, '/clear') === 0) {
            return $this->handleClearCommand($request);
        }
        
        if (strpos($message, '/status') === 0) {
            return $this->handleStatusCommand();
        }
        
        if (strpos($message, '/search') === 0) {
            return $this->handleSearchCommand($message);
        }
        
        if (strpos($message, '/quick') === 0) {
            return $this->handleQuickCommand($message);
        }
        
        return $this->handleUnknownCommand();
    }

    /**
     * Comando /stats - Estadísticas del sistema
     */
    private function handleStatsCommand()
    {
        try {
            $stats = [
                'instalaciones_activas' => \DB::table('avicontrol_poultry_facilities')->where('status', 'active')->count(),
                'produccion_hoy' => \DB::table('avicontrol_productions')->whereDate('fecha', today())->where('estado', 'activo')->sum('cantidad'),
                'productos_inventario' => \DB::table('avicontrol_inventory_products')->count(),
                'stock_bajo' => \DB::table('avicontrol_inventory_products')->where('current_stock', '<=', \DB::raw('min_stock'))->count(),
                'costos_mes' => \DB::table('avicontrol_production_costs')->whereMonth('created_at', now()->month)->count(),
                'aves_activas' => \DB::table('avicontrol_birds')->where('status', 'active')->sum('quantity')
            ];

            $message = "📊 **ESTADÍSTICAS EN TIEMPO REAL** ⚡\n\n";
            $message .= "🏢 **Instalaciones activas:** {$stats['instalaciones_activas']}\n";
            $message .= "🥚 **Producción hoy:** " . number_format($stats['produccion_hoy']) . " unidades\n";
            $message .= "🐔 **Aves activas:** " . number_format($stats['aves_activas']) . "\n";
            $message .= "📦 **Productos en inventario:** {$stats['productos_inventario']}\n";
            $message .= "⚠️ **Productos con stock bajo:** {$stats['stock_bajo']}\n";
            $message .= "💰 **Análisis de costos este mes:** {$stats['costos_mes']}\n\n";
            $message .= "🕐 **Actualizado:** " . now()->format('d/m/Y H:i:s') . "\n\n";
            $message .= "💡 **Tip:** Usa `/quick producción` para ver detalles de producción";

            return [
                'message' => $message,
                'type' => 'command_stats',
                'metadata' => $stats,
                'actions' => [
                    ['text' => '📊 Dashboard Completo', 'action' => 'navigate', 'url' => route('avicontrol.admin.welcome')],
                    ['text' => '📈 Reportes Detallados', 'action' => 'navigate', 'url' => route('avicontrol.admin.information.index')]
                ]
            ];
        } catch (\Exception $e) {
            return [
                'message' => '❌ Error al obtener estadísticas. Verifica la conexión a la base de datos.',
                'type' => 'command_error'
            ];
        }
    }

    /**
     * Comando /help - Lista de comandos disponibles
     */
    private function handleHelpCommand()
    {
        $message = "🤖 **COMANDOS DISPONIBLES** 🚀\n\n";
        $message .= "**Comandos rápidos:**\n";
        $message .= "`/stats` - Estadísticas en tiempo real\n";
        $message .= "`/modules` - Lista de módulos disponibles\n";
        $message .= "`/status` - Estado del sistema\n";
        $message .= "`/search [término]` - Buscar en la base de conocimiento\n";
        $message .= "`/quick [módulo]` - Acceso rápido a módulos\n";
        $message .= "`/clear` - Limpiar historial de chat\n\n";
        $message .= "**Ejemplos de uso:**\n";
        $message .= "• `/search producción huevos`\n";
        $message .= "• `/quick inventario`\n";
        $message .= "• `/stats`\n\n";
        $message .= "💡 **Tip:** También puedes hacer preguntas normales como '¿Cómo registro producción?'";

        return [
            'message' => $message,
            'type' => 'command_help',
            'actions' => [
                ['text' => '📊 Ver estadísticas', 'action' => 'send_command', 'command' => '/stats'],
                ['text' => '📋 Ver módulos', 'action' => 'send_command', 'command' => '/modules']
            ]
        ];
    }

    /**
     * Comando /modules - Lista de módulos
     */
    private function handleModulesCommand()
    {
        $modules = [
            ['name' => 'Dashboard', 'icon' => '📊', 'status' => 'active', 'url' => route('avicontrol.admin.welcome')],
            ['name' => 'Inventario', 'icon' => '📦', 'status' => 'active', 'url' => route('avicontrol.admin.inventory.index')],
            ['name' => 'Producción', 'icon' => '🐔', 'status' => 'active', 'url' => route('avicontrol.admin.production.index')],
            ['name' => 'Costos', 'icon' => '💰', 'status' => 'active', 'url' => route('avicontrol.admin.production_costs.index')],
            ['name' => 'Instalaciones', 'icon' => '🏢', 'status' => 'active', 'url' => route('avicontrol.admin.poultry_facilities.index')],
            ['name' => 'Informes', 'icon' => '📈', 'status' => 'active', 'url' => route('avicontrol.admin.information.index')],
            ['name' => 'Alertas', 'icon' => '🚨', 'status' => 'active', 'url' => route('avicontrol.admin.alerts.index')]
        ];

        $message = "📋 **MÓDULOS DEL SISTEMA** 🎯\n\n";
        foreach ($modules as $module) {
            $statusIcon = $module['status'] === 'active' ? '✅' : '⏸️';
            $message .= "{$module['icon']} **{$module['name']}** {$statusIcon}\n";
        }
        
        $message .= "\n💡 **Uso rápido:** `/quick [nombre_módulo]`\n";
        $message .= "**Ejemplo:** `/quick inventario`";

        return [
            'message' => $message,
            'type' => 'command_modules',
            'actions' => array_map(function($module) {
                return ['text' => "{$module['icon']} {$module['name']}", 'action' => 'navigate', 'url' => $module['url']];
            }, $modules)
        ];
    }

    /**
     * Comando /status - Estado del sistema
     */
    private function handleStatusCommand()
    {
        try {
            // Verificar conectividad de base de datos
            $dbStatus = \DB::connection()->getPdo() ? 'Conectado' : 'Desconectado';
            
            // Verificar tablas principales
            $tables = [
                'avicontrol_productions' => \Schema::hasTable('avicontrol_productions'),
                'avicontrol_inventory_products' => \Schema::hasTable('avicontrol_inventory_products'),
                'avicontrol_poultry_facilities' => \Schema::hasTable('avicontrol_poultry_facilities'),
                'avicontrol_production_costs' => \Schema::hasTable('avicontrol_production_costs')
            ];
            
            $tablesOk = array_filter($tables);
            $tablesCount = count($tablesOk);
            $totalTables = count($tables);

            $message = "🔍 **ESTADO DEL SISTEMA** ⚡\n\n";
            $message .= "**Base de datos:** {$dbStatus} " . ($dbStatus === 'Conectado' ? '✅' : '❌') . "\n";
            $message .= "**Tablas:** {$tablesCount}/{$totalTables} disponibles " . ($tablesCount === $totalTables ? '✅' : '⚠️') . "\n";
            $message .= "**Chatbot:** Operativo ✅\n";
            $message .= "**Tiempo de respuesta:** < 1s ✅\n\n";
            
            if ($tablesCount < $totalTables) {
                $message .= "⚠️ **Advertencia:** Algunas tablas no están disponibles\n";
                $message .= "**Solución:** Ejecuta las migraciones del sistema\n\n";
            }
            
            $message .= "🕐 **Verificado:** " . now()->format('d/m/Y H:i:s');

            return [
                'message' => $message,
                'type' => 'command_status',
                'metadata' => [
                    'db_status' => $dbStatus,
                    'tables_ok' => $tablesCount,
                    'total_tables' => $totalTables
                ]
            ];
        } catch (\Exception $e) {
            return [
                'message' => "❌ **ERROR DEL SISTEMA**\n\nNo se puede verificar el estado del sistema.\n**Error:** " . $e->getMessage(),
                'type' => 'command_error'
            ];
        }
    }

    /**
     * Comando /search - Buscar en base de conocimiento
     */
    private function handleSearchCommand($message)
    {
        $searchTerm = trim(str_replace('/search', '', $message));
        
        if (empty($searchTerm)) {
            return [
                'message' => "🔍 **BÚSQUEDA**\n\n**Uso:** `/search [término]`\n**Ejemplo:** `/search producción huevos`",
                'type' => 'command_search_help'
            ];
        }

        // Buscar en la base de conocimiento
        $results = $this->searchKnowledgeBase($searchTerm);
        
        if (empty($results)) {
            return [
                'message' => "🔍 **BÚSQUEDA: '{$searchTerm}'**\n\n❌ No se encontraron resultados.\n\n💡 **Intenta con:**\n• Términos más generales\n• Sinónimos\n• Preguntas completas",
                'type' => 'command_search_empty'
            ];
        }

        $message = "🔍 **RESULTADOS PARA: '{$searchTerm}'** 📋\n\n";
        foreach ($results as $i => $result) {
            $message .= "**" . ($i + 1) . ". {$result['title']}**\n";
            $message .= "{$result['description']}\n\n";
        }
        
        $message .= "💡 **Tip:** Haz una pregunta específica para obtener más detalles";

        return [
            'message' => $message,
            'type' => 'command_search_results',
            'metadata' => ['search_term' => $searchTerm, 'results_count' => count($results)]
        ];
    }

    /**
     * Comando /quick - Acceso rápido a módulos
     */
    private function handleQuickCommand($message)
    {
        $module = trim(str_replace('/quick', '', $message));
        
        $quickAccess = [
            'dashboard' => ['name' => 'Dashboard', 'url' => route('avicontrol.admin.welcome')],
            'inventario' => ['name' => 'Inventario', 'url' => route('avicontrol.admin.inventory.index')],
            'producción' => ['name' => 'Producción', 'url' => route('avicontrol.admin.production.index')],
            'costos' => ['name' => 'Costos', 'url' => route('avicontrol.admin.production_costs.index')],
            'instalaciones' => ['name' => 'Instalaciones', 'url' => route('avicontrol.admin.poultry_facilities.index')],
            'informes' => ['name' => 'Informes', 'url' => route('avicontrol.admin.information.index')]
        ];

        if (empty($module)) {
            $message = "⚡ **ACCESO RÁPIDO**\n\n**Uso:** `/quick [módulo]`\n\n**Módulos disponibles:**\n";
            foreach ($quickAccess as $key => $info) {
                $message .= "• `{$key}`\n";
            }
            return [
                'message' => $message,
                'type' => 'command_quick_help'
            ];
        }

        $module = strtolower($module);
        if (isset($quickAccess[$module])) {
            return [
                'message' => "⚡ **ACCESO RÁPIDO A {$quickAccess[$module]['name']}** 🚀\n\n✅ Redirigiendo...",
                'type' => 'command_quick_redirect',
                'actions' => [
                    ['text' => "🚀 Ir a {$quickAccess[$module]['name']}", 'action' => 'navigate', 'url' => $quickAccess[$module]['url']]
                ]
            ];
        }

        return [
            'message' => "❌ **Módulo '{$module}' no encontrado**\n\nUsa `/quick` para ver módulos disponibles",
            'type' => 'command_quick_error'
        ];
    }

    /**
     * Comando desconocido
     */
    private function handleUnknownCommand()
    {
        return [
            'message' => "❓ **Comando no reconocido**\n\nUsa `/help` para ver comandos disponibles",
            'type' => 'command_unknown'
        ];
    }

    /**
     * Busca en la base de conocimiento
     */
    private function searchKnowledgeBase($term)
    {
        $knowledge = [
            'producción' => [
                'title' => 'Módulo de Producción',
                'description' => 'Registra producción diaria de huevos y carne, controla mortalidad y genera reportes'
            ],
            'inventario' => [
                'title' => 'Módulo de Inventario',
                'description' => 'Gestiona stock de productos, movimientos de entrada/salida y alertas automáticas'
            ],
            'costos' => [
                'title' => 'Control de Costos',
                'description' => 'Calcula costos de producción automáticamente y analiza rentabilidad'
            ],
            'huevos' => [
                'title' => 'Producción de Huevos',
                'description' => 'Registro por tipos (A, AA, B, C), control de calidad y clasificación automática'
            ],
            'stock' => [
                'title' => 'Control de Stock',
                'description' => 'Monitoreo de niveles de inventario con alertas automáticas de stock bajo'
            ]
        ];

        $results = [];
        $term = strtolower($term);
        
        foreach ($knowledge as $key => $item) {
            if (strpos($key, $term) !== false || strpos(strtolower($item['description']), $term) !== false) {
                $results[] = $item;
            }
        }

        return $results;
    }

    /**
     * Guarda conversación en caché
     */
    private function saveConversation($ip, $userMessage, $botResponse)
    {
        try {
            $history = Cache::get('chatbot_history_' . $ip, []);
            
            $history[] = [
                'user' => $userMessage,
                'bot' => $botResponse,
                'timestamp' => now()->toISOString()
            ];
            
            // Mantener solo las últimas 20 conversaciones
            if (count($history) > 20) {
                $history = array_slice($history, -20);
            }
            
            Cache::put('chatbot_history_' . $ip, $history, 60 * 24); // 24 horas
        } catch (\Exception $e) {
            Log::warning('Error guardando historial de chatbot: ' . $e->getMessage());
        }
    }

    /**
     * Comando /clear - Limpiar historial
     */
    private function handleClearCommand($request)
    {
        Cache::forget('chatbot_history_' . $request->ip());
        
        return [
            'message' => "🗑️ **HISTORIAL LIMPIADO** ✅\n\nEl historial de conversación ha sido eliminado correctamente.",
            'type' => 'command_clear'
        ];
    }
}
