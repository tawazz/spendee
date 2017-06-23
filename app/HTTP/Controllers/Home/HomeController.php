<?php
  namespace HTTP\Controllers\Home;

  /**
   *
   */
  class HomeController extends \HTTP\Controllers\BaseController
  {
    public function __invoke($req, $resp,$args)
    {
        $this->homeView($req,$resp,$args);
    }

    public function homeView($req, $resp,$args)
    {
        $this->view->render($resp,'home/about.php');
    }

    public function registerView($req, $resp,$args)
    {
        $this->view->render($resp,'auth/register.php');
    }

    public function expensesView($req,$resp,$args)
    {
      $app = $this->container;
      $year = isset($args['year']) ? $args['year'] : Null;
      $month = isset($args['month']) ? $args['month'] : Null;
      $day = isset($args['day']) ? $args['day'] : Null;

      $data = $this->container->Helper->getData($app,$app->auth->user_id,$year,$month,$day);
      $this->view->render($resp,'main/expenses.php',[
        'appData' => $data,
        'page'    => 'expenses',
        'totals'  => []
      ]);
    }
  }

 ?>
