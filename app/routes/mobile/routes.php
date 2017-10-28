<?php
use \HTTP\Controllers\API\MobileApi;
$this->post('/auth',MobileApi::class.':auth');
$this->get('/data/{user}[/{year}[/{month}[/{day}]]]',MobileApi::class.':get');
$this->post('/expenses/add',MobileApi::class.':addExpense');
$this->post('/expenses/update',MobileApi::class.':updateExpense');
$this->post('/expenses/delete',MobileApi::class.':deleteExpense');
$this->post('/incomes/add',MobileApi::class.':addIncome');
$this->post('/incomes/update',MobileApi::class.':updateIncome');
$this->post('/incomes/delete',MobileApi::class.':deleteIncome');
$this->get('/graph/{type}/{user}[/{year}]',MobileApi::class.':graph');

 ?>
