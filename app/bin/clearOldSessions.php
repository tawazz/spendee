<?php
  require_once __DIR__.'/../../vendor/autoload.php';

  use \HTTP\Services\ServiceProvider;
  use \Slim\App;
  use \Slim\Http\Environment;

  $app = new App();
  $app->environment = Environment::mock([
      'REQUEST_URI' => '/'
  ]);
  $container = $app->getContainer();
  $container->register(new ServiceProvider());

  $r = $container->Remember;
  $deleted = $r->clearOldSessions();
  echo $container->Carbon->now(new \DateTimeZone('Australia/Perth'))->toDayDateTimeString()." cleared $deleted old sessions cleared\n";
 ?>
