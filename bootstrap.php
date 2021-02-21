<?php

// bootstrap.php

use App\Application\Settings\SettingsInterface;
use DI\Container;
use DI\ContainerBuilder;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/vendor/autoload.php';

//instantiate containerbuilder to build up the container
$containerBuilder = new ContainerBuilder();

//add settings to the containerbuilder
$settings = require __DIR__ . '/app/settings.php';
$settings($containerBuilder);

$containerBuilder->addDefinitions([
    EntityManager::class => function (ContainerInterface $container): EntityManager {
        $doctrineSettings = $container->get(SettingsInterface::class)->get('doctrine');
        $config = Setup::createAnnotationMetadataConfiguration(
            $doctrineSettings['metadata_dirs'],
            $doctrineSettings['dev_mode']
        );

        $config->setMetadataDriverImpl(
            new AnnotationDriver(
                new AnnotationReader,
                $doctrineSettings['metadata_dirs']
            )
        );

        $config->setMetadataCacheImpl(
            new FilesystemCache(
                $doctrineSettings['cache_dir']
            )
        );

        return EntityManager::create(
            $doctrineSettings['connection'],
            $config
        );
    }
]);

return $containerBuilder->build();