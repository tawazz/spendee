<?php
  session_start();
  require 'vendor/autoload.php';
  require 'Tazzy-Helpers/autoload.php';
  use Slim\App;
  use Carbon\Carbon;
  use HTTP\Middleware\{Error,Dump,Csrf};

  $app = new App(
    new \Slim\Container([
      'settings' => [
        'displayErrorDetails'=> Settings::get('debug'),
      ]
    ])
  );
  $container = $app->getContainer();
  //Middleware
  $app->add(new Dump($container));
  $app->add(new Error($container));
  $app->add(new Csrf($container));
  require 'app/HTTP/Middleware/auth_filters.php';
  //views
  $container["view"] = function($c){
    $view = new HTTP\Helpers\Twig(Settings::get('views.dir'), [
        'cache' => Settings::get('views.cache')
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new HTTP\Helpers\TwigExtension($c['router'], $basePath));
    $view->addExtension(new \Twig_Extension_Debug());

    return $view;
  };
  $app->view = $container->view;
  //models
  require 'app/HTTP/Models/Models.php';
  //dependancies
  $container['session'] = function(){
    return  new Session();
  };
  $container['hash'] = function(){
    return  new Hash();
  };

  $container['Config'] = function(){
    return  new Settings();
  };

  $container['Helper'] = function(){
    return  new Helper();
  };

  $container['Cookie'] = function(){
    return  new HTTP\Helpers\Cookie();
  };
  $container['flash'] = function () {
    return new \Slim\Flash\Messages();
  };

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
    "flash"    => $container->flash
  ]);
  //routes
  require'app/routes/routes.php';

  $app->run();

 ?>
