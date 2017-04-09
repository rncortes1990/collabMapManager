<?php

   $nombre_recurso = $_POST['nombre_recurso'];
    $direccion_recurso = $_POST['direccion_recurso'];
    $descripcion_recurso=$_POST['descripcion_recurso'];
    $tipo_pto_interes=$_POST['tipo_pto_interes'];
    $latitud=$_POST['latitud'];
    $longitud=$_POST['longitud'];//DATOS PARA INGRESO DE RECURSO ESTÁTICO

$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");//CONEXIÓN A BASE DE DATOS

$consultaIdRecurso=pg_query("SELECT static_resource_name FROM static_resource WHERE static_resource_name='".$nombre_recurso."'");//CONSULTA DE IDENTIFICADOR
$consultaImagen=pg_query("SELECT i.imagen FROM img_pto_interes i WHERE i.tipo_pto_interes='$tipo_pto_interes'");//VERIFICAR IMAGEN PARA EL RECURSO A INGRESAR
$consulta_Tipo_punto=pg_query("SELECT t.tipo_pto FROM tipos_ptos_interes t WHERE t.tipo_pto_interes='$tipo_pto_interes'");//CONSULTAR EL TIPO DE PUNTO PARA UTILIZAR LA FUNCIÓN INSERT_STATIC_RESOURCE



while($registro_Tipo_punto=pg_fetch_array($consulta_Tipo_punto)){//SE CAPTURA EL VALOR DE TIPO PUNTO
$tipo_punto=$registro_Tipo_punto['tipo_pto'];
}

if(pg_num_rows($consultaIdRecurso)==1 )//SI EL RECURSO YA EXISTE ANSWER=1
{   
     $answer[]=array(
        'answer'=>1
                );
    echo json_encode($answer);

}else
    { 
    
    $consulta_direccion=pg_query("SELECT address FROM static_resource WHERE address='$direccion_recurso'");//SE CONSULTA QUE LA DIRECCIÓN SEA ÚNICA
   if(pg_num_rows($consulta_direccion)==1){//SI LA DIRECCIÓN EXISTE ANSWER=2
       
          $answer[]=array(
            'answer'=>2
                    );
        echo json_encode($answer); 
   }
    else{//SI LA DIRECCIÓN NO EXISTE SE REALIZA LA INSERCIÓN DEL NUEVO RECURSO ESTÁTICO
        $insertarRecursoDB=pg_query("SELECT insert_static_resource('$nombre_recurso','$direccion_recurso','$descripcion_recurso','$latitud','$longitud','$tipo_punto')");//SE INSERTA EL RECURSO EN LA TABLA STATIC_RESOURCE
    
    
if($tipo_pto_interes==1)//SI EL TIPO DE PUNTO DE INTERES ES GRIFO SE RELIZA LO SIGUIENTE
    {   
        $numero2=pg_fetch_assoc(pg_query("select currval('fire_hydrants_seq')::text as maxx"));//SE CAPTURA EL VALOR ACTUAL DE FIREHYDRANTS 
        $tipo_punto=$tipo_punto.' '.$numero2['maxx']; //SE CONCATENA EL NOMBRE Grifo MAS EL VALOR ACTUAL
        $insertarRecurso_backend1=pg_query("INSERT INTO static_resource_backend VALUES('$tipo_punto',
        '$tipo_pto_interes')"); //SE INSERTA DICHO NOMBRE MAS EL VALOR ACTUAL Y EL TIPO DE PUNTO DE INTERÉS EN LA TABLA STATIC_RESOURCE_BACKEND


        while($registro=pg_fetch_assoc($consultaImagen))
                {//SE ALMACENA EL NOMBRE DE LA IMAGEN EN IMAGEN_RECURSO

                $answer[]=array(
                    'nombre_recurso'=>$tipo_punto,
                    'latitud'=>$latitud,
                    'longitud'=>$longitud,
                    'imagen_recurso'=>$registro['imagen'],
                    'answer'=>0
                            );

                echo json_encode($answer);
            }
    }
    else
    {
        
        $insertarRecurso_backend2=pg_query("INSERT INTO static_resource_backend VALUES('$nombre_recurso',
        '$tipo_pto_interes')");//SE REALIZA EL INGRESO EN STATIC_RESOURCE_BACKEND




        while($registro=pg_fetch_assoc($consultaImagen))
        {//SE ALMACENA EL NOMBRE DE LA IMAGEN EN IMAGEN_RECURSO

            $answer[]=array(
                'nombre_recurso'=>$nombre_recurso,
                'latitud'=>$latitud,
                'longitud'=>$longitud,
                'imagen_recurso'=>$registro['imagen'],
                'answer'=>0
                        );

            echo json_encode($answer);
        }    
    }
  }
}

pg_close($dbconn3);

?>