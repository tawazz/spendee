<?php
namespace HTTP\Helpers;
class AmexImporter extends BankImporter
{
  public $headers = ['Date','Debit','Description'];

  protected function generateExpenses(){
    $expenses = [];
    foreach ($this->records as $offset => $record) {
      $record = (object) $record;
      $description = explode("-",$record->Description);
      if (!empty($record->Debit) && floatval($record->Debit) > 0) {
        $data = [
          "name" => ucwords(strtolower(trim($description[0]))),
          "date" => $this->container->Carbon->createFromFormat('d/m/Y',trim($record->Date))->toDateString(),
          "cost" => floatval($record->Debit),
          "user_id" => $this->container->auth->id
        ];
        array_push($expenses,$data);
      }
    }
    return $expenses;
  }
}

?>
