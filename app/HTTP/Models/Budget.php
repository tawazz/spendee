<?php
namespace HTTP\Models;
/**
 * Model for budget
 *13/08/2016
 *
 */
 use Carbon\Carbon;
class Budget extends BaseTable
{
  protected $table='budgets';
  protected $primary_key ='id';
  protected $hasMany =[
    ['class' => \HTTP\Models\BudgetTag::class, 'id' => 'bud_id']
  ];

  public function get($id,$field=[]){
    $result = parent::get($id,$field=[]);
    $tags = [];
    if (sizeof($result->budget_tags) > 0) {
      foreach ($result->budget_tags as $bud) {
        array_push($tags,$bud->tag_id);
      }
    }
    $result->tags = $tags;
    unset($result->budget_tags);
    return $result;
  }
  public function getBudgetData($app,$year=2016,$month=8)
  {
      $startDate = $year."-".$month."-1";
      $endDate = $year."-".($month+1)."-1";
      $sql = $this->qb->table($this->table)->where("date",">=",$startDate)->andWhere("date","<",$endDate)->andWhere("user_id","=",$app->auth->id)->get();
      $budgets = $this->db->query($sql)->result();
      foreach ($budgets as $budget) {
        $budgetTags = $app->BudgetTag->find('all',[
          "where" => ["bud_id","=",$budget->id]
        ]);
        $tags = [];
        foreach ($budgetTags as $tag) {
          $tags[]= $app->Tags->find('all',[
            "where" => ["id","=",$tag->tag_id]
          ]);
        }
        $spent =  $app->ExpTags->expTagsTotalSpent($app->auth->id,$startDate,$endDate,$tags);
        $exptags= [];
        foreach ($tags as $tagone) {
          foreach ($tagone as $tag) {
            $tagInfo = $app->Tags->find();
            $sql = "SELECT Sum(exp.cost ) as total FROM {$app->ExpTags->getTable()} as tag,expenses as exp where tag_id = ? and exp.id = tag.exp_id and  exp.date >= ? and exp.date <= ? and exp.user_id = ?";
            $amount = (int) $this->db->query($sql,[$tag->id,$startDate,$endDate,$app->auth->id])->first()->total;
            if($amount > 0){
              $exptags[$tag->name] =  $amount;
            }
          }
        }
        $budgetDate = new Carbon($budget->date,'Australia/Perth'); //perth time zone
        $budget->tags = $exptags;
        $budget->spent = $spent;
        $budget->spentPercentage = number_format( ($spent/$budget->amount)*100,2,'.',',');
        $budget->future = Carbon::now('Australia/Perth')->lt($budgetDate->copy()->addMonth());
        $budget->spendingLeft = ($budget->future == true ) ? (($budget->amount - $spent)/ (cal_days_in_month(CAL_GREGORIAN,$month, $year)) ): number_format( ($budget->amount - $spent)/(cal_days_in_month(CAL_GREGORIAN,$month, $year)-(int)date('j')),2,'.',',' );
        $budget->expired = Carbon::now('Australia/Perth')->gt($budgetDate->addMonth());
        $budget->saved =  ($budget->amount - $spent > 0) ? $budget->amount - $spent : $spent - $budget->amount;
      }

      return $budgets;

  }

}


 ?>
