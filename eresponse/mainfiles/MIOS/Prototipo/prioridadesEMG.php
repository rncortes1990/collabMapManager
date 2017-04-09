<?php
     //ESTE PHP SE ENCARGA DE LISTAR LAS PRIORIDADES EXISTENTES PARA LAS EMERGENCIAS

    //SE REALIZA CONEXIÓN CON LA BASE DE DATOS
    $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
    //SE RECUPERAN LOS VALORES DE LAS PRIORIDADES
    $registros=pg_query("SELECT priority FROM img_prioridades");
        

while ($reg=pg_fetch_assoc($registros))
                    {
                        $RecursosEMG[]=$reg;//SE ASIGNAN LOS VALORES DE LAS PRIORIDADES A $RecursosEMG
                    }
echo json_encode($RecursosEMG);
pg_close($conexion);
 ?>   