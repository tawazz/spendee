<?php
  require_once __DIR__.'/shell.php';
  $container->auth = $container->User->find(7);
  \HTTP\Helpers\Utils::nomalize($container);

 ?>
