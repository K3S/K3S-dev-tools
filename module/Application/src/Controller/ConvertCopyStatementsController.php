<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class ConvertCopyStatementsController extends AbstractActionController
{
    public function __construct()
    {

    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self();
    }

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/convert-copy-statements.phtml');
        $viewModel->setVariables([
            'input' => '',
            'output' => '',
        ]);
        if ($this->getRequest()->isPost())
        {
            $params = $this->params()->fromPost();
            $viewModel->setVariable('input', $params['input']);
            $copyStatements = explode("\n", str_replace("\r", "", $params['input']));
            $output = '';
            foreach ($copyStatements as $copyStatement) {
                if (strlen($copyStatement) === 0) {
                    continue;
                }
                $sourceMemberBefore = explode('/copy ', trim($copyStatement))[1];
                $sourceMemberAfter = '\'qrpglesrc/' . $sourceMemberBefore . '.rpgle\'';
                $output .= str_replace($sourceMemberBefore, $sourceMemberAfter, $copyStatement) . "\n";
            }

            $viewModel->setVariable('output', $output);
        }



        return $viewModel;
    }
}