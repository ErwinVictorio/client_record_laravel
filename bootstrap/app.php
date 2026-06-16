<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SalesManMiddleware;
use App\Http\Middleware\CashierMidllewire;
use App\Http\Middleware\SuperAdmin;
use App\Http\Middleware\AfterSalesMiddleware;
use App\Http\Middleware\WarehouseMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // register the middlware and give alies each middleware
        $middleware->alias([
          'isAdmin' => AdminMiddleware::class,
           'isSalesman' => SalesManMiddleware::class,
           'isCashier' => CashierMidllewire::class,
            'isSuperAdmin' => SuperAdmin::class,
            'isAfterSales' => AfterSalesMiddleware::class,
            'isWarehouse' => WarehouseMiddleware::class
        ]);
       
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
