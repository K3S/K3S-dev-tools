<?php
declare(strict_types=1);

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ModelInterface;
use Laminas\View\Model\ViewModel;

final class GetObjectDependenciesController extends AbstractActionController
{
    private AdapterInterface $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class)
        );
    }

    public function indexAction(): ModelInterface
    {
        $input = $this->params()->fromQuery();
        $object = $input['sourceMembers'];
        $dependencies = [$input['sourceMembers']];

        $fileExtension = substr(strrchr($object,'.'),1);
        if ($fileExtension === 'CLLE') {
            $sql = "SELECT * FROM TABLE(QSYS2.IFS_READ(PATH_NAME => '/QSYS.LIB/WEB_5DEV.LIB/QCLLESRC.FILE/$object.MBR',
                                   END_OF_LINE => 'CRLF')) where ucase(line) like '%/COPY%'";
        } elseif ($fileExtension === 'RPGLE' || $fileExtension === 'SQLRPGLE') {
            $sql = "SELECT * FROM TABLE(QSYS2.IFS_READ(PATH_NAME => '/QSYS.LIB/WEB_5DEV.LIB/QRPGLESRC.FILE/$object.MBR',
                                   END_OF_LINE => 'CRLF')) where ucase(line) like '%/COPY%'";
        }

        $results = $this->adapter->query($sql)->execute();

        foreach ($results as $result) {

            if ($fileExtension === 'RPGLE' || $fileExtension === 'SQLRPGLE')
            {
                $parsedLine = explode('/copy', $result['LINE']);
                if (count($parsedLine) === 1) {
                    $parsedLine = explode('/COPY', $result['LINE']);
                }
            } elseif ($fileExtension === 'CLLE') {
                $parsedLine = explode('/copy', $result['LINE']);
                if (count($parsedLine) === 1) {
                    $parsedLine = explode('/COPY', $result['LINE']);
                }
            }


            $memberName = strtoupper(trim($parsedLine[1]));

            $dependencies[] = [
                'name' => $memberName,
                'library' => (str_contains($memberName, 'K3S_')) ? 'K3S_5SRC' : 'WEB_5DEV',
            ];
        }

        return new JsonModel(['dependencies' => $dependencies]);
    }
}