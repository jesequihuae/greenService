<?php
  require 'vendor/autoload.php';
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\Responseinterface as Response;

  $app->post('/insert',function(Request $request,Response $response,$args){
    $data  = $request->getParsedBody();
    print_r($data);
  });
  $app->get('/insert',function(Request $request,Response $response,$args){
    $arr = array('maickol'=>'12','jose','123');
    return sendOkResponse(json_encode($arr),$response);
  });
?>
