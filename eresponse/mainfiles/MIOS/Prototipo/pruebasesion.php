<?php

//$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
    ob_start();
    session_start();
    $_SESSION['usr']=$_POST['id_usuario'];
    $_SESSION['psw']=$_POST['clave_usuario'];
        $url="mapa.php";
        echo '<script>window.location = "'.$url.'";</script>';




               // pg_close($conexion);

?>