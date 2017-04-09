<?php

    //EN ESTE PHP SE INHABILITA AL USUARIO SELECCIONADO DEL LISTADO DE USUARIOS DESPLEGADOS EN LA FUNCIONALIDAD DE INHABILITAR USUARIOS
    session_start();//SE INICIA LA SESIÓN PARA LAS VARIABLES DE SESSION
    $id_admin=$_SESSION['id_usuario']; //SE RECUPERA EL ID_USUARIO UTILIZADO EN EL LOGIN
    $run_usuario_inhab=$_POST['id_usuario_inhab'];//ESTE ES EL RUN ELEGIDO PARA LA INHABILITACIÓN
    $clave_admin=$_POST['clave_admin'];//CLAVE DE ADMINISTRADOR PARA LA INHABILITACIÓN DEL USUARIO
    //SE REALIZA CONEXIÓN A LA BASE DE DATOS
    $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
    
//SE VERIFICA QUE TANTO ID_ADMIN COMO CLAVE_ADMIN SEAN DEL MISMO REGISTRO, ES DECIR DE UN USUARIO DE TIPO ADMINISTRADOR
$consulta_admin=pg_query("  SELECT id_usuario
                                FROM usuarios
                                WHERE id_usuario='$id_admin' AND clave_usuario='$clave_admin'
                                ");

if(pg_num_rows($consulta_admin)==1){
    //SI LA CONSULTA ES VALIDA, SE INHABILITA EL USUARIO DE ID=$RUN_USUARIO_INHAB
    //POR LO TANTO SE REALIZA UN UPDATE SOBRE EL $RUN_USUARIO_INHAB
    $registros=pg_query("   UPDATE  usuarios 
                            SET tipo_usuario='Inhabilitado' 
                            WHERE id_usuario=$run_usuario_inhab");
    $answer=1;
    }else{
    
    $answer=0;//SI LA CLAVE DE ADMINISTRADOR NO COINCIDE CON EL ID_ADMIN, ANSWER=0
    
    
    
    }   
        


    echo json_encode($answer);
pg_close($conexion);

?>