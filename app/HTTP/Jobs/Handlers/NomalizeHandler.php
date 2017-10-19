<?php
  namespace HTTP\Jobs\Handlers;
  use HTTP\Jobs\Handlers\BaseHandler;
  use \HTTP\Helpers\Utils;

  /**
   * NomalizeHandler
   */
  class NomalizeHandler extends BaseHandler
  {
    public function fire($job, $data)
    {
      $pb = $this->container->pb;
      $this->container->auth = $this->container->User->find($data['user_id']);
      foreach ($data['nomalize'] as $key => $value) {
        $query = "update expenses set name = '{$value}' where LOWER(name) like '%{$key}%'";
        $this->container->log->debug('nomalize',["query" => $query]);
        \Tazzy\Database\DB::connect()->query($query);
      }
      Utils::cacheClear($this->container);
    }

  }

 ?>
