<?php
//ESTE PHP ES UTILIZADO PARA MODIFICAR LA UBICACIÓN DE LOS PUNTOS DE INTERÉS DE RECURSOS ESTÁTICOS Y EMERGENCIAS, AL MISMO TIEMPO MODIFICA LA LATITUD Y LONGITUD DE LOS MISMOS. POR LO QUE ES UNA MODIFICACIÓN A NIVEL VISUAL Y DE DATOS.

$id_entrada=$_POST['id_entrada'];//DATO DE ENTRADA QUE PUEDE SER RECURSO ESTÁTICO O DINÁMICO
$latitud=$_POST['latitud'];
$longitud=$_POST['longitud'];//SON LOS NUEVOS VALORES DE LATITUD Y LONGITUD

//SE REALIZA LA CONEXIÓN EN LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");

if(is_numeric($id_entrada)){//SI EL IDENTIFICADOR ES UN NUMERO, ENTONCES SE TRATA DE UNA EMERGENCIA
    //SE VERIFICA QUE LA EMERGENCIA EXISTA
$consultaEmergencia=pg_query("SELECT id_emergency FROM emergency WHERE id_emergency='$id_entrada'");

if(pg_num_rows($consultaEmergencia)==1){//SI LA EMERGENCIA EXISTE, SE ACTUALIZA LATITUDE Y LONGITUDE ASOCIADOS AL ID DE ENTRADA
   pg_query("  UPDATE emergency
                SET latitude='$latitud', longitude='$longitud'
                WHERE id_emergency='$id_entrada'
                ");
    echo json_encode("terminado");
}
}else{//SI EL IDENTIFICADOR ES UNA CADENA ALFANUMÉRICA, ENTONCES SE TRATA DE UN RECURSO ESTÁTICO
    
    //SE VERIFICA QUE EL ID DE ENTRADA EXISTA EN LA TABLA STATIC_RESOURCE
    $consultaRecursoEstatico=pg_query("SELECT static_resource_name FROM static_resource WHERE static_resource_name='$id_entrada'");

if(pg_num_rows($consultaRecursoEstatico)==1){//SI EL RECURSO EXISTE, ENTONCES SE ACTUALIZA LATITUDE Y LOGITUDE ASOCIADOS AL ID DE ENTRADA
   pg_query("  UPDATE static_resource
                SET latitude='$latitud', longitude='$longitud'
                WHERE static_resource_name='$id_entrada'
                ");
    echo json_encode("terminado");
}
     }
pg_close($dbconn3);
?>