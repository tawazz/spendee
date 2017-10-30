<?php
namespace HTTP\Middleware;
/**
 * Remember
 */
    class Remember extends \HTTP\Middleware\BaseMiddleware {

      public function __invoke($req,$resp,$next){
        $this->run($req,$resp);
        $resp = $next($req,$resp);
        return $resp;
      }

      public function run($req,$resp){
        $app = $this->container;
        if($app->session->exists('id')){
          $user = $app->User->find($app->session->get('id'));
          if(isset($user)){
            $app->auth = $user;
          }
          $app->view->appendData([
              "auth"=>$app->auth
          ]);
        }
        $resp = $this->rememberMe($req,$resp);
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
            $user = $this->User->find($id);
            if(isset($user)){
                $this->session->put('id',$user->user_id);
                $this->container['auth'] = $user;
                $this->view->appendData([
                    "auth"=>$user
                ]);
            }else {
              $this->User->removeRemember($user->user_id);
            }
          }else{
            $resp = $this->Cookie->deleteCookie($resp,'remember');
          }
        }else{
          $resp = $resp->withStatus(302)->withHeader('Location','/login');
        }
        return $resp;
      }

    }

 ?>
