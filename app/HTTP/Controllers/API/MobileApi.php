<?php
    namespace HTTP\Controllers\API;
    use \HTTP\Helpers\Utils;

    /**
     *
     */
    class MobileApi extends \HTTP\Controllers\BaseController
    {
        public function __invoke($req, $resp,$args)
        {
            return $this->get($req,$resp,$args);
        }

        public function auth($req, $resp,$args)
        {
          $app = $this->container;
          $body = json_decode($req->getBody()->getContents());
          $user = $this->User;
          $fetch_user = $user->where('username',$body->auth->username)->first();

          if($fetch_user && $app->hash->make($body->auth->password,$body->auth->username) == $fetch_user->password ){
            $response = [
              'auth'=>true,
              "user"=>$fetch_user
            ];
          }else{
            $response = [
              'auth'=>false
            ];
            $resp = $resp->withStatus(403);
          }

          return $resp->withJson($response);
        }

        public function get($req, $resp, $args)
        {
            $app = $this->container;
            $year = isset($args['year']) ? $args['year'] : Null;
            $month = isset($args['month']) ? $args['month'] : Null;
            $day = isset($args['day']) ? $args['day'] : Null;
            $user_id = isset($args['user']) ? $args['user'] : Null;

            if(isset($year)&& isset($month) && isset($day) ){

              $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-".$day,$year."-".$month."-".($day+1));
              $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-".$day,$year."-".$month."-".($day+1));
              $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
              $allIncomes= $app->Inc->read($user_id)->activity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
              $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-".$day,$year."-".$month."-".($day+1));
              $exptags = $app->ExpTags->tagData($user_id,false,0,$year."-".$month."-1",$year."-".$month."-".($day+1));
          }else if(isset($year)&& isset($month) ){

            $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
            $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
            $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
            $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
            $allIncomes = $app->Inc->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
            $exptags = $app->ExpTags->tagData($user_id,false,0,$year."-".$month."-1",$year."-".($month+1)."-1");

          }else if(isset($year)){
            $totalexp = $app->Exp->read($user_id)->totalExp($year."-"."1"."-1",($year+1)."-1-1");
            $totalinc = $app->Inc->read($user_id)->totalInc($year."-"."1"."-1",($year+1)."-1-1");
            $allExpenses = $app->Exp->read($user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
            $allIncomes = $app->Inc->read($user_id)->activity($year."-"."1"."-1",($year+1)."-1-1");
            $itemSpent= $app->Exp->read($user_id)->allActivity($year."-"."1"."-1",($year+1)."-1-1");
            $exptags = $app->ExpTags->tagData($user_id,false,0,$year."-"."1"."-1",($year+1)."-1-1");

          }else{
              $month= date('m');
              $year= date('Y');
              $day = date('d');
              $totalexp = $app->Exp->read($user_id)->totalExp($year."-".$month."-1",$year."-".($month+1)."-1");
              $totalinc = $app->Inc->read($user_id)->totalInc($year."-".$month."-1",$year."-".($month+1)."-1");
              $allExpenses = $app->Exp->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
              $allIncomes= $app->Inc->read($user_id)->activity($year."-".$month."-1",$year."-".($month+1)."-1");
              $itemSpent = $app->Exp->read($user_id)->allActivity($year."-".$month."-1",$year."-".($month+1)."-1");
              $exptags = $app->ExpTags->tagData($user_id,false,0,$year."-".$month."-1",$year."-".($month+1)."-1");

          }

          $totalinc = isset($totalinc->sum)?$totalinc->sum:0;
          $totalexp = isset($totalexp->sum)?$totalexp->sum:0;

          $expDates = [];
          $expData = [];

          $incDates = [];
          $incTotals = [];

          foreach ($allExpenses as $exp) {
            $expDates[$exp->date] = NULL;
          }

          foreach ($allIncomes as $inc) {
            $incDates[$inc->date] = NULL;
          }

          foreach ($expDates as $key => $value) {
            $expenses = [];
            foreach ($allExpenses as $exp) {
              if($key == $exp->date){
                array_push($expenses,$exp);
                $expDates[$exp->date] = $expenses;
              }
            }
          }

          foreach ($incDates as $key => $value) {
            $incomes = [];
            foreach ($allIncomes as $inc) {
              if($key == $inc->date){
                array_push($incomes,$inc);
                $incDates[$inc->date] = $incomes;
              }
            }
          }


          $response = [
            "exp_total" => $totalexp,
            "inc_total" => $totalinc,
            "balance"   => $totalinc - $totalexp,
            "exp_data"  => $expDates,
            "inc_data"  => $incDates
          ];

          return $resp->withJson($response);
        }

        public function addExpense($req,$resp,$args)
        {
          $app = $this->container;
          $body = json_decode($req->getBody()->getContents());
          $body->item->cost = Utils::fixMoneyInput($body->item->cost);
          $exp_id = $app->Exp->save($body->item);
          if(isset($body->tags)){

            foreach ($body->tags as $tag) {
                $tags_data = [
                  'exp_id' => $exp_id,
                  'tag_id' => $tag["tag_id"]
                ];
                $app->ExpTags->save($tags_data);
            }

          }

          $response = [
            "added" => true
          ];
          return $resp->withJson($response);
        }
        
        public function addIncome($req,$resp,$args)
        {
          $app = $this->container;
          $body = json_decode($req->getBody()->getContents());
          $body->item->cost = Utils::fixMoneyInput($body->item->cost);
          $id = $app->Inc->save($body->item);
          if(isset($body->tags)){

            foreach ($body->tags as $tag) {
                $tags_data = [
                  'inc_id' => $id,
                  'tag_id' => $tag["tag_id"]
                ];
                $app->IncTags->save($tags_data);
            }

          }

          $response = [
            "added" => true
          ];
          return $resp->withJson($response);
        }
        public function update($req, $resp,$args)
        {
          $app = $this->container;
          $body = json_decode($req->getBody()->getContents());
          $bud_id= $args['id'];
          $app->Budget->read($bud_id)->set([
            "name"       => $body->name,
            "amount"     => Utils::fixMoneyInput($body->amount),
          ]);

          $app->BudgetTag->deleteTagsFromBudget($bud_id);

          if(in_array(0,$body->tags)){
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
            foreach ($body->tags as $tag_id) {
              $data = [
                "tag_id"     => $tag_id,
                "bud_id"     => $bud_id
              ];
              $app->BudgetTag->save($data);
            }
          }
          return $resp->withJson($app->Budget->get($bud_id));
        }
        public function delete($req, $resp,$args)
        {
          $app = $this->container;
          $id = $args['id'];
          $app->Budget->read($id)->delete();
          return $resp->withJson(['success' => true]);
        }
    }

 ?>
