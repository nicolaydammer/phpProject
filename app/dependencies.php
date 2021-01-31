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
        //add twig to the dependency container
        Twig::class => function (ContainerInterface $container) {
            //get settings for twig from the settings file
            $twigSettings = $container->get(SettingsInterface::class)->get('twig');

            //load the settings for twig
            $options = $twigSettings['options'];
            $options['cache'] = $options['cache_enabled'] ? $options['cache_path'] : false;

            //create the twig instance with the settings
            return Twig::create($twigSettings['paths'], $options);
        },
        TwigMiddleware::class => function (ContainerInterface $container) {
        //create the twig middleware with settings
            return TwigMiddleware::createFromContainer(
                $container->get(App::class),
                Twig::class
            );
        },
    ]);
};