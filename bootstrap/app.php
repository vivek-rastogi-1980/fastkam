<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'franchise/payment-callback',
            'franchise/payment-callback*',
            'wallet/payment-callback',
            'wallet/payment-callback*',
            'txnResult',
            'txnResult*',
        ]);
        
        $middleware->alias([
            'api.auth' => \App\Http\Middleware\AuthenticateApiKey::class,
            'admin' => \App\Http\Middleware\EnsureAdminUser::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
