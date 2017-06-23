<?php
  session_start();
  require 'vendor/autoload.php';
  require 'Tazzy-Helpers/autoload.php';
  use Slim\App;
  use Carbon\Carbon;
  use HTTP\MiddleWare\{Before,Dump,Csrf};

  $app = new App(
    new \Slim\Container([
      'settings' => [
        'displayErrorDetails'=> Settings::get('debug'),
      ]
    ])
  );
  $container = $app->getContainer();
  //Middleware
  #$app->add(new Dump());
  #$app->add(new Before());
  #$app->add(new Csrf());
  require 'app/HTTP/MiddleWare/auth_filters.php';
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
  $container['User'] = function(){
      return new User();
  };
  $container['Exp'] = function(){
      return new Expenses();
  };
  $container['Inc'] = function(){
      return new Incomes();
  };
  $container['Tags'] = function(){
      return new Tags();
  };
  $container['ExpTags'] = function(){
      return new ExpTags();
  };
  $container['IncTags'] = function(){
      return new IncTags();
  };
  $container['Remember'] = function(){
      return new Remember();
  };
  $container['Budget'] = function(){
      return new Budget();
  };
  $container['BudgetTag'] = function(){
      return new BudgetTag();
  };
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

 //variables
  $app->debug = Settings::get('debug');
  $app->auth  = false;
  $app->month = date('m');
  $app->year  = date('Y');
  $app->day   = date('d');
  $app->baseUrl = Settings::get('urls.baseUrl');
  $app->urlFor = function ($name,$params=[]){
    return $container->router->pathFor($name,$params);
  };
  $app->view->appendData([
    "baseUrl"  => $app->baseUrl,
    "ver"      => Settings::get('ver'),
    "brand"    => Settings::get('locale.brand'),
    "address"  => Settings::get('locale.address'),
    "phone"    => Settings::get('locale.phone'),
    "email"    => Settings::get('locale.email'),
  ]);
  //routes
  require'app/routes/routes.php';

  $app->run();

  if($app->debug){
      echo "User </br>";
      dump($app->auth);
  }

 ?>
