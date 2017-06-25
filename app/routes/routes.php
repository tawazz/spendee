<?php
  use HTTP\Middleware\AuthMiddleware;
  use \HTTP\Middleware\Guest;

  // Vissible by guest users
  $app->group('',function(){
      require 'home/home.php';
  })->add(new Guest($container));

  //Vissible by logged in users
  $app->group('',function() use($app){
    require 'main/main.php';
  })->add(new AuthMiddleware($container));

  //Vissible by all users
  require 'auth/auth.php';
  require 'api/api.php';
  require 'errors/errors.php';
 ?>
