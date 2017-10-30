<?php
  use HTTP\Controllers\Main\IncomeController;
  //Incomes
  $this->get('/incomes[/{year}[/{month}[/{day}]]]',IncomeController::class)->setName('incomes');
  $this->get('/income/{name}[/{year}]',IncomeController::class.':retrieve')->setName('income.retrieve');
  $this->post('/income',IncomeController::class.':create')->setName('income.create');
  $this->put('/income/{id}',IncomeController::class.':update')->setName('income.update');
  $this->delete('/income/{id}',IncomeController::class.':delete')->setName('income.delete');
 ?>
