<?php
declare(strict_types=1);

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class MoveGutterCommentsToEndOfLineController extends AbstractActionController
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
        $input = '';
        $output = '';

        if ($this->getRequest()->isPost())
        {
            $params = $this->params()->fromPost();
            $input = $params['input'];
            $inputArray = explode("\n", str_replace("\r", "", $params['input']));

            foreach ($inputArray as $line) {
                $firstFiveCharacters = substr($line, 0, 5);
                $codeLine = substr($line, 5);
                if (!$codeLine) {
                    $codeLine = '';
                }
                $codeLine = "     " . str_pad($codeLine, 88, ' ');
                if (strlen(trim($firstFiveCharacters)) > 0) {
                    $output .= $codeLine . '//' . $firstFiveCharacters . "\n";
                } else {
                    $output .= $codeLine . "\n";
                }
            }
        }

        $viewModel = new ViewModel([
            'input' => $input,
            'output' => $output,
        ]);

        $viewModel->setTemplate('application/move-gutter-comments-to-end-of-line.phtml');

        return $viewModel;
    }
}