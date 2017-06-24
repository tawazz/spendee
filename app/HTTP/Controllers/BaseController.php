<?php
  namespace HTTP\Controllers;

  /**
   * BaseContoller
   */
  class BaseController
  {
    protected $container;
    protected $view;
    protected $session;

    // constructor receives container instance
    public function __construct($container) {
       $this->container = $container;
       $this->view = $container->view;
       $this->session = new \Session();
    }

    public function __get($prop)
    {
        if ($this->{$prop}) {
            return $this->{$prop};
        }

        if ($this->container->{$prop}) {
            return $this->container->{$prop};
        }
    }

    public function redirect($resp,$url,$status=302)
    {
      return $resp->withStatus($status)->withHeader('Location', $url);
    }

    public function urlFor($name,$params=[])
    {
        return $this->container->router->pathFor($name,$params);
    }

    public function flash($key,$value)
    {
        $this->session->flash($key,$value);
    }
  }

 ?>
