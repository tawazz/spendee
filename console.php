<?php
require_once __DIR__.'/vendor/autoload.php';
use Psy\Shell;
use Psy\Configuration;

$path = __DIR__.'/app/bin/shell.php';
$config = new Configuration([
  'updateCheck' => 'never',
  'defaultIncludes' => [
      $path,
    ],
]);

$shell = new Shell($config);

try {
  $shell->run();
} catch (ErrorException $e) {
  dump($e);
}
 ?>
