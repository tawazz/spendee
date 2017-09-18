<?php
  namespace HTTP\Jobs\Handlers;
  use HTTP\Jobs\Handlers\BaseHandler;
  use \HTTP\Helpers\Utils;

  /**
   * ImportHandler
   */
  class ImportHandler extends BaseHandler
  {
    public function fire($job, $data)
    {
      $pb = $this->container->pb;
      $this->container->auth = $this->container->User->find($data['user_id']);
      $added = Utils::addFromCsv($this->container,$data['path']);
      if($added){
        //inteligent tagging
        $Carbon = $this->container->Carbon;
        $start = $Carbon->now()->month(1)->day(1)->toDateString();
        $end = $Carbon->now()->toDateString();
        Utils::relatedTags($this->container,$start,$end);
        $pb->allDevices()->pushNote("Spendee - Your file has been imported.","");
      } else {
        $pb->allDevices()->pushNote("Spendee - Failed to import yor file.","");
      }
    }

  }

 ?>
