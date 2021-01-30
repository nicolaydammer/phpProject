<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        Twig::class => function (ContainerInterface $container) {
            $twigSettings = $container->get(SettingsInterface::class)->get('twig');

            $options = $twigSettings['options'];
            $options['cache'] = $options['cache_enabled'] ? $options['cache_path'] : false;

            return Twig::create($twigSettings['paths'], $options);
        },
        TwigMiddleware::class => function (ContainerInterface $container) {
            return TwigMiddleware::createFromContainer(
                $container->get(App::class),
                Twig::class
            );
        },
    ]);
};