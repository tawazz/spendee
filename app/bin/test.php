<?php

require_once __DIR__.'/../../vendor/autoload.php';

use Illuminate\Queue\Worker;
use Illuminate\Queue\WorkerOptions;
use HTTP\Jobs\{ Container,DebugException};
use HTTP\Services\ServiceProvider;
use Slim\DefaultServicesProvider;
use Pimple\Container as App;

$app = new App();
#$app->register(new DefaultServicesProvider());
$app->register(new ServiceProvider());
$queue = $app['queue'];
$data = [
      'body'    => "hello email job jost",
      'subject' => "Test",
      'name'    => "Tawanda",
      'email'   => "tawazz@me.com",
      'phone'   => "04035123",
      'to'      => "tawanda.nyakudjga@gmail.com"
    ];
  #$view = $app->view();
  #$view->appendData($data);
  #$data['body'] = $view->render('email/contact_email.php');
  $queue->push(HTTP\Jobs\Handlers\EmailHandler::class,$data);
