#!/usr/bin/env php
<?php

use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'on');
ini_set('log_errors', 'on');

chdir(__DIR__);

// Composer autoloading
require __DIR__ . '/vendor/autoload.php';

$servicesConfig = require __DIR__ . '/config/services.php';

$serviceManager = new ServiceManager($servicesConfig);
$consoleApplication = $serviceManager->get(Application::class);
$consoleApplication->setHelperSet($serviceManager->get(HelperSet::class));

$consoleApplication->run();
