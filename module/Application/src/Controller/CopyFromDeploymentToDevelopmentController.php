<?php
declare(strict_types=1);

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ModelInterface;
use ToolkitApi\Toolkit;
use ToolkitApi\ToolkitInterface;

final class CopyFromDeploymentToDevelopmentController extends AbstractActionController
{
    private ToolkitInterface $toolkit;

    public function __construct(ToolkitInterface $toolkit)
    {
        $this->toolkit = $toolkit;
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Toolkit::class)
        );
    }

    public function indexAction(): ModelInterface
    {
        // @todo: support multiple source members
        // @todo: support RPG source

        $input = $this->params()->fromPost();
        $sourceMember = $input['sourceMembers'];
        $this->toolkit->CLCommand('CPYSRCF FROMFILE(WEB_5DEV/QCLLESRC) TO_FILE(ACS_5DEV/QCLLESRC) FROMMBR(' . $sourceMember . ') TOMBR(' . $sourceMember . ')');

        return new JsonModel();
    }
}