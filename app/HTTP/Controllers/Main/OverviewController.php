<?php
  namespace HTTP\Controllers\Main;
  /**
   * OverviewController
   */
  class OverviewController extends \HTTP\Controllers\BaseController
  {
    public function __invoke($req,$resp,$args)
    {
      $app = $this->container;
      $year = isset($args['year'])?$args['year']:NULL;
      if(!isset($year)){
        $year = $year= date('Y');
      }
      $overviewData = $app->Helper->yearOverView($app,$app->auth->id,$year);
      return $resp->withJson($overviewData);
    }
  }

 ?>
