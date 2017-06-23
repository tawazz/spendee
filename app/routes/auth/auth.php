<?php
use Carbon\Carbon;
  $app->get('/register',$guest(),function() use ($app){
    $app->render('auth/register.php');
  })->setName('register');



  $app->post('/register',$guest(),function() use ($app){
    $user = $app->User;
    if ($user->validate($_POST)) {
      $data = [
        "email" => $app->request->post('email'),
        "username"=> $app->request->post('username'),
        "password"=> $app->hash->make($app->request->post('password'),$app->request->post('username')),
        'firstname'=>$app->request->post('firstname'),
        'lastname'=>$app->request->post('lastname'),
        'email'=>strtolower($app->request->post('email'))
      ];

      $id =$user->save($data);
        $app->flash("global","you registered succesfully");
        $app->response->redirect($app->urlFor('login'));

    }else{
      $app->render('auth/register.php',['errors'=>$user->errors(),'values'=>$_POST]);
    }
  })->setName('post.register');

  $app->get('/login',$guest(),function() use ($app){
    $app->render('auth/login.php');
  })->setName('login');

  $app->post('/login',$guest(),function() use ($app){
    $user = $app->User;
    $rules = [
      'password'=> [
                    'required'=> TRUE,
      ],
      'username'=>[
          'required' => TRUE,
        ]
    ];
    if ($user->validate($_POST,$rules)) {
        $exist = $user->exist($_POST);
        $remember = $app->request->post('remember');
        if($exist){
          $fetch_user = $user->find('first',[
            'where'=>['username','=',$_POST['username']]
          ]);

          if($fetch_user && $app->hash->make($app->request->post('password'),$app->request->post('username')) == $fetch_user->password ){

              $app->session->put('id',$fetch_user->user_id);
              if ($remember == 'on') {
                if (!$fetch_user->session) {
                  $remember_hash = $app->hash->make($app->hash->salt(10));
                  $user->remember($fetch_user->user_id,$remember_hash);
                  $app->setCookie('remember',"{$remember_hash}",Carbon::parse('+1 week')->timestamp);

                }else{
                  $app->setCookie('remember',"{$fetch_user->session->hash}",Carbon::parse('+1 week')->timestamp);
                }
                $app->response->redirect($app->urlFor('expenses'));
              }else{
                $app->response->redirect($app->urlFor('expenses'));
              }
        }else {
          $app->flash("global","wrong username or password");
          $app->response->redirect($app->urlFor('login'));
        }
      }else{
        $app->render('auth/login.php',['errors'=>['login'=>"wrong username or password"]]);
      }
  }else{
    $app->render('auth/login.php',['errors'=>$user->errors()]);
  }

})->setName('post.login');

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
