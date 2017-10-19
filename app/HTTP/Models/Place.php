<?php
  namespace HTTP\Models;

  class Place extends \HTTP\Models\BaseModel{

    protected $guarded = [];
    protected $table = "places";

    public function exists($value)
    {
      $exists = $this->where('query', 'LIKE', "%".$value."%")->get();
      if ($exists->isNotEmpty()) {
        $data = [];
        array_push($data,["query"=>$exists[0]->query,"response"=>$exists[0]->response()]);
        return $data;
      }
      return false;
    }

    public function response()
    {
      return json_decode($this->response)->response->venues;
      //return $this->response;
    }

  }
 ?>
