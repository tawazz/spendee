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
        ];
    }
  }

 ?>
