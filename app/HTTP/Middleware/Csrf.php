<?php
namespace HTTP\Middleware;
use HTTP\Middleware\BaseMiddleware;

/**
 *
 */
class Csrf extends BaseMiddleware {
  protected $key;

  public function __invoke($req,$resp,$next){

    $this->key = "csrf_token";
    $resp = $this->check($req,$resp);
    $resp = $next($req,$resp);
    return $resp;
  }

  public function check($req,$resp){
    $session = $this->session;
    $hash = $this->hash;

    if (!$session->exists($this->key)) {
      $token = $hash->make($hash->salt(10));
      $session->put($this->key,$token);
    }
    $token = $session->get($this->key);

    if (in_array($req->getMethod(),['POST','PUT','DELETE'])) {
      $submited_token = $req->getParam($this->key) ? : "";
      if (empty($submited_token)) {
        $submited_token = $this->Cookie->getCookie($req,$this->key)->getValue() ? : "";
      }
      if (!$hash->check($token,$submited_token)) {
         throw new \Exception("CSRF token mismatch ");
      }
    }
    unset($_POST[$this->key]);
    $this->view->appendData([
      'csrf_key'=>$this->key,
      'csrf_token'=>$token
    ]);
    $resp = $this->Cookie->deleteCookie($resp,$this->key);
    $resp = $this->Cookie->setCookie($resp,$this->key,$token);
    return $resp;
  }

}

 ?>
