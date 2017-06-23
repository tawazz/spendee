<?php
  $authCheck = function($required) use($app){
    return function() use($required,$app){
      if((!$app->auth && $required) || ($app->auth && !$required)){
        #$app->redirect($app->urlFor('home'));
      }
    };
  };
  $require_login = function() use($app){
    return function() use($app){
      if(!$app->auth && TRUE){
        #$app->flash('global','Login required to access the resource');
        #$app->redirect($app->urlFor('login'));
      }
    };
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
