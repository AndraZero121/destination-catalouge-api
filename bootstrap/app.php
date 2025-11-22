<?php

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        api: __DIR__ . "/../routes/api.php",
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
        then: function () {
            Route::middleware("api")->group(
                base_path("routes/api.php"),
            );
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend: [
            ForceJsonResponse::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            "api" => "throttle:api",
            "auth" => \Illuminate\Auth\Middleware\Authenticate::class,
        ]);

        $middleware->redirectGuestsTo(fn () => null);

        $middleware->validateCsrfTokens(except: ["api/*"]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        "message" => "Unauthenticated.",
                    ],
                    401,
                );
            }
        });

        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        "message" => "Validation failed",
                        "errors" => $e->errors(),
                    ],
                    422,
                );
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        "message" => "Resource not found",
                    ],
                    404,
                );
            }
        });

        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        "message" => "Server error",
                        "error" => config("app.debug")
                            ? $e->getMessage()
                            : "Something went wrong",
                    ],
                    500,
                );
            }
        });
    })
    ->create();
