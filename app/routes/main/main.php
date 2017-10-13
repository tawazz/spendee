<?php
  use HTTP\Controllers\VueController;
  require 'expenses/routes.php';
  require 'incomes/routes.php';
  require 'dashboard/routes.php';
  require 'budget/routes.php';
  $this->get('/settings',VueController::class);

 ?>
