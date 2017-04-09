<?php
//ESTE PHP ES UTILIZADO PARA INGRESAR UN NUEVO RECURSO DINÁMICO EN EL SISTEMA
   $nombre_recurso = $_POST['nombre_recurso_din'];
    $id_dispositivo = $_POST['id_dispositivo'];
    $tipo_pto_interes=$_POST['tipo_pto_interes'];
    $rut=$_POST['rut'];
    $cuartel=$_POST['cuartel'];//DATOS DE ENTRADA DEL PHP
    
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");//CONEXIÓN A LABASE DE DATOS

$consultaIdRecurso=pg_query("SELECT dynamic_resource_name from dynamic_resource where dynamic_resource_name='".$nombre_recurso."'");//SE CONSULTA LA EXISTENCIA DEL NOMBRE DEL RECURSO DINÁMICO
$consultaIdDisp=pg_query("SELECT dynamic_resource_name from dynamic_resource where id_device='".$id_dispositivo."'");
//SE CONSULTA LA EXISTENCIA DEL IMEI O SERIAL A INGRESAR
$Tipo_pto=pg_fetch_assoc(pg_query("SELECT t.tipo_pto FROM tipos_ptos_interes t  WHERE t.tipo_pto_interes='$tipo_pto_interes'"));
//SE CAPTURA EL NOMBRE DEL TIPO DE PUNTO DE INTERES
$Texto=$Tipo_pto['tipo_pto'];//SE ASIGNA EL VALOR CAPTURADO A $TEXTO





if(pg_num_rows($consultaIdRecurso)==1)//NO PUEDE REPETIRSE EL NOMBRE DEL RECURSO DINÁMICO
{   
     $answer[]=array(
        'answer'=>1//SI SE REPITE ANSWER=1
                );
    echo json_encode($answer);


}
else
    { 
    
    if(pg_num_rows($consultaIdDisp)==1)//NO PUEDE REPETIRSE EL ID DEL DISPOSITIVO
    {   
     $answer[]=array(
        'answer'=>2//SI SE REPITE ANSWER=2
                );
    echo json_encode($answer);


    }else{//SI EL NOMBRE Y EL IMEI(O SERIAL) NO EXISTEN SE REALIZA LO SIGUIENTE
    
        $tipo_recurso_rd="";
    
        if($tipo_pto_interes==5){ // SI EL TIPO DE PUNTO DE INTERÉS ES 5(CARRO BOMBA) SE REALIZA LO SIGUIENTE
            $rut=2123;//AL INGRESAR UN RECURSO DE TIPO VEHICULO EL VALOR DEL RUT NO SERÁ RELEVANTE PERO DEBE SER DISTINTO DE NULL O VACÍO
            $tipo_recurso_rd="vehiculo";//SE UTILIZA EL TIPO DE RECURSO RD

            //SE REALIZA EL INGRESO DEL NUEVO RECURSO DINÁMICO EN LA TABLA DYNAMIC_RESOURCE Y FIRE_TRUCK
            $insertarRecurso=pg_query("SELECT insert_dynamic_resource('$nombre_recurso','$id_dispositivo','$rut','$cuartel','$Texto')");
            //SE REALIZA EL INGRESO DEL NUEVO RECURSO DINÁMICO EN DYNAMIC_RESOURCE_BACKEND
            pg_query("INSERT INTO dynamic_resource_backend 
            VALUES((SELECT dynamic_resource_name FROM dynamic_resource 
            WHERE dynamic_resource_name='$nombre_recurso'),
            (SELECT tipo_pto_interes FROM tipos_ptos_interes WHERE tipo_pto_interes='$tipo_pto_interes'),'$tipo_recurso_rd') ");

            $answer[]=array(
                    'answer'=>0//SE ENVÍA A MAPA.JS EL VALOR DE LA RESPUESTA
                            );
                echo json_encode($answer);

        }//FIN IF PARA RECURSO DE TIPO VEHÍCULO
        else{//SI EL TIPO DE PUNTO DE INTERÉS ES DIFERENTE DE 5
        if(empty($rut)){ //SI EL INPUT DEL RUT ES VACÍO SUCEDE LO SIGUIENTE
                    $answer[]=array(
                            'answer'=>5 //SI RUT ES VACÍO ANSWER=5
                                    );
                        echo json_encode($answer);
               }else{//EN ESTE ELSE SE REALIZAN LAS SIGUIENTES ACCIONES, SIEMPRE QUE EL RUT NO SEA VACÍO
            
                if(ctype_digit($rut)==false){//SE VERIFICA QUE EL RUT SOLO CONTENGA NÚMEROS
                    $answer[]=array(
                            'answer'=>5//SE ENTREGA LA MISMA RESPUESTA QUE CUANDO EL RUT ES VACÍO
                                    );
                        echo json_encode($answer);
                }else{//SI EL RUT NO ES VACIO, SOLO TIENE NUMEROS Y QUE EL NOMBRE E IMEI NO EXISTAN EN EL SISTEMA SE REALIZA LO SIGUIENTE
                    
                    //SE CONSULTA LA EXISTENCIA DEL RUT INGRESADO
                    $consultaIdRUN=pg_query("SELECT firefighter_name from firefighter where firefighter_rut='$rut'");
                    
                    
                    if(pg_num_rows($consultaIdRUN)==1){//SI EXISTE EL RUT INGRESADO, ANSWER=1
                         $answer[]=array(
                            'answer'=>4
                                    );
                        echo json_encode($answer);

                    }else{//SI EL RUT NO EXISTE EN EL SISTEMA, SE REALIZA LO SIGUIENTE
              
               
                
                    $tipo_recurso_rd="persona";
                    //SE ESTABLECE QUE EL TIPO DE RECURSO DINÁMICO ES DE TIPO PERSONA
                    //YA QUE EL TIPO DE PUNTO DE INTERES ES DIFERENTE DE 5

                    //SE INGRESA EL NUEVO RECURSO DINÁMICO EN LAS TABLAS DYNAMIC_RESOURCE Y FIREFIGHTER
                    pg_query("SELECT insert_dynamic_resource('$nombre_recurso','$id_dispositivo','$rut','$cuartel','$Texto')");
                    //SE INGRESA EL NUEVO RECURSO DINÁMICO EN LA TABLA DYNAMIC_RESOURCE_BACKEND    
                    pg_query("INSERT INTO dynamic_resource_backend 
                    VALUES((SELECT dynamic_resource_name FROM dynamic_resource WHERE dynamic_resource_name='$nombre_recurso'),
                    (SELECT tipo_pto_interes FROM tipos_ptos_interes WHERE tipo_pto_interes='$tipo_pto_interes'),'$tipo_recurso_rd') ");

                        $answer[]=array(
                                'answer'=>0//SI SE CUMPLEN LAS CONDICIONES ANTERIORES ANSWER=0
                                        );
                            echo json_encode($answer);
                    }//FIN ELSE DE EXISTENCIA DE RUT DE BOMBERO
                }
            }
    }//FIN ELSE PARA RECURSO TIPO PERSONA

}//FIN ELSE ID DISPOSITIVO
    

   
}//FIN ELSE EXISTENCIA DE RECURSO
pg_close($dbconn3);

?>