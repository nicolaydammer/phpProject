<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected $request;
    protected $response;
    protected $args;

    //when this class is triggered get the input via dependency injection
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
    }

    //needs to be implemented
    abstract protected function action(): ResponseInterface;

    //get data from forms
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request, 'Bad JSON input');
        }

        return $input;
    }

    //get specific data from request
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument {$name}.");
        }
        return $this->args[$name];
    }

    //send response with data
    protected function respondWithData($data = null, int $statusCode = 200): ResponseInterface
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    //send a response and write it on the page directly
    protected function respond(ActionPayload $payload): ResponseInterface
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT);
        $this->response->getBody()->write($json);

        return $this->response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($payload->getStatusCode());
    }
}