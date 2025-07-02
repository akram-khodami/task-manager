<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        if (!$adminRole || !$userRole) {
            $this->call(RoleSeeder::class);
            $adminRole = Role::where('name', 'admin')->first();
            $userRole = Role::where('name', 'user')->first();
        }

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'مدیر سیستم',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->roles()->attach($adminRole);

        // Create regular users
        $users = User::factory(5)->create();
        $users->each(fn ($user) => $user->roles()->attach($userRole));

        // Create some additional users without roles
        User::factory(3)->create();
    }
} 