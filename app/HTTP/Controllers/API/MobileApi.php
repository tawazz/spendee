<?php
    namespace HTTP\Controllers\API;
    use \HTTP\Helpers\Utils;

    /**
     *
     */
    class MobileApi extends \HTTP\Controllers\BaseController
    {
        public function __invoke($req, $resp,$args)
        {
            return $this->get($req,$resp,$args);
        }

        public function auth($req, $resp,$args)
        {
          $app = $this->container;
          $body = json_decode($req->getBody()->getContents());
          $user = $this->User;
          $fetch_user = $user->where('username',$body->auth->username)->first();

          if($fetch_user && $app->hash->make($body->auth->password,$body->auth->username) == $fetch_user->password ){
            $response = [
              'auth'=>true,
              "user"=>$fetch_user
            ];
          }else{
            $response = [
              'auth'=>false
            ];
            $resp = $resp->withStatus(403);
          }

          return $resp->withJson($response);
        }

        public function get($req, $resp, $args)
        {
            $app = $this->container;
            $year = isset($args['year']) ? $args['year'] : Null;
            $month = isset($args['month']) ? $args['month'] : Null;
            $day = isset($args['day']) ? $args['day'] : Null;
            $data = $app->Budget->getBudgetData($app,$year,$month);
            return $resp->withJson($data);
            $cache = $app->cache;
            $cache_key = 'api.budget.'.$app->auth->id.'.'.$year.'.'.$month;
            $data = [];
            if (!$cache->has($cache_key)) {
              $data = $app->Budget->getBudgetData($app,$year,$month);
              $cache->set($cache_key,$data);
            } else {
              $data = $cache->get($cache_key);
            }

            return $resp->withJson($data);
        }

        public function create($req, $resp,$args)
        {
          $app = $this->container;
          $body = json_decode($req->getBody()->getContents());
          $data = [
            "name"       => $body->name,
            "amount"     => Utils::fixMoneyInput($body->amount),
            "date" => $body->date,
            "user_id"    => $app->auth->id
          ];
          $bud_id = $app->Budget->save($data);

          if(in_array(0,$body->tags)){
            $tags = $app->Tags->find('all');
            foreach ($tags as $tag) {
              $data = [
                "tag_id"     => $tag->id,
                "bud_id"     => $bud_id
              ];
              $app->BudgetTag->save($data);
            }
          }
          else
          {
            foreach ($body->tags as $tag_id) {
              $data = [
                "tag_id"     => $tag_id,
                "bud_id"     => $bud_id
              ];
              $app->BudgetTag->save($data);
            }
          }
          return $resp->withJson($app->Budget->get($bud_id));
        }
        public function update($req, $resp,$args)
        {
          $app = $this->container;
          $body = json_decode($req->getBody()->getContents());
          $bud_id= $args['id'];
          $app->Budget->read($bud_id)->set([
            "name"       => $body->name,
            "amount"     => Utils::fixMoneyInput($body->amount),
          ]);

          $app->BudgetTag->deleteTagsFromBudget($bud_id);

          if(in_array(0,$body->tags)){
            $tags = $app->Tags->find('all');
            foreach ($tags as $tag) {
              $data = [
                "tag_id"     => $tag->id,
                "bud_id"     => $bud_id
              ];
              $app->BudgetTag->save($data);
            }
          }
          else
          {
            foreach ($body->tags as $tag_id) {
              $data = [
                "tag_id"     => $tag_id,
                "bud_id"     => $bud_id
              ];
              $app->BudgetTag->save($data);
            }
          }
          return $resp->withJson($app->Budget->get($bud_id));
        }
        public function delete($req, $resp,$args)
        {
          $app = $this->container;
          $id = $args['id'];
          $app->Budget->read($id)->delete();
          return $resp->withJson(['success' => true]);
        }
    }

 ?>
