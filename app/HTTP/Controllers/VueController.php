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

    public function eoy($req,$resp,$args)
    {
      $user = $this->User->find(7);
      $start_date = $this->Carbon->now(new \DateTimeZone('Australia/Perth'))->month(1)->day(1)->toDateString();
      $end_date = $this->Carbon->now(new \DateTimeZone('Australia/Perth'))->month(12)->day(31)->toDateString();
      $totalexp = $this->Exp->read($user->id)->totalExp($start_date, $end_date);
      $totalinc = $this->Inc->read($user->id)->totalInc($start_date, $end_date);
      $tags = $this->Helper->getExpenseTagsBetweenDates($this,$user->id,$start_date, $end_date);
      if(sizeof($tags) > 0) {
          $data = [
              'name'    => "$user->firstname $user->lastname",
              'email'   => "$user->email",
              'tags'    => $tags,
              'totalInc' => $totalinc,
              'totalExp'=> $totalexp
          ];
      }
      return $this->view->render($resp,'main/eoy.php',["data" => $data]);
    }
  }

 ?>
