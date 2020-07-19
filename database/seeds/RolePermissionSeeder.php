<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;



class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'unlock parkings']);
        Permission::create(['name' => 'monitor dashboard']);
        Permission::create(['name' => 'manage everything']);
        // Permission::create(['name' => 'unpublish articles']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'client']);
        $role1->givePermissionTo('unlock parkings');

        $role2 = Role::create(['name' => 'viewer']);
        $role2->givePermissionTo('monitor dashboard');
        // $role1->givePermissionTo('edit articles');
        // $role1->givePermissionTo('delete articles');

        $role3 = Role::create(['name' => 'admin']);
        $role3->givePermissionTo('manage everything');
        // $role2->givePermissionTo('publish articles');
        // $role2->givePermissionTo('unpublish articles');

        // $role3 = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        // $user = Factory(App\User::class)->create([
        //     'name' => 'Example User',
        //     'email' => 'test@example.com',
        // ]);
        // $user->assignRole($role1);

        // $user = Factory(App\User::class)->create([
        //     'name' => 'Example Admin User',
        //     'email' => 'admin@example.com',
        // ]);
        // $user->assignRole($role2);

        // $user = Factory(App\User::class)->create([
        //     'name' => 'Example Super-Admin User',
        //     'email' => 'superadmin@example.com',
        // ]);
        // $user->assignRole($role3);
    }
}
