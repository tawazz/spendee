<?php
  require_once __DIR__.'/../../vendor/autoload.php';

  use \HTTP\Services\ServiceProvider;
  use \Slim\App;
  use \Slim\Http\Environment;
  use \Symfony\Component\Filesystem\Filesystem;

  $app = new App();
  $app->environment = Environment::mock([
      'REQUEST_URI' => '/'
  ]);
  $container = $app->getContainer();
  $container->register(new ServiceProvider());

  $cache = $container->cache;
  $cache->clear();
  $filesystem = new Filesystem();
  $views_cache ='/app/app/views/cache';

  if ($filesystem->exists($views_cache)) {
      $filesystem->remove($views_cache);
      $container->log->debug('View Cache Cleared');
  }
  $container->log->debug('Cache Cleared');

 ?>
