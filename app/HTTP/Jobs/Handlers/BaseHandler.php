<?php
  namespace HTTP\Jobs\Handlers;
  use \Pimple\Container;
  use \HTTP\Services\ServiceProvider;
  /**
   * BaseHander
   */
  class BaseHandler
  {
    protected $container;

    public function __construct()
    {
      $this->container = new Container();
      $this->container->register(new ServiceProvider());
    }
  }

 ?>
