<?php
//ESTE PHP SE ENCARGA DE GENERAR RESPALDOS DE LOS RECURSOS DIGITALES ALMACENADOS EN EL SISTEMA, TAMBIÉN REALIZA LA LLAMADA DEL PHP QUE GENERA LOS RESPALDOS DE LOS REPORTES COMO ARCHIVOS DE TIPO PDF

//SE REALIZA LA LLAMADA DEL ARCHIVO PHP
require('respaldoReportes.php');
//SE REALIZA LLAMADA DE LA FUNCIÓN GENERADORA DE RESPALDOS DE REPORTES
respaldoReportes();
//SE REALIZA LA CONEXIÓN EN LA BASE DE DATOS
$conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
//SE CUENTA LA CANTIDAD DE RECURSOS DIGITALES QUE SE VAN A RESPALDAR
$numero_archivos=pg_fetch_assoc(pg_query("SELECT count(digital_resource_name) AS total from digital_resource"));
//SE ASIGNA EL TOTAL DE RECURSOS DIGITALES ENCONTRADOS EN LA VARIABLE $CANTIDAD_ARCHIVOS
$cantidad_archivos=$numero_archivos['total'];
//SE RECUPERAN LOS NOSMBRES DE LOS ARCHIVOS DIGITALES A RESPALDAR
$registro=pg_query("SELECT digital_resource_name FROM digital_resource");
//$codigo64= array();

while($reg=pg_fetch_assoc($registro)){
 
    $nombre[]=$reg['digital_resource_name'];//SE ALMACENA EN UN ARREGLO TODOS LOS NOMBRES ENCONTRADOS DE LOS ARCHIVOS

}

for($i=0;$i<$cantidad_archivos;$i++){//ESTE FOR ITERARÁ TANTAS VECES COMO IMÁGENES Y ARCHIVOS SE VAYAN A RESPALDAR
        $palabra=$nombre[$i];//SE LEE EL NOMBRE DEL ARCHIVO EN LA POSICIÓN $i
        $str=pg_fetch_assoc(pg_query("SELECT * from get_digital_resource('$palabra')"));//SE RECUPERA EL CÓDIGO BASE64 ASOCIADO AL NOMBRE DEL RECURSO DIGITAL
        $codigo[$i]=$str['get_digital_resource'];//SE ASIGNA EL VALOR RECUPERADO EN LA VARIABLE $CODIGO
    
        //SE CONSULTAN EL NOMBRE Y TIPO DE ARCVHIVO DEL RECURSO DIGITAL, JUNTO CON LA EMERGENCIA A LA QUE PERTENECEN
        $foto = pg_fetch_assoc(pg_query("SELECT digital_resource_name, related_emergency, format.file_type from digital_resource, format WHERE digital_resource_name='$palabra' AND format.format_name=digital_resource.format"));
        $tipo_foto=$foto['file_type'];//SE ASIGNA EL TIPO DE RECURSO DIGITAL A $TIPO_FOTO
        $nombre_foto=$foto['digital_resource_name'];//SE ASIGNA EL NOMBRE DE RECURSO DIGITAL A $NOMBRE_FOTO
        $em=$foto['related_emergency'];//SE ASIGNA EL ID_EMERGENCY A $EM
    
        //LUEGO SE UTILIZA $EM PARA ENCONTRAR LA FECHA EN QUE SE INICIÓ LA EMERGENCIA A LA QUE EL RECURSO DIGITAL ESTÁ ASOCIADO
        $capturafecha=pg_fetch_assoc(pg_query("SELECT DISTINCT e.start_date FROM emergency e, digital_resource d WHERE e.id_emergency=d.related_emergency AND d.related_emergency='$em'"));
        //DICHA FECHA SE RECORTA DE TAL MANERA QUE PASE DE AAA-MM-DD HH:MM:SS A AAAA-MM-DD, ESTO SE HACE PARA QUE CUANDO SE GENEREN LAS CARPETAS QUE CONTENDRÁN LOS RESPALDOS DE LOS ARCHIVOS, LAS CARPETAS CONTENGAN EL NOMBRE DE LA EMERGENCIA A LA QUE PERTENECEN MÁS LA FECHA EN QUE SE INICIARON
        $fecha=substr((string)$capturafecha['start_date'],0,10);
        //SE CREA UN STRING "EMERGENCIA"
        $string="Emergencia ";
        //LUEGO SE CONCATENA CON EL ID DE LA EMERGENCIA ASIGNADA EN $EM MÁS LA FECHA RECORTADA
        $string=$string.$em.' '.$fecha;
        //SE PRUEBA QUE EL NOMBRE DE LA CARPETA ESTÉ CORRECTAMENTE CREADO
        echo $string;
        //SE CREA UN STRING QUE REPRESENTA LA RUTA DE LA CARPETA A CREAR
        $mypath = "respaldosEMG/$string/";
        //SE DECODIFICA EL CODIGO ASIGNADO A LA VARIABLE $CODIGO(REVISAR INICIO DEL FOR)
        $lol=base64_decode($codigo[$i]);
    
    if (!is_dir($mypath)) {//SI EL STRING DE LA RUTA NO EXISTE COMO CARPETA, SE CREA
            mkdir('respaldosEMG/'.$string.'/', 0777, true);         
      }
            //SI EL STRING DE LA RUTA YA EXISTE COMO CARPETA, 
            //NO SE CREA LA CARPETA respaldosEMG Y SE ESCRIBE DIRECTAMENTE LOS ARCHIVOS A RESPALDAR
            
            if($tipo_foto=='Video'){//SI EL ARCHIVO A RESPALDAR ES DE FILE_TYPE = VIDEO, SE CREA UN STRING CON EL NOMBRE DEL ARCHIVO A RESPALDAR, JUNTO A LA RUTA EN LA QUE SERA RESPALDADO
            $myFile = $mypath.$nombre_foto.".mp4";
            }else{//SI EL ARCHIVO A RESPALDAR ES DE FILE_TYPE = IMAGEN, SE CREA UN STRING CON EL NOMBRE DEL ARCHIVO A RESPALDAR, JUNTO A LA RUTA EN LA QUE SERA RESPALDADO
            $myFile = $mypath.$nombre_foto.".jpg";
            }
            
            $fh = fopen($myFile, 'w') or die("can't open file");//SE CREA EL ARCHIVO
            $stringData = "Some text";//EJEMPLO UTILIZADO PARA CREAR LOS STRINGS DE LAS RUTAS
            fwrite($fh, $lol);//SE ESCRIBE EL ARCHIVO
            fclose($fh);  //SE FINALIZA EL RESPALDADO DEL ARCHIVO Y SE REALIZA LA SIGUIENTE ITERACIÓN
}//FIN FOR DE RESPALDO





    
pg_close($conexion);
?>