<?php

use \HTTP\Migrations\Migration;

class LocationsTable extends Migration
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
      $this->schema->getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
      $this->schema->create('locations',function($table){
        $table->increments('id')->unsigned();
        $table->string('name');
        $table->double('lat',7,4);
        $table->double('long',7,4);
      });

      $this->schema->table('expenses',function($table){
        $table->renameColumn('exp_id','id');
        $table->integer('location_id')->unsigned()->nullable();
        $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        $table->enum('repeat',['0','1','7','14','30','365'])->nullable();
        $table->date('end_repeat')->nullable();
        $table->enum('notification',['-7','-2','-1','0'])->nullable();
        $table->text('photo')->nullable();
      });

      $this->schema->table('incomes',function($table){
        $table->renameColumn('inc_id','id');
      });

      $this->schema->table('users',function($table){
        $table->renameColumn('user_id','id');
      });

      $this->schema->rename('exp_tags','expense_tags');
      $this->schema->rename('inc_tags','income_tags');
      $this->schema->rename('bud_tags','budget_tags');
      $this->schema->rename('budget','budgets');
      $this->schema->rename('session','remember');



      $this->schema->dropIfExists('pageview');

    }

    public function down()
    {
      $this->schema->table('expenses',function($table){
        $table->renameColumn('id','exp_id');
        $table->dropColumn('location_id')->unsigned()->nullable();
        $table->dropForeign('location_id');
        $table->dropColumn('repeat');
        $table->dropColumn('end_repeat');
        $table->dropColumn('notification');
        $table->dropColumn('photo');
      });

      $this->schema->table('incomes',function($table){
        $table->renameColumn('id','inc_id');
      });

      $this->schema->table('users',function($table){
        $table->renameColumn('id','user_id');
      });

      $this->schema->rename('expense_tags','exp_tags');
      $this->schema->rename('income_tags','inc_tags');
      $this->schema->rename('budget_tags','bud_tags');
      $this->schema->rename('budgets','budget');
      $this->schema->rename('remember','session');

      $this->schema->dropIfExists('locations');
    }
}
