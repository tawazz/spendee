<?php
require 'json_const.php';


$app->post('/api/incomes/add',function() use($app){
  $body = $app->request->getBody();
  $body = json_decode($body,true);
  $body["item"]['cost'] = str_replace( ',', '',$body["item"]['cost'] );
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

$app->get('/api/graph/:type/:user(/:year)',function($type,$user_id,$year = NULL,$month = NULL,$day=NULL) use($app){

    if(!isset($year)){
      $year = $year= date('Y');
    }
    $totalexp = $app->Exp->read($user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
    $totalinc = $app->Inc->read($user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
    $allIncomes = json_decode($app->Inc->read($user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1"));
    $allExpenses = json_decode($app->Exp->read($user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1"));

    $earned=[];
    $itemSpent =[];
    for($i=1;$i<=12;$i++){
      $earned[$i] = isset($app->Inc->read($user_id)->totalInc($year."-".$i."-1",$year."-".($i+1)."-1")->sum) ? $app->Inc->read($user_id)->totalInc($year."-".$i."-1",$year."-".($i+1)."-1")->sum :0 ;
      $itemSpent[$i] = isset($app->Exp->read($user_id)->totalExp($year."-".$i."-1",$year."-".($i+1)."-1")->sum) ? $app->Exp->read($user_id)->totalExp($year."-".$i."-1",$year."-".($i+1)."-1")->sum : 0;
    }
    $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
    $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
    $exptags = $app->ExpTags->expTagsData($user_id,$year."-"."1"."-1",($year+1)."-1-1");
    $date = new DateTime($year."-1-1");
    $date = $date->format('Y');

    $response = [
      'totalExp'=>$totalexp,
      'totalInc'=>$totalinc,
      'date'=>$date,
      'allIncomes'=>$allIncomes,
      'allExpenses'=>$allExpenses,
      'earned'=>$earned,
      'spent'=>$itemSpent,
      'exptags'=>$exptags
    ];
    switch ($type) {
      case "line":
            $app->render('api/yearLineGraph.php',$response);
        break;
      case "bar":
          $app->render('api/yearBarGraph.php',$response);
        break;
      case "incomes":
          $app->render('api/yearIncomesGraph.php',$response);
        break;
      case "expenses":
          $app->render('api/yearExpensesGraph.php',$response);
        break;
      case "tags":
          $app->render('api/yearTagsGraph.php',$response);
        break;

      default:
        # code...
        break;
    }

});

// get routes
$app->get('/api/expenses/:id',function($id) use($app){

   $app->Helper->JsonResponse($app,json_encode($app->Exp->read($id)->get()));

});

?>
