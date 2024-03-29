<?php
declare(strict_types=1);

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

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
$callableResolver = $app->getCallableResolver();

//set the middleware in the application
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

//set the routes in the application
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

//set display error details
$displayErrorDetails = $container->get(SettingsInterface::class)->get('displayErrorDetails');

//create request object
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

//set error handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

//set shutdown handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

$app->addRoutingMiddleware();

//set error handling for middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

//run the application
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);