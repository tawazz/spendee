<?php

use \HTTP\Migrations\Migration;

class ReccuringExpenses extends Migration
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
       $this->schema->create('recurring_expenses',function($table){
         $table->increments('id')->unsigned();
         $table->integer('exp_id');
         $table->foreign('exp_id')->references('id')->on('expenses')->onDelete('cascade');
         $table->integer('interval')->default(1);
         $table->enum('reminder',[0,-1,-7,-30])->nullable();
         $table->timestamps();
       });

       $this->schema->table('expenses',function($table){
         $table->dropForeign('expenses_location_id_foreign');
         $table->dropColumn('location_id');
         $table->dropColumn('notification');
       });

       $this->schema->table('locations',function($table){
         $table->integer('exp_id');
         $table->foreign('exp_id')->references('id')->on('expenses')->onDelete('cascade');
       });
     }

     public function down()
     {
       $this->schema->dropIfExists('reccuring_expenses');
     }
}
