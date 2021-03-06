<?php
  namespace HTTP\Controllers\Main;
  /**
   * BudgetController
   */
  class BudgetController extends \HTTP\Controllers\BaseController
  {

    public function __invoke($req, $resp,$args)
    {
        $this->list($req,$resp,$args);
    }
    public function retrieve($req, $resp,$args)
    {
      $app = $this;
      $id = $args['id'];

      $budget = $app->Budget->get($id);
      if ($budget->id == $app->auth->id) {
        $budgetTags = $app->BudgetTag->find("all",[
          "fields" => ["tag_id"],
          "where"=>["bud_id","=",$budget->id]
        ]);
        $budget->tags = $budgetTags;

        return $resp->withJson($budget);
      }
      return $resp->withJson(["error"=>"Forbidden"],403);
    }
    public function list($req,$resp,$args)
    {
      $app = $this->container;
      $year = isset($args['year'])?$args['year']:NULL;
      $month = isset($args['month'])?$args['month']:NULL;
      $data = $app->Helper->getData($app,$app->auth->id,$year,$month,NULL);
      $budgets = $app->Budget->getBudgetData($app,$data->nav['current']['year'],$data->nav['current']['month']);
      $app->view->render($resp,'budget/home.php',[
        'appData' => $data,
        'budgets' => $budgets,
        'page'    => 'budget',
        'totals'  => []
      ]);
    }
    public function create($req, $resp,$args)
    {
      $app = $this->container;
      $data = [
        "name"       => $_POST['name'],
        "amount"     => str_replace( ',', '',$_POST['amount'] ),
        "start_date" => $_POST['date'],
        "id"    => $app->auth->id
      ];
      $bud_id = $app->Budget->save($data);

      if(in_array(0,$_POST['tags'])){
        $tags = $app->Tags->find('all');
        foreach ($tags as $tag) {
          $data = [
            "tag_id"     => $tag->id,
            "bud_id"     => $bud_id
          ];
          $app->BudgetTag->save($data);
        }
      }
      else
      {
        foreach ($_POST['tags'] as $tag_id) {
          $data = [
            "tag_id"     => $tag_id,
            "bud_id"     => $bud_id
          ];
          $app->BudgetTag->save($data);
        }
      }
      return $this->redirect($resp,$app->urlFor('budgets'));
    }
    public function update($req, $resp,$args)
    {
      $app = $this;
      $bud_id= $args['id'];
      $app->Budget->read($bud_id)->set([
        "name"       => $_POST['name'],
        "amount"     => str_replace( ',', '',$_POST['amount'] ),
      ]);

      $app->BudgetTag->deleteTagsFromBudget($bud_id);

      if(in_array(0,$_POST['tags'])){
        $tags = $app->Tags->find('all');
        foreach ($tags as $tag) {
          $data = [
            "tag_id"     => $tag->id,
            "bud_id"     => $bud_id
          ];
          $app->BudgetTag->save($data);
        }
      }
      else
      {
        foreach ($_POST['tags'] as $tag_id) {
          $data = [
            "tag_id"     => $tag_id,
            "bud_id"     => $bud_id
          ];
          $app->BudgetTag->save($data);
        }
      }
      return $this->redirect($resp,$app->urlFor('budgets'));
    }
    public function delete($req, $resp,$args)
    {
      $app = $this->container;
      $id = $args['id'];
      $app->Budget->read($id)->delete();
      return $this->redirect($resp,$app->urlFor('budgets'));
    }
  }

 ?>
