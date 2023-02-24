<?php

namespace Console;

use Console\Command\CopyData;
use Console\Command\GenerateSqlInsert;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Helper\HelperSet;
use Console\Command\PromoteToTesting;
use Console\Command\PromoteToTestingAndCompile;

final class ApplicationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        /** @var ConsoleApplication $consoleApplication */
        $consoleApplication = new ConsoleApplication();
        $consoleApplication->setHelperSet($container->get(HelperSet::class));

//        $consoleApplication->add($container->get(CopyData::class));
//        $consoleApplication->add($container->get(GenerateSqlInsert::class));
        $consoleApplication->add($container->get(PromoteToTesting::class));
        $consoleApplication->add($container->get(PromoteToTestingAndCompile::class));

        return $consoleApplication;
    }
}
