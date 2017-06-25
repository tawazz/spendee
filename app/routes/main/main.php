<?php
  #require 'expenses/index.php';
  require 'incomes/index.php';
  require 'dashboard/index.php';
  require 'budget/index.php';
  $this->get('/account',function() use($app){
      $app->render('user/account.php');
  })->setName('account');
 ?>
