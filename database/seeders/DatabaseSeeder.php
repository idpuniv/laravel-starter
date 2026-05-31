<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
            TeamSeeder::class,
            RolePermissionSeeder::class,
            CountrySeeder::class,
        ]);

        $teamId = DB::table('teams')->where('name', 'root')->value('id');
        setPermissionsTeamId($teamId);
        $user = User::factory()->create([
            'username' => 'root',
            'email' => 'root@email.com',
            'password' => Hash::make('root'),
        ])->assignTeam('root')->assignRole(Roles::ROOT);


        $this->call([
            SystemUserSeeder::class,
        ]);

        // $teamId = DB::table('teams')->where('name', 'admin')->value('id');
        // setPermissionsTeamId($teamId);
        
        // User::factory()->create([
        //     'username' => 'admin',
        //     'email' => 'admin@email.com',
        //     'password' => Hash::make('admin')
        // ])->assignTeam('admin')->assignRole(Roles::ADMIN);

        // $teamId = DB::table('teams')->where('name', 'default')->value('id');
        // setPermissionsTeamId($teamId);
        // User::factory()->create([
        //     'username' => 'user',
        //     'email' => 'user@email.com',
        //     'password' => Hash::make('user')
        // ])->assignTeam('default')->assignRole(Roles::USER);

        // User::factory()->create([
        //     'username' => 'user2',
        //     'email' => 'user2@email.com',
        //     'password' => Hash::make('user2')
        // ])->assignTeam('default')->assignRole(Roles::USER);

        // Person::factory(1)->create();
        // User::factory(10)->create();
        // // User::factory(3)->user()->create();
        // // User::factory(3)->admin()->create();
        // User::factory(5)->inactive()->create();

        // setPermissionsTeamId(null);
    }
}
