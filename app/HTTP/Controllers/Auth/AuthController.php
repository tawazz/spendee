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
          if (!$exists->active) {
            $this->flash->addMessage("global","Check your email to activate your account");
            return $this->redirect($resp,$this->urlFor('login'));
          }
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

    public function activate($req, $resp, $args)
    {
      $email = $req->getParam('email');
      $active_hash = $req->getParam('active_hash');

      $user = $this->User->where('email',$email)
                   ->where('active',false)
                   ->first();
      if (!$user || !$user->active_hash == $active_hash) {
        $this->flash->addMessage("global","There was a problem activating your account");
        return $this->redirect($resp,$this->urlFor('login'));
      } else {
        $user->activateAccount();
        $this->flash->addMessage("global","Account Activated. login below");
        return $this->redirect($resp,$this->urlFor('login'));
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
        $active_hash = $this->hash->unique();
        $data = [
          "email" => $req->getParam('email'),
          "username"=> $req->getParam('username'),
          "password"=> $this->hash->make($req->getParam('password'),$req->getParam('username')),
          'firstname'=>$req->getParam('firstname'),
          'lastname'=>$req->getParam('lastname'),
          'email'=>strtolower($req->getParam('email')),
          'active_hash' => $active_hash
        ];

        $user->create($data);
        $this->queue->push(\HTTP\Jobs\Handlers\RegestrationEmailHandler::class,[
          'to' => $data['email'],
          'username' => $data['username'],
          'active_hash' => $data['active_hash'],
          'heading' => 'Welcome to Spendee'
        ]);
        $this->flash->addMessage("global","you registered succesfully. Check your email to activate your account.");
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

    public function resetPassword($req,$resp,$args)
    {
      if ($this->hash->make($req->getParam('old_password'),$this->auth->username) == $this->auth->password) {
        $new_pass= $req->getParam('new_password');
        $rep_pass= $req->getParam('repeat_password');
        $validate = [
          "new_password" => $this->User->validation_rules['password'],
          "repeat_password" =>$this->User->validation_rules['password_again']
        ];
        if($this->User->validate($_POST,$validate)){
          $this->User->find($this->auth->id)->update([
            'password' => $this->hash->make($new_pass,$this->auth->username)
          ]);
          $this->queue->push(\HTTP\Jobs\Handlers\PasswordChangedEmailHandler::class,[
            'to' => $this->auth->email,
            'username' => $this->auth->username,
            'heading' => "Password Changed"
          ]);
          $this->flash->addMessage("global","password changed");
          return $this->redirect($resp,'/settings');
        } else {
          $this->flash->addMessage("global","passwords dont match");
          return $this->redirect($resp,'/settings');
        }
      }else{
        $this->flash->addMessage("global","old passwords dont match");
        return $this->redirect($resp,'/settings');
      }
    }

    public function forgotPassword($req,$resp,$args)
    {
      $email = $req->getParam('email');
      $user = $this->User->where('email',$email)->first();
      if (isset($user)) {
        $recover_hash = $this->hash->unique();
        $user->recover_hash = $recover_hash;
        $user->save();
        $this->queue->push(\HTTP\Jobs\Handlers\ResetPasswordEmailHandler::class,[
          'to' => $user->email,
          'username' => $user->username,
          'recover_hash' => $recover_hash,
          'heading' => 'Reset Password'
        ]);

      }
      $this->flash->addMessage("global","check your email for password reset instructions");
      return $this->redirect($resp,$this->urlFor('login'));
    }

  }

 ?>
