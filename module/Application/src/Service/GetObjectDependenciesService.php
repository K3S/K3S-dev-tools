<?php
declare(strict_types=1);

namespace Application\Service;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;

final class GetObjectDependenciesService
{
    /**
     * @var Adapter|AdapterInterface
     */
    private AdapterInterface|Adapter $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class)
        );
    }

    /**
     * @param string $object
     * @return array
     */
    public function __invoke(string $object): array
    {
        $dependencies = [];
        $sql = "SELECT * FROM TABLE(QSYS2.IFS_READ(PATH_NAME => '/QSYS.LIB/WEB_5DEV.LIB/QRPGLESRC.FILE/$object.MBR',
                                   END_OF_LINE => 'CRLF')) where ucase(line) like '%/COPY%'";
        $results = $this->adapter->query($sql)->execute();

        foreach ($results as $result) {

            $parsedLine = explode('/copy', $result['LINE']);
            if (count($parsedLine) === 1) {
                $parsedLine = explode('/COPY', $result['LINE']);
            }

            $memberName = strtoupper(trim($parsedLine[1]));

            $dependencies[] = [
                'name' => $memberName,
                'library' => (str_contains($memberName, 'K3S_')) ? 'K3S_5SRC' : 'WEB_5DEV',
            ];
        }

        return $dependencies;
    }
}