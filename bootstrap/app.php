<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(

        using: function (\Illuminate\Routing\Router $router) {

            $router->middleware('api')

                ->prefix('api')

                ->group(base_path('routes/api.php'));

            $router->middleware('web')

                ->group(base_path('routes/web.php'));

            $router->middleware('web', 'auth')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            $router->middleware('web', 'auth')
                ->prefix('students')
                ->name('student.')
                ->group(base_path('routes/students.php'));
        },

    )
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {})->create();
