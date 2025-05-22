<?php
namespace Modules\AVICONTROL\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\SICA\Entities\Person;


class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Buscar la persona por document_number
        $person = Person::where('document_number', '1075793788')->first();
            User::updateOrCreate(['nickname' => 'Yisus'], [
                    'person_id' => $person->id,
                    'email' => 'yeisonalbeiromarinduran@gmail.com',
                   // Yema3788
                ]);
        
    }
}