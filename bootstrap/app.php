<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi(); // important for sanctum for use current session
        $middleware->alias([
            '2fa' => \App\Http\Middleware\TwoFactorMiddleware::class,
            'audit' => \App\Http\Middleware\AuditMiddleware::class,
            'auth.404'  => \App\Http\Middleware\Authenticate::class,
        ]);

        // Ajouter le middleware au groupe web
        $middleware->web(append: [
            \App\Http\Middleware\SetSessionLifetime::class,
        ]);

    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('audit:clean 90')->daily();

        // Autres schedules possibles
        // $schedule->command('queue:work --stop-when-empty')->everyMinute();
        // $schedule->command('backup:run')->daily()->at('02:00');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) {
            if (app()->isProduction()) {
                throw new NotFoundHttpException();
            }
        });
        //
    })->create();
