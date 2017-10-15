<?php

use \HTTP\Migrations\Migration;

class RenameBudgetDateColumn extends Migration
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
      $this->schema->table('budgets',function($table){
        $table->renameColumn('start_date','date');
      });
    }

    public function down()
    {
      $this->schema->table('budgets',function($table){
        $table->renameColumn('date','start_date');
      });
    }
}
