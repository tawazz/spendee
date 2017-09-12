<?php
  namespace HTTP\Controllers;
  /**
   * VueController
   */
  class VueController extends \HTTP\Controllers\BaseController
  {

    public function __invoke($req, $resp,$args)
    {
        $this->get($req,$resp,$args);
    }
    public function get($req,$resp,$args)
    {
      $this->view->render($resp,'main/vue.php');
    }

  }

 ?>
