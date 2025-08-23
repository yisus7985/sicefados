<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GalponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Galpones para gallinas ponedoras (huevos)
        DB::table('avicontrol_poultry_facilities')->insert([
            [
                'name' => 'Galpón A - Ponederas',
                'tipo' => 'gallinas_ponedoras',
                'length' => 50.0,
                'width' => 10.0,
                'height' => 3.0,
                'capacity' => 5000,
                'description' => 'Galpón para gallinas ponedoras - Producción de huevos',
                'status' => 'active',
                'creation_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Galpón B - Ponederas',
                'tipo' => 'gallinas_ponedoras',
                'length' => 60.0,
                'width' => 12.0,
                'height' => 3.5,
                'capacity' => 6000,
                'description' => 'Galpón para gallinas ponedoras - Producción de huevos',
                'status' => 'active',
                'creation_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Galpón C - Ponederas',
                'tipo' => 'gallinas_ponedoras',
                'length' => 45.0,
                'width' => 8.0,
                'height' => 2.8,
                'capacity' => 4000,
                'description' => 'Galpón para gallinas ponedoras - Producción de huevos',
                'status' => 'active',
                'creation_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Galpones para pollos de engorde (carne)
        DB::table('avicontrol_poultry_facilities')->insert([
            [
                'name' => 'Galpón 1 - Engorde',
                'tipo' => 'pollos_engorde',
                'length' => 55.0,
                'width' => 15.0,
                'height' => 3.2,
                'capacity' => 8000,
                'description' => 'Galpón para pollos de engorde - Producción de carne',
                'status' => 'active',
                'creation_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Galpón 2 - Engorde',
                'tipo' => 'pollos_engorde',
                'length' => 65.0,
                'width' => 18.0,
                'height' => 3.8,
                'capacity' => 10000,
                'description' => 'Galpón para pollos de engorde - Producción de carne',
                'status' => 'active',
                'creation_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Galpón 3 - Engorde',
                'tipo' => 'pollos_engorde',
                'length' => 40.0,
                'width' => 12.0,
                'height' => 3.0,
                'capacity' => 6000,
                'description' => 'Galpón para pollos de engorde - Producción de carne',
                'status' => 'active',
                'creation_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
