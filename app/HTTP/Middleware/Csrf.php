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
    $this->check($req);
    $resp = $next($req,$resp);
    return $resp;
  }

  public function check($req){
    $session = $this->session;
    $hash = $this->hash;

    if (!$session->exists($this->key)) {
      $session->put($this->key,$hash->make($hash->salt(10)));
    }
    $token = $session->get($this->key);
    if (strpos($req->getUri()->getPath(), 'api') === false) {

      if (in_array($req->getMethod(),['POST','PUT','DELETE'])) {
        $submited_token = $req->getParam($this->key) ? : "";
        if (!$hash->check($token,$submited_token)) {
           throw new Exception("CSRF token mismatch ");
        }
      }
      unset($_POST[$this->key]);
      $this->view->appendData([
        'csrf_key'=>$this->key,
        'csrf_token'=>$token
      ]);
    }
  }

}

 ?>
