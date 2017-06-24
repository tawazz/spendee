<?php
namespace HTTP\Middleware;
use GuzzleHttp\Exception\RequestException;

/**
*
*/
class AuthMiddleware extends \HTTP\Middleware\BaseMiddleware {

  public function __invoke($req,$resp,$next){
    /*
    if (!$this->auth && !$this->session->get('id')){
      $this->session->flash('global','Login required to access the resource');
      return $resp->withRedirect('/login');
    }
    if ($this->session->get('id')) {
        //dump($this->container);
        die('auth middleware');
    }*/
    $this->run($req,$resp);
    $resp = $next($req,$resp);
    return $resp;
  }

  protected function run($req,$resp){
    $app = $this->container;
    if($app->session->exists('id')){
      $user = $app->User;
      if($user->read($app->session->get('id'))){
        $app->auth = $user->get();
      }
      $app->view->appendData([
          "auth"=>$app->auth
      ]);
    }
    $this->rememberMe($req,$resp);
  }

  protected function rememberMe($req,$resp)
  {
    if ($this->Cookie->getCookie($req,'remember') && !$this->auth) {
      $cookie = $this->Cookie->getCookie($req,'remember');
      $hash = $cookie->getValue();
      $exist = $this->Remember->find('first',['where'=>['hash','=',$hash]]);
      if(isset($exist))
      {
        $id = $exist->user_id;
        $user = $this->User->find('first',[
            'where'=>['user_id','=',$id]
        ]);
        if($user){
            $this->session->put('id',$user->user_id);
            $this->container->auth = $user;
            $this->view->appendData([
                "auth"=>$this->auth
            ]);
        }else {
          $this->User->removeRemember($user->user_id);
        }
      }else{
        $this->Cookie->deleteCookie($resp,'remember');
      }
    }
    else{
      $this->session->flash('global','Login required to access the resource');
      return $resp->withStatus(302)->withHeader('Location','/login');
    }
  }


}

?>
