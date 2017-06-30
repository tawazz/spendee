<?php
namespace HTTP\Models;
/**
 * Model for budget
 *13/08/2016
 *

 */
class BudgetTag extends Table
{
  protected $table='budget_tags';
  protected $primary_key ='id';

  public function deleteTagsFromBudget($bud_id)
  {
    $this->db->query("delete from bud_tags where bud_id = ?",[$bud_id]);
  }
}


 ?>
