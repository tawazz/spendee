<?php
namespace HTTP\Models;
/**
 * Model for budget
 *13/08/2016
 *

 */
class BudgetTag extends BaseTable
{
  protected $table='budget_tags';
  protected $primary_key ='id';

  public function deleteTagsFromBudget($bud_id)
  {
    $this->db->query("delete from {$this->table} where bud_id = ?",[$bud_id]);
  }
}


 ?>
