<?php

	require 'vendor/autoload.php';
	use \Psr\Http\Message\ServerRequestInterface as Request;
     use \Psr\Http\Message\Responseinterface as Response;

    $c = new \Slim\Container();
    $c['errorHandler'] = function ($c) {
        return function ($request, $response, $exception) use ($c) {
        	$error = array('error' => $exception->getMessage());
          return $c['response']->withStatus(500)
                                 ->withHeader('Content-Type', 'application/json')
                                 ->write(json_encode($error));
        };
    };

    $app = new \Slim\App($c);

    require 'utils.php';

	require 'view/components.php';

    /* Obtener actual de cualquier tipo cualquier posicion */
    $app->get('/actual/{tipo}/{posicion}/{anio}/{mes}/{dia}', function(Request $request, Response $response, $args){
    	 $tipo = $args['tipo']; $posicion = $args['posicion']; $anio = $args['anio']; $mes = $args['mes']; $dia = $args['dia'];
      $temperatura = 0; $humedadSuelo = 0;  $humedadRelativa = 0;
      $Array = array();

       $url_enviada = '?q=position==\''.$posicion.'\';f_anio==\''.$anio.'\';f_mes==\''.$mes.'\';f_dia==\''.$dia.'\';&type='.$tipo.'&limit=200';
    	$respuesta = url($url_enviada);
      // print_r($url_enviada);
    	$respuesta = json_decode($respuesta);
      // print_r($respuesta);
      $respuesta = $respuesta[sizeof($respuesta)-1];
      //print_r(json_encode($respuesta));
      $temperatura = $respuesta->temperatura->value;
      $humedadSuelo = $respuesta->humedadSuelo->value;
      $humedadRelativa = $respuesta->humedadRelativa->value;

      array_push($Array, array("humedadRelativa" => $humedadRelativa,
                               "humedadSuelo" => $humedadSuelo,
                               "temperatura" => $temperatura));
    	//return sendOkResponse(json_encode($respuesta),$response);
      print_r(json_encode($Array));
			//echo $url_enviada;
    });

    /* Retorna las instancias creadas dentro de una hora */
    $app->get('/hora/{tipo}/{posicion}/{anio}/{mes}/{dia}/{hora}', function(Request $request, Response $response, $args){
        $tipo = $args['tipo']; $posicion = $args['posicion']; $anio = $args['anio']; $mes = $args['mes']; $dia = $args['dia'];  $hora = $args['hora'];
        $temperatura = 0; $humedadSuelo = 0;  $humedadRelativa = 0;
        // ($hora < 10) ? $hora = '0'.$hora : $hora = $hora;
        $Array = array();

        $url_enviada = '?q=position==\''.$posicion.'\';f_anio==\''.$anio.'\';f_mes==\''.$mes.'\';f_dia==\''.$dia.'\';f_hora==\''.$hora.'\'&type='.$tipo.'\'&limit=200';
        $respuesta = url($url_enviada);

        $respuesta = json_decode($respuesta);

        for ($i=0; $i < sizeof($respuesta) ; $i++)
        {
          $temperatura = $respuesta[$i]->temperatura->value;
          $humedadSuelo = $respuesta[$i]->humedadSuelo->value;
          $humedadRelativa = $respuesta[$i]->humedadRelativa->value;

          array_push($Array, array( "hora" => $hora.':'.$respuesta[$i]->f_minutos->value,
                                       "humedadRelativa" => $humedadRelativa,
                                       "humedadSuelo" => $humedadSuelo,
                                       "temperatura" => $temperatura));
        }

       //print_r(json_encode($Array));
      	return sendOkResponse(json_encode($Array),$response);
    });

    /* Retorna las instancias creadas en un dia promediadas por hora /{tipo}/{posicion}/{anio}/{mes}/{dia} */
    $app->get('/dia/{tipo}/{posicion}/{anio}/{mes}/{dia}', function(Request $request, Response $response, $args){
        $tipo = $args['tipo']; $posicion = $args['posicion']; $anio = $args['anio']; $mes = $args['mes']; $dia = $args['dia'];
        $temperatura = 0;
        $humedadRelativa = 0;
        $humedadSuelo = 0;
        $Array = array(); // JSON a retornar con los promedios por hora

        for ($i=0; $i < 24; $i++)
        {
            if($i<10)
                $hora = '0'.$i;
            else
                $hora = $i;

             $url_enviada = '?q=position==\''.$posicion.'\';f_anio==\''.$anio.'\';f_mes==\''.$mes.'\';f_dia==\''.$dia.'\';f_hora==\''.$hora.'\'&type='.$tipo;
             $respuesta = url($url_enviada);
             $respuesta = json_decode($respuesta);

             for ($j=0; $j < sizeof($respuesta); $j++)
             {
                $temperatura += $respuesta[$j]->temperatura->value;
                $humedadRelativa += $respuesta[$j]->humedadRelativa->value;
                $humedadSuelo += $respuesta[$j]->humedadSuelo->value;
             }

             $totalRegistros = sizeof($respuesta);

             if($temperatura != 0 && $humedadRelativa != 0 && $humedadSuelo == 0){
                  $temperatura = $temperatura/$totalRegistros;
                  $humedadRelativa = $humedadRelativa/$totalRegistros;
                  $humedadSuelo = $humedadSuelo/$totalRegistros;
                  $temperatura = round($temperatura, 2);
             }

             array_push($Array, array( "hora" => "".$hora.":00",
                                       "humedadRelativa" => $humedadRelativa,
                                       "humedadSuelo" => $humedadSuelo,
                                       "temperatura" => $temperatura));
             $temperatura = 0;
             $humedadRelativa = 0;
             $humedadSuelo = 0;
        }
        print_r(json_encode($Array));
    });

    /* Retorna los valores de todo un mes promediados por dia */
    $app->get('/mes/{tipo}/{posicion}/{anio}/{mes}', function(Request $request, Response $response, $args){
        $tipo = $args['tipo']; $posicion = $args['posicion']; $anio = $args['anio']; $mes = $args['mes'];
        if(strlen($mes) == 1)
            $mes = '0'.$mes;


        $Array = array();
        $totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        $temperatura = 0;
        $humedadSuelo = 0;
        $humedadRelativa = 0;

        for ($i=0; $i <= $totalDias; $i++)
        {
            if($i<10)
                $dia = '0'.$i;
            else
                $dia = $i;

           $url_enviada = '?q=position==\''.$posicion.'\';f_anio==\''.$anio.'\';f_mes==\''.$mes.'\';f_dia==\''.$dia.'\';&type='.$tipo;
           $respuesta = url($url_enviada);
           $respuesta = json_decode($respuesta);

           for ($j=0; $j < sizeof($respuesta) ; $j++)
           {
               $temperatura += $respuesta[$j]->temperatura->value;
               $humedadSuelo += $respuesta[$j]->humedadSuelo->value;
               $humedadRelativa += $respuesta[$j]->humedadRelativa->value;
               // echo $temperatura;
           }

           $totalRegistros = sizeof($respuesta);

           if($temperatura != 0 && $humedadRelativa != 0 && $humedadSuelo == 0){
             $temperatura = $temperatura/$totalRegistros;
             $humedadRelativa = $humedadRelativa/$totalRegistros;
             $humedadSuelo = $humedadSuelo/$totalRegistros;
          }

           array_push($Array, array( "dia" => "".$i,
                                     "humedadRelativa" => $humedadRelativa,
                                     "humedadSuelo" => $humedadSuelo,
                                     "temperatura" => $temperatura));

           $temperatura = 0;
           $humedadRelativa = 0;
           $humedadSuelo = 0;

        }

        // return sendOkResponse(json_encode($Array), $response);
        print_r(json_encode($Array));
    });

    /* Retorna los valores de una semana a partir de una fecha dada */
    $app->get('/semana/{tipo}/{posicion}/{anio}/{mes}/{dia}',function(Request $request, Response $response, $args){
      $anio = $args['anio'];  $mes = $args['mes'];  $dia = $args['dia'];  $tipo = $args['tipo'];  $posicion = $args['posicion'];

      $temperatura = 0;
      $humedadRelativa = 0;
      $humedadSuelo = 0;
      $Array = array();

      $fechaInicio = $anio.'-'.$mes.'-'.$dia;
      $fechaFinal = strtotime('+6 day', strtotime($fechaInicio));
      $fechaFinal = date ('Y-m-j',$fechaFinal);

      while(strcmp($fechaInicio, $fechaFinal) != 0){

        if($dia<10)
          $dia = '0'.$dia;

        $url_enviada = '?q=position==\''.$posicion.'\';f_anio==\''.$anio.'\';f_mes==\''.$mes.'\';f_dia==\''.$dia.'\';&type='.$tipo;
        $respuesta = url($url_enviada);
        $respuesta = json_decode($respuesta);

        for ($j=0; $j < sizeof($respuesta) ; $j++)
        {
          $temperatura += $respuesta[$j]->temperatura->value;
          $humedadSuelo += $respuesta[$j]->humedadSuelo->value;
          $humedadRelativa += $respuesta[$j]->humedadRelativa->value;
        }

        $totalRegistros = sizeof($respuesta);

        if($temperatura != 0 && $humedadRelativa != 0 && $humedadSuelo == 0){
          $temperatura = $temperatura/$totalRegistros;
          $humedadRelativa = $humedadRelativa/$totalRegistros;
          $humedadSuelo = $humedadSuelo/$totalRegistros;
        }

        array_push($Array, array( "dia" => "".$dia,
                                     "humedadRelativa" => $humedadRelativa,
                                     "humedadSuelo" => $humedadSuelo,
                                     "temperatura" => $temperatura));

        #Aumentando fechas
        $fechaInicio = strtotime('+1 day',strtotime($fechaInicio));
        $fechaInicio = date('Y-m-j',$fechaInicio);
        $fecha = explode('-',$fechaInicio);
        $anio = $fecha[0]; $mes = $fecha[1]; $dia = $fecha[2];

        $temperatura = 0;
        $humedadRelativa = 0;
        $humedadSuelo = 0;
      }

       print_r(json_encode($Array));
    });

    /*Funcion global para la url y retorna el objeto*/
    function url($url_extension){
    	header("Content-Type: application/json");
    	$curl = curl_init();
    	@curl_setopt("http://207.249.127.215:1026/v2/entities".$url_extension);
    	curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => "http://207.249.127.215:1026/v2/entities".$url_extension
		));
    	$answer = curl_exec($curl);
    	return $answer;
    }

	//FUNCION PARA INSERTAR UN JSON AL OREON CONTEXT BROKER CON CURL
	function url_post($data){
		header("Content-Type: application/json");
		$url = 'http://207.249.127.215:1026/v2/entities';
		$ch = curl_init($url);
		@curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array(
	       	'Content-Type:application/json',
			'Content-Length:'.strlen($data))
		);
		$response = curl_exec($ch);
		return $response;
	}

    $app->run();

?>
