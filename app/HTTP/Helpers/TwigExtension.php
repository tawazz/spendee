<?php
  namespace HTTP\Helpers;

  /**
   * TwigExtension
   */
  class TwigExtension extends \Slim\Views\TwigExtension
  {
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path_for', array($this, 'pathFor')),
            new \Twig_SimpleFunction('urlFor', array($this, 'pathFor')),
            new \Twig_SimpleFunction('base_url', array($this, 'baseUrl')),
            new \Twig_SimpleFunction('is_current_path', array($this, 'isCurrentPath')),
            new \Twig_SimpleFunction('var_dump', array($this, 'dump')),
            new \Twig_SimpleFunction('assets', array($this, 'getAssetsPath')),
            new \Twig_SimpleFunction('flashMsg', array($this, 'flash')),
        ];
    }
    public static function dump($var)
    {
      dump($var);
    }

    public function getAssetsPath($path,$host = false)
    {
      if(substr($path,-1)==='/')
      {
        $path = substr($path,0,-1);
      }
      if(substr($path,0,1)==='/')
      {
        $path = substr($path,1);
      }
      return ($host) ? "http://".$_SERVER['HTTP_HOST'].$this->baseUrl()."/public/".$path : $this->baseUrl()."/public/".$path ;
    }

    public function flash($key)
    {
      $session = new \Session();
      return $session->flash($key);
    }

  }

 ?>
