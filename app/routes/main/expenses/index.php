<?php
  use HTTP\Controllers\Main\ExpenseController;
  //Expenses
  $this->get('/expenses[/{year}[/{month}[/{day}]]]',ExpenseController::class)->setName('expenses');
  $this->get('/expense/{name}[/{year}]',ExpenseController::class.':retrieve')->setName('expense.retrieve');
  $this->post('/expense',ExpenseController::class.':create')->setName('expense.create');
  $this->put('/expense/{id}',ExpenseController::class.':update')->setName('expense.update');
  $this->delete('/expense/{id}',ExpenseController::class.':delete')->setName('expense.delete');

 ?>
