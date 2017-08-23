<?php

use \HTTP\Migrations\Migration;

class ExpenseIsrecurring extends Migration
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
    public function up() {
      $this->schema->table('expenses',function($table) {
        $table->boolean('is_recurring')->default(false);
      });
    }

    public function down() {
      $this->schema->table('expenses',function($table){
        $table->dropColumn('is_recurring');
      });
    }
}
