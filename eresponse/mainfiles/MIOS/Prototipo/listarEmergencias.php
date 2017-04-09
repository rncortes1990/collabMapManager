<?php
//ESTE PHP ES UTILIZADO PARA LISTAR LAS EMERGENCIAS EN EL MENÚ LATERAL IZQUIERDO AL PRESIONAR SHIFT+E
$conexion= pg_connect("host=localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");

//SE CONSULTAN TODOS LOS DATOS DE TODAS LAS EMERGENCIAS DE FORMA ASCENDENTE (ID_EMERGENCY) Y SU IMAGEN ASOCIADA CON RESPECTO A SU PRIORIDAD
$consultaEmergencias=pg_query("SELECT e.*, i.imagen FROM emergency e, img_prioridades i where end_date IS NULL and e.priority=i.priority ORDER BY id_emergency ASC");


while($registroLista=pg_fetch_assoc($consultaEmergencias)){

    $listado[]=$registroLista;//SE ASIGNAN LOS DATOS RECUPERADOS A LA VARIABLE $LISTADO
    
}
echo json_encode($listado);
pg_close($conexion);




?>
