<?php

use \HTTP\Migrations\Migration;

class UpdateRecurringExpenses extends Migration
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
    public function change()
    {
      $this->schema->table('recurring_expenses',function($table){
        $table->date('end_repeat')->nullable();
        $table->boolean('ended')->default(false);
        $table->enum('repeat',['0','1','7','14','30','365'])->nullable();
      });

      $this->schema->table('expenses',function($table){
        $table->dropColumn('end_repeat');
        $table->dropColumn('repeat');
        $table->integer('parent_id')->nullable();
        $table->foreign('parent_id')->references('id')->on('expenses')->onDelete('cascade');
      });
    }
}
