<?php
require 'app/config/settings.php';
if(Settings::get('debug')){
  ini_set('display_errors',1);
  ini_set('display_startup_errors',1);
  error_reporting(-1);
}else{
  ini_set('display_errors','Off');
  ini_set('display_startup_errors','Off');
  error_reporting(0);
  register_shutdown_function( "fatal_handler" );
}
function fatal_handler() {
  $errfile = "unknown file";
  $errstr  = "shutdown";
  $errno   = E_CORE_ERROR;
  $errline = 0;

  $error = error_get_last();

  if( $error !== NULL) {
    $errno   = $error["type"];
    $errfile = $error["file"];
    $errline = $error["line"];
    $errstr  = $error["message"];


    echo "<h1 style='text-align:center;'>An Error Occurred </h1>";
  }
}

  require 'app/bootstrap.php';
?>
