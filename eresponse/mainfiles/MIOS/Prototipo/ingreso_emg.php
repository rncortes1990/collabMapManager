

<?php
//PHP UTILIZADO PARA INGRESAR NUEVAS EMERGENCIAS
$direccion = $_POST['direccion'];
//$prioridad = $_POST['prioridad'];
$tipo_pto_interes=$_POST['tipo_pto_interes'];
$latitud=$_POST['latitud'];
$longitud=$_POST['longitud'];//DATOS QUE UTILIZA LA FUNCIÓN DE INGRESO DE LAS EMERGENCIAS
 
//SE REALIZA CONEXIÓN A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
$consulta_direccion=pg_query("  SELECT id_emergency
                                FROM emergency
                                WHERE address='$direccion'
                                AND end_date IS NULL");//SE CONSULTA SI EXISTE ALGUNA EMERGENCIA CON END DATE NO FINALIZADA QUE CONTENGA LA MISMA DIRECCIÓN INGRESADA




if(pg_num_rows($consulta_direccion)==1){//SE VERIFICA QUE LA DIRECCIÓN INGRESADA ESTÉ ASIGNADA A UNA EMERGENCIA ACTIVA (NOFINALIZADA) EN EL SISTEMA, SI ESTO SUCEDE ANSWER=1, LO QUE IMPLICA QUE NO SE PODRÁ ALMACENAR UNA EMERGENCIA CON UNA DIRECCIÓN IDÉNTICA A UNA EMERGENCIA ACTIVA
    
    $answer[]= array(
    'answer'=>1
    );
    echo json_encode($answer);
}

else{// ALTER SEQUENCE emergency_id_emergency_seq  RESTART WITH 9, resetea las id de las emergencias

//SE INGRESA NUEVA EMERGENCIA A LA BASE DE DATOS
$insertarEMGDB=pg_query("SELECT insert_emergency('$direccion','$latitud','$longitud')");

$IDemg=pg_query("SELECT MAX(id_emergency) as maximo FROM emergency");//SE CAPTURA EL MAXIMO ID EXISTENTE EN LA TABLA EMERGENCY

//SE INSERTA LA NUEVA EMERGENCIA EN LA TABLA EMERGENCY_BACKEND
$insertarRecurso=pg_query("INSERT INTO emergency_backend (id_emergency,tipo_pto_interes) VALUES((SELECT MAX(id_emergency) FROM emergency),'$tipo_pto_interes')");

$reg=pg_fetch_assoc($IDemg);//SE REALIZA UN FECH DE LA ÚNICA FILA QUE RESULTA DE LA CONSULTA DEL MAXIMO ID_EMERGENCY

//SE CAPTURA LA RUTA DE LA IMAGEN DE LA NUEVA EMERGENCIA INGRESADA
$consultaImagen=pg_query("SELECT i.imagen, i.priority from img_prioridades i, emergency e  where i.priority=e.priority AND e.id_emergency=(SELECT MAX(id_emergency) FROM emergency)");


  while($registro=pg_fetch_array($consultaImagen)){//SE ASIGNAN LOS VALORES OBTENIDOS E INGRESADOS AL ARREGLO ASOCIATIVO $ANSWER
    
    
    
    $answer[]=array(
        'direccion'=>$direccion,
        'latitud'=>$latitud,
        'longitud'=>$longitud,
        'priority'=>$registro['priority'],
        'imagen'=>$registro['imagen'],
        'id_emergency'=>$reg['maximo'],
        'answer'=>0
                );
    echo json_encode($answer);
    
  }

}

pg_close($dbconn3);

?>

