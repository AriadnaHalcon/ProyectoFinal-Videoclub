<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $levels = [
        // ...
    ];

    protected $dontReport = [
        // ...
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        // Error 403 para Inertia
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException && $exception->getStatusCode() === 403) {
            if ($request->header('X-Inertia')) {
                return inertia('Errors/Error403')->toResponse($request)->setStatusCode(403);
            }
        }
        return parent::render($request, $exception);
    }
}