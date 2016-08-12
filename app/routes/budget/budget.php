<?php
    $app->group("/budget",$require_login(), function () use ($app) {

      $app->get('(/:year(/:month))',function($year=NULL,$month=NULL) use ($app){
        $data = getData($app,$app->auth->user_id,$year,$month,NULL);
        $app->render('budget/home.php',[
          'appData' => $data,
          'page'    => 'budget',
          'totals'  => []
        ]);
      })->name('budget.home');

    });

?>
