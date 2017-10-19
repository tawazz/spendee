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
        $exists = $user->exist($_POST);
        $remember = $req->getParam('remember');
        if($exists){
          if($exists && $this->container->hash->make($req->getParam('password'),$req->getParam('username')) == $exists->password ){
            $this->session->put('id',$exists->id);
            if ($remember == 'on') {
                $remember_hash = $app->hash->make($app->hash->salt(10));
                $user->remember($exists->id,$remember_hash);
                $resp = $app->Cookie->setCookie($resp,'remember',"{$remember_hash}",Carbon::parse('+4 weeks')->timestamp);
              return $this->redirect($resp,$this->urlFor('expenses'));
            }else{
              return $this->redirect($resp,$this->urlFor('expenses'));
            }
          }else {
            $this->flash->addMessage("global","wrong username or password");
            return $this->redirect($resp,$this->urlFor('login'));
          }
        }else{
          $this->flash->addMessage("global","wrong username or password");
          $this->view->render($resp,'auth/login.php',['errors'=>['login'=>"wrong username or password"]]);
        }
      }else{
        $this->flash->addMessage("global","fill in all details");
        $this->view->render($resp,'auth/login.php',['errors'=>$user->errors()]);
      }
    }

    public function registerView($req, $resp,$args)
    {
        $this->view->render($resp,'auth/register.php');
    }
    public function resetView($req, $resp,$args)
    {
        $this->view->render($resp,'auth/reset.php');
    }
    public function forgotView($req, $resp,$args)
    {
        $this->view->render($resp,'auth/forgot.php');
    }
    public function register($req, $resp,$args)
    {
      $user = $this->User;
      if ($user->validate($_POST)) {
        $data = [
          "email" => $req->getParam('email'),
          "username"=> $req->getParam('username'),
          "password"=> $this->hash->make($req->getParam('password'),$req->getParam('username')),
          'firstname'=>$req->getParam('firstname'),
          'lastname'=>$req->getParam('lastname'),
          'email'=>strtolower($req->getParam('email'))
        ];

        $user->create($data);
        $this->flash->addMessage("global","you registered succesfully. Log in below");
        return $this->redirect($resp,$this->urlFor('login'));

      }else{
        return $this->view->render($resp,'auth/register.php',['errors'=>$user->errors(),'values'=>$_POST]);
      }
      return $this->view->render($resp,'auth/register.php');
    }

    public function logout($req,$resp,$args)
    {
      $app = $this->container;
      if ($app->Cookie->getCookie($req,'remember')) {
          $app->User->removeRemember($app->auth->id);
          $app->Cookie->deleteCookie($resp,'remember');
      }

      $app->session->delete('id');
      $app->auth = false;
      return $this->redirect($resp,$this->urlFor('login'));
    }

  }

 ?>
