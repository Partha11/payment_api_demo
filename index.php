<?php

use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\Payments\PaymentController;

use function FastRoute\simpleDispatcher;

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/vendor/autoload.php';

$dispatcher = simpleDispatcher(function (FastRoute\RouteCollector $routes) {

    $routes->addRoute('GET', '/', function () {
        echo json_encode(
            ["message" => "Hello World"
        ]);
    });

    $routes->addRoute('GET', '/invoices', [InvoiceController::class, 'index']);
    $routes->addRoute('GET', '/invoices/{id}', [InvoiceController::class, 'find']);

    $routes->addRoute('POST', '/invoices', [InvoiceController::class, 'create']);
    $routes->addRoute('POST', '/payments', [PaymentController::class, 'create']);
});

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if ($pos = strpos($uri, '?') !== false) {

    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);
$route = $dispatcher->dispatch($method, $uri);

switch ($route[0]) {

    case FastRoute\Dispatcher::NOT_FOUND:

        echo json_encode([
            "message" => "Not Found"
        ]);
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:

        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:

        $handler = $route[1];
        $vars = $route[2];

        if (is_array($handler)) {

            call_user_func_array([new $handler[0], $handler[1]], $vars);

        } else {

            call_user_func_array($handler, $vars);
        }

        break;

    default:
        break;
}
