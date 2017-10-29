<?php
  session_start();
  date_default_timezone_set('Australia/Perth');
  require 'vendor/autoload.php';
  use Slim\App;
  use HTTP\Middleware\{Error,Dump};
  use HTTP\Services\ServiceProvider;

  $app = new App(
    new \Slim\Container([
      'settings' => [
        'displayErrorDetails'=> Settings::get('debug'),
      ]
    ])
  );
  $container = $app->getContainer();
  //Register service provider
  $container->register(new ServiceProvider());
  //Middleware
  $app->add(new Dump($container));
  $app->add(new Error($container));
  //$app->add($container->csrf);
  if ($container->Config->get('debug')) {
    #$app->add($container->debugbar_middleware);
  }
  require 'app/HTTP/Middleware/auth_filters.php';
  //views
  $app->view = $container->view;

 //variables
  $container['debug'] = Settings::get('debug');
  $container['auth']  = false;
  $container['month'] = date('m');
  $container['year'] = date('Y');
  $container['day']   = date('d');
  $container['baseUrl'] = Settings::get('urls.baseUrl');
  $container['urlFor'] = function ($name,$params=[]) use($container){
    return $container->router->pathFor($name,$params);
  };
  $container['redirect'] = function($resp,$url,$status=302){
    return $resp->withStatus($status)->withHeader('Location', $url);
  };
  $container->view->appendData([
    "ver"      => Settings::get('ver'),
    "brand"    => Settings::get('locale.brand'),
    "address"  => Settings::get('locale.address'),
    "phone"    => Settings::get('locale.phone'),
    "email"    => Settings::get('locale.email'),
    "flash"    => $container->flash,
    "vue_template"=> Settings::get('views.vue')
  ]);
  //routes
  require'app/routes/routes.php';

  $app->run();

 ?>
