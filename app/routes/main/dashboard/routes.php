<?php
  use HTTP\Controllers\VueController;

  $this->get('/overview[/{year}]',VueController::class)->setName('overview');
 ?>
