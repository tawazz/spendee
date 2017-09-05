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
       $this->session = new \Tazzy\Utils\Session();
    }

    public function __get($prop)
    {
        if (isset($this->{$prop})) {
            return $this->{$prop};
        }

        if (isset($this->container->{$prop})) {
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

    public function notFound(){
      return $this->container['response']
          ->withStatus(404)
          ->withHeader('Content-Type', 'text/html')
          ->write($this->view->fetch('errors/404.php'));
    }
  }

 ?>
