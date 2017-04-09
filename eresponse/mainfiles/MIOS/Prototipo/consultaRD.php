

<?php
//PHP UTILIZADO PARA RECUPERAR LOS DATOS DE LOS RECURSOS DINÁMICOS DEL SISTEMA, UTILIZADO PRINCIPALMENTE EN INICALIZARMAPA() EN MAPA.JS

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");



//SE CONSULTAN LOS DATOS DE LA BASE DE DATOS DE TODOS LOS RECURSOS DINÁMICOS Y SUS RESPECTIVAS IMÁGENES
$query1=pg_query("
SELECT DISTINCT d.*,i.imagen, i.tipo_pto_interes
FROM dynamic_resource d, img_pto_interes i, dynamic_resource_backend db
WHERE db.tipo_pto_interes=i.tipo_pto_interes AND db.dynamic_resource_name=d.dynamic_resource_name AND d.latitude IS NOT NULL AND d.longitude IS NOT NULL
");



while($reg=pg_fetch_assoc($query1)){

    $registro_rd[]=$reg;//SE REALIZA UN FECTH DE LAS FILAS ENCONTRADAS DE LOS RECURSOS DINÁMICOS

}
    
echo json_encode($registro_rd);//SE ENVÍAN LOS DATOS A LA FUNCIÓN QUE LLAME A ESTE PHP.
   



pg_close($dbconn3);

  
?>





