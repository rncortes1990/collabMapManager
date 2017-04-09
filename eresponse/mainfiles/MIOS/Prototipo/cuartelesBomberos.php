<?php
//ESTE PHP ES UTILIZADO PARA QUE LA LISTA DE CUARTELES DE BOMBEROS SIEMPRE ESTÉ ACTUALIZADA AL MOMENTO DE INGRESAR UN NUEVO RECURSO DINÁMICO EN EL SISTEMA WEB, YA QUE SI SE REALIZA LA CONSULTA DESDE MAPA.PHP SE DEBERÍA REFRESCAR LA PÁGINA PARA QUE DICHO LISTADO SE ACTUALICE                        
//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");

//SE RECUPERAN LOS NOMBRES DE LOS CUARTELES DE BOMBEROS
$registros=pg_query("select fire_station_name from fire_station");
                    
                    
while ($reg=pg_fetch_assoc($registros))
{

    $cuartelBomberos[]=$reg;//SE ASIGNAN LOS DATOS RECUPERADOS A $cuartelBomberos
    

}
echo json_encode($cuartelBomberos);
pg_close($conexion);
                        
                        
?> 