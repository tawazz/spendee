<?php
  $app->notFound(function () use($app){
    $app->render('errors/404.php');
  });
  $app->error(function (\Exception $e) use ($app) {
    $app->render('errors/500.php');
});
 ?>
