<?php
    $app->group("/budget",$require_login(), function () use ($app) {

      $app->get('/data/:id',function($id) use ($app)
      {
        $budget = $app->Budget->read($id)->get();
        $budgetTags = $app->BudgetTag->find("all",[
          "fields" => ["tag_id"],
          "where"=>["bud_id","=",$budget->id]
        ]);
        $budget->tags = $budgetTags;
        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setBody(json_encode($budget));
      })->setName("budget.data");

      $app->post('/add',function() use ($app){

        $data = [
          "name"       => $_POST['name'],
          "amount"     => str_replace( ',', '',$_POST['amount'] ),
          "start_date" => $_POST['date'],
          "user_id"    => $app->auth->user_id
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
      })->setName('budget.add');

      $app->post('/edit',function() use ($app){

        $bud_id= $_POST['id'];
        $app->Budget->read($bud_id)->set([
          "name"       => $_POST['name'],
          "amount"     => str_replace( ',', '',$_POST['amount'] ),
        ]);

        $app->BudgetTag->deleteTagsFromBudget($bud_id);

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
      })->setName('budget.edit');

      $app->get('/edit/:id',function($id) use ($app){

        echo ($app->Budget->read($id)->delete());
      })->setName("budget.delete");

    });

    $app->get('/budget(/:year(/:month(/:day)))',$require_login(),function($year=NULL,$month=NULL) use ($app){

      $data = $app->Helper->getData($app,$app->auth->user_id,$year,$month,NULL);
      $budgets = $app->Budget->getBudgetData($app,$data->nav['current']['year'],$data->nav['current']['month']);
      $app->render('budget/home.php',[
        'appData' => $data,
        'budgets' => $budgets,
        'page'    => 'budget',
        'totals'  => []
      ]);
    })->setName('budget.home');

?>
