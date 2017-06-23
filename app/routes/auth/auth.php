<?php
use Carbon\Carbon;
use HTTP\Controllers\Auth\AuthController;

  $app->get('/login',AuthController::class.':loginView')->setName('login');
  $app->post('/login',AuthController::class.':login')->setName('post.login');
  $app->get('/register',AuthController::class.':registerView')->setName('register');
  $app->post('/register',AuthController::class.':register')->setName('post.register');

$app->get('/logout', function() use($app){

  if ($app->getCookie('remember')) {
      $app->User->removeRemember($app->auth->user_id);
      $app->deleteCookie('remember');
  }

  $app->session->delete('id');
  $app->auth = false;
  $app->response->redirect($app->urlFor('login'));
})->setName('logout');

$app->post('/update/user',$require_login(), function() use($app){
    if (empty($_POST['email'])) {
      $app->flash("global","fill in all details");
      $app->response->redirect($app->urlFor('account'));
    }else{
      $app->User->read($app->auth->user_id)->set('email',$_POST['email']);
      $app->flash("global","user details updated");
      $app->response->redirect($app->urlFor('account'));
    }
});
$app->post('/update/password',$require_login(), function() use($app){
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
