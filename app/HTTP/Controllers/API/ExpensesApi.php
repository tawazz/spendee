<?php
    namespace HTTP\Controllers\API;
    use \HTTP\Helpers\Utils;

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
          $id = $args['id'];
          return isset($id) ? $resp->withJson($app->Exp->get($id)) : $app->view->render($resp,'errors/404.php');
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
          $isRecurring = false;
          if (isset($repeat) && $repeat !=  '0') {
            $end_repeat = ($body->end_repeat == 'never') ? null : $body->repeat_until;
            $recurring = $app->RecurringExpense;
            $recurring->reminder = isset($body->reminder) ? $body->reminder : '0';
            $recurring->end_repeat = $end_repeat;
            $recurring->repeat = $repeat;
            $isRecurring = true;
          } else {
            $end_repeat = null;
          }

          $exp_data = [
              'name'=> $body->name,
              'cost'=> str_replace( ',', '',$body->cost ),
              'date'=> $body->date,
              'user_id'=> $app->auth->id,
              'is_recurring'=>$isRecurring ? 1 : 0
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
          $data = json_decode(json_encode($req->getParsedBody()));
          $updated = Utils::updateExpense($app, $data);
          if ($updated) {
            return $resp->withJson($updated,200);
          }
          return $resp->withJson(['Error'=> "Failed to update"],400);
        }
        public function delete($req, $resp,$args)
        {
          $app = $this;
          $id = $args['id'];
          $exp = $app->Exp->get($id);
          $app->Exp->read($id)->delete();
          \HTTP\Helpers\Utils::clearExpRouteCache($app,$exp->date);
          return $resp->withJson(['success' => true],200);
        }
        public function repeatOptions($req, $resp,$args)
        {
          return $resp->withJson($this->Exp->getPossbileEnumValues('repeat'));
        }
    }

 ?>
