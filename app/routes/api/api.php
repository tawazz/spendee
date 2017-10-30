<?php
require 'json_const.php';


$app->post('/api/expenses/update',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

$app->post('/api/incomes/update',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

$app->post('/api/expenses/delete',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

$app->post('/api/incomes/delete',function() use($app){
    $body = $app->request->getBody();
    $body = json_decode($body);

});

// get routes
$app->get('/api/expenses/:id',function($id) use($app){

   $app->Helper->JsonResponse($app,json_encode($app->Exp->read($id)->get()));

});

?>
