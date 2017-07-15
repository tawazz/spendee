<?php
  use HTTP\Controllers\Main\BudgetController;
  //budgets
  $this->get('/budgets[/{year}[/{month}[/{day}]]]',BudgetController::class)->setName('budgets');
  $this->get('/budget/{id}',BudgetController::class.':retrieve')->setName('budget.retrieve');
  $this->post('/budget',BudgetController::class.':create')->setName('budget.create');
  $this->put('/budget/{id}',BudgetController::class.':update')->setName('budget.update');
  $this->delete('/budget/{id}',BudgetController::class.':delete')->setName('budget.delete');

 ?>
