<?php

namespace Tools;

use Tools\Command\CopyData;
use Tools\Command\GenerateSqlInsert;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Helper\HelperSet;

final class ApplicationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        /** @var ConsoleApplication $consoleApplication */
        $consoleApplication = new ConsoleApplication();
        $consoleApplication->setHelperSet($container->get(HelperSet::class));

        $consoleApplication->add($container->get(CopyData::class));
        $consoleApplication->add($container->get(GenerateSqlInsert::class));

        return $consoleApplication;
    }
}
