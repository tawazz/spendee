<?php
require_once __DIR__.'/../vendor/fzaninotto/faker/src/autoload.php';
require_once __DIR__. '/../Tazzy-Helpers/autoload.php';
use \HTTP\Migrations\Migration;
use \Faker\Factory;

class TestData extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
         $exp = new \Expenses();
         $inc = new \Incomes();
         $faker = Factory::create();
         $faker->addProvider(new Faker\Provider\DateTime($faker));
         for ($i=0; $i < 250; $i++) {
            $exp_data = [
                'name'=> $faker->randomElement($array = array ('food','car','travel','coles','target','hungry jacks','petrol','phone','woolworths')),
                'cost'=> $faker->numberBetween($min = 10, $max = 100),
                'date'=> $faker->dateTimeInInterval($startDate = 'now', $interval = '+ '.$i.' days', $timezone = date_default_timezone_get())->format("Y-m-d"),
                'user_id'=> 20
              ];
           $exp_id = $exp->save($exp_data);

         }

         for ($i=1; $i < 336; $i+=14) {
            $inc_data = [
                'name'=> $faker->randomElement($array = array ('salary','bussiness','gifts')),
                'cost'=> $faker->numberBetween($min = 150, $max = 2000),
                'date'=> $faker->dateTimeInInterval($startDate = 'now', $interval = '+ '.$i.' days', $timezone = date_default_timezone_get())->format("Y-m-d"),
                'user_id'=> 20
              ];
           $inc_id = $inc->save($inc_data);
         }

    }
}
