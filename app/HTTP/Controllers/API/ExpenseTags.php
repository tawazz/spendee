<?php
namespace HTTP\Controllers\API;

/**
*
*/
class ExpenseTags extends \HTTP\Controllers\BaseController
{
  public function __invoke($req, $resp,$args)
  {
    return $this->get($req,$resp,$args);
  }

  public function retrieve($req, $resp,$args)
  {

  }

  public function get($req, $resp, $args)
  {
    $app = $this->container;
    $with_detail = $req->getParam('detail') ? $req->getParam('detail') == "true" : false;
    $year = isset($args['year']) ? $args['year'] : Null;
    $month = isset($args['month']) ? $args['month'] : Null;
    $id =  $req->getParam('tag_id') ? $req->getParam('tag_id') : null;
    $cache = $app->cache;
    $detail_cache_key = ($with_detail) ? 'with_detail' : 'with_out_detail';
    $cache_key = 'api.exp.tags.'.$app->auth->id.'.'.$year.'.'.$month.'.'.$detail_cache_key;
    $data = [];
    if (true ) { # !$cache->has($cache_key)
      $data = $app->Helper->getExpenseTags($app,$app->auth->id,$with_detail,$id,$year,$month);
      $cache->set($cache_key,$data);
    } else {
      $data = $cache->get($cache_key);
    }

    return $resp->withJson($data);
  }

  public function create($req, $resp,$args)
  {

  }

  public function update($req, $resp,$args)
  {

  }

  public function delete($req, $resp,$args)
  {

  }
}

?>
