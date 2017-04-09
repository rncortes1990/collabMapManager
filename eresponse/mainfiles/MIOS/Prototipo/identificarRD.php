<?php
//ESTE PHP ES UTILIZADO PARA IDENTIFICAR LOS DATOS DEL RECURSO DINÁMICO A MODIFICAR, DADO QUE NO ES REQUISITO DEL SISTEMA WEB, SE DEJA ABIERTO PARA POSIBLE FUTURA FUNCIONALIDAD
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
$ID_RD=$_POST['dynamic_resource_name'];



$query1=pg_query("SELECT * FROM dynamic_resource WHERE dynamic_resource_name='".$ID_RD."'" );
//$consultaID=pg_query("select id_emergency from emergency where id_emergency='".$asda."'");

while($registro=pg_fetch_assoc($query1)){

    $registro_recurso_dinamico[]=$registro;

}
    
echo json_encode($registro_recurso_dinamico);
pg_close($dbconn3);


?>