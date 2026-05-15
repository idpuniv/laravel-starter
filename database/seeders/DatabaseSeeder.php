<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Roles\Roles;

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
            'username' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin')
        ])->assignRole(Roles::ADMIN);

        User::factory()->create([
            'username' => 'user',
            'email' => 'user@email.com',
            'password' => Hash::make('user')
        ])->assignRole(Roles::USER);

        User::factory()->create([
            'username' => 'user2',
            'email' => 'user2@email.com',
            'password' => Hash::make('user2')
        ]);

        User::factory()->create([
            'username' => 'viewer',
            'email' => 'viewer@email.com',
            'password' => Hash::make('viewer')
        ])->assignRole(Roles::VIEWER);

        User::factory()->create([
            'username' => 'root',
            'email' => 'root@email.com',
            'password' => Hash::make('root'),
        ])->assignRole(Roles::ROOT);

        Person::factory(1)->create();
        User::factory(10)->create();
        User::factory(3)->user()->create();
        User::factory(3)->viewer()->create();
        User::factory(3)->admin()->create();
        User::factory(5)->inactive()->create();
    }
}
