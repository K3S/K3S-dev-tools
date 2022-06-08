<?php
declare(strict_types=1);

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Factory;
use Laminas\Form\FormInterface;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Form\Element\Csrf;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Renderer\RendererInterface;

final class GenerateNewApiController extends AbstractActionController
{
    private RendererInterface $renderer;
    private FormInterface $form;

    /**
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;

        $this->form = (new Factory())->createForm([
            'hydrator' => ArraySerializableHydrator::class,
            'elements' => [
                [
                    'spec' => [
                        'type' => Text::class,
                        'name' => 'apiRootName',
                        'options' => [
                            'label' => 'API Root Name',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Text::class,
                        'name' => 'description',
                        'options' => [
                            'label' => 'Description',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Text::class,
                        'name' => 'author',
                        'options' => [
                            'label' => 'Author',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Text::class,
                        'name' => 'gitHubIssueNumber',
                        'options' => [
                            'label' => 'GitHub Issue Number',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Csrf::class,
                        'name' => 'csrf',
                    ],
                ],
                [
                    'spec' => [
                        'type' => Submit::class,
                        'name' => 'submit',
                        'attributes' => [
                            'value' => 'Submit',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Textarea::class,
                        'name' => 'rpgOutput',
                        'attributes' => [
                            'rows' => '30',
                            'class' => 'form-control',
                            'style' => 'width:100%; font-family: monospace, monospace; white-space: pre-wrap; font-size: .85em; height:20em',
                        ],
                        'options' => [
                            'label' => 'RPG',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Textarea::class,
                        'name' => 'clOutput',
                        'attributes' => [
                            'rows' => '30',
                            'class' => 'form-control',
                            'style' => 'width:100%; font-family: monospace, monospace; white-space: pre-wrap; font-size: .85em; height:20em',
                        ],
                        'options' => [
                            'label' => 'CL',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Textarea::class,
                        'name' => 'tsOutput',
                        'attributes' => [
                            'rows' => '30',
                            'class' => 'form-control',
                            'style' => 'width:100%; font-family: monospace, monospace; white-space: pre-wrap; font-size: .85em; height:20em',
                        ],
                        'options' => [
                            'label' => 'Test',
                        ],
                    ],
                ],
            ],
        ]);

    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(PhpRenderer::class)
        );
    }

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/generate-new-rpg-api.phtml');

        $apiOutput = '';

        if ($this->getRequest()->isPost())
        {
            $input = $this->params()->fromPost();
            $this->form->setData($input);

            if (!$this->form->isValid()) {
                var_dump('Invalid form');
                var_dump($this->form->getMessages());
                die();
            }

            $rpgApiViewModel = new ViewModel();
            $rpgApiViewModel->setTemplate('application/templates/rpg-api-template.phtml');
            $rpgApiViewModel->setVariables([
                'apiRootName' => $input['apiRootName'],
                'description' => $input['description'],
                'author' => $input['author'],
                'currentDate' => new \DateTimeImmutable('now'),
                'githubIssueNumber' => $input['gitHubIssueNumber'],
            ]);
            $rpgApiOutput = $this->renderer->render($rpgApiViewModel);
            $this->form->get('rpgOutput')->setValue($rpgApiOutput);


            $clViewModel = new ViewModel();
            $clViewModel->setTemplate('application/templates/cl-api-template.phtml');
            $clViewModel->setVariables([
                'apiRootName' => $input['apiRootName'],
                'description' => $input['description'],
                'author' => $input['author'],
                'currentDate' => new \DateTimeImmutable('now'),
                'githubIssueNumber' => $input['gitHubIssueNumber'],
            ]);
            $this->form->get('clOutput')->setValue($this->renderer->render($clViewModel));
        }

        $viewModel->setVariables([
            'form' => $this->form,
            'apiOutput' => $apiOutput,
        ]);


        return $viewModel;
    }
}