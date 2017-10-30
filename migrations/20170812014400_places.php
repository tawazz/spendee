<?php

use \HTTP\Migrations\Migration;

class Places extends Migration
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
      $this->schema->create('places',function($table){
        $table->increments('id')->unsigned();
        $table->string('query');
        $table->json('response');
        $table->timestamps();
      });
    }

    public function down()
    {
      $this->schema->dropIfExists('places');
    }
}
