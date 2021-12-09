<?php
use Symfony\Component\Console\Application;
use Tools\ApplicationFactory;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterServiceFactory;

return [
    'factories' => [
        Application::class => ApplicationFactory::class,
        Adapter::class => AdapterServiceFactory::class,
        'config' => function (\Psr\Container\ContainerInterface $container) {
            return require __DIR__ . '/local.php';
        },
        \Symfony\Component\Console\Helper\HelperSet::class => \Tools\HelperSetFactory::class,
        \Tools\Command\CopyData::class => [\Tools\Command\CopyData::class, 'fromContainer'],
        \Tools\Command\GenerateSqlInsert::class => [\Tools\Command\GenerateSqlInsert::class, 'fromContainer'],
    ],
];