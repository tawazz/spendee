<?php

/**
 * Model for budget
 *13/08/2016
 *
 */
class Budget extends Table
{
  protected $table='budget';
  protected $primary_key ='id';
  protected $hasMany =['BudgetTag'];

  public function getBudgetData($app,$year=2016,$month=8)
  {
      $startDate = $year."/".$month."/1";
      $endDate = $year."/".($month+1)."/1";
      $budgets = $this->db->query($this->qb->table($this->table)->where("start_date",">=",$startDate)->andWhere("start_date","<",$endDate)->get())->result();
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
            $amount = (int) $this->db->query("SELECT Sum(exp.cost ) as total FROM exp_tags as tag,expenses as exp where tag_id = ? and exp.exp_id = tag.exp_id and  exp.date >= ? and exp.date < ? and exp.user_id = ?",[$tag->id,$startDate,$endDate,$app->auth->user_id])->first()->total;
            if($amount > 0){
              $exptags[$tag->name] =  $amount;
            }
          }
        }
        $budget->tags = $exptags;
        $budget->spent = $spent;
        $budget->spentPercentage = number_format( ($spent/$budget->amount)*100,2,'.',',');
        $budget->spendingLeft = number_format( ($budget->amount - $spent)/(cal_days_in_month(CAL_GREGORIAN,$month, $year)-(int)date('j')),2,'.',',' );
      }

      return $budgets;

  }

}


 ?>
