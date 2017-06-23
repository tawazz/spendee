<?php
namespace HTTP\Middleware;
use GuzzleHttp\Exception\RequestException;

/**
 *
 */
    class Before extends \HTTP\Middleware\BaseMiddleware {

      public function __invoke($req,$resp,$next){
        $this->run();
        $resp = $next($req,$resp);
        return $resp;
      }

      public function run(){
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
        #$this->rememberMe();
      }

      protected function rememberMe()
      {
        if ($this->app->getCookie('remember') && !$this->app->auth) {
          $hash = $this->app->getCookie('remember');
          if($this->app->debug){
              echo "Remember Cookie </br>";
              dump($hash);
          }
          $exist = $this->app->Remember->find('first',['where'=>['hash','=',$hash]]);
          if(isset($exist))
          {
            $id = $exist->user_id;
            $user = $this->app->User->find('first',[
                'where'=>['user_id','=',$id]
            ]);
            if($user){
                $this->app->session->put('id',$user->user_id);
                $this->app->auth = $user;
                $this->app->view()->appendData([
                    "auth"=>$this->app->auth
                ]);
            }else {
              $this->app->User->removeRemember($user->user_id);
            }
          }else{
            $this->app->deleteCookie('remember');
          }
        }
      }

    }

 ?>
