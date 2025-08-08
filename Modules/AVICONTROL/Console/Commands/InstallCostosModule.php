<?php

namespace Modules\AVICONTROL\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class InstallCostosModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avicontrol:install-costos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Instala el módulo de costos de AVICONTROL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Instalando módulo de costos de AVICONTROL...');
        
        try {
            // Verificar si las tablas ya existen
            $tables = [
                'avicontrol_production_costs',
                'avicontrol_cost_components',
                'avicontrol_profitability_analysis'
            ];
            
            $existingTables = [];
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $existingTables[] = $table;
                }
            }
            
            if (!empty($existingTables)) {
                $this->warn('Las siguientes tablas ya existen:');
                foreach ($existingTables as $table) {
                    $this->line("  - {$table}");
                }
                
                if (!$this->confirm('¿Desea continuar con la instalación? Esto puede sobrescribir datos existentes.')) {
                    $this->info('Instalación cancelada.');
                    return 0;
                }
            }
            
            // Ejecutar migraciones
            $this->info('Ejecutando migraciones...');
            $migrationPath = 'Modules/AVICONTROL/Database/Migrations';
            
            $exitCode = Artisan::call('migrate', [
                '--path' => $migrationPath,
                '--force' => true
            ]);
            
            if ($exitCode === 0) {
                $this->info('✅ Migraciones ejecutadas correctamente.');
            } else {
                $this->error('❌ Error al ejecutar las migraciones.');
                return 1;
            }
            
            // Verificar que las tablas se crearon correctamente
            $this->info('Verificando tablas creadas...');
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $this->info("  ✅ Tabla {$table} creada correctamente.");
                } else {
                    $this->error("  ❌ Error: Tabla {$table} no se creó.");
                    return 1;
                }
            }
            
            // Información adicional
            $this->info('');
            $this->info('🎉 Módulo de costos instalado correctamente!');
            $this->info('');
            $this->info('Funcionalidades disponibles:');
            $this->line('  • Control de Costos de Producción (RF-013)');
            $this->line('  • Análisis de Rentabilidad (RF-014)');
            $this->line('  • Cálculo automático desde inventario');
            $this->line('  • Reportes y exportación PDF');
            $this->info('');
            $this->info('Acceso al módulo:');
            $this->line('  • Costos de Producción: /avicontrol/admin/production_costs');
            $this->line('  • Análisis de Rentabilidad: /avicontrol/admin/profitability_analysis');
            $this->info('');
            $this->info('Para más información, consulte: Modules/AVICONTROL/README_COSTOS.md');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Error durante la instalación: ' . $e->getMessage());
            return 1;
        }
    }
} 