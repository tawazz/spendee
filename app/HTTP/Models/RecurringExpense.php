<?php
  namespace HTTP\Models;
  use \HTTP\Models\Expense;
  class RecurringExpense extends \HTTP\Models\BaseModel{

    protected $guarded = [];
    protected $table = "recurring_expenses";

    public function expense() {
      $exp = new Expense();
      return $exp->get($this->exp_id);
    }

  }
 ?>
