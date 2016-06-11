<?php
require 'json_const.php';
$app->post('/api/auth',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

    $user = $app->User;
    $fetch_user = $user->find('first',[
      'where'=>['username','=',$body->auth->username],
    ]);

    if($fetch_user && $app->hash->make($body->auth->password,$body->auth->username) == $fetch_user->password ){
      $response = [
        $app->JSON_AUTH =>true,
        "user"=>$fetch_user
      ];
    }else{
      $response = [
        $app->JSON_AUTH=>false
      ];
      $app->response->setStatus(403);
    }

    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->setBody(json_encode($response));
});

$app->post('/api/data/:user(/:year(/:month(/:day)))',function($user_id,$year = NULL,$month = NULL,$day=NULL) use($app){

  if(isset($year)&& isset($month) && isset($day) ){

    $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $allIncomes= $app->Inc->read($user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    $exptags = $app->ExpTags->expTagsData($year."-".$month."-1",$year."-".$month."-".($day+1));
}else if(isset($year)&& isset($month) ){

  $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
  $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
  $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
  $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
  $allIncomes = $app->Inc->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
  $exptags = $app->ExpTags->expTagsData($year."-".$month."-1",$year."-".($month+1)."-1");

}else if(isset($year)){
  $totalexp = $app->Exp->read($user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
  $totalinc = $app->Inc->read($user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
  $allExpenses = $app->Exp->read($user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
  $allIncomes = $app->Inc->read($user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
  $itemSpent= $app->Exp->read($user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
  $exptags = $app->ExpTags->expTagsData($year."-"."1"."-1",($year+1)."-1-1");

}else{
    $month= date('m');
    $year= date('Y');
    $day = date('d');
    $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
    $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
    $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
    $allIncomes= $app->Inc->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
    $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
    $exptags = $app->ExpTags->expTagsData($year."-".$month."-1",$year."-".($month+1)."-1");

}

$totalinc = isset($totalinc->sum)?$totalinc->sum:0;
$totalexp = isset($totalexp->sum)?$totalexp->sum:0;

$expDates = [];
$expData = [];

$incDates = [];
$incTotals = [];

foreach ($allExpenses as $exp) {
  $expDates[$exp->date] = NULL;
}

foreach ($allIncomes as $inc) {
  $incDates[$inc->date] = NULL;
}

foreach ($expDates as $key => $value) {
  $expenses = [];
  foreach ($allExpenses as $exp) {
    if($key == $exp->date){
      array_push($expenses,$exp);
      $expDates[$exp->date] = $expenses;
    }
  }
}

foreach ($incDates as $key => $value) {
  $incomes = [];
  foreach ($allIncomes as $inc) {
    if($key == $inc->date){
      array_push($incomes,$inc);
      $incDates[$inc->date] = $incomes;
    }
  }
}


$response = [
  "exp_total" => $totalexp,
  "inc_total" => $totalinc,
  "balance"   => $totalinc - $totalexp,
  "exp_data"  => $expDates,
  "inc_data"  => $incDates
];

$app->response->headers->set('Content-Type', 'application/json');
$app->response->setBody(json_encode($response));
});

$app->post('/api/expenses/add',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body,true);

    $exp_id = $app->Exp->save($body["item"]);
    if(isset($body["tags"])){

      foreach ($body['tags'] as $tag) {
          $tags_data = [
            'exp_id' => $exp_id,
            'tag_id' => $tag["tag_id"]
          ];
          var_dump($tags_data);
          $app->ExpTags->save($tags_data);
      }

    }

    $response = [
      "added" => true
    ];

    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->setBody(json_encode($response));

});

$app->post('/api/incomes/add',function() use($app){
  $body = $app->request->getBody();
  $body = json_decode($body,true);

  $inc_id = $app->Inc->save($body["item"]);
  if(isset($body["tags"])){

    foreach ($body['tags'] as $tag) {
        $tags_data = [
          'inc_id' => $inc_id,
          'tag_id' => $tag["tag_id"]
        ];
        $app->IncTags->save($tags_data);
    }

  }

  $response = [
    "added" => true
  ];

  $app->response->headers->set('Content-Type', 'application/json');
  $app->response->setBody(json_encode($response));

});

$app->post('/api/expenses/update',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

$app->post('/api/incomes/update',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

$app->post('/api/expenses/delete',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

$app->post('/api/incomes/delete',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

?>
