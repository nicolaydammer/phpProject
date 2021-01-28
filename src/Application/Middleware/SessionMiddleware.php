<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class SessionMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        //check if authorization is set
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            session_start();
            //get session
            $request = $request->withAttribute('session', $_SESSION);
        }

        return $handler->handle($request);
    }
}