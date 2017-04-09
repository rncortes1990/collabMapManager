<?php 

//ESTE PHP SE ENCARGA DE ACTUALIZAR LOS DATOS DE UNA EMERGENCIA SELCCIONADA EN EL MAPA DE TRABAJO
$id_emergencia=$_POST['id_emergency'];
$direccion_emg=$_POST['address'];
$priority=$_POST['priority'];
$tipo_pto_interes=$_POST['tipo_pto_interes'];
$answer="Emergencia actualizada!";//DATOS QUE SERÁN UTILIZADOS EN LA FUNCIONALIDAD

//SE REALIZA LA CONEXIÓN EN LA BASE DE DATOS
$conexiondb = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
$consulta_direccion=pg_query("  SELECT address
                                FROM emergency
                                WHERE id_emergency='$id_emergencia'
                                AND end_date IS NULL");//SE CONSULTA SI EXISTE ALGUNA EMERGENCIA CON END DATE NO FINALIZADA QUE CONTENGA LA MISMA DIRECCIÓN INGRESADA
$consulta_direccion_fetch=pg_fetch_assoc($consulta_direccion);
$direccion=$consulta_direccion_fetch['address'];
if($direccion==$direccion_emg){//SI LA DIRECCION DE ENTRADA ES IDENTICA A LA DIRECCION QUE ACTUALMENTE TIENE LA EMERGENCIA, SUCEDE LO SIGUIENTE
    
    pg_query("UPDATE emergency SET address='$direccion_emg'WHERE id_emergency='$id_emergencia'");//SE ACTUALIZA LA DIRECCIÓN DE LA EMERGENCIA
    pg_query("SELECT  update_emergency_priority('$id_emergencia','$priority')");//SE ACTUALIZA LA PRIORIDAD
    pg_query("UPDATE emergency_backend SET tipo_pto_interes='$tipo_pto_interes' WHERE id_emergency='$id_emergencia'");//SE ACTUALIZA EL TIPO DE PUNTO DE INTERÉS DE DICHA EMERGENCIA


    $imagen_seleccionada=pg_query("SELECT i.imagen FROM img_prioridades i WHERE i.priority='$priority'");//SE BUSCA LA RUTA DE LA IMAGEN A UTILIZAR EN LA MODIFICACION DE LA PRIORIDAD

    while($registro=pg_fetch_array($imagen_seleccionada)){

        $imagen[]=array(
            'imagen_recurso'=>$registro['imagen'],
            'answer'=>1
                    );
        echo json_encode($imagen);
    }//FIN WHILE

}else{//SI LA DIRECCIÓN DE ENTRADA ES DIFERENTE A LA QUE TIENE ACTUALMENTE LA EMERGENCIA, SE REALIZA LO SIGUIENTE
    $consulta_duplicidad=pg_query(" SELECT count(address) AS duplicada
                                    FROM emergency
                                    WHERE id_emergency!='$id_emergencia'
                                    AND address='$direccion_emg'
                                    AND end_date IS NULL");
    $consulta_duplicidad_fetch=pg_fetch_assoc($consulta_duplicidad);//SE VERIFICA QUE NINGUNA OTRA EMERGENCIA TENGA LA MISMA DIRECCIÓN Y SI OTRA EMERGENCIA LA TIENE, DEBE AL MENOS ESTAR FINALIZADA (END_DATE!=NULL)
    $duplicado=$consulta_duplicidad_fetch['duplicada'];
    if($duplicado==1){
        
        $imagen[]=array(
        'answer'=>0
        );
        echo json_encode($imagen);
    }else{
        
    pg_query("UPDATE emergency SET address='$direccion_emg'WHERE id_emergency='$id_emergencia'");//SE ACTUALIZA LA DIRECCIÓN DE LA EMERGENCIA
    pg_query("SELECT  update_emergency_priority('$id_emergencia','$priority')");//SE ACTUALIZA LA PRIORIDAD
    pg_query("UPDATE emergency_backend SET tipo_pto_interes='$tipo_pto_interes' WHERE id_emergency='$id_emergencia'");//SE ACTUALIZA EL TIPO DE PUNTO DE INTERÉS DE DICHA EMERGENCIA


    $imagen_seleccionada=pg_query("SELECT i.imagen FROM img_prioridades i WHERE i.priority='$priority'");//SE BUSCA LA RUTA DE LA IMAGEN A UTILIZAR EN LA MODIFICACION DE LA PRIORIDAD

    while($registro=pg_fetch_array($imagen_seleccionada)){

        $imagen[]=array(
            'imagen_recurso'=>$registro['imagen'],
            'answer'=>1
                    );
        echo json_encode($imagen);
    }//FIN WHILE
    }
    
    
    
    
}
   
    
    


pg_close($conexiondb);
?>