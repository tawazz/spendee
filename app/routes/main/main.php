<?php
  $app->post('/expenses/add',$require_login(),function() use($app){
    $data = [
        'name'=> $_POST['name'],
        'cost'=> str_replace( ',', '',$_POST['cost'] ),
        'date'=> $_POST['date'],
        'user_id'=> $app->auth->user_id
      ];

      $exp_id = $app->Exp->save($data);
      foreach ($_POST['tags'] as $tag_id) {
          $tags_data = [
            'exp_id' => $exp_id,
            'tag_id' => $tag_id
          ];
          $app->ExpTags->save($tags_data);
      }

      $app->response->redirect($app->urlFor('expenses'));
  });
  $app->post('/incomes/add',$require_login(),function() use($app){

      $data = [
          'name'=> $_POST['name'],
          'cost'=> str_replace( ',', '',$_POST['cost'] ),
          'date'=> $_POST['date'],
          'user_id'=> $app->auth->user_id
        ];
      $app->Inc->save($data);
      $app->response->redirect($app->urlFor('incomes'));
  });
  $app->post('/expense/update',$require_login(),function() use($app){
      $exp_id = $_POST['exp_id'];
      $data = [
        'name'=> $_POST['name'],
        'cost'=> str_replace( ',', '',$_POST['cost'] ),
        'date'=> $_POST['date'],
      ];
      $app->Exp->read($exp_id)->set($data);
      $app->response->redirect($app->urlFor('exp',['name'=>$_POST['name']."#".$exp_id]));
  });
  $app->post('/expense/delete',$require_login(),function() use($app){
      $exp_id = $_POST['exp_id'];
      $app->Exp->read($exp_id)->delete();
      $app->response->redirect($app->urlFor('exp',['name'=>$_POST['name']]));
  });
  $app->post('/income/update',$require_login(),function() use($app){
      $id = $_POST['inc_id'];
      $data = [
        'name'=> $_POST['name'],
        'cost'=>str_replace( ',', '',$_POST['cost'] ),
        'date'=> $_POST['date'],
      ];
      $app->Inc->read($id)->set($data);
      $app->response->redirect($app->urlFor('inc',['name'=>$_POST['name']."#".$id]));
  });
  $app->post('/income/delete',$require_login(),function() use($app){
      $id = $_POST['inc_id'];
      $app->Inc->read($id)->delete();
      $app->response->redirect($app->urlFor('inc',['name'=>$_POST['name']]));
  });
  //expenses
  $app->get('/expenses(/:year(/:month(/:day)))',$require_login(),function($year = NULL,$month = NULL,$day=NULL) use ($app){

      $data = $app->Helper->getData($app,$app->auth->user_id,$year,$month,$day);
      $app->render('main/expenses.php',[
        'appData' => $data,
        'page'    => 'expenses',
        'totals'  => []
      ]);
  })->name('expenses');
  //Incomes
  $app->get('/incomes(/:year(/:month(/:day)))',$require_login(),function($year = NULL,$month = NULL,$day=NULL) use ($app){
    $data = $app->Helper->getData($app,$app->auth->user_id,$year,$month,$day);
    $app->render('main/incomes.php',[
      'appData' => $data,
      'page'    => 'incomes',
      'totals'  => []
    ]);
  })->name('incomes');

  $app->get('/overview(/:year)',$require_login(),function($year=NULL ) use($app){
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
  })->name('overview');

  $app->get('/account',$require_login(),function() use($app){
      $app->render('user/account.php');
  })->name('account');

  $app->get('/expense/:name(/:year)',$require_login(),function($name=NULL,$year=NULL) use($app){
    if(!isset($year)){
      $year = $year= date('Y');
    }
    $product = $app->Exp->read($app->auth->user_id)->getProduct($name,$year."-"."1"."-1",($year+1)."-1-1");
    $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
    $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
    $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
    $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
    $nav = $app->Helper->getNav($year);
    $exp_monthly = [];
    $exp = isset($app->Exp->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year+1)."1-1")->cost)?$app->Exp->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost:0;
    for($i=1;$i<=12;$i++){
      $startDate = $year."-".$i."-1";
      $endDate = $year."-".($i+1)."-1";
      $exp_monthly[$i] = isset($app->Exp->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost) ? $app->Exp->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost : 0;
    }
    $appData = [
      'exp_total' => $totalexp,
      'inc_total' => $totalinc,
      'balance'   => $totalinc - $totalexp,
      'nav'=>$nav,
    ];
    $app->render('main/exp.php',[
      'monthly_exp'=>$exp_monthly,
      'exp'=>$exp,
      'name'=>$name,
      'appData'=>$appData,
      'page'=>'expense/'.$name,
      'products'=>$product
    ]);
  })->name('exp');

  $app->get('/income/:name(/:year)',$require_login(),function($name=NULL,$year=NULL) use($app){
    if(!isset($year)){
      $year = $year= date('Y');
    }
    $product = $app->Inc->read($app->auth->user_id)->getProduct($name,$year."-"."1"."-1",($year+1)."-1-1");
    $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
    $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
    $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
    $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
    $nav = $app->Helper->getNav($year);
    $inc_monthly = [];
    $inc = isset($app->Inc->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost)?$app->Inc->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost:0;
    for($i=1;$i<=12;$i++){
      $startDate = $year."-".$i."-1";
      $endDate = $year."-".($i+1)."-1";
      $inc_monthly[$i] = isset($app->Inc->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost) ? $app->Inc->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost : 0;
    }
    $appData = [
      'exp_total' => $totalexp,
      'inc_total' => $totalinc,
      'balance'   => $totalinc - $totalexp,
      'nav'=>$nav,
    ];
    $app->render('main/inc.php',[
      'appData' => $appData,
      'page' => 'income/'.$name,
      'monthly_inc'=>$inc_monthly,
      'inc'=>$inc,
      'name'=>$name,
      'products'=>$product
    ]);
  })->name('inc');

 ?>
