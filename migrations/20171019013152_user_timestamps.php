<?php

use \HTTP\Migrations\Migration;

class UserTimestamps extends Migration
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
       $this->schema->table('users',function($table) {
         $table->timestamps();
         $table->string('firstname', 120)->nullable()->change();
         $table->string('lastname', 120)->nullable()->change();
       });
     }

     public function down() {
       $this->schema->table('users',function($table){
         $table->dropColumn('created_at');
         $table->dropColumn('updated_at');
         $table->string('firstname', 30)->change();
         $table->string('lastname', 30)->change();
       });
     }
}
