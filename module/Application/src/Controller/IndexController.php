<?php

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ModelInterface;
use Laminas\View\Model\ViewModel;

final class IndexController extends AbstractActionController
{
    public function __construct()
    {

    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self();
    }

    /**
     * @return ModelInterface
     */
    public function indexAction(): ModelInterface
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/index.phtml');

        return $viewModel;
    }
}