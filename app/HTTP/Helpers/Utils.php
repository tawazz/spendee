<?php
namespace HTTP\Helpers;

/**
 *
 */
class Utils
{

  public static function clearExpRouteCache($app,$date){
    // clear cache
    $date = $app->Carbon->parse($date);
    $cache_keys = [
      'api.expenses.get.'.$app->auth->id.'.'.$date->year.'.'.$date->month,
      'api.totals.'.$app->auth->id.'.'.$date->year.'.'.$date->month,
      'api.exp.tags'.$app->auth->id.'.'.$date->year.'.'.$date->month
    ];
    $app->cache->deleteMultiple($cache_keys);
  }
}


 ?>
