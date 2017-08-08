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

        }

        public function get($req, $resp, $args)
        {
            $app = $this->container;
            return $resp->withJson($app->Tags->find('all'));
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
