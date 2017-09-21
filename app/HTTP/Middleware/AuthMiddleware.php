<?php
namespace HTTP\Middleware;
use GuzzleHttp\Exception\RequestException;

/**
*
*/
class AuthMiddleware extends \HTTP\Middleware\BaseMiddleware {

  public function __invoke($req,$resp,$next){
    $resp = $this->run($req,$resp);
    $resp = $next($req,$resp);
    return $resp;
  }

  protected function run($req,$resp){
    $app = $this->container;
    if($app->session->exists('id')){
      $user = $app->User->find($app->session->get('id'));
      if($user->exists){
        $app['auth'] = $user;
        $app->view->appendData([
            "auth"=>$user
        ]);
      } else {
        $this->flash->addMessage('global','Login required to access the resource');
        $resp = $resp->withStatus(302)->withHeader('Location','/login');
      }
    }else{
      $resp = $this->rememberMe($req,$resp);
    }
    return $resp;
  }

  protected function rememberMe($req,$resp)
  {
    if ($this->Cookie->getCookie($req,'remember')->getValue() !== Null && !$this->container->auth) {
      $cookie = $this->Cookie->getCookie($req,'remember');
      $hash = $cookie->getValue();
      $exist = $this->Remember->find('first',['where'=>['hash','=',$hash]]);
      if(isset($exist))
      {
        $id = $exist->user_id;
        $user = $this->User->find($id);
        if($user){
            $this->session->put('id',$user->id);
            $this->container->auth = $user;
            $this->view->appendData([
                "auth"=>$this->auth
            ]);
        }else {
          $this->User->removeRemember($user->user_id);
          $this->flash->addMessage('global','Login required to access the resource');
          $resp = $this->Cookie->deleteCookie($resp,'remember');
          $resp->withStatus(302)->withHeader('Location','/login');
        }
      }else{
        $this->flash->addMessage('global','Login required to access the resource');
        $resp = $this->Cookie->deleteCookie($resp,'remember');
        $resp->withStatus(302)->withHeader('Location','/login');
      }
    }
    else{
      $this->flash->addMessage('global','Login required to access the resource');
      $resp = $resp->withStatus(302)->withHeader('Location','/login');

    }
    return $resp;
  }


}

?>
