<?php
  $app->get('/overview(/:year)',function($year=NULL ) use($app){
    if(!isset($year)){
      $year = $year= date('Y');
    }
    $data = $app->Helper->getData($app,$app->auth->user_id,$year);
    $overviewData = $app->Helper->yearOverView($app,$app->auth->user_id,$year);

    $app->render('main/dashboard.php',[
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
  })->setName('overview');
 ?>
