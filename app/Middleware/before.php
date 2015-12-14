<?php
use Slim\Middleware;

/**
 *
 */
class Before extends Middleware{

  public function call (){
    $this->app->hook('slim.before',[$this,'run']);
    $this->next->call();
  }

  public function run(){
    if($this->app->session->exists('id')){
      $user = $this->app->User;
      if($user->read($this->app->session->get('id'))){
        $this->app->auth = $user->get();
      }
      $this->app->view()->appendData([
          "auth"=>$this->app->auth
      ]);
    }
    $this->rememberMe();
  }

  protected function rememberMe()
  {
    if ($this->app->getCookie('remember') && !$this->app->auth) {
      $hash = $this->app->getCookie('remember');
      $remember = new Remember();
      $id = $remember->find('first',['where'=>['hash','=',$hash]])->user_id;
      $user = $this->app->User->find('first',[
          'where'=>['user_id','=',$id]
      ]);
      if($user){
          $this->app->session->put('id',$user->user_id);
          $this->app->auth = $user;
      }else {
        $this->app->User->removeRemember($user->user_id);
      }
    }
  }

}

 ?>
