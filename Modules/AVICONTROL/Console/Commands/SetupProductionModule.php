<?php

namespace Modules\AVICONTROL\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SetupProductionModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'avicontrol:setup-production {--fresh : Ejecutar migración fresh} {--seed : Ejecutar seeder después de la migración}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configurar el módulo de producción de AVICONTROL (migración + seeder)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Configurando módulo de producción de AVICONTROL...');
        $this->newLine();

        try {
            // Verificar si la tabla existe
            if (Schema::hasTable('avicontrol_productions')) {
                $this->warn('⚠  La tabla avicontrol_productions ya existe.');
                
                if ($this->option('fresh')) {
                    $this->info('🔄 Ejecutando migración fresh...');
                    $this->call('migrate:fresh', [
                        '--path' => 'Modules/AVICONTROL/Database/Migrations',
                        '--force' => true
                    ]);
                } else {
                    $this->info('🔄 Ejecutando migración...');
                    $this->call('migrate', [
                        '--path' => 'Modules/AVICONTROL/Database/Migrations',
                        '--force' => true
                    ]);
                }
            } else {
                $this->info('🔄 Ejecutando migración inicial...');
                $this->call('migrate', [
                    '--path' => 'Modules/AVIControl/Database/Migrations',
                    '--force' => true
                ]);
            }

            $this->newLine();
            $this->info('✅ Migración completada exitosamente!');

            // Ejecutar seeder si se solicita
            if ($this->option('seed')) {
                $this->newLine();
                $this->info('🌱 Ejecutando seeder de producción...');
                
                $this->call('db:seed', [
                    '--class' => 'Modules\\AVICONTROL\\Database\\Seeders\\ProductionSeeder',
                    '--force' => true
                ]);
                
                $this->info('✅ Seeder completado exitosamente!');
            }

            // Mostrar resumen
            $this->newLine();
            $this->info('📊 Resumen de la configuración:');
            
            if (Schema::hasTable('avicontrol_productions')) {
                $count = DB::table('avicontrol_productions')->count();
                $this->line("   • Tabla: avicontrol_productions ✅");
                $this->line("   • Registros: {$count}");
                
                // Mostrar estructura de la tabla
                $columns = Schema::getColumnListing('avicontrol_productions');
                $this->line("   • Columnas: " . implode(', ', $columns));
            }

            $this->newLine();
            $this->info('🎉 Módulo de producción configurado correctamente!');
            $this->info('🌐 Accede a: /avicontrol/admin/production');
            
        } catch (\Exception $e) {
            $this->error('❌ Error durante la configuración:');
            $this->error($e->getMessage());
            return 1;
        }

        return 0;
    }
}