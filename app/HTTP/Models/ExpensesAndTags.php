<?php
  namespace HTTP\Models;
  use Illuminate\Database\Capsule\Manager as DB;

  class ExpensesAndTags extends \HTTP\Models\BaseModel {

    protected $table = "expenses_and_tags";

    public function getRelatedTags($name)
    {
      return $this->select('tag',DB::raw('Count(*) as count'))
      ->where('name', 'LIKE', "%".$name."%")
      ->groupBy('tag')
      ->orderBy(DB::raw('count'),'desc')->get();
    }

    public function hasTag($exp_id)
    {
      $hasTag = $this->where("exp_id",$exp_id)->count();
      return $hasTag > 0;
    }

  }
 ?>
