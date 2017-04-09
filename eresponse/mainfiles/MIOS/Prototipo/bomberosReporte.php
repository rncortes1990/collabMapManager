<?php
//ESTE PHP SE ENCARGA DE RECUPERAR LOS DATOS DE LOS BOMBEROS ASISTEN A UNA EMERGENCIA PREVIO A INGRESAR UN REPORTE


//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$conexion = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");

//SE UTILIZA EL ID_EMERGENCY DE LA EMERGENCIA REFERENCIADA
$id_emergency=$_POST['id_emergency'];


//SE RECUPERAN LOS DATOS DE LOS BOMBEROS PARTICIPANTES EN LA EMERGENCIA UTILIZADA
$listarDatos=pg_query("
SELECT DISTINCT	f.*
FROM firefighter f, emergency_dynamic_resource e  
WHERE f.firefighter_name=e.dynamic_resource_name and e.assisted_emergency='$id_emergency' order by firefighter_type DESC");
  
if(pg_num_rows($listarDatos)==0){//SI NO EXISTEN BOMBEROS RELACIONADOS A LA EMERGENCIA, ANSWER=0, RESPUESTA DE INEXISTENCIA
 $listado[]=array(
'answer'=>0
    
    );

echo json_encode($listado);
}else{   //SI EXISTEN BOMBEROS RELACIONADOS A LA EMERGENCIA, ANSWER=1, RESPUESTA DE QUE HAY EXISTENCIA 

while($registroLista=pg_fetch_assoc($listarDatos)){

    //$listado[]=$registroLista;
    $listado[]=array(
    'firefighter_name'=>$registroLista['firefighter_name'],
    'firefighter_type'=>$registroLista['firefighter_type'],
    'answer'=>1
    
    );
}
echo json_encode($listado);
}


pg_close($conexion);
?>