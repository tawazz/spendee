<?php
    $app->group("/budget",$require_login(), function () use ($app) {

      $app->get('/',function() use ($app){
        $data = getData($app,$app->auth->user_id,2016,8,NULL);
        $app->render('budget/home.php',[
          'appData' => $data,
          'page'    => 'budget',
          'totals'  => []
        ]);
      })->name('budget.home');

    });

?>
