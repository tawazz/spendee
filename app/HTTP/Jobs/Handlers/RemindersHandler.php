<?php
  namespace HTTP\Jobs\Handlers;
  /**
   *
   */
  class Remindershandler extends \HTTP\Jobs\Handlers\BaseHandler
  {
    public function fire($job, $data){
      
      \HTTP\Helpers\Utils::sendReminders($this->container);
    }

  }

 ?>
