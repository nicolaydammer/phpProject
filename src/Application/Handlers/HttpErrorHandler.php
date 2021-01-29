<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler;
use Throwable;

class HttpErrorHandler extends ErrorHandler
{
    protected function respond(): ResponseInterface
    {
        $exception = $this->exception;
        //set default response
        $statusCode = 500;
        $error = new ActionError(
            ActionError::SERVER_ERROR,
            'An internal error has occurred while processing your request.'
        );

        //when a exception is instance of HttpException, set the right statuscode, description and type of error
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getCode();
            $error->setDescription($exception->getMessage());

            //set type of exception
            if ($exception instanceof HttpNotFoundException) {
                $error->setType(ActionError::RESOURCE_NOT_FOUND);
            } elseif ($exception instanceof HttpMethodNotAllowedException) {
                $error->setType(ActionError::NOT_ALLOWED);
            } elseif ($exception instanceof HttpUnauthorizedException) {
                $error->setType(ActionError::UNAUTHENTICATED);
            } elseif ($exception instanceof HttpForbiddenException) {
                $error->setType(ActionError::INSUFFICIENT_PRIVILEGES);
            } elseif ($exception instanceof HttpBadRequestException) {
                $error->setType(ActionError::BAD_REQUEST);
            } elseif ($exception instanceof HttpNotImplementedException) {
                $error->setType(ActionError::NOT_IMPLEMENTED);
            }
        }

//when it's not an httpException and it is throwable and the displayErrorDetails is set to true, show error message
        if (
            !($exception instanceof HttpException)
            && $exception instanceof Throwable
            && $this->displayErrorDetails
        ) {
            $error->setDescription($exception->getMessage());
        }

        //create data object for response
        $payload = new ActionPayload($statusCode, null, $error);
        //encode to json
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        //create response with title of status code
        $response = $this->responseFactory->createResponse($statusCode);
        //set body of the response
        $response->getBody()->write($encodedPayload);

        //return response with json http header
        return $response->withHeader('Content-Type', 'application/json');
    }
}