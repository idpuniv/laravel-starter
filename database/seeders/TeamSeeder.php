<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create([
            'name' => 'root',
            'label' => 'Équipe racine',
            'description' => null,
        ]);

        Team::create([
            'name' => 'system',
            'label' => 'Équipe système',
            'description' => null,
        ]);

        Team::create([
            'name' => 'admin',
            'label' => 'Équipe administrateur',
            'description' => null,
        ]);

        Team::create([
            'name' => 'default',
            'label' => 'Equipe par défaut',
            'description' => null,
        ]);
    }
}
