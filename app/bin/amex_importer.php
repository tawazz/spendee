<?php
require_once __DIR__.'/shell.php';
use HTTP\Helpers\AmexImporter;
  $path = __DIR__.'/../../assets/imports/amex.csv';
  $Carbon = $container->Carbon;
  $container->auth = $container->User->find(7);
  $amex_importer = new AmexImporter($container,$path);
  $amex_importer->import();

 ?>
