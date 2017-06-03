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
    ini_set('date.timezone','America/Mexico_City');
    $date = getdate();

    $json = array(
      'id'=>Obtener_Nombre(),
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
        'value'=>(strlen($date['mday'])==1)?'0'.$date['mday']:$date['mday']
      ),
      'f_hora'=>array(
        'type'=>'Float',
        'value'=>(strlen($date['hours'])==1)?'0'.$date['hours']:$date['hours']
      ),
      'f_mes'=>array(
        'type'=>'Float',
        'value'=>$date['mon']
      ),
      'f_minutos'=>array(
        'type'=>'Float',
        'value'=>(strlen($date['minutes'])==1)?'0'.$date['minutes']:$date['minutes']
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
    ini_set('date.timezone','America/Mexico_City');
    $date = getdate();
    print_r($date);
  });

  function Obtener_Nombre(){
    ini_set('date.timezone','America/Mexico_City');
    $date = getdate();
    return $date['seconds'].$date['minutes'].$date['hours'].$date['mday'].$date['wday'].$date['mon'].$date['year'].$date['yday'].$date['weekday'].$date['month'];
  }



?>
