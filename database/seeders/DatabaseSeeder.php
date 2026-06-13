<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Roles\Roles;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            CountrySeeder::class,
        ]);

        if (app()->isLocal()) {
            User::factory()->create([
                'username' => 'root',
                'email' => 'root@email.com',
                'password' => Hash::make('root'),
            ])->assignRole(Roles::ROOT);

            User::factory()->create([
                'username' => 'admin',
                'email' => 'admin@email.com',
                'password' => Hash::make('admin')
            ])->assignRole(Roles::ADMIN);
        }

        $this->call([
            SystemUserSeeder::class,
        ]);

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

        Person::factory(1)->create();
        User::factory(10)->create();
        User::factory(3)->user()->create();
        User::factory(3)->viewer()->create();
        User::factory(3)->admin()->create();
        User::factory(5)->inactive()->create();

        // Afficher les identifiants en dev
        if (app()->isLocal()) {
            echo "\n";
            echo "+----------+-------------------------+----------+\n";
            echo "| Username | Email                   | Password |\n";
            echo "+----------+-------------------------+----------+\n";
            echo "| root     | root@email.com          | root     |\n";
            echo "| admin    | admin@email.com         | admin    |\n";
            echo "| user     | user@email.com          | user     |\n";
            echo "| user2    | user2@email.com         | user2    |\n";
            echo "| viewer   | viewer@email.com        | viewer   |\n";
            echo "+----------+-------------------------+----------+\n";
            echo "\n";
        }
    }
}
