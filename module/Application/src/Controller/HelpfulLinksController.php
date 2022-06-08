<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class HelpfulLinksController extends AbstractActionController
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
        $viewModel->setTemplate('application/helpful-links.phtml');
        return $viewModel;
    }
}