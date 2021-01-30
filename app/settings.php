<?php
declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            return new Settings([
                'displayErrorDetails' => true,
                'twig' => [
                    'paths' => [
                        __DIR__ . '/../src/Templates',
                    ],
                    'options' => [
                        // Should be set to true in production
                        'cache_enabled' => false,
                        'cache_path' => __DIR__ . '/../tmp/twig',
                    ],
                ],
            ]);
        }
    ]);
};