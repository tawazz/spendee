<?php
  use \HTTP\Controllers\Home\HomeController;
  use \HTTP\Middleware\Guest;
  $app->group('',function(){
      $this->get('/',HomeController::class)->setName('home');
  })->add(new Guest($container));


  $app->get('/about',function() use ($app){
    $app->render('home/about.php');
  })->setName('about');

  $app->get('/contact',function() use ($app){
    $app->render('home/contact.php');
  })->setName('contact');

  $app->get('/help',function() use ($app){
    $app->render('home/help.php');
  })->setName('help');

  $app->post('/contact', function() use ($app){
    if( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) ){
     $name = $_POST['name'];
     $email = $_POST['email'];
     $phone = $_POST['phone'];
     $msg = $_POST['msg'];

     $M= "Name : {$name}". "Email: {$email} Phone : {$phone} "."Message : {$msg}";
     mail("tawanda.nyakudjga@gmail.com","tawazz.net/me",$M);
    }
      $app->flash("global","your message was sent");
      $app->response->redirect($app->urlFor('contact'));
  })->setName('post.contact');

 ?>
