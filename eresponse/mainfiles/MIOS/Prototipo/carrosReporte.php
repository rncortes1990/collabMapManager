<?php
//ESTE PHP SE ENCARGA DE RECUPERAR LOS DATOS DE LOS CARROS BOMBA ASISTEN A UNA EMERGENCIA PREVIO A INGRESAR UN REPORTE

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
//SE RECIBE UN ID DE EMERGENCIA AL CUAL ESTÁN RELACIONADOS LOS RECURSOS DINÁMICOS
$id_emergency=$_POST['id_emergency'];

//SE REALIZA LA CONSULTA SOBRE LA BASE DE DATOS
$query_carros=pg_query("SELECT DISTINCT	f.*
FROM fire_truck f, emergency_dynamic_resource e  
WHERE f.fire_truck_name=e.dynamic_resource_name and e.assisted_emergency='$id_emergency'");

if(pg_num_rows($query_carros)==0){//SI NO EXISTEN CARROS BOMBA ASISTENTES A LA EMERGENCIA REFERENCIADA ANSWER=0, ES UNA RESPUESTA DE INEXISTENCIA
$carros_encontrados[]= array(
    'answer'=>0
    
    );
echo json_encode($carros_encontrados);    

}else{ //SI ES QUE EXISTEN CARROSBOMBA ASOCIADOS A LA EMERGENCIA, SUCEDERÁ LO SIGUIENTE   
    
while($registro=pg_fetch_assoc($query_carros)){

    
    //AL VECTOR ASOCIATIVO CARROS_ENCONTRADOS SE LE ASIGNAN LOS NOMBRES DE LOS CARROS BOMBAS RELACIONADOS AL ID DE LA EMERGENCIA Y ANSWER=1, RESPUESTA DE QUE HAY EXISTENCIA  
    
    $carros_encontrados[]= array(
    'fire_truck_name'=>$registro['fire_truck_name'],
    'answer'=>1
    
    );
}

echo json_encode($carros_encontrados);
}

pg_close($conexion);



?>