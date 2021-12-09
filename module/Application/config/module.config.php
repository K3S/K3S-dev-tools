<?php

use Application\Controller\CopyFromDeploymentToDevelopmentController;
use Application\Controller\GenerateSqlFileForGithubIssueController;
use Application\Controller\GenerateSqlInsertController;
use Application\Controller\GetObjectDependenciesController;
use Application\Form\GenerateSqlFileForGithubIssueForm;
use Application\Service\GetObjectDependenciesService;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterServiceFactory;
use Laminas\InputFilter\InputFilterPluginManager;
use Laminas\InputFilter\InputFilterPluginManagerFactory;
use Laminas\Router\Http\Literal;

return [
    'controllers' => [
        'factories' => [
            CopyFromDeploymentToDevelopmentController::class => [CopyFromDeploymentToDevelopmentController::class, 'fromContainer'],
            GenerateSqlFileForGithubIssueController::class => [GenerateSqlFileForGithubIssueController::class, 'fromContainer'],
            GenerateSqlInsertController::class => [GenerateSqlInsertController::class, 'fromContainer'],
            GetObjectDependenciesController::class => [GetObjectDependenciesController::class, 'fromContainer'],
        ],
    ],
    'form_elements' => [
        'factories' => [
            GenerateSqlFileForGithubIssueForm::class => [GenerateSqlFileForGithubIssueForm::class, 'fromContainer'],
        ],
    ],
    'router' => [
        'routes' => [
            'homepage' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => GenerateSqlFileForGithubIssueController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'generate-sql-file-for-github-issue' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/generate-sql-file-for-github-issue',
                    'defaults' => [
                        'controller' => GenerateSqlFileForGithubIssueController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'get-object-dependencies' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/get-object-dependencies',
                    'defaults' => [
                        'controller' => GetObjectDependenciesController::class,
                        'action' => 'index',
                    ]
                ]
            ],
            'generate-sql-insert' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/generate-sql-insert',
                    'defaults' => [
                        'controller' => GenerateSqlInsertController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'copy-from-deployment-to-development' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/copy-from-deployment-to-development',
                    'defaults' => [
                        'controller' => CopyFromDeploymentToDevelopmentController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            Adapter::class => AdapterServiceFactory::class,
            GetObjectDependenciesService::class => [GetObjectDependenciesService::class, 'fromContainer'],
            InputFilterPluginManager::class => InputFilterPluginManagerFactory::class,
        ],
    ],
    'view_manager' => [
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/500',
        'layout'                   => 'layout/layout',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/layout'           => __DIR__ . '/../view/layout/error.phtml',
            'error/400'               => __DIR__ . '/../view/error/400.phtml',
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/500'               => __DIR__ . '/../view/error/500.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            'application' => __DIR__ . '/../view',
        ],
    ],
];