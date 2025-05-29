<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AVICONTROL\Entities\Bird;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Carbon\Carbon;

class BirdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener las instalaciones existentes
        $facilities = PoultryFacility::all();
        
        if ($facilities->isEmpty()) {
            $this->command->info('No hay instalaciones avícolas. Ejecuta primero el seeder de instalaciones.');
            return;
        }

        $birds = [
            [
                'poultry_facility_id' => $facilities->first()->id,
                'batch_code' => 'LOT-001-2025',
                'bird_type' => 'laying_hens',
                'quantity' => 800,
                'initial_quantity' => 850,
                'entry_date' => Carbon::now()->subMonths(3),
                'age_weeks' => 20,
                'breed' => 'Hy-Line Brown',
                'average_weight' => 1.8,
                'status' => 'active',
                'purchase_price' => 15000,
                'supplier' => 'Avícola San Fernando',
                'notes' => 'Lote de gallinas ponedoras en producción'
            ],
            [
                'poultry_facility_id' => $facilities->count() > 1 ? $facilities->skip(1)->first()->id : $facilities->first()->id,
                'batch_code' => 'LOT-002-2025',
                'bird_type' => 'broilers',
                'quantity' => 500,
                'initial_quantity' => 520,
                'entry_date' => Carbon::now()->subWeeks(6),
                'age_weeks' => 6,
                'breed' => 'Ross 308',
                'average_weight' => 2.2,
                'status' => 'active',
                'purchase_price' => 3500,
                'supplier' => 'Pollitos El Dorado',
                'notes' => 'Pollos de engorde próximos a sacrificio'
            ],
            [
                'poultry_facility_id' => $facilities->first()->id,
                'batch_code' => 'LOT-003-2025',
                'bird_type' => 'chicks',
                'quantity' => 200,
                'initial_quantity' => 200,
                'entry_date' => Carbon::now()->subWeeks(2),
                'age_weeks' => 2,
                'breed' => 'Hy-Line W-36',
                'average_weight' => 0.15,
                'status' => 'active',
                'purchase_price' => 2500,
                'supplier' => 'Incubadora Nacional',
                'notes' => 'Pollitos recién llegados para reemplazo'
            ],
            [
                'poultry_facility_id' => $facilities->count() > 2 ? $facilities->skip(2)->first()->id : $facilities->first()->id,
                'batch_code' => 'LOT-004-2025',
                'bird_type' => 'breeders',
                'quantity' => 100,
                'initial_quantity' => 105,
                'entry_date' => Carbon::now()->subMonths(6),
                'age_weeks' => 45,
                'breed' => 'Cobb 500',
                'average_weight' => 3.5,
                'status' => 'active',
                'purchase_price' => 25000,
                'supplier' => 'Genética Avícola S.A.',
                'notes' => 'Reproductores para producción de huevos fértiles'
            ]
        ];

        foreach ($birds as $birdData) {
            Bird::updateOrCreate(
                ['batch_code' => $birdData['batch_code']],
                $birdData
            );
        }

        $this->command->info('Seeder de aves ejecutado exitosamente.');
    }
}
