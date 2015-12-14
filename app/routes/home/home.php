<?php
  $app->get('/',function() use ($app){
    if($app->auth){
      $app->response->redirect($app->urlFor('expenses'));
      return 0;
    }
    $app->render('auth/login.php');
  })->name('home');

  $app->get('/about',function() use ($app){
    $app->render('home/about.php');
  })->name('about');

  $app->get('/contact',function() use ($app){
    $app->render('home/contact.php');
  })->name('contact');

  $app->get('/help',function() use ($app){
    $app->render('home/help.php');
  })->name('help');

 ?>
