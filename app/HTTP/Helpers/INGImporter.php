<?php
namespace HTTP\Helpers;
use \League\Csv\Reader;
class INGImporter extends BankImporter
{
  public function __construct($container,$path)
  {
    $this->path = $path;
    $this->container = $container;
    $this->reader = Reader::createFromPath($path);
    $this->reader->setHeaderOffset(0);
    $this ->records = $this->reader->getRecords($this->reader->getHeader());
  }
  protected function generateExpenses(){
    $expenses = [];
    foreach ($this->records as $offset => $record) {
      $record = (object) $record;
      $description = explode("-",$record->Description);
      if (!empty($record->Debit)) {
        $data = [
          "name" => ucwords(strtolower(trim($description[0]))),
          "date" => $this->container->Carbon->createFromFormat('d/m/Y',trim($record->Date))->toDateString(),
          "cost" => floatval($record->Debit) * -1,
          "user_id" => $this->container->auth->id
        ];
        array_push($expenses,$data);
      }
    }
    return $expenses;
  }
}

?>
