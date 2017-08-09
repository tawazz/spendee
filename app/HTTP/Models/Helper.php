<?php
namespace HTTP\Models;
use \HTTP\Models\Tag;
use \HTTP\Models\Expense;
use \HTTP\Models\Income;
/**
* Helper class
*/
class Helper
{
  public function getItems($model,$user_id,$year=null,$month=null,$day=null)
  {
    if(isset($year)&& isset($month) && isset($day) ){
      $all= $model->read($user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    }else if(isset($year)&& isset($month) ){
      $all= $model->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
    }else if(isset($year)){
      $all = $model->read($user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
    }else{
      $month= date('m');
      $year= date('Y');
      $all = $model->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
    }
    $Dates = [];

    foreach ($all as $itm) {
      $Dates[$itm->date] = NULL;
    }

    foreach ($Dates as $key => $value) {
      $items = [];
      foreach ($all as $itm) {
        if($key == $itm->date){
          array_push($items,$itm);
          $Dates[$itm->date] = $items;
        }
      }

    }

    return $Dates;
  }

  public static function getExpenseTags($app,$user_id,$year=null,$month=null,$day=null){

    if(isset($year)&& isset($month) && isset($day) ){
      if($month == 13){
        $month=1;
        $year +=1;
      }
      if($month == 0){
        $month=12;
        $year -=1;
      }
      $exptags = $app->ExpTags->tagData($user_id,$year."-".$month."-1",$year."-".$month."-".($day+1));

    }else if(isset($year)&& isset($month) ){

      if($month == 13){
        $month=1;
        $year +=1;
      }
      if($month == 0){
        $month=12;
        $year -=1;
      }

      $exptags = $app->ExpTags->tagData($user_id,$year."-".$month."-1",$year."-".($month+1)."-1");

    }else if(isset($year)){
      $exptags = $app->ExpTags->tagData($user_id,$year."-"."1"."-1",($year+1)."-1-1");

    }else{

      $month= date('m');
      $year= date('Y');
      $exptags = $app->ExpTags->tagData($user_id,$year."-".$month."-1",$year."-".($month+1)."-1");

    }
    return $exptags;
  }

  public static function getData($app,$user_id,$year=null,$month=null,$day=null){

    if(isset($year)&& isset($month) && isset($day) ){
      if($month == 13){
        $month=1;
        $year +=1;
      }
      if($month == 0){
        $month=12;
        $year -=1;
      }
      $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-".$day,$year."-".$month."-".($day+1));
      $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-".$day,$year."-".$month."-".($day+1));
      $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
      $allIncomes= $app->Inc->read($user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
      $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
      $earned= $app->Inc->read($user_id)->allActivity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
      $exptags = $app->ExpTags->expTagsData($user_id,$year."-".$month."-1",$year."-".$month."-".($day+1));
      $nav = Helper::getNav($year,$month,$day);

    }else if(isset($year)&& isset($month) ){

      if($month == 13){
        $month=1;
        $year +=1;
      }
      if($month == 0){
        $month=12;
        $year -=1;
      }

      $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
      $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
      $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
      $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
      $earned= $app->Inc->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
      $allIncomes = $app->Inc->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
      $exptags = $app->ExpTags->expTagsData($user_id,$year."-".$month."-1",$year."-".($month+1)."-1");
      $nav = Helper::getNav($year,$month);

    }else if(isset($year)){

      $totalexp = $app->Exp->read($user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
      $totalinc = $app->Inc->read($user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
      $allExpenses = $app->Exp->read($user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
      $allIncomes = $app->Inc->read($user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
      $itemSpent= $app->Exp->read($user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
      $earned= $app->Inc->read($user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
      $exptags = $app->ExpTags->expTagsData($user_id,$year."-"."1"."-1",($year+1)."-1-1");
      $nav = Helper::getNav($year);

    }else{

      $month= date('m');
      $year= date('Y');
      $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
      $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
      $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
      $allIncomes= $app->Inc->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
      $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
      $earned= $app->Inc->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
      $exptags = $app->ExpTags->expTagsData($user_id,$year."-".$month."-1",$year."-".($month+1)."-1");
      $nav = Helper::getNav($year,$month);

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
      "balance"   => round($totalinc - $totalexp,2,PHP_ROUND_HALF_UP),
      "exp_data"  => $expDates,
      "inc_data"  => $incDates,
      "expenses"  => json_decode($itemSpent),
      "incomes"   => json_decode($earned),
      "exp_tags"  => $exptags,
      "tags"      => $app->Tags->find('all'),
      "nav"   => $nav
    ];

    return (object) $response;

  }
  public static function yearOverView($app,$user_id,$year)
  {
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

    $response = [
      'totalExp'=>$totalexp,
      'totalInc'=>$totalinc,
      'allIncomes'=>$allIncomes,
      'allExpenses'=>$allExpenses,
      'earned'=>$earned,
      'spent'=>$itemSpent,
    ];

    return $response;
  }
  public static function getNav($year=null,$month=null,$day=null)
  {
    if(isset($year)&& isset($month) && isset($day) ){
      if($month == 13){
        $month=1;
        $year +=1;
      }
      if($month == 0){
        $month=12;
        $year -=1;
      }
      $date = new \DateTime($year."-".$month."-".$day);
      $date = $date->format('d/F/Y');
      $nav['display'] = $date;
      $nav['next'] = $year."/".$month."/".($day+1);
      $nav['prev'] = $year."/".$month."/".($day-1);
      $nav['current']=['year'=>$year,'month'=>$month];

    }else if(isset($year)&& isset($month) ){

      if($month == 13){
        $month=1;
        $year +=1;
      }
      if($month == 0){
        $month=12;
        $year -=1;
      }

      $date = new \DateTime($year."-".$month."-1");
      $date = $date->format('F/Y');
      $nav['display'] = $date;
      $nav['prev'] = $year."/".($month-1);
      $nav['next'] = $year."/".($month+1);
      $nav['current']=['year'=>$year,'month'=>$month];

    }else if(isset($year)){

      $date = new \DateTime($year."-1-1");
      $date = $date->format('Y');
      $nav['display'] = $date;
      $nav['prev'] = ($year-1);
      $nav['next'] = ($year+1);
      $nav['current']=['year'=>$year,'month'=>date('m')];

    }else{
      $month= date('m');
      $year= date('Y');
      $day = date('d');

      $date = new DateTime($year."-".$month."-".$day);
      $date = $date->format('F/Y');
      $nav['display'] = $date;
      $nav['prev'] = $year."/".($month-1);
      $nav['next'] = $year."/".($month+1);
      $nav['current']=['year'=>$year,'month'=>$month];

    }

    return $nav;
  }
  public static function getTags()
  {
    $tags = new Tag();
    return $tags->find('all');
  }
  public static function JsonResponse($app,$value='')
  {
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->setBody($value);
  }
  public static function getTotals($user_id,$year=null,$month=null,$day=null)
  {
    $exp = $inc = $bal = 0;
    $Exp = new Expense();
    $Inc = new Income();
    if(isset($year) && isset($month) && isset($day)) {
      $exp = $Exp->read($user_id)->totalExp($year."-".$month."-".$day,$year."-".$month."-".($day+1));
      $inc = $Inc->read($user_id)->totalInc($year."-".$month."-".$day,$year."-".$month."-".($day+1));
    }else if(isset($year)&& isset($month) ){
      $exp = $Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
      $inc = $Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
    }else if(isset($year)){
      $exp = $Exp->read($user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
      $inc = $Inc->read($user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
    }else{
      $month= date('m');
      $year= date('Y');
      $exp = $Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
      $inc = $Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
    }
    $exp = isset($exp) ? $exp : 0;
    $inc = isset($inc) ? $inc : 0;
    $bal = round($inc - $exp,2,PHP_ROUND_HALF_UP);

    return [
      'expenses' => $exp,
      'incomes'  => $inc,
      'balance'  => $bal
    ];
  }
}
?>
