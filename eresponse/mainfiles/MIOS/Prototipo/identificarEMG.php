<?php
//ESTE PHP ES UTILIZAD OPARA IDENTIFICAR LOS DATOS PERTENECIENTES A UNA EMERGENCIA A TRAVÉS DE SU ID_EMERGENCY

//SE REALIZA CONEXIÓN A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");

$ID_EMG=$_POST['id_emergency'];//DATOS A UTILIZAR EN LA FUNCIONALIDAD



$query1=pg_query("SELECT * FROM emergency WHERE id_emergency='".$ID_EMG."' ORDER BY priority" );//SE RECUPERAN LOS DATOS ASOCIADOS A LA EMERGENCIA


while($registro=pg_fetch_assoc($query1)){

    $registro_emergencia[]=$registro;//SE ASIGNAN LAS FILAS RECUPERADAS DE LA CONSULTA A $REGISTRO_EMERGENCIA

}
    
echo json_encode($registro_emergencia);
pg_close($dbconn3);


?>