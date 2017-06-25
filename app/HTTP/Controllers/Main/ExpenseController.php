<?php
  namespace HTTP\Controllers\Main;
  /**
   * ExpenseController
   */
  class ExpenseController extends \HTTP\Controllers\BaseController
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
        $product = $app->Exp->read($app->auth->user_id)->getProduct($name,$year."-"."1"."-1",($year+1)."-1-1");
        $totalexp = $app->Exp->read($app->auth->user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
        $totalinc = $app->Inc->read($app->auth->user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
        $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
        $totalexp = isset($totalexp->sum)?$totalexp->sum:0;
        $nav = $app->Helper->getNav($year);
        $exp_monthly = [];
        $exp = isset($app->Exp->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year+1)."1-1")->cost)?$app->Exp->read($app->auth->user_id)->spentOnProduct($name,$year."-1-1",($year)."12-31")->cost:0;
        for($i=1;$i<=12;$i++){
          $startDate = $year."-".$i."-1";
          $endDate = $year."-".($i+1)."-1";
          $exp_monthly[$i] = isset($app->Exp->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost) ? $app->Exp->read($app->auth->user_id)->spentOnProduct($name,$startDate,$endDate)->cost : 0;
        }
        $appData = [
          'exp_total' => $totalexp,
          'inc_total' => $totalinc,
          'balance'   => $totalinc - $totalexp,
          'nav'=>$nav,
        ];
        $app->view->render($resp,'main/exp.php',[
          'monthly_exp'=>$exp_monthly,
          'exp'=>$exp,
          'name'=>$name,
          'appData'=>$appData,
          'page'=>'expense/'.$name,
          'products'=>$product,
          'tags'=> $app->Helper->getTags()
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
      $data = $this->container->Helper->getData($app,$app->auth->user_id,$year,$month,$day);
      $this->view->render($resp,'main/expenses.php',[
        'appData' => $data,
        'page'    => 'expenses',
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
          'user_id'=> $app->auth->user_id
      ];
      $exp_id = $app->Exp->save($data);
      foreach ($_POST['tags'] as $tag_id) {
          $tags_data = [
            'exp_id' => $exp_id,
            'tag_id' => $tag_id
          ];
          $app->ExpTags->save($tags_data);
      }
      return $this->redirect($resp,$app->urlFor('expenses'));
    }
    public function update($req, $resp,$args)
    {
      $app = $this;
      $id = $args['id'];
      $data = [
        'name'=> $_POST['name'],
        'cost'=> str_replace( ',', '',$_POST['cost'] ),
        'date'=> $_POST['date'],
      ];
      $app->Exp->read($id)->set($data);
      $exp_tags = $app->ExpTags->find("all",[
         "where"=>["exp_id", "=" ,$exp_id]
      ]);
      foreach ($exp_tags as $tag) {
          $app->ExpTags->read($tag->id)->delete();
      }
      if (isset($_POST['tags'])) {
         foreach ($_POST['tags'] as $tag_id) {
             $tags_data = [
               'exp_id' => $id,
               'tag_id' => $tag_id
             ];
             $app->ExpTags->save($tags_data);
         }
      }
      return $this->redirect($resp,$app->urlFor('expense.retrieve',['name'=>$_POST['name']."#".$id]));
    }
    public function delete($req, $resp,$args)
    {
      $app = $this;
      $id = $args['id'];
      $app->Exp->read($id)->delete();
      return $this->redirect($resp,$app->urlFor('expense.retrieve',['name'=>$_POST['name']]));
    }
  }

 ?>
