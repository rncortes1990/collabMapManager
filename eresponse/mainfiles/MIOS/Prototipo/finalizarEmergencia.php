<?php
//ESTE PHP SE ENCARGA DE ASIGNAR UN VALOR A END_DATE DEL ID_EMERGENCY UTILIZADO

//SE REALIZA LA CONEXIÓN A LA BASE DE DATOS
$conexion= pg_connect("host=localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");

//SE RECIBE EL ID_EMERGENCY
$id_emergencia=$_POST['id_emergencia'];
//SE EJECUTA LA FUNCIÓN UPDATE_TO_EMERGENCY_SOLVED CON EL ID_EMERGENCY UTILIZADO, ESTO CAMBIARÁ EL VALOR DE END_DATE DE LA EMERGENCIA A FINALIZAR
$consultaEmergencias=pg_query("SELECT update_to_emergency_solved('$id_emergencia')");

echo $id_emergencia;
pg_close($conexion);

?>