<?php 
//ESTE PHP ES UTILIZADO PARA ACTUALIZAR LOS DATOS DE ALGÚN RECURSO ESTÁTICO
$nombre_re=$_POST['static_resource_name'];
$direccion_re=$_POST['address'];
$description_re=$_POST['description'];//DATOS UTILIZADOS EN LA ACTUALIZACIÓN


$conexiondb = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");//SE REALIZA CONEXIÓN A LA BASE DE DATOS



$consulta_direccion=pg_query(" SELECT address,description,static_resource_name
                                FROM static_resource
                                WHERE static_resource_name='$nombre_re'");//SE CONSULTAN TODOS LOS DATOS ASOCIADOS AL IDENTIFICADOR DEL RECURSO
$consulta_direccion_fetch=pg_fetch_assoc($consulta_direccion);
$direccion=$consulta_direccion_fetch['address'];
$descripcion=$consulta_direccion_fetch['description'];
$nombre=$consulta_direccion_fetch['static_resource_name'];//SE ASIGNAN LOS VALORES RECUPERADOS



if($direccion==$direccion_re&&$descripcion==$description_re){//ESTA CONDICIÓN ES PARA CUANDO NO SE MODIFICA LA DIRECCIÓN NI LA DESCRIPCION

$answer=0;
}else if($direccion==$direccion_re&&$descripcion!=$description_re){
    pg_query("UPDATE static_resource SET description='$description_re' WHERE static_resource_name='$nombre_re'");//SE REALIZA LA ACTUALIZACIÓN DE DESCRIPCIÓN EN EL RECURSO QUE TENGA EL NOMBRE ASIGNADO A NOMBRE_RE
    $answer=1;
    
}else if($direccion!=$direccion_re&&$descripcion==$description_re){
    $consulta_duplicidad=pg_query(" SELECT static_resource_name 
                            FROM static_resource 
                            WHERE address='$direccion_re'");//SE CONSULTA EL NOMBRE DEL RECURSO ESTÁTICO SEGÚN LA DIRECCIÓN
    
    if(pg_num_rows($consulta_duplicidad)>0){//SI LA DIRECCIÓN YA ESTÁ SIENDO UTILIZADA
    $answer=2;    
    }else{
    pg_query(" UPDATE static_resource SET address='$direccion_re' WHERE static_resource_name='$nombre_re'");//SE REALIZA LA ACTUALIZACIÓN DE DIRECCIÓN EN EL RECURSO QUE TENGA EL NOMBRE ASIGNADO A NOMBRE_RE}
    $answer=1;
    }
}
else{ 
    
       $consulta_duplicidad=pg_query(" SELECT static_resource_name 
                            FROM static_resource 
                            WHERE address='$direccion_re'");//SE CONSULTA EL NOMBRE DEL RECURSO ESTÁTICO SEGÚN LA DIRECCIÓN
    
    if(pg_num_rows($consulta_duplicidad)>0){
            $answer=2;    
    }else{
            pg_query(" UPDATE static_resource SET address='$direccion_re' WHERE static_resource_name='$nombre_re'");//SE REALIZA LA ACTUALIZACIÓN DE DIRECCIÓN EN EL RECURSO QUE TENGA EL NOMBRE ASIGNADO A NOMBRE_RE}
            $answer=1;
    

        pg_query("UPDATE static_resource SET description='$description_re' WHERE static_resource_name='$nombre_re'");//SE REALIZA LA ACTUALIZACIÓN DE DESCRIPCIÓN EN EL RECURSO QUE TENGA EL NOMBRE ASIGNADO A NOMBRE_RE
        }
}
echo json_encode($answer);

pg_close($conexiondb);
?>