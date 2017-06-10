    <?php

    $temperatura = $_GET['temperatura'];
    $humedadRelativa = $_GET['humedadRelativa'];
    $humedadSuelo = $_GET['humedadSuelo'];
    $position = $_GET['position'];
    $type = $_GET['type'];


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

echo json_encode($json);


function Obtener_Nombre(){
    ini_set('date.timezone','America/Mexico_City');
    $date = getdate();
    return $date['seconds'].$date['minutes'].$date['hours'].$date['mday'].$date['wday'].$date['mon'].$date['year'].$date['yday'].$date['weekday'].$date['month'];
  }

    
    $context = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => "Authorization: application/json\r\n".
        "Content-Type: application/json\r\n",
        'content' => json_encode($json)
    )
));
    
    $response = file_get_contents('http://207.249.127.215:1026/v2/entities', FALSE, $context);

  ?>