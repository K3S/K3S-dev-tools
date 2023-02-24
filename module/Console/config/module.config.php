<?php
return [
    'service_manager' => [
        'factories' => [
            \Laminas\Db\Adapter\Adapter::class => \Laminas\Db\Adapter\AdapterServiceFactory::class,
            \Symfony\Component\Console\Application::class => \Console\ApplicationFactory::class,
            \Symfony\Component\Console\Helper\HelperSet::class => \Console\HelperSetFactory::class,
            \Console\Command\PromoteToTesting::class => [\Console\Command\PromoteToTesting::class, 'fromContainer'],
            \Console\Command\PromoteToTestingAndCompile::class => [\Console\Command\PromoteToTestingAndCompile::class, 'fromContainer'],
        ],
    ],
];