<?php
  require 'expenses/routes.php';
  require 'incomes/routes.php';
  require 'dashboard/routes.php';
  require 'budget/routes.php';
  $this->get('/account',function() use($app){
      $app->render('user/account.php');
  })->setName('account');
 ?>
