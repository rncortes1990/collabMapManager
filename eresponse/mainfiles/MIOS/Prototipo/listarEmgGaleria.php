<?php
/*ESTE PHP CONSULTA TODOS LOS DATOS DE LAS EMERGENCIAS*/

/*SE REALIZA CONEXIÓN A LA BASE DE DATOS*/
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");


/*SE REALIZA LA CONSULTA A LA BASE DE DATOS*/

$query1=pg_query("select e.id_emergency,e.address ,e.latitude, e.longitude, e.priority, i.imagen  from emergency e, img_prioridades i where e.priority=i.priority AND id_emergency!= 0 ORDER BY e.id_emergency ASC");


while($reg=pg_fetch_assoc($query1)){//SE REALIZA UN FETCH DE LA CONSUTLTA

    $regg[]=$reg;//SE ALMACENA LO CAPTURADO DEL FETCHEN REGG

}
    
echo json_encode($regg);//SE ENVÍA LO OBTENIDO A MAPA.JS
   



pg_close($dbconn3);


  
?>
