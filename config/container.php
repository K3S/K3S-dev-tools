<?php

use Laminas\Mvc\Application;
use Laminas\Stdlib\ArrayUtils;

chdir(dirname(__DIR__));

// Composer autoloading
require __DIR__ . '/../vendor/autoload.php';

// Retrieve configuration
$appConfig = require __DIR__ . '/application.config.php';
$environment = getenv('APPLICATION_ENV');
if ($environment === 'development' && file_exists(__DIR__ . '/development.config.php')) {
    $appConfig = ArrayUtils::merge(
        $appConfig,
        require __DIR__ . '/development.config.php'
    );
}

return Application::init($appConfig)->run()->getServiceManager();