<?php

require __DIR__ . '/../vendor/autoload.php';

use SalesAppApi\Bootstrap\App;

$app = new App();

$dependencies = require __DIR__ . '/../src/Config/dependencies.php';
$dependencies($app);

$routes = require __DIR__ . '/../src/Config/routes.php';
$routes($app); 

$response = $app->run();
