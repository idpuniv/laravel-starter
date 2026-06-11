<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
use Illuminate\Database\Seeder;

class SystemUserSeeder extends Seeder
{
    public function run(): void
    {

         $systemPerson = Person::create([
            'first_name' => 'System',
            'last_name' => 'System',
        ]);
        User::updateOrCreate(
            ['email' => 'system@local.local'],
            [
                'username' => 'system',
                'password' => bcrypt(''),
                'is_system' => true,
                'person_id' => $systemPerson->id,
                'system_type' => 'system'
            ]
        );
    }
}