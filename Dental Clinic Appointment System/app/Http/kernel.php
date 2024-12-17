<?php
namespace App\Http;

use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\AdminMiddleware;
use Symfony\Component\HttpKernel\HttpKernel;

class Kernel extends HttpKernel
{
    // Other properties and methods...

    protected $routeMiddleware = [
        // Other middlewares...
        'admin' => AdminMiddleware::class,
    ];
}