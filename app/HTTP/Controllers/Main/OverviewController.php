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
      $data = $app->Helper->getData($app,$app->auth->id,$year);
      $overviewData = $app->Helper->yearOverView($app,$app->auth->id,$year);

      $app->view->render($resp,'main/dashboard.php',[
        'totalExp'=>$overviewData['totalExp'],
        'totalInc'=>$overviewData['totalInc'],
        'allIncomes'=>$overviewData['allIncomes'],
        'allExpenses'=>$overviewData['allExpenses'],
        'earned'=>$overviewData['earned'],
        'spent'=>$overviewData['spent'],
        'appData' => $data,
        'page'    => 'overview',
        'totals'  => []
      ]);
    }
  }

 ?>
