    RUTAS PARA HACER USO DE LA API DE GREENHOUSE

    *****************NOTA**************************
    *  Los formatos a recibir son: (ejemplos)     *
    *  {tipo} = cherubs                           *
    *  {posicion} = 0,0                           *
    *  {anio} = 2017                              *
    *  {mes} = 05 --> DOS DIGITOS                 *
    *  {dia} = 01 --> DOS DIGITOS                 *
    *  {hora} = 11 --> DOS DIGITOS FORMATO 24 HRS.*
    ***********************************************


    +++ <host> actual = tatallerarquitectura.com/fiware +++


    Rutas:

    ****************************************************************************************
    Obtener los valores más recientes de las variables:
      URL:        http://<host>/actual/{tipo}/{posicion}
      RESPUESTA:  {
                    "humedadRelativa": "VALOR",
                    "humedadSuelo": "VALOR",
                    "temperatura": "VALOR"
                  }
    ****************************************************************************************


    ****************************************************************************************
    Obtener los valores por hora (retorna cada registro realizado dentro de determinada hora)
      URL:        http://<host>/hora/{tipo}/{posicion}/{anio}/{mes}/{dia}/{hora}
      RESPUESTA:  {
                    "hora" : '12:00' 
                    "humedadRelativa" : "VALOR",
                    "humedadSuelo" : "VALOR",
                    "temperatura" : "VALOR"
                  }
    ****************************************************************************************


    ****************************************************************************************
    Obtener valores por dia (retorna los valores de un dia indicado, promediando por hora)
      URL:        http://<host>/dia/{tipo}/{posicion}/{anio}/{mes}/{dia}
      RESPUESTA:  {
                    "hora" : "00:00", 
                    "humedadRelativa" : "VALOR",
                    "humedadSuelo" : "VALOR",
                    "temperatura" : "VALOR"
                  }
    ****************************************************************************************



    ****************************************************************************************
    Obtener valores por mes (retorna los valores de todo el mes indicado, promediando por dia)
      URL:       http://<host>/mes/{tipo}/{posicion}/{anio}/{mes}
      RESPUESTA: {
                    "dia" : "1", 
                    "humedadRelativa" : "VALOR",
                    "humedadSuelo" : "VALOR",
                    "temperatura" : "VALOR"
                 }
    ****************************************************************************************


    ****************************************************************************************
    Obtener valores por semana (retorna los valores de una semana completa a partir de una fecha, promediando por dia)
      URL:       http://<host>/semana/{tipo}/{posicion}/{anio}/{mes}/{dia}
      RESPUESTA: {
                    "dia" : "12", 
                    "humedadRelativa" : "VALOR",
                    "humedadSuelo" : "VALOR",
                    "temperatura" : "VALOR"
                 }
    ****************************************************************************************

  