<?php

namespace Modules\AVICONTROL\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SICA\Entities\App;
use Modules\SICA\Entities\Permission;
use Modules\SICA\Entities\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            // Define permission arrays to be assigned to roles
            $permissions_admin = []; // Permissions for Administrator
            $permissions_all_users = []; // Permissions for all users

            // Query SICA application to register roles
            $app = App::where('name', 'AVICONTROL')->first();
            
            if (!$app) {
                // If the app doesn't exist, create it
                $app = App::create([
                    'name' => 'AVICONTROL',
                    'description' => 'AVICONTROL Application',
                    'description_english' => 'AVICONTROL Application',
                    'icon' => 'fa-feather-alt',
                    'color' => '#4d7c0f',
                    'status' => 'active'
                ]);
                
                Log::info('Created AVICONTROL app with ID: ' . $app->id);
            }

            // ===================== Register all permissions for AVICONTROL application ==================
            // Administrator main view
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.welcome'], [
                'name' => 'Access to Administrator Role',
                'description' => 'Access to Administrator Role',
                'description_english' => 'Access to the Administrator Role',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;
            
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.index'], [
                'name' => 'Access to Administrator View',
                'description' => 'Access to Administrator Role',
                'description_english' => 'Access to the Administrator Role',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Permissions for poultry facilities management
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_facilities.index'], [
                'name' => 'View poultry facilities list',
                'description' => 'Allows viewing the list of all poultry facilities',
                'description_english' => 'View list of all poultry facilities',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_facilities.create'], [
                'name' => 'Create poultry facilities',
                'description' => 'Allows creating new poultry facilities in the system',
                'description_english' => 'Create new poultry facilities',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_facilities.edit'], [
                'name' => 'Edit poultry facilities',
                'description' => 'Allows editing information of existing poultry facilities',
                'description_english' => 'Edit existing poultry facilities',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_facilities.destroy'], [
                'name' => 'Delete poultry facilities',
                'description' => 'Allows deleting poultry facilities from the system',
                'description_english' => 'Delete poultry facilities',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Add permissions for poultry_houses routes (alias for poultry_facilities)
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.index'], [
                'name' => 'View poultry houses list',
                'description' => 'Allows viewing the list of all poultry houses',
                'description_english' => 'View list of all poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.store'], [
                'name' => 'View poultry houses list',
                'description' => 'Esta es la funcion para cargar la lista de galpones',
                'description_english' => 'View list of all poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;


            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.create'], [
                'name' => 'Create poultry houses',
                'description' => 'Allows creating new poultry houses in the system',
                'description_english' => 'Create new poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.edit'], [
                'name' => 'Edit poultry houses',
                'description' => 'Allows editing information of existing poultry houses',
                'description_english' => 'Edit existing poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.destroy'], [
                'name' => 'Delete poultry houses',
                'description' => 'Allows deleting poultry houses from the system',
                'description_english' => 'Delete poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Add permissions for store, update, and show methods
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_facilities.store'], [
                'name' => 'Store poultry facilities',
                'description' => 'Allows storing new poultry facilities in the system',
                'description_english' => 'Store new poultry facilities',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_facilities.update'], [
                'name' => 'Update poultry facilities',
                'description' => 'Allows updating existing poultry facilities',
                'description_english' => 'Update existing poultry facilities',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_facilities.show'], [
                'name' => 'Show poultry facilities',
                'description' => 'Allows viewing details of poultry facilities',
                'description_english' => 'View details of poultry facilities',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Same for poultry_houses
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.store'], [
                'name' => 'Store poultry houses',
                'description' => 'Allows storing new poultry houses in the system',
                'description_english' => 'Store new poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.update'], [
                'name' => 'Update poultry houses',
                'description' => 'Allows updating existing poultry houses',
                'description_english' => 'Update existing poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.poultry_houses.show'], [
                'name' => 'Show poultry houses',
                'description' => 'Allows viewing details of poultry houses',
                'description_english' => 'View details of poultry houses',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Permissions for birds management
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.birds.index'], [
                'name' => 'View birds list',
                'description' => 'Allows viewing the list of all birds',
                'description_english' => 'View list of all birds',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.birds.create'], [
                'name' => 'Create birds',
                'description' => 'Allows creating new bird batches in the system',
                'description_english' => 'Create new bird batches',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.birds.store'], [
                'name' => 'Store birds',
                'description' => 'Allows storing new bird batches in the system',
                'description_english' => 'Store new bird batches',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.birds.show'], [
                'name' => 'Show birds',
                'description' => 'Allows viewing details of bird batches',
                'description_english' => 'View details of bird batches',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.birds.edit'], [
                'name' => 'Edit birds',
                'description' => 'Allows editing information of existing bird batches',
                'description_english' => 'Edit existing bird batches',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.birds.update'], [
                'name' => 'Update birds',
                'description' => 'Allows updating existing bird batches',
                'description_english' => 'Update existing bird batches',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.birds.destroy'], [
                'name' => 'Delete birds',
                'description' => 'Allows deleting bird batches from the system',
                'description_english' => 'Delete bird batches',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.facilities.capacity'], [
                'name' => 'View facility capacity',
                'description' => 'Allows viewing facility capacity information',
                'description_english' => 'View facility capacity information',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Permissions for inventory management
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.index'], [
                'name' => 'View inventory list',
                'description' => 'Allows viewing the list of all inventory products',
                'description_english' => 'View list of all inventory products',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.create'], [
                'name' => 'Create inventory products',
                'description' => 'Allows creating new inventory products in the system',
                'description_english' => 'Create new inventory products',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.store'], [
                'name' => 'Store inventory products',
                'description' => 'Allows storing new inventory products in the system',
                'description_english' => 'Store new inventory products',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.show'], [
                'name' => 'Show inventory products',
                'description' => 'Allows viewing details of inventory products',
                'description_english' => 'View details of inventory products',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.edit'], [
                'name' => 'Edit inventory products',
                'description' => 'Allows editing information of existing inventory products',
                'description_english' => 'Edit existing inventory products',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.update'], [
                'name' => 'Update inventory products',
                'description' => 'Allows updating existing inventory products',
                'description_english' => 'Update existing inventory products',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.destroy'], [
                'name' => 'Delete inventory products',
                'description' => 'Allows deleting inventory products from the system',
                'description_english' => 'Delete inventory products',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Permissions for inventory movements
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.movements.create'], [
                'name' => 'Create inventory movements',
                'description' => 'Allows creating new inventory movements',
                'description_english' => 'Create new inventory movements',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.movements.store'], [
                'name' => 'Store inventory movements',
                'description' => 'Allows storing new inventory movements',
                'description_english' => 'Store new inventory movements',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.movements.index'], [
                'name' => 'View inventory movements',
                'description' => 'Allows viewing inventory movements list',
                'description_english' => 'View inventory movements list',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Permissions for inventory alerts
            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.low_stock'], [
                'name' => 'View low stock alerts',
                'description' => 'Allows viewing low stock alerts',
                'description_english' => 'View low stock alerts',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            $permission = Permission::updateOrCreate(['slug' => 'avicontrol.admin.inventory.expiring'], [
                'name' => 'View expiring products',
                'description' => 'Allows viewing expiring products alerts',
                'description_english' => 'View expiring products alerts',
                'app_id' => $app->id,
            ]);
            $permissions_admin[] = $permission->id;
            $permissions_all_users[] = $permission->id;

            // Query ROLES
            $rol_admin = Role::where('slug', 'avicontrol.admin')->first(); // Administrator Role
            
            if (!$rol_admin) {
                // If the role doesn't exist, create it
                $rol_admin = Role::create([
                    'name' => 'AVICONTROL Administrator',
                    'slug' => 'avicontrol.admin',
                    'description' => 'Administrator role for AVICONTROL',
                    'app_id' => $app->id,
                ]);
                
                Log::info('Created AVICONTROL admin role with ID: ' . $rol_admin->id);
            }

            // Assignment of PERMISSIONS to ROLES of the AVICONTROL application (Synchronization of relationships without deleting existing relationships)
            $rol_admin->permissions()->syncWithoutDetaching($permissions_admin);
            
            // Assign permissions to all users
            // Get all roles
            $all_roles = Role::all();
            foreach ($all_roles as $role) {
                $role->permissions()->syncWithoutDetaching($permissions_all_users);
                Log::info('Assigned permissions to role: ' . $role->name);
            }
            
            // Assign the current user to the admin role if they're not already
            if (auth()->check()) {
                $user = auth()->user();
                $user->roles()->syncWithoutDetaching([$rol_admin->id]);
                Log::info('Assigned admin role to user: ' . $user->name);
            }
            
            // Force permissions in database directly for all users
            // This is a last resort approach - use with caution
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                // Check if user_role entry exists
                $exists = DB::table('user_role')
                    ->where('user_id', $user->id)
                    ->where('role_id', $rol_admin->id)
                    ->exists();
                    
                if (!$exists) {
                    DB::table('user_role')->insert([
                        'user_id' => $user->id,
                        'role_id' => $rol_admin->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    Log::info('Forced admin role assignment to user ID: ' . $user->id);
                }
            }
            
            Log::info('PermissionsTableSeeder completed successfully');
            
        } catch (\Exception $e) {
            Log::error('Error in PermissionsTableSeeder: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
