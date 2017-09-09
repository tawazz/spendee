<?php
  use Carbon\Carbon;
  use HTTP\Controllers\Auth\AuthController;
  use \HTTP\Middleware\Guest;
  $auth = AuthController::class;

  $app->group('',function() use($auth) {
      $this->get('/login',$auth.':loginView')->setName('login');
      $this->post('/login',$auth.':login')->setName('post.login');
      $this->get('/register',$auth.':registerView')->setName('register');
      $this->post('/register',$auth.':register')->setName('post.register');
      $this->get('/logout',$auth.':logout')->setName('logout');
  })->add(new Guest($container))->add($container->csrf);

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
$app->post('/update/password', function() use($app){
    if ($app->hash->make($app->request->post('old_password'),$app->auth->username) == $app->auth->password) {
      $new_pass= $app->request->post('new_password');
      $rep_pass= $app->request->post('repeat_password');
      if(!empty($new_pass) && $new_pass == $rep_pass){
        $app->User->read($app->auth->user_id)->set('password',$app->hash->make($new_pass,$app->auth->username));
        $app->flash("global","password changed");
        $app->response->redirect($app->urlFor('account'));
      }
      $app->flash("global","passwords dont match");
      $app->response->redirect($app->urlFor('account'));
    }else{
      $app->flash("global","passwords dont match");
      $app->response->redirect($app->urlFor('account'));
    }
});
 ?>
