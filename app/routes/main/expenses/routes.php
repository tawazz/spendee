<?php
  use HTTP\Controllers\VueController;
  //Expenses
  $this->get('/expenses[/{year}[/{month}[/{day}]]]',VueController::class)->setName('expenses');
  $this->get('/expense/{name}[/{year}]',VueController::class)->setName('expense.retrieve');
  $this->post('/expense',VueController::class)->setName('expense.create');
  $this->put('/expense/{id}',VueController::class)->setName('expense.update');
  $this->delete('/expense/{id}',VueController::class)->setName('expense.delete');

  //Tags
  $this->get('/tags[/{id}]',VueController::class)->setName('tags');

 ?>
