<?php
  $app->notFound(function () use($app){
    $app->render('errors/404.php');
  });
  
  if(!$app->debug)
  {
    $app->error(function (\Exception $e) use ($app) {
      $app->render('errors/500.php');
    });
  }
 ?>
