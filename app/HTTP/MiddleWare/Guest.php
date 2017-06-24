<?php
namespace HTTP\Middleware;
use GuzzleHttp\Exception\RequestException;

/**
*
*/
class Guest extends \HTTP\Middleware\BaseMiddleware {

  public function __invoke($req,$resp,$next){
    $resp = $this->run($req,$resp);
    $resp = $next($req,$resp);
    return $resp;
  }

  protected function run($req,$resp){
    $app = $this->container;
    if($app->session->exists('id')){
      $resp = $resp->withStatus(302)->withHeader('Location',$this->container->router->pathFor('expenses'));
    }
    return $resp;
  }

}

?>
