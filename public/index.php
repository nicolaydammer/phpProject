<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\ResponseEmitter;

//import autoloader
require __DIR__ . '/../vendor/autoload.php';

//instantiate containerbuilder to build up the container
$containerBuilder = new ContainerBuilder();

//add settings to the containerbuilder
$settings = require __DIR__ . '/../app/settings.php';
$settings($containerBuilder);

//add dependencies to the containerbuilder
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($containerBuilder);

//build the container
$container = $containerBuilder->build();

//put container into the slim application
AppFactory::setContainer($container);

//create slim application
$app = AppFactory::create();

//set the routes in the application
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

//set the middleware in the application
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

//todo: create display error details, request object, error handler, shutdown handler

$app->addRoutingMiddleware();

//todo: error middleware

//run the application
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);