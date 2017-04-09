<?php
//ESTE PHP ES UTILIZADO PARA LA ELIMINACIÓN DE UN RECURSO ESTÁTICO
$id_recurso=$_POST['static_resource_name'];//STATIC_RESOURCE_NAME A UTILIZAR

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
//SE CONSULTA LA SI EXISTEN BOMBEROS EN EL RECURSO ESTÁTICO SI ES QUE EL RECURSO A ELIMINAR ES UNA ESTACIÓN DE BOMBEROS
$consultaEstacionBomberos=pg_query("SELECT fire_station_name FROM firefighter WHERE fire_station_name='$id_recurso'");
//SE CONSULTA LA SI EXISTEN CARROS BOMBA EN EL RECURSO ESTÁTICO SI ES QUE EL RECURSO A ELIMINAR ES UNA ESTACIÓN DE BOMBEROS
$consultaEstacionCarros=pg_query("SELECT fire_truck_name FROM fire_truck WHERE fire_station_name='$id_recurso'");
if(pg_num_rows($consultaEstacionBomberos)==1|| pg_num_rows($consultaEstacionCarros)==1){//SI ES QUE EXISTEN CARROSBOMBA O BOMBEROS EN LA ESTACIÓN DE BOMBEROS A ELIMINAR, SE TIENE LA RESPUESTA NO, LO QUE NO PERMITIRÁ EFECTUAR LA ELIMINACIÓN
    
  
echo json_encode("NO");   
}else{//SI LA ESTACIÓN NO TIENE BOMBEROS O CARROS BOMBA ASIGNADOS, SE REALIZA LA ELIMINACIÓN
pg_query("DELETE FROM static_resource WHERE static_resource_name='$id_recurso'");
echo json_encode("terminado");
}
pg_close($dbconn3);
?>