<?php

//ESTE PHP SE ENCARGA DE RECUPERAR LOS DATOS DE LOS REPORTES PARA SER LISTADOS
$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");

//SE REALIZA LA CONSULTA A LA BASE DE DATOS
$consultaReportes=pg_query("SELECT distinct r.*, e.address FROM reportes r, emergency e, emergency_dynamic_resource d WHERE e.id_emergency=d.assisted_emergency and  d.assisted_emergency=r.id_emergency ORDER BY id_reporte ASC");

while($reg=pg_fetch_assoc($consultaReportes)){
    $listado[]=$reg;//SE ASIGNA A $LISTADO LO CAPTURADO EN EL FECTH

}
echo json_encode($listado);

pg_close($conexion);
?>