<?php
  namespace HTTP\Middleware;

  /**
   *
   */
  class BaseMiddleware
  {
    protected $container;

    function __construct($container)
    {
        $this->container = $container;
    }

    public function __get($prop)
    {
        if ($this->container->{$prop}) {
            return $this->container->{$prop};
        }
    }
  }

 ?>
