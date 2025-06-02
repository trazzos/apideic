<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {

            if ($request->is('api/*') || $request->expectsJson()) {

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => 'Los datos proporcionados no son válidos.',
                        'errors' => $e->errors(),
                        'code' => 422,
                    ], 422);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'message' => $e->getMessage() ?? 'No autenticado. Por favor, inicia sesión.',
                        'code' => 401,
                    ], 401);
                }

                if ($e instanceof AuthorizationException) {
                    return response()->json([
                        'message' => 'No autorizado para realizar esta acción.',
                        'code' => 403,
                    ], 403);
                }

                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => 'La ruta solicitada no existe.',
                        'code' => 'route_not_found',
                    ], 404);
                }

                // Para cualquier otra excepción no manejada, devolver un error genérico 500
                // En un entorno de producción, es crucial no exponer detalles internos
                if (config('app.debug')) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'code' => 500,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ], 500);
                } else {
                    return response()->json([
                        'message' => 'Ocurrió un error inesperado en el servidor.',
                        'code' => 500,
                    ], 500);
                }
            }
            return false;
        });
    })->create();
