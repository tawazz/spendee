<?php
    namespace HTTP\Controllers\API;

    /**
     *
     */
    class ExpensesApi extends \HTTP\Controllers\BaseController
    {
        public function __invoke($req, $resp,$args)
        {
            return $this->get($req,$resp,$args);
        }
        public function retrieve($req, $resp,$args)
        {
          $app = $this->container;
          $year = isset($args['year']) ? $args['year'] : Null;
          $name = isset($args['name']) ? $args['name'] : Null;

          if ($name !== Null) {
            if(!isset($year)){
              $year = $year= date('Y');
            }
            $product = $app->Exp->read($app->auth->id)->getProduct($name,$year."-"."1"."-1",($year+1)."-1-1");
            $totalexp = $app->Exp->read($app->auth->id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
            $totalinc = $app->Inc->read($app->auth->id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
            $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
            $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
            $nav = $app->Helper->getNav($year);
            $exp_monthly = [];
            $exp = isset($app->Exp->read($app->auth->id)->spentOnProduct($name,$year."-1-1",($year+1)."1-1")->cost)?$app->Exp->read($app->auth->id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost:0;
            for($i=1;$i<=12;$i++){
              $startDate = $year."-".$i."-1";
              $endDate = $year."-".($i+1)."-1";
              $exp_monthly[$i] = isset($app->Exp->read($app->auth->id)->spentOnProduct($name,$startDate,$endDate)->cost) ? $app->Exp->read($app->auth->id)->spentOnProduct($name,$startDate,$endDate)->cost : 0;
            }
            $appData = [
              'exp_total' => $totalexp,
              'inc_total' => $totalinc,
              'balance'   => $totalinc - $totalexp,
              'nav'=>$nav,
            ];

            return $resp->withJson($appData);

          }else{
            $app->view->render($resp,'errors/404.php');
          }
        }
        public function get($req,$resp,$args)
        {
          $app = $this->container;
          $cache = $app->cache;
          $year = isset($args['year']) ? $args['year'] : Null;
          $month = isset($args['month']) ? $args['month'] : Null;
          $day = isset($args['day']) ? $args['day'] : Null;
          $data = [];
          $cache_key = 'api.expenses.get.'.$app->auth->id.'.'.$year.'.'.$month;
          if (!$cache->has($cache_key)) {
            $data = $app->Helper->getItems($app->Exp,$app->auth->id,$year,$month,$day);
            $cache->set($cache_key,$data);
          } else {
            $data = $cache->get($cache_key);
          }
          return $resp->withJson($data);
        }
        public function create($req, $resp,$args)
        {
          $app = $this;
          $body = json_decode($req->getBody()->getContents());
          $repeatOptions = $this->RecurringExpense->getPossbileEnumValues('repeat');
          $repeat = in_array($body->repeat, $repeatOptions) ? $body->repeat : null ;
          $recurring = null;
          if (isset($repeat) && $repeat !=  '0') {
            $end_repeat = ($body->end_repeat == 'never') ? null : $body->repeat_until;
            $recurring = $app->RecurringExpense;
            $recurring->reminder = isset($body->reminder) ? $body->reminder : '0';
            $recurring->end_repeat = $end_repeat;
            $recurring->repeat = $repeat;
          } else {
            $end_repeat = null;
          }

          $exp_data = [
              'name'=> $body->name,
              'cost'=> str_replace( ',', '',$body->cost ),
              'date'=> $body->date,
              'user_id'=> $app->auth->id,
          ];
          try {
            $exp_id = $app->Exp->save($exp_data);

            if (isset($recurring)) {
              $recurring->exp_id = $exp_id;
              $recurring->save();
            }

            foreach ($body->tags as $tag_id) {
                $tags_data = [
                  'exp_id' => $exp_id,
                  'tag_id' => $tag_id
                ];
                $app->ExpTags->save($tags_data);
            }

            if (isset($body->location) && !empty($body->location->lat) && !empty($body->location->long) && !empty($body->location->name) ) {
              $loc = $this->Location;
              $loc->name = $body->location->name;
              $loc->lat = $body->location->lat;
              $loc->long = $body->location->long;
              $loc->exp_id = $exp_id;
              $loc->save();
            }

            // clear cache
            \HTTP\Helpers\Utils::clearExpRouteCache($app,$body->date);
            return $resp->withJson($app->Exp->get($exp_id),200);
          } catch (\Exception $e) {
            return $resp->withJson($e->getMessage(),400);
          }
        }

        public function update($req, $resp,$args)
        {
          $app = $this;
          $id = $args['id'];
          $data = [
            'name'=> $_POST['name'],
            'cost'=> str_replace( ',', '',$_POST['cost'] ),
            'date'=> $_POST['date'],
          ];
          $app->Exp->read($id)->set($data);
          $exp_tags = $app->ExpTags->find("all",[
             "where"=>["exp_id", "=" ,$exp_id]
          ]);
          foreach ($exp_tags as $tag) {
              $app->ExpTags->read($tag->id)->delete();
          }
          if (isset($_POST['tags'])) {
             foreach ($_POST['tags'] as $tag_id) {
                 $tags_data = [
                   'exp_id' => $id,
                   'tag_id' => $tag_id
                 ];
                 $app->ExpTags->save($tags_data);
             }
          }
          return $this->redirect($resp,$app->urlFor('expense.retrieve',['name'=>$_POST['name']."#".$id]));
        }
        public function delete($req, $resp,$args)
        {
          $app = $this;
          $id = $args['id'];
          $app->Exp->read($id)->delete();
          return $this->redirect($resp,$app->urlFor('expense.retrieve',['name'=>$_POST['name']]));
        }
        public function repeatOptions($req, $resp,$args)
        {
          return $resp->withJson($this->Exp->getPossbileEnumValues('repeat'));
        }
    }

 ?>
