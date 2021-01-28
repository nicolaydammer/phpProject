<?php
declare(strict_types=1);

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\App;

//return when called on the file
return function (App $app) {
    $app->get('/', function (RequestInterface $request, ResponseInterface $response)
    {
       //todo: add class with the response data
    });
};