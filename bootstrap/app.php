<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then:function ($router) {
            Route::prefix('api/v1')
                ->middleware([
                    'api'
                ])
                ->group(function ($router) {
                    require app_path('Components/Auth/Resource/routes.php');
                    require app_path('Components/Content/Resource/routes.php');
                    require app_path('Components/Recipe/Resource/routes.php');
                    require app_path('Components/MealPlanner/Resource/routes.php');
                    require base_path('routes/api.php');
                });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {


        // Render specific exceptions (mapping Handler.php logic here)
        $exceptions->renderable(function (\App\Libraries\Base\Exception\InternalApiException $e, $request) {
            $stack = [];
            if (env('APP_DEBUG') && $e->getCode() == \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR) {
                $stack = ['stackTrace' => $e->getStackTrace()];
            }
            return response()->json(array_merge($e->toArray(), $stack), $e->getCode());
        });

        $exceptions->renderable(function (\App\Libraries\Base\Exception\BaseException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => []
            ], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        $exceptions->renderable(function (\App\Libraries\Base\Exception\ApiSimpleException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => []
            ], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
        });

        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'data' => []
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please log in.',
                'data' => []
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Resource not found.',
                'data' => []
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        });



        // Unauthenticated response mapping
        $exceptions->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Please log in.',
                'data' => []
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*'))
            {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'data' => []
                ], \Symfony\Component\HttpFoundation\Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();

