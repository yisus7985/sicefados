# Módulo de Costos - AVICONTROL

## Descripción General

El módulo de costos de AVICONTROL implementa los requisitos funcionales RF-013 (Control de Costos de Producción) y RF-014 (Análisis de Rentabilidad) del SRS. Este módulo permite el cálculo detallado y automático de los costos de producción avícola, así como el análisis de rentabilidad para evaluar la viabilidad económica de las operaciones.

## Características Principales

### RF-013: Control de Costos de Producción

- **Cálculo Automático de Costos**: Integración con el módulo de inventario para calcular costos basados en consumo de insumos
- **Componentes de Costo**:
  - Concentrado (alimento)
  - Agua y energía
  - Medicamentos y biológicos
  - Embalajes
  - Mano de obra
  - Mantenimiento
  - Otros costos operativos

- **Métricas de Costo**:
  - Costo total de producción por lote o período
  - Costo por unidad de producto (huevo, kg de carne, ave)
  - Desglose detallado por componente

### RF-014: Análisis de Rentabilidad

- **Integración de Datos**: Combina costos de producción con ingresos por ventas
- **Métricas de Rentabilidad**:
  - Margen de ganancia bruta y neta
  - Rentabilidad por período (diario, semanal, mensual, por lote)
  - Análisis por unidad de producto
  - Estado de rentabilidad (rentable, pérdida, punto de equilibrio)

## Estructura del Módulo

### Entidades

1. **ProductionCost** (`avicontrol_production_costs`)
   - Registra los costos de producción por lote o período
   - Calcula automáticamente el costo total
   - Relaciona con galpones y lotes de aves

2. **CostComponent** (`avicontrol_cost_components`)
   - Componentes detallados de costo
   - Incluye cantidad, precio unitario y costo total
   - Relaciona con productos del inventario

3. **ProfitabilityAnalysis** (`avicontrol_profitability_analysis`)
   - Análisis de rentabilidad
   - Calcula métricas financieras automáticamente
   - Relaciona costos con ingresos

### Controladores

1. **ProductionCostController**
   - Gestión CRUD de costos de producción
   - Cálculo automático desde inventario
   - Validación y confirmación de costos

2. **ProfitabilityAnalysisController**
   - Gestión CRUD de análisis de rentabilidad
   - Generación de reportes
   - Exportación a PDF

### Vistas

1. **Costos de Producción**:
   - `index.blade.php`: Lista de costos con filtros
   - `create.blade.php`: Formulario de creación
   - `show.blade.php`: Detalles con gráficos
   - `edit.blade.php`: Formulario de edición

2. **Análisis de Rentabilidad**:
   - `index.blade.php`: Lista de análisis con estadísticas
   - `create.blade.php`: Formulario de creación
   - `show.blade.php`: Detalles del análisis
   - `report.blade.php`: Reportes consolidados

## Funcionalidades Principales

### 1. Cálculo Automático de Costos

```php
// Ejemplo de cálculo desde inventario
$inventoryMovements = DB::table('avicontrol_inventory_movements as im')
    ->join('avicontrol_inventory_products as ip', 'im.inventory_product_id', '=', 'ip.id')
    ->where('im.poultry_facility_id', $facilityId)
    ->whereBetween('im.movement_date', [$startDate, $endDate])
    ->where('im.movement_type', 'out')
    ->select('ip.product_type', 'im.quantity', 'ip.unit_price')
    ->get();
```

### 2. Análisis de Rentabilidad

```php
// Cálculo automático de métricas
$analysis->calculateGrossProfit();
$analysis->calculateGrossMarginPercentage();
$analysis->calculateNetProfit();
$analysis->calculateNetMarginPercentage();
$analysis->calculateProfitPerUnit();
```

### 3. Reportes y Exportación

- Reportes por período
- Análisis comparativo
- Exportación a PDF
- Gráficos de distribución de costos

## Rutas del Módulo

### Costos de Producción
- `GET /avicontrol/admin/production_costs` - Lista de costos
- `GET /avicontrol/admin/production_costs/create` - Crear costo
- `POST /avicontrol/admin/production_costs` - Guardar costo
- `GET /avicontrol/admin/production_costs/{id}` - Ver detalles
- `GET /avicontrol/admin/production_costs/{id}/edit` - Editar costo
- `PUT /avicontrol/admin/production_costs/{id}` - Actualizar costo
- `DELETE /avicontrol/admin/production_costs/{id}` - Eliminar costo
- `POST /avicontrol/admin/production_costs/{id}/confirm` - Confirmar costo

### Análisis de Rentabilidad
- `GET /avicontrol/admin/profitability_analysis` - Lista de análisis
- `GET /avicontrol/admin/profitability_analysis/create` - Crear análisis
- `POST /avicontrol/admin/profitability_analysis` - Guardar análisis
- `GET /avicontrol/admin/profitability_analysis/{id}` - Ver detalles
- `GET /avicontrol/admin/profitability_analysis/report` - Generar reporte
- `GET /avicontrol/admin/profitability_analysis/{id}/export-pdf` - Exportar PDF

## Integración con Otros Módulos

### Módulo de Inventario
- Obtiene datos de consumo de insumos
- Calcula costos basados en movimientos de inventario
- Utiliza precios de productos del inventario

### Módulo de Aves
- Relaciona costos con lotes específicos
- Calcula costos por ave
- Integra con información de producción

### Módulo de Instalaciones
- Asocia costos con galpones específicos
- Permite análisis por instalación

## Configuración y Uso

### 1. Migraciones
```bash
php artisan migrate --path=Modules/AVICONTROL/Database/Migrations
```

### 2. Acceso al Módulo
- Navegar a AVICONTROL > Costos de Producción
- Navegar a AVICONTROL > Análisis de Rentabilidad

### 3. Flujo de Trabajo Típico
1. Crear costo de producción
2. Calcular costos desde inventario (opcional)
3. Confirmar costo de producción
4. Crear análisis de rentabilidad
5. Revisar métricas y reportes

## Métricas y KPIs

### Costos
- Costo total por período
- Costo por unidad de producto
- Distribución de costos por componente
- Tendencia de costos en el tiempo

### Rentabilidad
- Margen bruto y neto
- Rentabilidad por período
- Análisis de punto de equilibrio
- Comparación entre lotes/instalaciones

## Consideraciones Técnicas

### Base de Datos
- Uso de transacciones para integridad de datos
- Índices optimizados para consultas de reportes
- Soft deletes para auditoría

### Rendimiento
- Cálculos automáticos en tiempo real
- Caché de métricas frecuentemente consultadas
- Paginación en listas grandes

### Seguridad
- Validación de datos en frontend y backend
- Control de acceso por roles
- Auditoría de cambios

## Próximas Mejoras

1. **Integración con Ventas**: Conectar con módulo de ventas para ingresos automáticos
2. **Análisis Predictivo**: Predicción de costos futuros
3. **Alertas de Costos**: Notificaciones cuando los costos excedan umbrales
4. **Reportes Avanzados**: Dashboards interactivos con más métricas
5. **API REST**: Endpoints para integración con sistemas externos

## Soporte

Para soporte técnico o consultas sobre el módulo de costos, contactar al equipo de desarrollo de AVICONTROL. 