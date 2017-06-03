<?php 
	require 'vendor/autoload.php';
	use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

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

    $app->get('/get_tomates',function(Request $request, Response $response, $args){
    	print_r(url('?type=cherubs'));
    });	

    /* Obtener actual de cualquier tipo cualquier posicion */
    $app->get('/actual/{tipo}/{posicion}',function(Request $request, Response $response, $args){
    	$tipo = $args['tipo'];
    	$posicion = $args['posicion'];
    	$url_enviada = '?q=position==\''.$posicion.'\'&type='.$tipo;
    	$respuesta = url($url_enviada);
    	$respuesta = json_decode($respuesta);
        $respuesta = $respuesta[sizeof($respuesta)-1];
    	return sendOkResponse(json_encode($respuesta),$response);
        /* Retorna un JSON */
    });

    /* Retorna las instancias creadas dentro de una hora */
    $app->get('/hora/{tipo}/{posicion}/{anio}/{mes}/{dia}/{hora}',function(Request $request, Response $response, $args){
        $tipo = $args['tipo'];
        $posicion = $args['posicion'];
        $anio = $args['anio'];
        $mes = $args['mes'];
        $dia = $args['dia'];
        $hora = $args['hora'];

        $minutos = 
        $url_enviada = '?q=position==\''.$posicion.'\';f_anio==\''.$anio.'\';f_mes==\''.$mes.'\';f_dia==\''.$dia.'\';f_hora==\''.$hora.'\'&type='.$tipo;
        $respuesta = url($url_enviada);

        return sendOkResponse($respuesta,$response);
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

    $app->run();

?>