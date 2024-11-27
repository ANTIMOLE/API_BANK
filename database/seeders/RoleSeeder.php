<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'viewAll']);
        Permission::create(['name' => 'viewOwn']);
        Permission::create(['name' => 'TransactionAdminPriviledge']);
        Permission::create(['name' => 'TransactionUserPriviledge']);


        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo([
            'create user',
            'edit user',
            'delete user',
            'viewAll',
            'TransactionAdminPriviledge'
        ]);

        $userRole = Role::findByName('user');
        $userRole->givePermissionTo([
            'viewOwn',
            'TransactionUserPriviledge'
        ]);
    }
}
