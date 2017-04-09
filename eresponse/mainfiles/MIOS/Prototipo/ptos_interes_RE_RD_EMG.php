<?php
    //SE REALIZA CONEXION CON LA BASE DE DATOS DEL SISTEMA          
    $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
    
    //SE CONSULTA LOS TIPOS DE PUNTO DE INTERÉS DEL SISTEMA
    $registros=pg_query("SELECT tipo_pto, tipo_pto_interes FROM tipos_ptos_interes ORDER BY tipo_pto");
        while ($reg=pg_fetch_assoc($registros))
                    {
                        $RecursosRE[]=$reg;//SE ASIGNAN LOS VALORES CAPTURADOS A $RECURSOSRE
                    }
        echo json_encode($RecursosRE);
                pg_close($conexion);
 ?>   