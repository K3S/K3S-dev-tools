<?php

require __DIR__ . '/../vendor/autoload.php';

$configurationLoader = new ConfigurationLoader();
$connectionLoader = \Doctrine\Migrations\Configuration\Connection\ConnectionLoader::
$factory = \Doctrine\Migrations\DependencyFactory::fromConnection($configurationLoader, $connectionLoader);
