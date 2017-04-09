<?php
$conexion= pg_connect("host=localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
$id_emergencia=$_POST['id_emergencia'];
$consultaEmergencias=pg_query("SELECT update_to_emergency_solved('$id_emergencia')");

echo $id_emergencia;
pg_close($conexion);

?>