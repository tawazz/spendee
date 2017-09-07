<?php
  namespace HTTP\Models;

  class ExpensesAndTags extends \HTTP\Models\BaseModel {

    protected $table = "expenses_and_tags";

    public function getRelatedTags($name)
    {
      return $this->select('tag')->where('name', 'LIKE', "%".$name."%")->distinct()->get();
    }

    public function hasTag($exp_id)
    {
      $hasTag = $this->where("exp_id",$exp_id)->count();
      return $hasTag > 0;
    }

  }
 ?>
