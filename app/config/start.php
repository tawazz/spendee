<?php
  session_start();
  require 'vendor/autoload.php';
  require 'Tazzy-Helpers/autoload.php';
  require 'app/config/settings.php';
  use Slim\Slim;

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
  //dependancies
  $app->container->singleton('session',function(){
    return  new Session();
  });
  $app->container->singleton('hash',function(){
    return  new Hash();
  });

  //routes
  require'app/routes/routes.php';

 //variables
 $app->debug =Settings::get('debug');
  $app->auth = false;
  $app->month = date('m');
  $app->year = date('Y');
  $app->day = date('d');
  $app->baseUrl = Settings::get('urls.baseUrl');
  $app->view()->appendData([
    "baseUrl"=> $app->baseUrl
  ]);
  $app->run();

  if($app->debug){
      echo "User </br>";
      var_dump($app->auth);
  }

 ?>
