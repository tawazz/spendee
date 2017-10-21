<?php
  use \Carbon\Carbon;
  use \HTTP\Controllers\Auth\AuthController;
  use \HTTP\Middleware\Guest;
  use \HTTP\Controllers\Auth\GoogleAuthController;
  use \HTTP\Middleware\AuthMiddleware;
  $auth = AuthController::class;

  $app->group('',function() use($auth) {
      $this->get('/login',$auth)->setName('login');
      $this->post('/login',$auth.':login')->setName('post.login');
      $this->get('/register',$auth.':registerView')->setName('register');
      $this->post('/register',$auth.':register')->setName('post.register');
      $this->get('/logout',$auth.':logout')->setName('logout');
      $this->get('/login/google',GoogleAuthController::class);
      $this->get('/forgot-password',$auth.':forgotView');
      $this->get('/reset-password',$auth.':resetView');
      $this->get('/activate',$auth.':activate');
  })->add(new Guest($container))->add($container->csrf);

  $app->group('',function() use($auth) {
    $this->post('/update/password', $auth.':resetPassword');
  })->add(new AuthMiddleware($container));
$app->post('/update/user', function() use($app){
    if (empty($_POST['email'])) {
      $app->flash("global","fill in all details");
      $app->response->redirect($app->urlFor('account'));
    }else{
      $app->User->read($app->auth->user_id)->set('email',$_POST['email']);
      $app->flash("global","user details updated");
      $app->response->redirect($app->urlFor('account'));
    }
});

 ?>
