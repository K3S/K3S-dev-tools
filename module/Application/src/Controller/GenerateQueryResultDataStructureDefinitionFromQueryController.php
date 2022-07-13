<?php

namespace Application\Controller;

use Application\Form\GenerateQueryResultDataStructureDefinitionFromQueryForm;
use Interop\Container\ContainerInterface;
use Laminas\Form\FormElementManager;
use Laminas\Form\FormInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class GenerateQueryResultDataStructureDefinitionFromQueryController extends AbstractActionController
{
    private FormInterface $form;

    public function __construct(FormInterface $form)
    {
        $this->form = $form;
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(FormElementManager::class)->get(
                GenerateQueryResultDataStructureDefinitionFromQueryForm::class
            )
        );
    }

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/generate-query-result-data-structure-definition-from-query.phtml');
        $viewModel->setVariable('result', '');
        $viewModel->setVariable('form', $this->form);
        $output = '';

        if ($this->getRequest()->isPost()) {
            $input = $this->params()->fromPost();

            $this->form->setData($input);
            if (!$this->form->isValid()) {
                $viewModel->setVariable('form', $this->form);
                return $viewModel;
            }

            $output = $this->generate($this->form->getData());
        }
        $viewModel->setVariable('output', $output);

        return $viewModel;
    }

    private function generate(array $input): string
    {
        $columns = trim()
        $columns = explode($input['query'], ',');
        var_dump($columns);
        die();
    }
}