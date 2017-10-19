<?php
  namespace HTTP\Helpers;

  /**
   * Twig Helper
   */
  class Twig extends \Slim\Views\Twig
  {
    public function appendData($data=[])
    {
      $this->defaultVariables = array_merge($this->defaultVariables, $data);
    }
  }

 ?>
