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

    public function aboutView($req, $resp,$args)
    {
        $this->view->render($resp,'home/about.php');
    }

    public function contactView($req, $resp,$args)
    {
        $this->view->render($resp,'home/contact.php');
    }
    public function registerView($req, $resp,$args)
    {
        $this->view->render($resp,'auth/register.php');
    }

    public function helpView($req, $resp,$args)
    {
        $this->view->render($resp,'home/help.php');
    }

    public function contact($req, $resp,$args)
    {
      if( isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) ){
       $name = $_POST['name'];
       $email = $_POST['email'];
       $phone = $_POST['phone'];
       $msg = $_POST['msg'];

       $M= "Name : {$name}". "Email: {$email} Phone : {$phone} "."Message : {$msg}";
       //mail("tawanda.nyakudjga@gmail.com","tawazz.net/me",$M);
      }
        $this->flash->addMessage("global","your message \"".$M."\"was sent");
        $resp = $this->redirect($resp,$this->urlFor('contact'));
        return $resp;
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
