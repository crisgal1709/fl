<?php

define('DEMO_VERSION', false);

use FL\Application;

require __DIR__.'/vendor/autoload.php';

$config = require(__DIR__ . '/config.php');

date_default_timezone_set($config['timezone'] ?? 'UTC');

$app = new Application(__DIR__, $config);
$response = $app->run();

$app->terminate();

return $response;
