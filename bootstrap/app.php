<?php

use App\Exceptions\Domain\Company\CompanyActiveException;
use App\Exceptions\Domain\Company\CompanyNotFoundException;
use App\Exceptions\Domain\Company\DuplicateCompanyException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(fn(CompanyNotFoundException $e, $request) => response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND));
        $exceptions->renderable(fn(DuplicateCompanyException $e, $request) => response()->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT));
        $exceptions->renderable(fn(CompanyActiveException $e, $request) => response()->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT));
    })->create();
