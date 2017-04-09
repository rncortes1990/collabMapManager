<?php
//ESTE PHP SE ENCARGA DE IDENTIFICIAR EL TIPO DE USUARIO QUE REALIZÓ LOGIN, PARA ASI DETERMINAR (DENTRO DE MAPA.JS) QUE FUNCIONALIDADES SERÁN DESPLEGADAS PARA CADA TIPO DE USUARIO OPERANTE
session_start();
$id_usuario=$_SESSION['id_usuario'];//SE UTILIZA LA VARIABLE DE SESIÓN

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
//SE RECUPERA EL TIPO DE USUARIO CON RESPECTO A LA VARIABLE DE SESIÓN
$tipoUsuario=pg_query("SELECT tipo_usuario FROM usuarios WHERE id_usuario='$id_usuario'");
//SE REALIZA EL FETCHING DEL TIPO DE USUARIO RECUPERADO
$tipoDeUsuario=pg_fetch_assoc($tipoUsuario);
//FINALMENTE SE ASIGNA EL TIPO DE USUARIO A UNA VARIABLE DE SALIDA
$answer=$tipoDeUsuario['tipo_usuario'];

echo json_encode($answer);
pg_close($dbconn3);
?>