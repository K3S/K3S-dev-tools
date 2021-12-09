<?php

declare(strict_types=1);

namespace Tools;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\ServiceManager;

/**
 * Class HelperSetFactory
 * @package DoctrineMigrations\Factory
 **/
final class HelperSetFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ServiceManager $container * */
        return new HelperSet();
    }
}
