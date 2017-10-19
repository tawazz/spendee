<?php

require_once __DIR__.'/../../vendor/autoload.php';

use HTTP\Services\ServiceProvider;
use \Slim\App;
use \Slim\Http\Environment;

$app = new App();
$app->environment = Environment::mock([
    'REQUEST_URI' => '/'
]);
$container = $app->getContainer();
$container->register(new ServiceProvider());
$queue = $container['queue'];
$data = [
      'body'    => "hello email job test",
      'subject' => "Test",
      'name'    => "Tawanda",
      'email'   => "tawazz@me.com",
      'phone'   => "04035123",
      'to'      => "tawanda.nyakudjga@gmail.com"
    ];
$queue->push(HTTP\Jobs\Handlers\EmailHandler::class,$data);
