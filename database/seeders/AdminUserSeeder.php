<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create the admin user
         $adminUser = User::create([
            'name' => 'Lee Baartman',
            'email' => 'leebaartman@gmail.com',
            'password' => bcrypt('password'), // Ensure to use a secure password hashing method
            'contact_number' => '0833910092',
            'id_number' => '9002135187083',
            'activated' => true
        ]);

        // Assign the admin role to the user
        $adminRole = Role::where('name', 'admin')->first();
        $adminUser->assignRole($adminRole);
    }
}
