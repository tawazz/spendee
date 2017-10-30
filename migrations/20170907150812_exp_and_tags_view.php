<?php

use \HTTP\Migrations\Migration;

class ExpAndTagsView extends Migration
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
      $this->execute("create or replace view expenses_and_tags as
            select e.id as exp_id, e.name as name, t.tag_id as tag from
            expense_tags as t join expenses as e on t.exp_id = e.id;");
    }

    public function down()
    {
      $this->execute("drop view expenses_and_tags");
    }
}
