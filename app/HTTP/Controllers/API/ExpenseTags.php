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
            $year = isset($args['year']) ? $args['year'] : Null;
            $month = isset($args['month']) ? $args['month'] : Null;
            $day = isset($args['day']) ? $args['day'] : Null;
            $cache = $app->cache;
            $cache_key = 'api.exp.tags'.$app->auth->id.'.'.$year.'.'.$month;
            $data = [];
            if (!$cache->has($cache_key)) {
              $data = $app->Helper->getExpenseTags($app,$app->auth->id,$year,$month,$day);
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
