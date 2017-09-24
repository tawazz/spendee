<?php
    namespace HTTP\Controllers\API;
    use \HTTP\Helpers\Utils;

    /**
     *
     */
    class IncomesApi extends \HTTP\Controllers\BaseController
    {
        public function __invoke($req, $resp,$args)
        {
            return $this->list($req,$resp,$args);
        }
        public function retrieve($req, $resp,$args)
        {
          $app = $this->container;
          $id = $args['id'];
          return isset($id) ? $resp->withJson($app->Inc->get($id)) : $app->view->render($resp,'errors/404.php');
        }
        public function list($req,$resp,$args)
        {
          $app = $this->container;
          $year = isset($args['year']) ? $args['year'] : Null;
          $month = isset($args['month']) ? $args['month'] : Null;
          $day = isset($args['day']) ? $args['day'] : Null;
          $data = $app->Helper->getItems($app->Inc,$app->auth->id,$year,$month,$day);

          return $resp->withJson($data);
        }
        public function create($req, $resp,$args)
        {
          $app = $this;
          $body = (object) $req->getParsedBody();
          $data = [
              'name'=> $body->name,
              'cost'=> Utils::fixMoneyInput($body->cost),
              'date'=> $body->date,
              'user_id'=> $app->auth->id
          ];
          $exp_id = $app->Inc->save($data);

          return $resp->withJson(['success' => true],200);
        }
        public function update($req, $resp,$args)
        {
          $app = $this;
          $id = $args['id'];
          $data = json_decode(json_encode($req->getParsedBody()));
          $data = [
            'name'=> $data->name,
            'cost'=> Utils::fixMoneyInput($data->cost),
            'date'=> $date->date
          ];
          $app->Inc->read($id)->set($data);
          return $resp->withJson(['success' => true],200);
        }
        public function delete($req, $resp,$args)
        {
          $app = $this;
          $id = $args['id'];
          $app->Inc->read($id)->delete();
          return $resp->withJson(['success' => true],200);
        }
    }

 ?>
