<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissions extends Seeder
{
    public function run()
    {
        // Create roles
        $roles = ['admin', 'manager', 'clerk', 'guest', 'vip'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create permissions
        $permissions = [
            'CanViewAll',
            'CanCreateAccommodations',
            'CanViewStats',
            'CanRate',
            'CanViewAllBookings',
            'CanUpdateUser',
            'CanMakeBooking',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        Role::where('name', 'admin')->first()->givePermissionTo($permissions);
        Role::where('name', 'manager')->first()->givePermissionTo(array_slice($permissions, 1));
        Role::where('name', 'clerk')->first()->givePermissionTo(array_slice($permissions, 4));
        Role::where('name', 'guest')->first()->givePermissionTo(array_slice($permissions, 5, 2));
        Role::where('name', 'vip')->first()->givePermissionTo(array_slice($permissions, 5, 2));
    }
}
