<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrar middleware personalizado para verificación de roles
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdministrador::class,
            'coordinador' => \App\Http\Middleware\CheckCoordinador::class,
            'cliente' => \App\Http\Middleware\CheckCliente::class,
            'estudiante' => \App\Http\Middleware\CheckEstudiante::class,
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
