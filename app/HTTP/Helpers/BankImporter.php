<?php
namespace HTTP\Helpers;
use \League\Csv\Reader;
use \Symfony\Component\Filesystem\Filesystem;
use \HTTP\Helpers\Utils;
date_default_timezone_set('Australia/Perth');

abstract class BankImporter
{
  public $path;
  public $container;
  public $reader;
  public $headers;

  protected $records;
  abstract protected function generateExpenses(); //returns array of expenses

  public function __construct($container,$path)
  {
    $this->path = $path;
    $this->container = $container;
    $this->reader = Reader::createFromPath($path);
    if (isset($this->headers)) {
      $this->records = $this->reader->getRecords($this->headers);
    }
  }

  public function import()
  {
    $filesystem = new Filesystem();
    $Carbon = $this->container->Carbon;
    if ($filesystem->exists($this->path)) {
      $expenses = $this->generateExpenses();
      foreach ($expenses as $exp) {
        # Utils::addExpense($this->container,(object) $exp);
      }
      $this->container['log']->debug("imported",$expenses);
      dump($expenses);
    }
    $this->container['log']->debug("bank import: file does not exist.");
  }
}

?>
