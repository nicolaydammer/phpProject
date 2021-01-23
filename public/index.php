<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

//import autoloader
require __DIR__ . '/../vendor/autoload.php';

//create new DI container
$container = new Container();

//put container into the slim application
AppFactory::setContainer($container);

//create slim application
$app = AppFactory::create();

//run the application
$app->run();