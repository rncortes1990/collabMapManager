<?php
    $id_usuario =       $_POST['id_usuario'];
     $clave_usuario =    $_POST['clave_usuario'];
      $nombre_usuario=    $_POST['nombre_usuario'];
       $tipo_usuario=      $_POST['tipo_usuario'];
        $clave_admin=     $_POST['clave_admin'];//SE RECIBEN LOS DATOS DEL FORMULARIO DE INGRESO
         $dbconn3 = pg_connect("host= localhost
                                port=5432 
                                dbname=EmergenciesResponse
                                user=postgres 
                                password=asdf");//SE REALIZA CONEXIÓN A LA BASE DE DATOS


if(ctype_digit($id_usuario))//SI EL RUN INGRESADO SON SOLO NÚMEROS SE ENTRA AL IF
{   
    
    $consultaID=pg_query("SELECT id_usuario FROM usuarios WHERE id_usuario='$id_usuario'");//SE REVISA SI EL ID_USUARIO YA EXISTE EN EL SISTEMA
    
        if(pg_num_rows($consultaID)==1)//SI EL ID EXISTE ANSWER=1
        {   
                $answer=1;
        }
            else//SI NO EXISTE EL ID EN EL SISTEMA, SE REVISARÁ QUE LA CLAVE DE DE ADMINISTRADOR EXISTA
                {   $consultaAdmin=pg_query("   SELECT  clave_usuario
                                                FROM    usuarios
                                                WHERE   clave_usuario='$clave_admin'
                                                AND     tipo_usuario='Administrador'
                                                ");
                    if(pg_num_rows($consultaAdmin)>=1){//SI LA CLAVE DE ADMINISTRADOR ES VÁLIDA SE REALIZA EL INGRESO DEL NUEVO USUARIO
                        
                        pg_query("  INSERT INTO usuarios
                                VALUES( '".$id_usuario."',
                                        '".$nombre_usuario."',
                                        '".$clave_usuario."',
                                        '".$tipo_usuario."')");
                        $answer=0;
                    }else{//SI LA CLAVE DE ADMINISTRADOR NO EXISTE ANSWER = 3
                        
                        $answer=3;
                    }
                    
                }
        }      
else{//SI EL RUN INGRESADO NO ES UN NUMERO ENTERO POSITIVO ANSWER = 2
                        $answer=2;
}


echo json_encode($answer);
pg_close($dbconn3);
?>