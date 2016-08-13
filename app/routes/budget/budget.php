<?php
    $app->group("/budget",$require_login(), function () use ($app) {

      $app->get('(/:year(/:month))',function($year=NULL,$month=NULL) use ($app){
        $data = getData($app,$app->auth->user_id,$year,$month,NULL);
        $budgets = $app->Budget->getBudgetData($app,$data->nav['current']['year'],$data->nav['current']['month']);
        $app->render('budget/home.php',[
          'appData' => $data,
          'budgets' => $budgets,
          'page'    => 'budget',
          'totals'  => []
        ]);
      })->name('budget.home');

      $app->post('/add',function() use ($app){

        $data = [
          "name"       => $_POST['name'],
          "amount"     => str_replace( ',', '',$_POST['amount'] ),
          "start_date" => $_POST['date'],
        ];

        $bud_id = $app->Budget->save($data);

        if(in_array(0,$_POST['tags'])){
          $tags = $app->Tags->find('all');
          foreach ($tags as $tag) {
            $data = [
              "tag_id"     => $tag->id,
              "bud_id"     => $bud_id
            ];
            $app->BudgetTag->save($data);
          }
        }
        else
        {
          foreach ($_POST['tags'] as $tag_id) {
            $data = [
              "tag_id"     => $tag_id,
              "bud_id"     => $bud_id
            ];
            $app->BudgetTag->save($data);
          }
        }


        $app->redirect($app->urlFor('budget.home'));
      })->name('budget.add');

    });

?>
