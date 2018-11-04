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
      $this->get('/recover-password',$auth.':recoverPassword');
      $this->post('/forgot-password',$auth.':forgotPassword')->setName('post.forgot-password');
      $this->post('/change-password',$auth.':changePassword')->setName('post.auth.change_password');
  })->add(new Guest($container))->add($container->csrf);

  $app->group('',function() use($auth) {
    $this->post('/update/password', $auth.':resetPassword');
    $this->post('/update/user', $auth.':updateUser');
  })->add(new AuthMiddleware($container));

 ?>
