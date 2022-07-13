<?php

use Application\Controller\CopyFromDeploymentToDevelopmentController;
use Application\Controller\GenerateNewApiController;
use Application\Controller\GenerateQueryResultDataStructureDefinitionFromQueryController;
use Application\Controller\GenerateSqlFileForGithubIssueController;
use Application\Controller\GenerateSqlInsertController;
use Application\Controller\GenerateTableDataGatewayServiceProgramController;
use Application\Controller\GetObjectDependenciesController;
use Application\Controller\HelpfulLinksController;
use Application\Controller\IndexController;
use Application\Controller\MoveGutterCommentsToEndOfLineController;
use Application\Controller\RPGFixedToFreeCommentColumnConverterController;
use Application\Form\GenerateQueryResultDataStructureDefinitionFromQueryForm;
use Application\Form\GenerateSqlFileForGithubIssueForm;
use Application\Form\GenerateSqlInsertForm;
use Application\Service\GetObjectDependenciesService;
use Application\Validator\SourceMemberExistsValidator;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterServiceFactory;
use Laminas\InputFilter\InputFilterPluginManager;
use Laminas\InputFilter\InputFilterPluginManagerFactory;
use Laminas\Router\Http\Literal;
use LaminasIbmiToolkit\Factory\IbmiToolkitFactory;
use ToolkitApi\Toolkit;

return [
    'controllers' => [
        'factories' => [
            CopyFromDeploymentToDevelopmentController::class => [CopyFromDeploymentToDevelopmentController::class, 'fromContainer'],
            GenerateNewApiController::class => [GenerateNewApiController::class, 'fromContainer'],
            GenerateQueryResultDataStructureDefinitionFromQueryController::class => [GenerateQueryResultDataStructureDefinitionFromQueryController::class, 'fromContainer'],
            GenerateSqlFileForGithubIssueController::class => [GenerateSqlFileForGithubIssueController::class, 'fromContainer'],
            GenerateSqlInsertController::class => [GenerateSqlInsertController::class, 'fromContainer'],
            GenerateTableDataGatewayServiceProgramController::class => [GenerateTableDataGatewayServiceProgramController::class, 'fromContainer'],
            GetObjectDependenciesController::class => [GetObjectDependenciesController::class, 'fromContainer'],
            HelpfulLinksController::class => [HelpfulLinksController::class, 'fromContainer'],
            IndexController::class => [IndexController::class, 'fromContainer'],
            MoveGutterCommentsToEndOfLineController::class => [MoveGutterCommentsToEndOfLineController::class, 'fromContainer'],
            RPGFixedToFreeCommentColumnConverterController::class => [RPGFixedToFreeCommentColumnConverterController::class, 'fromContainer'],
        ],
    ],
    'form_elements' => [
        'factories' => [
            GenerateQueryResultDataStructureDefinitionFromQueryForm::class => [GenerateQueryResultDataStructureDefinitionFromQueryForm::class, 'fromContainer'],
            GenerateSqlFileForGithubIssueForm::class => [GenerateSqlFileForGithubIssueForm::class, 'fromContainer'],
            GenerateSqlInsertForm::class => [GenerateSqlInsertForm::class, 'fromContainer'],
        ],
    ],
    'router' => [
        'routes' => [
            'index' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'helpful-links' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/helpful-links',
                    'defaults' => [
                        'controller' => HelpfulLinksController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'generate-query-result-data-structure-definition-from-query' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/generate-query-result-data-structure-definition-from-query',
                    'defaults' => [
                        'controller' => GenerateQueryResultDataStructureDefinitionFromQueryController::class,
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
            'generate-table-data-gateway-service-program' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/generate-table-data-gateway-service-program',
                    'defaults' => [
                        'controller' => GenerateTableDataGatewayServiceProgramController::class,
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
            'rpg-fixed-to-free-comment-column-converter' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/rpg-fixed-to-free-comment-column-converter',
                    'defaults' => [
                        'controller' => RPGFixedToFreeCommentColumnConverterController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'move-gutter-comments-to-end-of-line' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/move-gutter-comments-to-end-of-line',
                    'defaults' => [
                        'controller' => MoveGutterCommentsToEndOfLineController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'generate-new-api' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/generate-new-api',
                    'defaults' => [
                        'controller' => GenerateNewApiController::class,
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
            Toolkit::class => IbmiToolkitFactory::class,
        ],
    ],
    'validators' => [
        'factories' => [
            SourceMemberExistsValidator::class => [SourceMemberExistsValidator::class, 'fromContainer'],
        ]
    ],
    'view_manager' => [
        'doctype'                  => 'HTML5',
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
            'form-element'            => __DIR__ . '/../view/partial/form-element.phtml',
        ],
        'template_path_stack' => [
            'application' => __DIR__ . '/../view',
        ],
    ],
];