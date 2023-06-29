<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class InstallController extends Controller
{

    function Install()  {
        $role = Role::create(['name' => 'admin']);
        $create = Permission::create(['name' => 'create_products']);
        $read = Permission::create(['name' => 'read_products']);
        $update = Permission::create(['name' => 'update_products']);
        $delete = Permission::create(['name' => 'delete_products']);
        $role->givePermissionTo($create);
        $role->givePermissionTo($update);
        $role->givePermissionTo($read);
        $role->givePermissionTo($delete);
    }
    //
}
