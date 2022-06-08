<?php

namespace Application\Controller;

use Application\Form\GenerateTableDataGatewayServiceProgramForm;
use Interop\Container\ContainerInterface;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\Form\FormElementManager;
use Laminas\Form\FormInterface;
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

    /**
     * @param FormInterface $generateTableDataGatewayServiceProgramForm
     * @param RendererInterface $renderer
     */
    public function __construct(FormInterface $generateTableDataGatewayServiceProgramForm, RendererInterface $renderer)
    {
        $this->form = $generateTableDataGatewayServiceProgramForm;
//        $this->toolkit = $toolkit;

        $this->renderer = $renderer;

        $form = new Form();
        $form->setAttribute('class', 'form');
        $form->setAttribute('method', 'POST');

        $library = new Text('library');
        $form->add($library);
        $tableName = new Text('table_name');
        $form->add($tableName);

        $submit = new Submit('submit');
        $submit->setValue('Submit');
        $form->add($submit);
        $csrf = new Csrf('generate_table_data_gateway_service_program_csrf');
        $form->add($csrf);

        $result = new Textarea('result');
        $result->setAttribute('cols', '150');
        $result->setAttribute('rows', '25000');
        $result->setAttribute('style', 'font-family: monospace, monospace; white-space: pre-wrap; height: 100vh');
        $form->add($result);

        $this->form = $form;

    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(FormElementManager::class)->get(GenerateTableDataGatewayServiceProgramForm::class),
            $container->get(PhpRenderer::class)
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
            ]);
            $this->form->get('result')->setValue($this->renderer->render($resultViewModel));
        }



        $viewModel->setVariable('form', $this->form);

        return $viewModel;
    }
}