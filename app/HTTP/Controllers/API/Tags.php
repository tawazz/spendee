<?php
    namespace HTTP\Controllers\API;
    /**
     *
     */
    class Tags extends \HTTP\Controllers\BaseController
    {
        public function __invoke($req, $resp,$args)
        {
            return $this->get($req,$resp,$args);
        }

        public function retrieve($req, $resp,$args)
        {
            $app = $this->container;
            return $resp->withJson($app->Tags->get($args['id']));
        }

        public function get($req, $resp, $args)
        {
            $app = $this->container;
            $cache = $app->cache;
            $type = $req->getParam('type');
            $cache_key = isset($type) ? 'api.get.tags'.$type : 'api.get.tags' ;

            if (!$cache->has($cache_key)) {
              if (isset($type)) {
                $data = $app->Tags->getTags($type);
              } else {
                $data = $app->Tags->find('all');
              }
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
