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
          $app->User->removeRemember($app->auth->id);
          $app->Cookie->deleteCookie($resp,'remember');
      }

      $app->session->delete('id');
      $app->auth = false;
      return $this->redirect($resp,$this->urlFor('login'));
    }

  }

 ?>
