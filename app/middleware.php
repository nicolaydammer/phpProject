<?php
declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use Slim\App;

return function (App $app) {
    // so we have access to $_SESSION variables
    $app->add(SessionMiddleware::class);
};