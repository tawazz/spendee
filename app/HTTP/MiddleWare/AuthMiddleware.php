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
      $user = $app->User;
      if($user->read($app->session->get('id'))){
        $app['auth'] = $user->get();
      }
      $app->view->appendData([
          "auth"=>$app->auth
      ]);
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
        return $this->Cookie->deleteCookie($resp,'remember');
      }
    }
    else{
      $this->session->flash('global','Login required to access the resource');
      $resp = $resp->withStatus(302)->withHeader('Location','/login');

    }
    return $resp;
  }


}

?>
