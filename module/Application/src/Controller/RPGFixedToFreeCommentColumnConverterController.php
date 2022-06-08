<?php
declare(strict_types=1);

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class RPGFixedToFreeCommentColumnConverterController extends AbstractActionController
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
        $viewModel->setTemplate('application/rpg-fixed-to-free-comment-column-converter.phtml');
        return $viewModel;
    }
}