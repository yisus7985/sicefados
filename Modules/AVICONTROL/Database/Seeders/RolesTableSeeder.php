<?php   

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\SICA\Entities\Role;
use Modules\SICA\Entities\App;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $app = App::where('name', 'AVICONTROL')->firstOrFail();
        
        $roleadmin = Role::updateOrCreate(['slug' => 'avicontrol.admin'], [
            'name' => 'Administrador',
            'description' => 'Administrador del sistema de Avicola',
            'description_english' => 'Avicola system administrator',
            'full_access' => 'No',
            'app_id' => $app->id,
        ]);
        $useradministrador = User::where('nickname', 'Yisus')->firstOrFail();
        $useradministrador ->roles()->syncWithoutDetaching([$roleadmin->id]);

        
    }
}