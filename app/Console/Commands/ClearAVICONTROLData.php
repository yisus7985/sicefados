<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearAVICONTROLData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avicontrol:clear-data {--confirm : Confirmar sin preguntar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpiar todos los datos de las tablas de AVICONTROL (mantiene tablas y usuarios)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🗑️  Limpiando datos de AVICONTROL...');
        $this->newLine();

        // Lista de tablas de AVICONTROL a limpiar
        $tablesToClear = [
            'avicontrol_productions',
            'avicontrol_production_costs', 
            'avicontrol_cost_components',
            'avicontrol_profitability_analysis',
            'avicontrol_inventory_products',
            'avicontrol_inventory_movements',
            'avicontrol_poultry_facilities',
            'avicontrol_birds',
            'avicontrol_food_consumption',
            'avicontrol_food_conversion',
            'avicontrol_food_waste',
            'avicontrol_alerts'
        ];

        // Verificar qué tablas existen
        $existingTables = [];
        foreach ($tablesToClear as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $existingTables[$table] = $count;
            }
        }

        if (empty($existingTables)) {
            $this->warn('⚠️  No se encontraron tablas de AVICONTROL para limpiar.');
            return 0;
        }

        // Mostrar resumen de datos a eliminar
        $this->info('📊 Datos encontrados en las tablas de AVICONTROL:');
        $totalRecords = 0;
        foreach ($existingTables as $table => $count) {
            $this->line("   • {$table}: {$count} registros");
            $totalRecords += $count;
        }
        $this->newLine();
        $this->info("📈 Total de registros a eliminar: {$totalRecords}");
        $this->newLine();

        // Confirmar acción
        if (!$this->option('confirm')) {
            if (!$this->confirm('¿Estás seguro de que quieres eliminar TODOS estos datos? Esta acción no se puede deshacer.')) {
                $this->info('❌ Operación cancelada.');
                return 0;
            }
        }

        // Deshabilitar verificación de claves foráneas temporalmente
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        try {
            $this->info('🧹 Iniciando limpieza de datos...');
            $this->newLine();

            $bar = $this->output->createProgressBar(count($existingTables));
            $bar->start();

            foreach ($existingTables as $table => $count) {
                if ($count > 0) {
                    DB::table($table)->truncate();
                    $this->line("   ✅ {$table}: {$count} registros eliminados");
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            // Rehabilitar verificación de claves foráneas
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            $this->info('🎉 ¡Limpieza completada exitosamente!');
            $this->info('✅ Se eliminaron ' . number_format($totalRecords) . ' registros de las tablas de AVICONTROL.');
            $this->info('✅ Las tablas se mantienen intactas.');
            $this->info('✅ Los datos de usuarios y acceso se preservan.');
            $this->newLine();
            $this->info('💡 Ahora puedes comenzar con datos limpios en el módulo AVICONTROL.');

        } catch (\Exception $e) {
            // Rehabilitar verificación de claves foráneas en caso de error
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            
            $this->error('❌ Error durante la limpieza:');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}
