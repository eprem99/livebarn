<?php
/*
 * Project: Livebarn
 * Author: VECTO
 * Email: info@vecto.digital
 * Site: https://vecto.digital/
 * Last Modified: Friday, 28th April 2023
 */

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\RoleUser;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = "Manager";
        $role->display_name = ucwords('Manager');
        $role->save();

        $roleId = $role->id;
        $permissions = Permission::all();

        $role = Role::findOrFail($roleId);
        $role->perms()->sync([]);
        $role->attachPermissions($permissions);

    }
}
