<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AVICONTROLDatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::beginTransaction();
        try {
            $this->call(AppTableSeeder::class);
            $this->call(PeopleTableSeeder::class);
            $this->call(UsersTableSeeder::class);
            $this->call(RolesTableSeeder::class);
            $this->call(PermissionsTableSeeder::class);
            $this->call(PoultryFacilitiesTableSeeder::class); // Actualizado

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        Model::reguard();
    }
}