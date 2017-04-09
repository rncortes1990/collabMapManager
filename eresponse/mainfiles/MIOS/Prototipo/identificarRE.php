<?php
//EN ESTE PHP SE CAPTURAN LOS DATOS DE UN RECURSO ESTÁTICO
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
$ID_RE=$_POST['static_resource_name'];

$query1=pg_query("SELECT * FROM static_resource WHERE static_resource_name='$ID_RE'");//A TRAVÉS DEL NOMBRE DEL RECURSO SE RECUPERAN SUS DATOS
$query_Tipo_punto=pg_query("SELECT t.tipo_pto_interes FROM tipos_ptos_interes t, static_resource_backend sb  WHERE t.tipo_pto_interes=sb.tipo_pto_interes AND sb.static_resource_name='$ID_RE'");//SE CAPTURA EL NOMBRE DEL TIPO DE PUNTO DE INTERÉS(POR EJEMPLO, 1=GRIFO) DONDE EL NOMBRE DEBE SER EL MISMO EN LAS TBALAS DE STATIC_RESOURCE COMO DE STATIC_RESOURCE_BACKEND

$Reg_tipo_punto_interes=pg_fetch_assoc($query_Tipo_punto);//SE REALIZA EL FETCH DE LA UNICA FILA QUE RESULTA DE LA CONSULTA DE LA BASE DE DATOS
$tipo_pto_interes=$Reg_tipo_punto_interes['tipo_pto_interes'];//SE ASIGNA EL NÚMERO DEL TIPO DE PUNTO DE INTERES A $TIPO_PTO_INTERES

$query_imagen=pg_query("SELECT i.imagen FROM img_pto_interes i WHERE i.tipo_pto_interes='$tipo_pto_interes'");//SE BUSCA LA RUTA DE LA IMAGEN ASOCIADA AL TIPO DE PUNTO DE INTERÉS ENCONTRADO ANTERIORMENTE
$Reg_imagen=pg_fetch_assoc($query_imagen);//SE REALIZA EL FETCH DE LA ÚNICA FILA RECUPERADA DE LA CONSULTA Y SE ASIGNA A $IMAGEN
$imagen=$Reg_imagen['imagen'];

while($registro=pg_fetch_assoc($query1)){//LUEGO SE REALIZA UN FECH SOBRE LA PRIMERA CONSULTA A LA BASE DE DATOS DONDE SE UTILIZA EL NOMBRE DEL RECURSO Y SE ASIGNAN LOS RESULTADOS AL ARREGLO ASOCIATIVO $REGISTRO_RECURSO
$registro_recurso[]= array(
'static_resource_name'=>$registro['static_resource_name'],
'address'=>$registro['address'],
'description'=>$registro['description'],
'latitude'=>$registro['latitude'],
'longitude'=>$registro['longitude'],
'tipo_pto_interes'=>$tipo_pto_interes, 


);

}
    
echo json_encode($registro_recurso);
pg_close($dbconn3);


?>

