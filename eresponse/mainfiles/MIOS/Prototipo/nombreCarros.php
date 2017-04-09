<?php
//ESTE PHP SE ENCARGA DE RECUPERAR LOS NOMBRES DE LOS CARROS BOMBA QUE APARECEN EN UN REPORTE PREVIAMENTE ALMACENADO
$id_reporte=$_POST['id_reporte'];//ID_REPORTE A UTILIZAR

//SE REALIZA LA CONEXIÓN EN LA BASE DE DATOS
$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");


//SE REALIZA LA CONSULTA DE LOS CARROS BOMBA EN LA BASE DE DATOS
$queryCarros=pg_query("   SELECT fire_truck_name
                            FROM reporte_carros 
                            WHERE id_reporte='$id_reporte'");

while($reg=pg_fetch_assoc($queryCarros)){
    $listado[]=$reg;//SE ASISGNAN LOS DATOS RECUPERADOS A $LISTADO

}
echo json_encode($listado);

pg_close($conexion);

?>