<?php
//ESTE PHP SE ENCARGA DE MOSTRAR LOS BOMBEROS PRESENTES EN UN REPORTE PREVIAMENTE ALMACENADO

$id_reporte=$_POST['id_reporte'];//SE UTILZA UN ID_REPORTE PARA ACCEDER A DICHOS DATOS

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");


//SE REALIZA LA CONSULTA DE LOS BOMBEROS QUE APARECEN EN EL REPORTE
$queryBomberos=pg_query("   SELECT r.firefighter_name, f.firefighter_type 
                            FROM reporte_bomberos r, firefighter f 
                            WHERE r.firefighter_name=f.firefighter_name AND id_reporte='$id_reporte'");

while($reg=pg_fetch_assoc($queryBomberos)){
    $listado[]=$reg;//SE ASIGNAN LOS DATOS RECUPERADOS A $LISTADO

}
echo json_encode($listado);
//esto es para el reporte seleccionado
pg_close($conexion);


?>