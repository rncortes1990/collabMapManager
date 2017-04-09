<?php
//ESTE PHP SE ENCARGA DE INSERTAR UN NUEVO REPORTE O ACTUALIZAR UNO YA ALMACENADO

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$conexion = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
$id_emergency=$_POST['id_emergency'];
$address=$_POST['address'];
$priority=$_POST['priority'];
$comandante=$_POST['comandante'];
$bomberos=$_POST['bomberos'];
$carros=$_POST['carros'];
$reporte=$_POST['reporte'];
date_default_timezone_set('Chile/Continental');
$fecha=date('Y-m-d H:i:s');//DATOS UTILIZADOS PARA EL REPORTE A GENERAR
//$string1="B-8/magg";
$string2=explode("/",$bomberos);//EXPLODE SEPARA LAS PALABRAS DE UN STRING QUE TIENEN UN TIPO DE CARACTER ESPECIAL ENTRE LETRAS, SIMILAR A UN TOKENIZER
$string3=explode("/",$carros);
$iteration=count($string2);//SE CUENTA LA CANTIDAD DE PALABRAS QUE RESULTAN DEL EXPLODE, ESTO ES NECESARIO PARA SABER CUANTAS ITERACIONES SE REALIZARÁN AL AMACENAR LOS RECURSOS DINÁMICOS ASOCIADOS AL REPORTE
$iteration2=count($string3);

//SE CONSULTA POR EL REPORTE ASOCIADO AL ID_EMERGENCY UTILIZADO EN EL PHP
$consulta_reporte=pg_query("SELECT id_reporte FROM reportes WHERE id_emergency='$id_emergency'");


//SI EL REPORTE EXISTE
if(pg_num_rows($consulta_reporte)<1){
        //SE INSERTA EL NUEVO REPORTE
        $insertarReporte= pg_query("INSERT INTO reportes (id_emergency,reporte,fecha_reporte) VALUES('$id_emergency','$reporte','$fecha')");
        //SE CAPTURA EL ID DEL REPORTE PARA ALMACENAR LOS CARROS BOMBA Y BOMBEROS ASIGNADOS A DICHO ID_REPORTE (SON TABLAS DIFERENTES A LA DE REPORTES, REPORTE_CARROS Y REPORTE_BOMEROS RESPECTIVAMENTE)
        $numero_reporte=pg_fetch_assoc(pg_query("select max(id_reporte)::text as maximo from reportes"));

        

        for($i=0;$i<$iteration-1;$i++){//SE ITERA PARA INSERTAR EN REPORTE_BOMBERO LA CANTIDAD EXACTA DE BOMBEROS QUE APARECERÁN EN EL REPORTE Y QUE ACUDIERON A LA EMERGENCIA

            $insertar_reporte_bomberos=pg_query("
            INSERT INTO 
            reporte_bomberos (id_reporte,firefighter_name) 
            VALUES('".$numero_reporte['maximo']."','$string2[$i]')");    

        }
        for($x=0;$x<$iteration2-1;$x++){//SE ITERA PARA INSERTAR EN REPORTE_CARROS LA CANTIDAD EXACTA DE CARROS BOMBA QUE APARECERÁN EN EL REPORTE Y  QUE ACUDIERON A LA EMERGENCIA

            $insertar_reporte_carros=pg_query("
            INSERT INTO reporte_carros (id_reporte,fire_truck_name) 
            VALUES('".$numero_reporte['maximo']."','$string3[$x]')");    

        }

        $respuesta="LISTO";//RESPUESTA UTILIZADA PARA LA INSERCIÓN DE UN NUEVO REPORTE

        
}else{//SI EL REPORTE YA EXISTÍA
    
    $chequeo=pg_query("SELECT length(reporte) as cantidad FROM reportes WHERE id_emergency='$id_emergency'");
    $cantidad_reporte_fetch=(pg_fetch_assoc($chequeo));
    $cantidad_reporte=(int)$cantidad_reporte_fetch['cantidad'];
    $cantidad_ingreso=strlen($reporte);
    
   if(($cantidad_ingreso+$cantidad_reporte)<=198){ //SE ACTUALIZA EL CONTENIDO DEL REPORTE
            pg_query("
            UPDATE reportes 
            SET reporte=CONCAT(reporte,'//','$reporte'), fecha_reporte='$fecha' 
            WHERE id_emergency='$id_emergency'");
            //LUEGO SE RECUPERA EL ID DEL REPORTE ACTUALIZADO
            $consulta_id_reporte=pg_fetch_assoc($consulta_reporte);
            $id_encontrado=$consulta_id_reporte['id_reporte'];

        
            for($i=0;$i<$iteration-1;$i++){
                //LUEGO SE ITERA PARA VERIFICAR SI EL REPORTE ACTUALIZADO TIENE NUEVOS BOMBEROS ASIGNADOS
                $existencia_bombero=pg_query("SELECT firefighter_name 
                                                FROM reporte_bomberos
                                                WHERE firefighter_name='$string2[$i]' AND id_reporte='$id_encontrado'");


                if(pg_num_rows($existencia_bombero)==0){//SI EL BOMBERO NO EXISTE SE AÑADE A LA TABLA REPORTE_BOMBEROS
                    pg_query("
                    INSERT INTO 
                    reporte_bomberos (id_reporte,firefighter_name) 
                    VALUES('$id_encontrado','$string2[$i]')");

                }

                }

            for($x=0;$x<$iteration2-1;$x++){//IDEM PARA LOS CARRO BOMBA
                $eCarros=pg_query("SELECT fire_truck_name 
                                    FROM reporte_carros
                                    WHERE fire_truck_name='$string3[$x]' AND id_reporte='$id_encontrado'");

                if(pg_num_rows($eCarros)==0){//SI ALGUNO DE LOS CARROBOMBA NO EXISTE, SE AÑADE ALA TABLA REPORTE_CARROS
                    pg_query("
                    INSERT INTO 
                    reporte_carros (id_reporte,fire_truck_name) 
                    VALUES('$id_encontrado','$string3[$x]')");
                }


                }

            $respuesta="NO";//RESPUESTA UTILIZADA PARA LA ACTUALIZACIÓN DE REPORTES
    }else{
       
    if($cantidad_reporte==200)
    {
        $respuesta= "LLENO";
    }else
    {   $faltantes=(198-($cantidad_ingreso+$cantidad_reporte));
        $respuesta=array('FALTA',-$faltantes);
    }     
   }
}

echo json_encode($respuesta);

pg_close($conexion);

?>