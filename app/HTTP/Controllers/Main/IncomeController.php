<?php
  namespace HTTP\Controllers\Main;
  /**
   * IncomeController
   */
  class IncomeController extends \HTTP\Controllers\BaseController
  {

    public function __invoke($req, $resp,$args)
    {
        $this->list($req,$resp,$args);
    }
    public function retrieve($req, $resp,$args)
    {
      $app = $this->container;
      $year = isset($args['year']) ? $args['year'] : Null;
      $name = isset($args['name']) ? $args['name'] : Null;

      if ($name !== Null) {
        if(!isset($year)){
          $year = $year= date('Y');
        }
        $product = $app->Inc->read($app->auth->id)->getProduct($name,$year."-"."1"."-1",($year+1)."-1-1");
        $totalexp = $app->Exp->read($app->auth->id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
        $totalinc = $app->Inc->read($app->auth->id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
        $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
        $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
        $nav = $app->Helper->getNav($year);
        $inc_monthly = [];
        $inc = isset($app->Inc->read($app->auth->id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost)?$app->Inc->read($app->auth->id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost:0;
        for($i=1;$i<=12;$i++){
          $startDate = $year."-".$i."-1";
          $endDate = $year."-".($i+1)."-1";
          $inc_monthly[$i] = isset($app->Inc->read($app->auth->id)->spentOnProduct($name,$startDate,$endDate)->cost) ? $app->Inc->read($app->auth->id)->spentOnProduct($name,$startDate,$endDate)->cost : 0;
        }
        $appData = [
          'exp_total' => $totalexp,
          'inc_total' => $totalinc,
          'balance'   => $totalinc - $totalexp,
          'nav'=>$nav,
        ];
        $app->view->render($resp,'main/inc.php',[
          'appData' => $appData,
          'page' => 'income/'.$name,
          'monthly_inc'=>$inc_monthly,
          'inc'=>$inc,
          'name'=>$name,
          'products'=>$product
        ]);
      }else{
        $app->view->render($resp,'errors/404.php');
      }
    }
    public function list($req,$resp,$args)
    {
      $app = $this->container;
      $year = isset($args['year']) ? $args['year'] : Null;
      $month = isset($args['month']) ? $args['month'] : Null;
      $day = isset($args['day']) ? $args['day'] : Null;

      $data = $app->Helper->getData($app,$app->auth->id,$year,$month,$day);
      $app->view->render($resp,'main/incomes.php',[
        'appData' => $data,
        'page'    => 'incomes',
        'totals'  => []
      ]);
    }
    public function create($req, $resp,$args)
    {
      $app = $this;
      $data = [
          'name'=> $_POST['name'],
          'cost'=> str_replace( ',', '',$_POST['cost'] ),
          'date'=> $_POST['date'],
          'id'=> $app->auth->id
        ];
      $this->Inc->save($data);
      return $this->redirect($resp,$app->urlFor('incomes'));
    }
    public function update($req, $resp,$args)
    {
      $app = $this;
      $id = $args['id'];
      $data = [
        'name'=> $_POST['name'],
        'cost'=>str_replace( ',', '',$_POST['cost'] ),
        'date'=> $_POST['date'],
      ];
      $app->Inc->read($id)->set($data);
      return $this->redirect($resp,$app->urlFor('income.retrieve',['name'=>$_POST['name']."#".$id]));
    }
    public function delete($req, $resp,$args)
    {
      $app = $this;
      $id = $args['id'];
      $app->Inc->read($id)->delete();
      return $this->redirect($resp,$app->urlFor('income.retrieve',['name'=>$_POST['name']]));
    }
  }

 ?>
