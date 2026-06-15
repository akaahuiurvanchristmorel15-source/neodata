<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\CheckCompanyActive::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 1. Intercepte l'échec brut de connexion PDO (Ex: MySQL éteint)
        $exceptions->render(function (\PDOException $e) {
            return response()->view('errors.database', [], 500);
        });

        // 2. Intercepte les coupures en cours de route via QueryException
        $exceptions->render(function (QueryException $e) {
            return response()->view('errors.database', [], 500);
        });
    })->create();
