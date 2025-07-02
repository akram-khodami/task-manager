<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default roles
        Role::factory()->create([
            'name' => 'admin',
            'description' => 'مدیر سیستم',
        ]);

        Role::factory()->create([
            'name' => 'user',
            'description' => 'کاربر عادی',
        ]);

        // Create some additional roles
        Role::factory(3)->create();
        Role::factory(2)->inactive()->create();
    }
} 