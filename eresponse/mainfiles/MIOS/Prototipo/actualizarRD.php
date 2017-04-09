<?php 
//PHP QUE ACTUALIZA LOS DATOS DE UN RECURSO DINÁMICO, ACTUALMENTE NO TIENE USO YA QUE NO SE ESTABLECIÓ UN REQUISITO PARA LA MODIFICACIÓN..
//SE DEJA ABIERTO PARA POSIBLES FUNCIONALIDADES FUTURAS.
$nombre_recurso_rd=$_POST['dynamic_resource_name'];
$id_device=$_POST['id_device'];
$respuesta;
$conexiondb = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
$consultaIdDisp=pg_query("SELECT dynamic_resource_name FROM dynamic_resource WHERE id_device='$id_device' AND dynamic_resource_name!='$nombre_recurso_rd'");

if(empty($id_device)){
    $respuesta=1;
    echo json_encode($respuesta);
    }else{

    if(pg_num_rows($consultaIdDisp)==1){
        
        $respuesta=2;
        echo json_encode($respuesta); 
    }else{
        pg_query("UPDATE dynamic_resource SET id_device='$id_device' WHERE dynamic_resource_name='$nombre_recurso_rd'");
        echo json_encode("Modificación terminada");
        }
}


pg_close($conexiondb);
?>