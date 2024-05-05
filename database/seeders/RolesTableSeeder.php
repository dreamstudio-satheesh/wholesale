<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        
        // Assign all permissions to Admin role
        $permissions = Permission::all();
        $adminRole->permissions()->sync($permissions->pluck('id'));
        
        // Repeat the process for other roles and assign permissions as needed
    }
}
