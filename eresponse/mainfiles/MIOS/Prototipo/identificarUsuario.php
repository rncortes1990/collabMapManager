<?php
    //ESTE PHP SE ENCARGA DE IDENTIFICAR A LOS USUARIOS ALMACENADOS PARA LA FUNCIONALIDAD DE INHABILITAR USUARIOS            
    
    //SE REALIZA CONEXIÓN A LA BASE DE DATOS
    $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
    
    //SE RECIBE EL DATO DE ENTRADA RUN USUARIO, QUE CORRESPONDE AL RUN DE USUARIO O AL NOMBRE DEL MISMO PARA LAS POSIBLES COINCIDENCIAS
    $run_usuario=$_POST['id_usuario'];
    
    //SE CONSULTAN TODOS LOS POSIBLES USUARIOS QUE COINCIDAN CON EL RUN O NOMBRE INGRESADO.
    $registros=pg_query("SELECT id_usuario, nombre_usuario, tipo_usuario FROM usuarios WHERE (nombre_usuario LIKE '%$run_usuario%' OR CAST (id_usuario AS TEXT) LIKE '%$run_usuario%') AND tipo_usuario!='Inhabilitado'");
        


        while ($reg=pg_fetch_assoc($registros))
                    {
                        $Usuarios[]=$reg;//EL RESULTADO DE LA CONSULTA SE ASIGNA A $USUARIOS
                    }
        echo json_encode($Usuarios);
                pg_close($conexion);
 ?>   