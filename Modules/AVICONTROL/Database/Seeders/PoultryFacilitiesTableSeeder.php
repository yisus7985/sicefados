<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AVICONTROL\Entities\PoultryFacility;
use Carbon\Carbon;

class PoultryFacilitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create initial poultry facilities
        $poultryFacilities = [
            [
                'name' => 'Poultry Facility 1',
                'length' => 20.00,
                'width' => 10.00,
                'height' => 3.50,
                'capacity' => 1000,
                'description' => 'Main poultry facility for laying hens',
                'status' => 'active',
                'creation_date' => Carbon::now()->subMonths(6),
            ],
            [
                'name' => 'Poultry Facility 2',
                'length' => 15.00,
                'width' => 8.00,
                'height' => 3.00,
                'capacity' => 600,
                'description' => 'Secondary poultry facility for laying hens',
                'status' => 'active',
                'creation_date' => Carbon::now()->subMonths(4),
            ],
            [
                'name' => 'Poultry Facility 3',
                'length' => 25.00,
                'width' => 12.00,
                'height' => 4.00,
                'capacity' => 1500,
                'description' => 'Poultry facility for broilers',
                'status' => 'active',
                'creation_date' => Carbon::now()->subMonths(2),
            ],
            [
                'name' => 'Poultry Facility 4',
                'length' => 18.00,
                'width' => 9.00,
                'height' => 3.20,
                'capacity' => 800,
                'description' => 'Poultry facility for chicks',
                'status' => 'maintenance',
                'creation_date' => Carbon::now()->subMonths(1),
            ],
        ];

        foreach ($poultryFacilities as $facility) {
            PoultryFacility::updateOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}