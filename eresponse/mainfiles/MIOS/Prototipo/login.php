<?php
session_start();
   $id_usuario = $_POST['id_usuario'];
    $clave_usuario = $_POST['clave_usuario'];

$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");//SE REALIZA CONEXIÓN A LA BASE DE DATOS


if(ctype_digit($id_usuario)==true){//SI EL RUT SOLAMENTE TIENE NÚMEROS SE ENTRA AL IF
    
    $consultaUsuario=pg_query("select id_usuario, clave_usuario from usuarios where id_usuario='$id_usuario' and clave_usuario='$clave_usuario'");//SE REVISA QUE EL USUARIO EXISTA Y QUE LA CLAVE INGRESADA TAMBIÉN COINCIDA

    if(pg_num_rows($consultaUsuario)==1){//SI ES QUE EXISTE SE ALMACEN EL ID EN UNA VARIABLE DE SESIÓN

            $_SESSION['id_usuario']=$id_usuario;

            $answer= 0;

           
        }else{//SI NO EXISTE SE DEVUELVE UN ANSWER=2
    $answer=2;
    }
  }
else if(ctype_digit($id_usuario)==false){
     $answer=1;  //SI EL RUN TIENE ALGÚN CARACTER ESPECIAL, LETRAS, PUNOS O COMAS ANSWER SERÁ IGUAL A 1
  }

  $respuesta =array(
      'answer'=>$answer
  );  
  
echo json_encode($respuesta);
pg_close($dbconn3);

?>