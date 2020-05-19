<?php
namespace HTTP\Helpers;
class UpImporter extends BankImporter
{

  protected function generateExpenses(){
    $expenses = [];
    foreach ($this->records as $offset => $record) {
      $record = (object) $record;
      if (!empty($record->{'Transaction Type'}) && $record->{'Transaction Type'} == 'Purchase') {
        $data = [
          "name" => $record->Payee,
          "date" => $this->container->Carbon->createFromFormat('Y-m-d',trim($record->{'Settled Date'}))->toDateString(),
          "cost" => floatval($record->{'Total (AUD)'}) * -1,
          "user_id" => $this->container->auth->id
        ];
        array_push($expenses,$data);
      }
    }
    return $expenses;
  }
}

?>
