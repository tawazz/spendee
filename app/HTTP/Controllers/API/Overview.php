<?php
    namespace HTTP\Controllers\API;
    /**
     *
     */
    class Overview extends \HTTP\Controllers\BaseController
    {
        public function __invoke($req, $resp,$args)
        {
            return $this->get($req,$resp,$args);
        }

        public function get($req, $resp, $args)
        {
            $app = $this->container;
            $year = $year = isset($args['year']) ? $args['year'] : date('Y');
            $data = $app->Helper->yearOverView($app,$app->auth->id,$year);
            return $resp->withJson($data);
            $cache = $app->cache;
            $cache_key = 'api.get.overview.'.$year;

            if (!$cache->has($cache_key)) {
              $data = $app->Helper->yearOverView($app,$app->auth->id,$year);
              $cache->set($cache_key,$data);
            } else {
              $data = $cache->get($cache_key);
            }
            return $resp->withJson($data);
        }
    }

 ?>
