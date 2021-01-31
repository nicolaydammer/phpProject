<?php
declare(strict_types=1);

namespace App\Application\Actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;

abstract class Action
{
    protected ServerRequestInterface $request;
    protected ResponseInterface $response;
    protected array $args;

    //when this class is triggered get the input via dependency injection
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        return $this->action();
    }

    //needs to be implemented
    abstract protected function action(): ResponseInterface;

    //get data from forms
    // note: doesn't work for files
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

    //prepare data for response
    protected function respondWithData($data = null, int $statusCode = 200): ResponseInterface
    {
        $payload = new ActionPayload($statusCode, $data);
        return $this->respond($payload);
    }

    //respond the data on the page
    protected function respond(ActionPayload $payload): ResponseInterface
    {
        return $this->response
            ->withStatus($payload->getStatusCode());
    }
}