<?php
  require 'vendor/autoload.php';
  use \Psr\Http\Message\ServerRequestInterface as Request;
  use \Psr\Http\Message\Responseinterface as Response;

  $app->post('/insert',function(Request $request,Response $response,$args){
    $data  = $request->getParsedBody();
    $temperatura = $data['temperatura'];
    $humedadRelativa = $data['humedadRelativa'];
    $humedadSuelo = $data['humedadSuelo'];
    $position = $data['position'];
    $type = $data['type'];
    $date = getdate();

    $json = array(
      'id'=>'maickol12332',
      'type'=>$type,
      "empresa"=>array(
        'type'=>'String',
        'value'=>'123',
      ),
      'f_anio'=>array(
        'type'=>'Float',
        'value'=>$date['year']
      ),
      'f_dia'=>array(
        'type'=>'Float',
        'value'=>$date['mon']
      ),
      'f_hora'=>array(
        'type'=>'Float',
        'value'=>$date['hours']
      ),
      'f_mes'=>array(
        'type'=>'Float',
        'value'=>$date['mon']
      ),
      'f_minutos'=>array(
        'type'=>'Float',
        'value'=>$date['minutes']
      ),
      'humedadRelativa'=>array(
        'type'=>'Float',
        'value'=>$humedadRelativa
      ),
      'humedadSuelo'=>array(
        'type'=>'Float',
        'value'=>$humedadSuelo
      ),
      'temperatura'=>array(
        'type'=>'Float',
        'value'=>$temperatura
      ),
      'position'=>array(
        'type'=>'Float',
        'value'=>$position
      )
    );
    //return sendOkResponse(json_encode($json),$response);
    return sendOkResponse(url_post(json_encode($json)),$response);
  });

  $app->get('/insert',function(Request $request,Response $response,$args){
    $arr = getdate();
    return sendOkResponse(json_encode($arr),$response);
  });



?>
