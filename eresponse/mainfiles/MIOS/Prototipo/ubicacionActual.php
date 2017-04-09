<?php

//ESTE PHP ES UTILIZADO PARA RECUPERAR LA UBICACIÓN ACTUAL DE LOS PUNTOS DE INTERÉS DE RECURSOS ESTÁTICOS Y EMERGENCIAS

$id_entrada=$_POST['id_entrada'];//ESTO PUEDE SER EL NOMBRE DE UN RECURO ESTÁTICO COMO EL ID DE UNA EMERGENCIA

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");


//SI EL ID DE ENTRADA ES NUMÉRICO SUCEDE LO SIGUIENTE
if(is_numeric($id_entrada)){
    //SE CONSULTAN LOS DATOS DE LATITUDE Y LONGITUDE CON RESPECTO AL ID DE ENTRADA
$consultaEmergencia=pg_query("SELECT latitude, longitude FROM emergency WHERE id_emergency='$id_entrada'");
if(pg_num_rows($consultaEmergencia)==1){
    while($reg=pg_fetch_assoc($consultaEmergencia))
    {
        $ubicacion[]=$reg;//SI EL ID DE ENTRADA EXISTE EN LA TABLA EMERGENCY, LOS DATOS SON ASIGNADOS A $UBICACION
    }
    echo json_encode($ubicacion);
}}
else{//SI EL ID DE ENTRADA ES UNA CADENA ALFANUMÉRICA, SUCEDE LO SIGUIENTE
    
//SE CONSULTAN LOS DATOS DE LATITUDE Y LONGITUDE CON RESPECTO AL ID DE ENTRADA    
$consultaRecursoEstatico=pg_query("SELECT latitude, longitude FROM static_resource WHERE static_resource_name='$id_entrada'");    
if(pg_num_rows($consultaRecursoEstatico)==1){
    while($reg=pg_fetch_assoc($consultaRecursoEstatico)){
        
        $ubicacion[]=$reg;//SI EL ID DE ENTRADA EXISTE EN LA TABLA STATI_RESOURCE, LOS DATOS SON ASIGNADOS A $UBICACION
    }
    echo json_encode($ubicacion);
}
}

pg_close($dbconn3);


?>