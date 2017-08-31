<?php

/**
 * Model for budget
 *13/08/2016
 *
 */
 use Carbon\Carbon;
class Budget extends Table
{
  protected $table='budget';
  protected $primary_key ='id';

  public function getBudgetData($app,$year=2016,$month=8)
  {
      $startDate = $year."/".$month."/1";
      $endDate = $year."/".($month+1)."/1";
      $budgets = $this->db->query($this->qb->table($this->table)->where("start_date",">=",$startDate)->andWhere("start_date","<",$endDate)->andWhere("user_id","=",$app->auth->user_id)->get())->result();
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
        $spent =  $app->ExpTags->expTagsTotalSpent($app->auth->user_id,$startDate,$endDate,$tags);
        $exptags= [];
        foreach ($tags as $tagone) {
          foreach ($tagone as $tag) {
            $tagInfo = $app->Tags->find();
            $amount = (int) $this->db->query("SELECT Sum(exp.cost ) as total FROM exp_tags as tag,expenses as exp where tag_id = ? and exp.exp_id = tag.exp_id and  exp.date >= ? and exp.date <= ? and exp.user_id = ?",[$tag->id,$startDate,$endDate,$app->auth->user_id])->first()->total;
            if($amount > 0){
              $exptags[$tag->name] =  $amount;
            }
          }
        }
        $budgetDate = new Carbon($budget->start_date,'Australia/Perth'); //perth time zone
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
