<?php

  $app->post('/expenses/add',$require_login(),function() use($app){
    $data = [
        'name'=> $_POST['name'],
        'cost'=> $_POST['cost'],
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
    $app->Inc->save($_POST);
      $app->response->redirect($app->urlFor('incomes'));
  });
  $app->post('/expense/update',$require_login(),function() use($app){
      $exp_id = $_POST['exp_id'];
      $data = [
        'name'=> $_POST['name'],
        'cost'=> $_POST['cost'],
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
        'cost'=> $_POST['cost'],
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
  if(isset($year)&& isset($month) && isset($day) ){
    if($month == 13){
        $month=1;
        $year +=1;
    }
    if($month == 0){
        $month=12;
        $year -=1;
    }
    $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $allExpenses = $app->Exp->read($app->auth->user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $itemSpent = $app->Exp->read($app->auth->user_id)->allActivity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $date = new DateTime($year."-".$month."-".$day);
    $date = $date->format('d/F/Y');
    $nav['next'] = "expenses/".$year."/".$month."/".($day+1);
    $nav['prev'] = "expenses/".$year."/".$month."/".($day-1);
}else if(isset($year)&& isset($month) ){
  if($month == 13){
      $month=1;
      $year +=1;
  }
  if($month == 0){
      $month=12;
      $year -=1;
  }
  $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
  $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
  $allExpenses = $app->Exp->read($app->auth->user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
  $itemSpent = $app->Exp->read($app->auth->user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
  $date = new DateTime($year."-".$month."-1");
  $date = $date->format('F/Y');
  $nav['prev'] = "expenses/".$year."/".($month-1);
  $nav['next'] = "expenses/".$year."/".($month+1);
}else if(isset($year)){
  $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
  $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
  $allExpenses = $app->Exp->read($app->auth->user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
  $itemSpent= $app->Exp->read($app->auth->user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
  $date = new DateTime($year."-1-1");
  $date = $date->format('Y');
  $nav['prev'] = "expenses/".($year-1);
  $nav['next'] = "expenses/".($year+1);
}else{
    $month= date('m');
    $year= date('Y');
    $day = date('d');
    $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
    $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
    $allExpenses = $app->Exp->read($app->auth->user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
    $itemSpent = $app->Exp->read($app->auth->user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
    $date = new DateTime($year."-".$month."-".$day);
    $date = $date->format('F/Y');
    $nav['prev'] = "expenses/".$year."/".($month-1);
    $nav['next'] = "expenses/".$year."/".($month+1);
}
$totalinc = isset($totalinc->sum)?$totalinc->sum:0;
$totalexp = isset($totalexp->sum)?$totalexp->sum:0;
//echo $app->Exp->read(7)->spentOnProduct('ebay',"2014/09/1","2015/09/1")."<br/>";
//echo $app->Exp->read(7)->biggest("2014/08/1","2015/09/1")->name . " -> ".$app->Exp->read(7)->biggest("2014/08/1","2015/09/1")->max;
$tags = $app->Tags->find('all');
$app->render('main/expenses.php',['totalExp'=>$totalexp,'totalInc'=>$totalinc,'date'=>$date,'allExpenses'=>$allExpenses,'items'=>json_decode($itemSpent),'nav'=>$nav,'tags'=>$tags]);
})->name('expenses');
    //Incomes
    $app->get('/incomes(/:year(/:month(/:day)))',$require_login(),function($year = NULL,$month = NULL,$day=NULL) use ($app){
      if(isset($year)&& isset($month) && isset($day) ){
        if($month == 13){
            $month=1;
            $year +=1;
        }
        if($month == 0){
            $month=12;
            $year -=1;
        }
        $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-".$month."-".$day,$year."-".$month."-".($day+1));
        $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-".$month."-".$day,$year."-".$month."-".($day+1));
        $allIncomes= $app->Inc->read($app->auth->user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
        $earned= $app->Inc->read($app->auth->user_id)->allActivity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
        $date = new DateTime($year."-".$month."-".$day);
        $date = $date->format('d/F/Y');
        $nav['next'] = "incomes/".$year."/".$month."/".($day+1);
        $nav['prev'] = "incomes/".$year."/".$month."/".($day-1);
    }else if(isset($year)&& isset($month) ){
      if($month == 13){
          $month=1;
          $year +=1;
      }
      if($month == 0){
          $month=12;
          $year -=1;
      }
      $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
      $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
      $allIncomes = $app->Inc->read($app->auth->user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
      $earned= $app->Inc->read($app->auth->user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
      $date = new DateTime($year."-".$month."-1");
      $date = $date->format('F/Y');
      $nav['prev'] = "incomes/".$year."/".($month-1);
      $nav['next'] = "incomes/".$year."/".($month+1);
    }else if(isset($year)){
      $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
      $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
      $allIncomes = $app->Inc->read($app->auth->user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
      $earned= $app->Inc->read($app->auth->user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
      $date = new DateTime($year."-1-1");
      $date = $date->format('Y');
      $nav['prev'] = "incomes/".($year-1);
      $nav['next'] = "incomes/".($year+1);
    }else{
        $month= date('m');
        $year= date('Y');
        $day = date('d');
        $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
        $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
        $allIncomes= $app->Inc->read($app->auth->user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
        $earned= $app->Inc->read($app->auth->user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
        $date = new DateTime($year."-".$month."-".$day);
        $date = $date->format('F/Y');
        $nav['prev'] = "incomes/".$year."/".($month-1);
        $nav['next'] = "incomes/".$year."/".($month+1);
    }
    $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
    $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
    //echo $app->Exp->read(7)->spentOnProduct('ebay',"2014/09/1","2015/09/1")."<br/>";
    //echo $app->Exp->read(7)->biggest("2014/08/1","2015/09/1")->name . " -> ".$app->Exp->read(7)->biggest("2014/08/1","2015/09/1")->max;
    $app->render('main/incomes.php',['totalExp'=>$totalexp,'totalInc'=>$totalinc,'date'=>$date,'allIncomes'=>$allIncomes,'nav'=>$nav,'earned'=>json_decode($earned)]);
  })->name('incomes');

  $app->get('/dashboard(/:year)',$require_login(),function($year=NULL ) use($app){
    if(!isset($year)){
      $year = $year= date('Y');
    }
    $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
    $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
    $allIncomes = $app->Inc->read($app->auth->user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
    $allExpenses = $app->Exp->read($app->auth->user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
    $earned=[];
    $itemSpent =[];
    for($i=1;$i<=12;$i++){
      $earned[$i] = isset($app->Inc->read($app->auth->user_id)->totalInc($year."-".$i."-1",$year."-".($i+1)."-1")->sum) ? $app->Inc->read($app->auth->user_id)->totalInc($year."-".$i."-1",$year."-".($i+1)."-1")->sum :0 ;
      $itemSpent[$i] = isset($app->Exp->read($app->auth->user_id)->totalExp($year."-".$i."-1",$year."-".($i+1)."-1")->sum) ? $app->Exp->read($app->auth->user_id)->totalExp($year."-".$i."-1",$year."-".($i+1)."-1")->sum : 0;
    }
    $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
    $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
    $date = new DateTime($year."-1-1");
    $date = $date->format('Y');
    $nav['prev'] = "dashboard/".($year-1);
    $nav['next'] = "dashboard/".($year+1);
    $app->render('main/dashboard.php',[
      'totalExp'=>$totalexp,
      'totalInc'=>$totalinc,
      'date'=>$date,
      'allIncomes'=>json_decode($allIncomes),
      'allExpenses'=>json_decode($allExpenses),
      'nav'=>$nav,
      'earned'=>$earned,
      'spent'=>$itemSpent
    ]);
  })->name('dashboard');

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
    $nav['prev'] = "expense/".$name."/".($year-1);
    $nav['next'] = "expense/".$name."/".($year+1);
    $exp_monthly = [];
    $exp = isset($app->Exp->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year+1)."1-1")->cost)?$app->Exp->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost:0;
    for($i=1;$i<=12;$i++){
      $startDate = $year."-".$i."-1";
      $endDate = $year."-".($i+1)."-1";
      $exp_monthly[$i] = isset($app->Exp->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost) ? $app->Exp->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost : 0;
    }
    $app->render('main/exp.php',[
      'totalExp'=>$totalexp,
      'totalInc'=>$totalinc,
      'monthly_exp'=>$exp_monthly,
      'exp'=>$exp,
      'name'=>$name,
      'date'=>$year,
      'nav'=>$nav,
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
    $nav['prev'] = "income/".$name."/".($year-1);
    $nav['next'] = "income/".$name."/".($year+1);
    $inc_monthly = [];
    $inc = isset($app->Inc->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost)?$app->Inc->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost:0;
    for($i=1;$i<=12;$i++){
      $startDate = $year."-".$i."-1";
      $endDate = $year."-".($i+1)."-1";
      $inc_monthly[$i] = isset($app->Inc->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost) ? $app->Inc->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost : 0;
    }
    $app->render('main/inc.php',[
      'totalExp'=>$totalexp,
      'totalInc'=>$totalinc,
      'monthly_inc'=>$inc_monthly,
      'inc'=>$inc,
      'name'=>$name,
      'date'=>$year,
      'nav'=>$nav,
      'products'=>$product
    ]);
  })->name('inc');

 ?>
