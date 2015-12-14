<?php
    function databaseLoader($class)
    {
        $filename = strtolower($class) . '.php';
        $file = __DIR__ .'/Tazzy/database/'.$filename;
        if (!file_exists($file))
        {
            return false;
        }
        require_once $file;
    }

    function helpersLoader($class)
    {
        $filename = strtolower($class) . '.php';
        $file = __DIR__ .'/Tazzy/helpers/'. $filename;
        if (!file_exists($file))
        {
            return false;
        }
        require_once $file;
    }
    spl_autoload_register('databaseLoader');
    spl_autoload_register('helpersLoader');

?>
