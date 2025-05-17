<?php

use App\Http\Middleware\MerchantMiddleware;
use App\Http\Middleware\ShopProductMiddleware;
use App\Http\Middleware\WalletAddressMiddleware;
use App\Http\Middleware\WalletMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\{
    QueryException,
    Eloquent\ModelNotFoundException,
    UniqueConstraintViolationException,
};
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,

            'type.merchant' => MerchantMiddleware::class,
            'shop_must_belong_to_user' => ShopProductMiddleware::class,
            'wallet_must_belong_to_user' => WalletMiddleware::class,
            'address_must_belong_to_wallet' => WalletAddressMiddleware::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {

            // Return JSON responses for API routes
            if ($request->is('api/*')) {

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => 'Validation error',
                        'errors' => $e->errors(),
                    ], 422);
                }


                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => explode('\\', $e->getModel())[2] . ' Not Found.',
                    ], 404);
                }

                if ($e instanceof NotFoundHttpException) {
                    return response()->json(['message' => explode(']', explode('\\', $e->getMessage())[2])[0] . ' Not Found.'], 404);
                }

                if ($e instanceof AuthorizationException) {
                    return response()->json([
                        'message' => 'This action is unauthorized.',
                    ], 403);
                }

                if ($e instanceof UniqueConstraintViolationException) {
                    return response()->json([
                        'message' => 'This record is already exists.',
                    ], 400);
                }

                /*if ($e instanceof QueryException) {
                    return response()->json([
                        'message' => 'Unknown sql error.',
                    ], 400);
                }*/

                if ($e instanceof RouteNotFoundException) {
                    return response()->json([
                        'message' => 'unauthenticated',
                    ], 401);
                }

                if ($e instanceof JobTimeoutException) {
                    // Log the job timeout exception
                    Log::error('JobTimeoutException: ' . $e->getMessage());
                    // Return an appropriate response (e.g., 504 Gateway Timeout)
                    return response()->json([
                        'message' => 'Something went wrong, please try again later.'
                    ], 504);
                }

                // return response()->json(get_class($e));
            }
        });
    })->create();
