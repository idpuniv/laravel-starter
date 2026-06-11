<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class HealthCheckTest extends TestCase
{

    public function test_routes_are_valid()
    {
        $exitCode = Artisan::call('route:list');
        $this->assertEquals(0, $exitCode, 'Route:list a détecté des erreurs');
    }

    public function test_config_is_valid()
    {
        $exitCode = Artisan::call('config:clear');
        $this->assertEquals(0, $exitCode, 'Configuration invalide');
    }

    public function test_views_are_valid()
    {
        $exitCode = Artisan::call('view:cache');
        $this->assertEquals(0, $exitCode, 'Vues Blade invalides');
    }

    public function test_database_connection_works()
    {
        try {
            DB::connection()->getPdo();
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail('Connexion BDD échouée : ' . $e->getMessage());
        }
    }
}