<?php
use Slim\Middleware;

/**
 *
 */
class Csrf extends Middleware{
  protected $key;

  public function call (){

    $this->key = "csrf_token";
    $this->app->hook('slim.before',[$this,'check']);
    $this->next->call();
  }

  public function check(){
    $session = $this->app->session;
    $hash = $this->app->hash;
    if (!$session->exists($this->key)) {
      $session->put($this->key,$hash->make($hash->salt(10)));
    }
    $token = $session->get($this->key);

    if (in_array($this->app->request()->getMethod(),['POST','PUT','DELETE'])) {
      $submited_token = $this->app->request()->post($this->key) ? : "";
      if (!$hash->check($token,$submited_token)) {
         throw new Exception("CSRF token mismatch ");
      }
    }
    unset($_POST[$this->key]);
    $this->app->view()->appendData([
      'csrf_key'=>$this->key,
      'csrf_token'=>$token
    ]);
  }

}

 ?>
