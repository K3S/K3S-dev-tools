<?php

use Build\Controller\IndexController;
use Laminas\Router\Http\Literal;

return [
    'controllers' => [
        'factories' => [
            IndexController::class => [IndexController::class, 'fromContainer'],
        ],
    ],
    'router' => [
        'routes' => [
            'homepage' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/build',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'build' => __DIR__ . '/../view',
        ],
    ],
];