<?php
namespace HTTP\Controllers\API;
use \GuzzleHttp\Client as Http;
/**
*
*/
class Places extends \HTTP\Controllers\BaseController
{
  protected $client_id = "0JOOESNULVBUOHVDNPVU5OMQG1JF0FLSFTOLTLHE3NAED3YZ";
  protected $client_secret= "JLQV5FM2KKDGYFJJA5FOT11YNY1T15MGJ2AXMW5YVLN15R5Y";

  public function __invoke($req, $resp,$args)
  {
    return $this->get($req,$resp,$args);
  }

  public function retrieve($req, $resp,$args)
  {

  }

  public function get($req, $resp, $args)
  {
    $app = $this->container;
    $places = $app->Places->exists($req->getQueryParam('query'));
    if ($places) {
      return $resp->withJson($places);
    }
    $api_endpoint = "https://api.foursquare.com/v2/venues/search?";
    $search_url = "{$api_endpoint}ll={$req->getQueryParam('ll')}&query={$req->getQueryParam('query')}&client_id={$this->client_id}&client_secret={$this->client_secret}&v=20170812";

    $client = new Http();
    $res = $client->request('GET', $search_url);
    if ($res->getStatusCode()==200) {
      $place = $app->Places;
      $place->query = $req->getQueryParam('query');
      $place->response = $res->getBody();
      $place->save();
      $places = $app->Places->exists($req->getQueryParam('query'));
      return $resp->withJson($places);
    }

    return $resp->withJson(false,400);
  }

  public function create($req, $resp,$args)
  {


  }

  public function update($req, $resp,$args)
  {

  }

  public function delete($req, $resp,$args)
  {

  }

  private function getDistance( $latitude1, $longitude1, $latitude2, $longitude2 ) {
    $earth_radius = 6371;

    $dLat = deg2rad( $latitude2 - $latitude1 );
    $dLon = deg2rad( $longitude2 - $longitude1 );

    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;

    $distance = getDistance( 56.130366, -106.34677099999, 57.223366, -106.34675644699 );
    if( $distance < 100 ) {
        echo "Within 100 kilometer radius";
    } else {
        echo "Outside 100 kilometer radius";
    }

    return $d;
  }
}

?>
