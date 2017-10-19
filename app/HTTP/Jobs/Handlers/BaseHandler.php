<?php
  namespace HTTP\Jobs\Handlers;
  use \Pimple\Container;
  use \HTTP\Services\ServiceProvider;
  use \Slim\App;
  use \Slim\Http\Environment;
  date_default_timezone_set('Australia/Perth');
  /**
   * BaseHander
   */
  class BaseHandler
  {
    protected $container;

    public function __construct()
    {
      $app = new App();
      $app->environment = Environment::mock([
          'REQUEST_URI' => '/'
      ]);
      $this->container = $app->getContainer();
      $this->container->register(new ServiceProvider());
    }
  }

 ?>
