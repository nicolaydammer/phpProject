<?php
declare(strict_types=1);

namespace App\Application\ResponseEmitter;

use Psr\Http\Message\ResponseInterface;

class ResponseEmitter extends \Slim\ResponseEmitter
{
    public function emit(ResponseInterface $response): void
    {
        //create the origin to only our site
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        //set response headers
        $response = $response
            //if we ever wanted to use credentials its possible
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            //set the orgin to only our site
            ->withHeader('Access-Control-Allow-Origin', $origin)
            //allow only a set part of headers
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            //allow only a set part of methods to be used for routes
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE')
            //turn off caching for developing
            ->withHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            //caching is off no need to check it
            ->withAddedHeader('Cache-Control', 'post-check=0, pre-check=0')
            ->withHeader('Pragma', 'no-cache');

        //if there is still content in the output buffer, clean it
        if (ob_get_contents()) {
            ob_clean();
        }

        //send response to user
        parent::emit($response);
    }
}