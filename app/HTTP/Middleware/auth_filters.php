<?php
  $authCheck = function($required) use($app){
    return function() use($required,$app){
      if((!$app->auth && $required) || ($app->auth && !$required)){
        #$app->redirect($app->urlFor('home'));
      }
    };
  };
  $require_login = function($req,$resp,$next) use($container){

      if ($container->Cookie->getCookie($req,'remember') !== Null && !$container->auth) {
        $container->session->flash('global','Login required to access the resource');
        
      }
      $resp = $next($req,$resp);
      return $resp;
  };

  $require_admin = function() use($app){
     $require_login;
     if($app->auth->role->name != "Admin"){
        $app->response->setStatus(403);
        $app->getContainer->view->render('home/forbiden.php');
     }
  };

  $guest = function() use($authCheck){
    return $authCheck(false);
  };
 ?>
