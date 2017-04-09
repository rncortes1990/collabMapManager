<?php

//ESTE PHP SE ECARGA DE RECUPERAR LOS DATOS DE LOS RECURSOS DIGITALES QUE SERÁN MOSTRADOS EN LA GALERÍA
$id_emergencia=$_POST['id_emergency'];//ID DE EMERGENCIA AL QUE ESTÁN ASOCIADOS LOS RECURSOS DIGITALES A MOSTRAR

//SE REALIZA CONEXIÓN A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
//SE CUENTA LA CANTIDAD DE RECURSOS DIGITALES ASOCIADOS A LA EMERGENCIA UTILIZADA
$numero_archivos=pg_fetch_assoc(pg_query("SELECT count(digital_resource_name) AS total from digital_resource WHERE related_emergency='$id_emergencia'"));

//SI LA CANTIDAD DE FILAS RECUPERADAS SON 1 O MÁS SE REALIZA LO SIGUIENTE
if($numero_archivos['total']>0){
    //SE SELECCIONA EL NOMBRE DEL RECURSO DIGITAL Y SU TIPO(FOTOGRAFIA O VIDEO)
    $registro=pg_query("SELECT d.digital_resource_name, f.file_type 
                        FROM digital_resource d, format f 
                        WHERE d.related_emergency='$id_emergencia' 
                        AND f.format_name=d.format"
                      );
    
    while($reg=pg_fetch_assoc($registro)){
 
    $nombre[]=$reg['digital_resource_name'];//EL NOMBRE DEL RECURSO Y EL TIPO SE ALMACENA EN ARREGLOS DIFERENTES
    $tipo[]=$reg['file_type']; 
        
    }
    
    for($i=0;$i<$numero_archivos['total'];$i++){//LUEGO SE REALIZA UN FOR QUE ITERARÁ TANTAS VECES COMO RECURSOS DIGITALES ASOCIADOS AL ID DE EMERGENCIA EXISTAN
        $file_name=$nombre[$i];//SE ASIGNA EL NOMBRE DEL ARCHIVO 
        $file_type=$tipo[$i];//SE ASIGNA EL TIPO
        $str=pg_fetch_assoc(pg_query("SELECT * from get_digital_resource('$file_name')"));//LUEGO SE RECUPERA EL CÓDIGO BASE64 DEL ARCHIVO
        $codificado64=$str['get_digital_resource'];//LUEGO SE CONVIERTE EN STRING EL VALOR RECUPERADO DEL FETCH
        $decodificado64=base64_decode($codificado64);//SE DECODIFICA EL STRING CODIFICADO EN CODE BASE64
        
        if($file_type=='Video'){//SI EL RECURSO DIGITAL ES DE TIPO VIDEO, SE REALIZA LO SIGUIENTE
           
        $answer[]=
                '<video width="115" height="115" controls="controls" poster="img/logo.png" preload>
                <source src="data:video/ogg;base64,'.$codificado64.'" type="video/ogg" /> 
                </video>';
                
        }else if($file_type=="Fotografía"){//SI EL RECURSO ES DE TIPO FOTOGRAFÍA, SE REALIZA LO SIGUIENTE
            
            $answer[]='<img src="data:image/jpg;base64,'.$codificado64.'" width="115" height="115"/>';
            
        }
       
    }
    
 
    
}
else{//SI NO EXISTEN RECURSOS DIGITALES ASOCIADOS, ANSWER=0
    
    $answer=0;
    
}

echo json_encode($answer);
pg_close($dbconn3);

?>

