<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 users assigned to clerks
        User::factory(3)->create()->each(function ($user) {
            $user->assignRole('clerk');
        });

        // Create 1 user assigned to manager
        User::factory(1)->create()->each(function ($user) {
            $user->assignRole('manager');
        });

        // Create 5 users assigned to guests
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('guest');
        });

        // Create 2 users assigned to vip
        User::factory(2)->create()->each(function ($user) {
            $user->assignRole('vip');
        });
    }
}
