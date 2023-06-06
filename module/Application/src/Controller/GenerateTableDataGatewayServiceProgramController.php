<?php

namespace Application\Controller;

use Application\Form\GenerateTableDataGatewayServiceProgramForm;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Factory;
use Laminas\Form\Form;
use Laminas\Form\FormElementManager;
use Laminas\Form\FormInterface;
use Laminas\Hydrator\ArraySerializableHydrator;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Renderer\RendererInterface;
use ToolkitApi\Toolkit;
use ToolkitApi\ToolkitInterface;

final class GenerateTableDataGatewayServiceProgramController extends AbstractActionController
{
    private FormInterface $form;
//    private ToolkitInterface $toolkit;
    private RendererInterface $renderer;

    /** @var AdapterInterface|Adapter */
    private AdapterInterface $adapter;

    /**
     * @param FormInterface $generateTableDataGatewayServiceProgramForm
     * @param RendererInterface $renderer
     * @param AdapterInterface|Adapter $adapter
     */
    public function __construct(
        FormInterface $generateTableDataGatewayServiceProgramForm,
        RendererInterface $renderer,
        AdapterInterface $adapter
    ) {
        $this->form = $generateTableDataGatewayServiceProgramForm;
//        $this->toolkit = $toolkit;

        $this->renderer = $renderer;

        $this->form = (new Factory())->createForm([
            'hydrator' => ArraySerializableHydrator::class,
            'elements' => [
                [
                    'spec' => [
                        'type' => Text::class,
                        'name' => 'library',
                        'options' => [
                            'label' => 'Library',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Text::class,
                        'name' => 'table_name',
                        'options' => [
                            'label' => 'Table',
                        ],
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
                        'name' => 'serviceProgram',
                        'options' => [
                            'label' => 'Service Program',
                        ],
                        'attributes' => [
                            'rows' => '30',
                            'class' => 'form-control',
                            'style' => 'width:100%; font-family: monospace, monospace; white-space: pre-wrap; font-size: .85em; height:20em',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Textarea::class,
                        'name' => 'binderSignature',
                        'options' => [
                            'label' => 'Binder Signature',
                        ],
                        'attributes' => [
                            'rows' => '30',
                            'class' => 'form-control',
                            'style' => 'width:100%; font-family: monospace, monospace; white-space: pre-wrap; font-size: .85em; height:20em',
                        ],
                    ],
                ],
                [
                    'spec' => [
                        'type' => Textarea::class,
                        'name' => 'prototypeDefinitions',
                        'options' => [
                            'label' => 'Prototype Definition',
                        ],
                        'attributes' => [
                            'rows' => '30',
                            'class' => 'form-control',
                            'style' => 'width:100%; font-family: monospace, monospace; white-space: pre-wrap; font-size: .85em; height:20em',
                        ],
                    ],
                ],
            ]
        ]);

        $this->adapter = $adapter;
    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(FormElementManager::class)->get(GenerateTableDataGatewayServiceProgramForm::class),
            $container->get(PhpRenderer::class),
            $container->get(Adapter::class)
//            $container->get(Toolkit::class)
        );
    }

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/generate-table-data-gateway-service-program.phtml');

        $request = $this->getRequest();

        if ($request->isPost()) {

            $this->form->setData($this->params()->fromPost());
            if (!$this->form->isValid()) {


            }

            $tableRootName = trim($this->params()->fromPost('table_name'), 'K_');
            $libraryName = $this->params()->fromPost('library');

            $columns = $this->adapter->query(
                'SELECT * FROM QSYS2.SYSCOLUMNS WHERE TABLE_NAME=? and TABLE_SCHEMA=?',
                [
                    'K_' . $tableRootName,
                    $libraryName
                ]
            )->toArray();


            $resultViewModel = new ViewModel();
            $resultViewModel->setTemplate('application/templates/service-program-template.phtml');
            $resultViewModel->setVariables([
                'title' => 'This is the title',
                'sourceMemberName' => 'AR_' . $tableRootName,
                'authors' => [
                    'Chuk Shirley',
                ],
                'tableRootName' => $tableRootName,
                'githubIssueNumber' => 'GH835',
                'currentDate' => new \DateTimeImmutable('now'),
                'columns' => $columns
            ]);

            $binderViewModel = new ViewModel();
            $binderViewModel->setTemplate('application/templates/service-program-binder-signature.phtml');
            $binderViewModel->setVariables([
                'title' => 'This is the title',
                'columns' => $columns,
                'tableRootName' => $tableRootName,
            ]);

            $prototypeDefinitionsModel = new ViewModel();
            $prototypeDefinitionsModel->setTemplate('application/templates/prototype-definitions.phtml');
            $prototypeDefinitionsModel->setVariables([
                'title' => 'This is the title',
                'columns' => $columns,
                'tableRootName' => $tableRootName,
            ]);

            $this->form->get('serviceProgram')->setValue($this->renderer->render($resultViewModel));
            $this->form->get('binderSignature')->setValue($this->renderer->render($binderViewModel));
            $this->form->get('prototypeDefinitions')->setValue($this->renderer->render($prototypeDefinitionsModel));
        }



        $viewModel->setVariable('form', $this->form);

        return $viewModel;
    }
}