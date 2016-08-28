<?php
  session_start();
  require 'vendor/autoload.php';
  require 'Tazzy-Helpers/autoload.php';
  use Slim\Slim;
  use Carbon\Carbon;

  $app = new Slim([
    'view'=> new \Slim\Views\Twig(),
    'debug'=> Settings::get('debug'),
  ]);
  //Middleware
  $app->add(new Before());
  $app->add(new Csrf());
  require 'app/Middleware/auth_filters.php';
  //views
  $view = $app->view();
  $view->setTemplatesDirectory('app/views');
  $view->parserExtensions = [
    new \Slim\Views\TwigExtension(),
    new \Twig_Extension_Debug()
  ];
  //models
  $app->container->set('User',function(){
      return new User();
  });
  $app->container->set('Exp',function(){
      return new Expenses();
  });
  $app->container->set('Inc',function(){
      return new Incomes();
  });
  $app->container->set('Tags',function(){
      return new Tags();
  });
  $app->container->set('ExpTags',function(){
      return new ExpTags();
  });
  $app->container->set('IncTags',function(){
      return new IncTags();
  });
  $app->container->set('Remember',function(){
      return new Remember();
  });
  $app->container->set('Budget',function(){
      return new Budget();
  });
  $app->container->set('BudgetTag',function(){
      return new BudgetTag();
  });
  //dependancies
  $app->container->singleton('session',function(){
    return  new Session();
  });
  $app->container->singleton('hash',function(){
    return  new Hash();
  });

  $app->container->singleton('Config',function(){
    return  new Settings();
  });

  $app->container->singleton('Helper',function(){
    return  new Helper();
  });
  //routes
  require'app/routes/routes.php';

 //variables
  $app->debug = $app->Config->get('debug');
  $app->auth  = false;
  $app->month = date('m');
  $app->year  = date('Y');
  $app->day   = date('d');
  $app->baseUrl = $app->Config->get('urls.baseUrl');
  $app->view()->appendData([
    "baseUrl"  => $app->baseUrl,
    "ver"      => Settings::get('ver'),
    "brand"    => Settings::get('locale.brand'),
    "address"  => Settings::get('locale.address'),
    "phone"    => Settings::get('locale.phone'),
    "email"    => Settings::get('locale.email'),
  ]);
  $app->run();

  if($app->debug){
      echo "User </br>";
      var_dump($app->auth);
  }

 ?>
