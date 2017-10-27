<?php
use \HTTP\Controllers\API\MobileApi;
$this->post('/auth',MobileApi::class.':auth');
$this->get('/data/{user}[/{year}[/{month}[/{day}]]]',MobileApi::class.'');
$this->post('/expenses/add',MobileApi::class.'');
$this->post('/expenses/update',MobileApi::class.'');
$this->post('/expenses/delete',MobileApi::class.'');
$this->post('/incomes/add',MobileApi::class.'');
$this->post('/incomes/update',MobileApi::class.'');
$this->post('/incomes/delete',MobileApi::class.'');
$this->get('/graph/{type}/{user}[/{year}]',MobileApi::class.'');

 ?>
