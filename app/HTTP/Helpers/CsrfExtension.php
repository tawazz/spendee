<?php
  namespace HTTP\Helpers;

  /**
   * TwigExtension
   */
  class CsrfExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
  {
    protected $guard;
    protected $view;
    private $keys;
    public function __construct($guard)
    {
      $this->guard = $guard;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('csrf', array($this, 'Csrf')),
        ];
    }

    public function getGlobals()
    {
        return $this->getKeys();
    }

    private function getKeys()
    {
      $csrfNameKey = $this->guard->getTokenNameKey();
      $csrfValueKey = $this->guard->getTokenValueKey();
      $csrfName = $this->guard->getTokenName();
      $csrfValue = $this->guard->getTokenValue();
      $this->keys = [
        'csrf'   => [
            'keys' => [
              'name'  => $csrfNameKey,
              'value' => $csrfValueKey
            ],
            'name'  => $csrfName,
            'value' => $csrfValue
        ]
      ];
      return $this->keys;
    }
    
    public function Csrf()
    {
      $this->getKeys();
      return '
        <input type="hidden" name="'.$this->keys['csrf']['keys']['name'].'" value="'.$this->keys['csrf']['name'].'">
        <input type="hidden" name="'.$this->keys['csrf']['keys']['value'].'" value="'.$this->keys['csrf']['value'].'">
      ';
    }

  }

 ?>
