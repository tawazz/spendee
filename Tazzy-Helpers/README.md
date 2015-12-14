# Tazzy-Helpers
php helper functions for common tasks ranging from database , file handling, validation,emails,hashing, tokens and sessions

# Using Tazzy-Helpers

* To use Tazzy-Helpers in your project:
    * include "Tazzy-Helpers/autoload.php"; 
    
* Database accesss:
    * edit the config file to set your database config settings. Note Database uses PDO
    
* There is two ways to use the database helpers, one way is to use the querybuilder class which uses php code to create sql queries
  and the other way is to inherit the Table class to use it on your models for a basic ORM
    
