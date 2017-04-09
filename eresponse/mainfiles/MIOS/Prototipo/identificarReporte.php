<?php
//ESTE PHP ES UTILIZADO PARA IDENTIFICAR A UN REPORTE  DEL LISTADO DE REVISAR REPORTES, Y RECUPERAR SUS DATOS

$id_reporte=$_POST['id_reporte'];//DATO DE REPORTE A UTILIZAR

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
//SE CONSULTAN LOS DATOS DEL REPORTE ALMACENADO
$consultaReportes=pg_query("SELECT r.id_emergency, r.fecha_reporte,r.id_reporte,r.reporte, e.priority, e.address FROM reportes r, emergency e WHERE  e.id_emergency= r.id_emergency AND r.id_reporte='$id_reporte'");

while($reg=pg_fetch_assoc($consultaReportes)){
    $listado[]=$reg;//LUEGO SE ASIGNAN LOS DATOS RECUPERADOS A $LISTADO PARA LUEGO SER UTILIZADOS

}
echo json_encode($listado);
//esto es para el reporte seleccionado
pg_close($conexion);
?>