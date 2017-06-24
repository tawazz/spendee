<?php
  namespace HTTP\Controllers\Auth;

  use Carbon\Carbon;
  /**
   * AuthController
   */
  class AuthController extends \HTTP\Controllers\BaseController
  {

    public function __invoke($req, $resp,$args)
    {
        $this->loginView($req,$resp,$args);
    }

    public function loginView($req, $resp,$args)
    {
        $this->view->render($resp,'auth/login.php');
    }

    public function login($req, $resp,$args)
    {
      $app = $this->container;
      $user = $this->container->User;
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
        $remember = $req->getParam('remember');
        if($exist){
          $fetch_user = $user->find('first',[
            'where'=>['username','=',$_POST['username']]
          ]);
          if($fetch_user && $this->container->hash->make($req->getParam('password'),$req->getParam('username')) == $fetch_user->password ){
            $this->session->put('id',$fetch_user->user_id);
            if ($remember == 'on') {
              if (!$fetch_user->session) {
                $remember_hash = $app->hash->make($app->hash->salt(10));
                $user->remember($fetch_user->user_id,$remember_hash);
                $resp = $app->Cookie->setCookie($resp,'remember',"{$remember_hash}",Carbon::parse('+4 weeks')->timestamp);

              }else{
                $resp = $app->Cookie->setCookie($resp,'remember',"{$fetch_user->session->hash}",Carbon::parse('+4 weeks')->timestamp);
              }
              return $this->redirect($resp,$this->urlFor('expenses'));
            }else{
              return $this->redirect($resp,$this->urlFor('expenses'));
            }
          }else {
            $this->flash("global","wrong username or password");
            return $this->redirect($resp,$this->urlFor('login'));
          }
        }else{
          $this->view->render($resp,'auth/login.php',['errors'=>['login'=>"wrong username or password"]]);
        }
      }else{
        $this->view->render($resp,'auth/login.php',['errors'=>$user->errors()]);
      }
    }

    public function registerView($req, $resp,$args)
    {
        $this->view->render($resp,'auth/register.php');
    }

    public function register($req, $resp,$args)
    {
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
        $this->view->render($resp,'auth/register.php');
    }

    public function logout($req,$resp,$args)
    {
      $app = $this->container;
      if ($app->Cookie->getCookie($req,'remember')) {
          $app->User->removeRemember($app->auth->user_id);
          $app->Cookie->deleteCookie($resp,'remember');
      }

      $app->session->delete('id');
      $app->auth = false;
      return $this->redirect($resp,$this->urlFor('login'));
    }

  }

 ?>
