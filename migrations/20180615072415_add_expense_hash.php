<?php

use \HTTP\Migrations\Migration;

class AddExpenseHash extends Migration
{
    public function up()
    {
        $this->schema->table('expenses',function($table) {
            $table->string('hash')->nullable();
        });
    }

    public function down()
    {
         $this->schema->table('expenses',function($table) {
            $table->dropColumn('hash');
        });
    }
}
