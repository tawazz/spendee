<?php
  namespace HTTP\Helpers;

  /**
   *
   */
  class CacheKeys
  {
    public $keys = [
      'API_EXP_GET' => 'api.expenses.get.',
      'API_TAGS_GET' => 'api.get.tags'
    ];

    public function __get($prop)
    {
      if ($this->$keys[$prop]) {
        return $this->keys[$prop];
      }
    }
  }

 ?>
