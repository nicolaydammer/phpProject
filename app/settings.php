<?php
declare(strict_types=1);

use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        //create a new class in the container which contains our settings
        SettingsInterface::class => function () {
            return new Settings([
                //makes it show errors
                'displayErrorDetails' => true,
                //twig settings
                'twig' => [
                    //path of where all the templates are located
                    'paths' => [
                        __DIR__ . '/../src/Templates',
                    ],
                    //extra options for twig
                    'options' => [
                        // Should be set to true in production
                        'cache_enabled' => false,
                        'cache_path' => __DIR__ . '/../tmp/twig',
                        'debug' => true,
                    ],
                ],
                'doctrine' => [
                    // if true, metadata caching is forcefully disabled
                    'dev_mode' => true,

                    // path where the compiled metadata info will be cached
                    // make sure the path exists and it is writable
                    'cache_dir' => __DIR__ . '/../cache/doctrine',

                    // you should add any other path containing annotated entity classes
                    'metadata_dirs' => [__DIR__ . '/../src/Domain'],

                    // Database connection details
                    'connection' => [
                        'driver' => 'pdo_mysql',
                        'host' => getenv('HOST'),
                        'port' => getenv('PORT'),
                        'dbname' => getenv('DBNAME'),
                        'user' => getenv('USER'),
                        'password' => getenv('PASSWORD'),
                        'charset' => 'utf-8'
                    ]
                ],
            ]);
        }
    ]);
};