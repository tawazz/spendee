<?php
    namespace HTTP\Controllers\API;
    use \HTTP\Helpers\{Utils,INGImporter,AmexImporter, UpImporter};
    use \Tazzy\Utils\File;

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
          if (isset($month) && $month > 13) {
            return $this->notFound();
          }
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
          $hash = $app->hash->make(serialize([
            "name" => $body->name,
            "date" => $body->date,
            "cost" => $body->cost,
            "user_id" => $app->auth->id
          ]));
          $body->hash = $hash;
          try{
            $exp = \HTTP\Helpers\Utils::addExpense($app,$body);
            return $resp->withJson($exp,200);
          } catch (\Exception $e) {
            return $resp->withJson($e->getMessage(),400);
          }
        }

        public function update($req, $resp,$args)
        {
          $app = $this;
          $data = json_decode(json_encode($req->getParsedBody()));
          $updated = Utils::updateExpense($app,$data);
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

        public function import($req, $resp,$args)
        {
          try {
            $bank = $req->getParam('bank');
            $file = new File($_FILES['expenses']);
            $file->allowed = ['csv'];
            if (!$file->error() && $file->isAllowed()) {
              $hash = $this->hash->unique();
              $folder = "/app/assets/imports";
              if(!is_dir($folder)) {
                mkdir($folder);
              }
              $path = '/app/assets/imports/'.$hash.'.csv';
              $file->move($path);
              switch ($bank) {
                case 'ing':
                    $ing_importer = new INGImporter($this->container,$path);
                    $ing_importer->import();
                  break;
                case 'amex':
                    $amex_importer = new AmexImporter($this->container,$path);
                    $amex_importer->import();
                  break;
                case 'up':
                    $up_importer = new UpImporter($this->container,$path, true);
                    $up_importer->import();
                  break;
                default:
                    $ing_importer = new INGImporter($this->container,$path);
                    $ing_importer->import();
                  break;
              }
              return $resp->withJson(["success" => true]);
            }
          } catch (Exception $e) {
            return $resp->withStatus(400)->withJson(["error" => $e->getMessage()]);
          }
          return $resp->withStatus(400);
        }

        public function autoTag($req,$resp, $args)
        {
          try {
            $Carbon = $this->container->Carbon;
            $date = ($req->getParam('date') != null) ? $req->getParam('date').'-1' : $Carbon->now()->month(1)->day(1)->toDateString();
            $date = $Carbon->createFromFormat('Y-m-d',$date);
            $start = $date->toDateString();
            $end = $Carbon->now()->toDateString();
            \HTTP\Helpers\Utils::relatedTags($this->container,$start,$end);
            return $resp->withJson(["success" => true]);
          } catch (Exception $e) {
            return $resp->withStatus(400)->withJson(["error" => $e->getMessage()]);
          }
        }

        public function nomalize($req,$resp, $args)
        {
          try {
            \HTTP\Helpers\Utils::nomalize($this->container);
            return $resp->withJson(["success" => true]);
          } catch (Exception $e) {
            return $resp->withStatus(400)->withJson(["error" => $e->getMessage()]);
          }
        }
    }

 ?>
