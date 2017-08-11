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
  $r->clearOldSessions();
  echo "Old Sessions Cleared";
 ?>
