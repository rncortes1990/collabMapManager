<?php

//ESTE PHP CONSULTA LOS DATOS DE LOS RECURSOS ESTÁTICOS

//SE REALIZA CONEXION A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");



/*SE  REALIZA CONSULTA DE DATOS*/
$query1=pg_query("select s.static_resource_name, s.address, s.latitude, s.longitude,sb.tipo_pto_interes, i.imagen  from static_resource s , img_pto_interes i, static_resource_backend  sb where s.static_resource_name=sb.static_resource_name AND sb.tipo_pto_interes=i.tipo_pto_interes");



while($reg=pg_fetch_assoc($query1)){

    $regg[]=$reg;//SE REALIZA EL FETCH DE DATOS

}
    
echo json_encode($regg);//SE ENVÍAN LOS DATOS RECUPERADOS A MAPA.JS
   



pg_close($dbconn3);


  
?>
