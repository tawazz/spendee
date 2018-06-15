<?php
namespace HTTP\Controllers\WebHooks;
use \HTTP\Helpers\Utils;
/**
 * WebHooksController
 */
class WebHooksController extends \HTTP\Controllers\BaseController
{
  public function import($req,$resp,$args)
  {
    $container = $this->container;
    $container->auth = $container->User->find(7);
    $pb = $container->pb;
    $response = [];
    try{
      $expenses = json_decode($req->getBody()->getContents());
        foreach ($expenses as $exp) {
          $is_valid = (property_exists($exp, "item") && property_exists($exp, "date")
          && property_exists($exp, "amount"));
          if ($is_valid) {
            $big_name = explode(" ",$exp->item);
            if(sizeof($big_name) >= 3){
              array_splice($big_name, 3);
            }
            $name = join(' ', $big_name);
            $data = [
              "name" => ucwords(strtolower(trim($name))),
              "date" => trim($exp->date),
              "cost" => $exp->amount,
              "user_id" => $container->auth->id
            ];
            $hash = $container->hash->make(serialize($data));
            if($container->Exp->isUniqueHash($hash)){
              $data['hash'] = $hash;
              Utils::addExpense($container,(object) $data); // TODO: make this faster, change to single insert 
              array_push($response, $data);
            }
          } else {
            return $resp->withJson(["error"=> "invalid webhook data"],400);
          }
        }
      if(sizeof($response) > 0) {
        $pb->allDevices()->pushNote("Spendee - import completed",sizeof($response)." expenses imported");
        $container->log->debug('expenses imported', $response);
      }
      return $resp->withJson($response,200);
    } catch (\Exception $e) {
      return $resp->withJson(["error" => $e->getMessage()] ,400);
    }
  }

}

 ?>
