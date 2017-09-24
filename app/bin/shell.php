<?php
date_default_timezone_set ('Australia/Perth' );
require_once __DIR__.'/../../vendor/autoload.php';

use \HTTP\Services\ServiceProvider;
use \Slim\App;
use \Slim\Http\Environment;
use \Symfony\Component\Filesystem\Filesystem;

$app = new App();
$app->environment = Environment::mock([
    'REQUEST_URI' => '/'
]);
$container = $app->getContainer();
$container->register(new ServiceProvider());



 ?>
