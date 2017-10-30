<?php

use \HTTP\Migrations\Migration;

class TagType extends Migration
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
      $this->schema->table('tags',function($table) {
        $table->enum('type',['expense','income'])->default('expense')->nullable();
      });
      $this->execute("update tags set type='income' where id in (25,26,27,28)");
      $this->execute("update tags set type=NULL where id in (22,24,31)");
      $this->execute("update tags set name='Interest' where id = 31");
      $this->execute("delete from tags where id = 37");
    }

    public function down()
    {
      $this->schema->table('tags',function($table) {
        $table->dropColumn('type');
      });
    }
}
