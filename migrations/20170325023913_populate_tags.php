<?php

use \HTTP\Migrations\Migration;

class PopulateTags extends Migration
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
      $this->execute("
      INSERT INTO `tags` (`id`, `name`) VALUES
         (1, 'Food & Drink'),
         (2, 'Car'),
         (3, 'Bills'),
         (4, 'Travel'),
         (5, 'Family & Friends'),
         (7, 'Entertainment'),
         (8, 'Shopping'),
         (9, 'Accomodation'),
         (10, 'Healthcare'),
         (11, 'Clothes'),
         (12, 'Transport'),
         (13, 'Pets'),
         (14, 'Groceries'),
         (15, 'Drinks'),
         (16, 'Hobbies'),
         (17, 'Education'),
         (18, 'Cinema'),
         (19, 'Love'),
         (20, 'Rent'),
         (21, 'Online Spending'),
         (22, 'Savings'),
         (23, 'Gifts'),
         (24, 'Other'),
         (25, 'Salary'),
         (26, 'Loan'),
         (27, 'Extra Income'),
         (28, 'Business'),
         (29, 'Movies'),
         (30, 'Fees'),
         (31, 'Interest Charges'),
         (32, 'Phone'),
         (33, 'Fitness'),
         (34, 'Shopping'),
         (35, 'Gifts or Presents'),
         (36, 'Credit card '),
         (37, 'Loan');
      ");
    }
}
