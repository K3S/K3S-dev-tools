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

$mvcApplicationConfig = require __DIR__ . '/config/application.config.php';
$mvcApplication = \Laminas\Mvc\Application::init($mvcApplicationConfig);
$mvcApplication->getServiceManager()->get(Application::class)->run();
