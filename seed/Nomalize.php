<?php

use \HTTP\Migrations\Seed;
use \HTTP\Models\Nomalize as NomalizeExpenses;

class Nomalize extends Seed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
      $normalize = [
        'coles' => 'coles',
        'mcdonalds' => 'mcdonalds',
        'nandos' => 'nandos',
        'car' => 'st george',
        'kfc' => 'kfc',
        'subway' => 'subway',
        'target' => 'target',
        'kmart' => 'kmart'
    ];

      foreach ($normalize as $key => $value) {
        $N = new NomalizeExpenses();
        $N->create(["key"=> $key, "value"=> $value, "user_id" => 7]);
      }
    }
}
