<?php
  namespace HTTP\Helpers;
  use \Twig\TwigFilter;
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

    public function getFilters()
    {
        return array(
            new TwigFilter('base64', array($this, 'base64')),
        );
    }

    public static function dump($var)
    {
      dump($var);
    }
    public function base64($img)
    {
      $type = pathinfo($img, PATHINFO_EXTENSION);
      $data = file_get_contents($img);
      $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
      return $base64;
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
      return ($host) ? "http://".$_SERVER['HTTP_HOST'].$this->baseUrl()."/assets/".$path : $this->baseUrl()."/assets/".$path ;
    }

  }

 ?>
