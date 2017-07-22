<?php
    namespace HTTP\Controllers\API;

    /**
     *
     */
    class Totals extends \HTTP\Controllers\BaseController
    {
        public function __invoke($req, $resp,$args)
        {
            return $this->list($req,$resp,$args);
        }

        public function retrieve($req, $resp,$args)
        {

        }

        public function list($req, $resp, $args)
        {
            $app = $this->container;
            $year = isset($args['year']) ? $args['year'] : Null;
            $month = isset($args['month']) ? $args['month'] : Null;
            $day = isset($args['day']) ? $args['day'] : Null;
            $data = $app->Helper->getTotals($app->auth->id,$year,$month,$day);

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
