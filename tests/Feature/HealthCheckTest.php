<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class HealthCheckTest extends TestCase
{
    public function test_optimize_is_valid(): void
    {
        $exitCode = Artisan::call('optimize');
        $this->assertEquals(0, $exitCode, 'php artisan optimize a détecté des erreurs');
    }

    public function test_home_returns_200(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}