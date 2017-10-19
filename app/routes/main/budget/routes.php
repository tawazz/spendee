<?php
  use HTTP\Controllers\VueController;
  //budgets
  $this->get('/budgets[/{year}[/{month}[/{day}]]]',VueController::class)->setName('budgets');
  $this->get('/budget/{id}',VueController::class)->setName('budget.retrieve');
  $this->post('/budget',VueController::class)->setName('budget.create');
  $this->put('/budget/{id}',VueController::class)->setName('budget.update');
  $this->delete('/budget/{id}',VueController::class)->setName('budget.delete');

 ?>
