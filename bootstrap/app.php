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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);

        $middleware->redirectUsersTo(fn () => route('trechos.index')); 

        // Validaçâo da tela de login caso o usuário não esteja logado.
        $middleware->redirectGuestsTo(function () {
            session()->flash('error', 'É necessário estar logado para acessar esta área!');
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
