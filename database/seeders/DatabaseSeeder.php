<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RolePermissionSeeder::class,
            TeamSeeder::class,
            CountrySeeder::class,
        ]);
        
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin')
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('user')
        ]);

        User::factory()->create([
            'name' => 'Viewer User',
            'email' => 'viewer@example.com',
            'password' => Hash::make('viewer')
        ]);

        User::factory()->create([
            'name' => 'Root user',
            'email' => 'root@example.com',
            'password' => Hash::make('root')
        ]);

        User::factory(10)->create();
        User::factory(3)->user()->create();
        User::factory(3)->viewer()->create();
        User::factory(3)->admin()->create();
        User::factory(5)->inactive()->create();
    }
}
