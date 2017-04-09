<?php
//ESTE PHP SE ENCARGA DE GENERAR RESPALDOS DE LOS REPORTES ALMACENADOS EN EL SISTEMA A TRAVÉS DE ARVCHIVOS PDF

//SE REALIZA LA CONEXXION A LA BASE DE DATOS
$dbconn3 = pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
//SE LLAMA A LA LIBRERIA FPDF DE PHP
require('libreria/fpdf.php');


function respaldoReportes(){
    //SE CONSULTA POR TODOS LOS DATOS DE TODOS LOS REPORTES ALMACENADOS EN EL SISTEMA
    $id_reportes=pg_query("SELECT id_reporte, id_emergency, reporte, fecha_reporte FROM reportes");
    //LUEGO SE CUENTA LA CANTIDAD DE REPORTE ALMACENADOS
    $consultaCantReportes=pg_fetch_assoc(pg_query("SELECT COUNT(id_reporte) as maxrep FROM reportes"));
    //SE ASIGNA LA CANTIDAD DE REPORTES OBTENIDOS A $CANTIDADREPORTES
    $cantidadReportes=$consultaCantReportes['maxrep'];
while($reg=pg_fetch_assoc($id_reportes)){
        $registro[]=$reg;//LUEGO SE ASIGNA A $REGISTRO LO ENCONTRADO EN LA CONSULTA DE $ID_REPORTES
        
}

for($i=0;$i<$cantidadReportes;$i++){//CADA ITERACIÓN DE ESTE FOR REPRESENTA UN REPORTE, ES DECIR SI EXISTEN 10 REPORTES EN EL SISTEMA, EL FOR ITERARÁ 10 VECES
    $registroBomberos= array();
    $registroCarros= array();//SE CREAN ARREGLOS PARA ALMACENAR LOS BOMBEROS Y CARROS BOMBAS QUE APARECEN EN LOS REPORTES
    $pdf = new FPDF();//SE CREA UN NUEVO PDF
    $pdf->AddPage();//SE AÑADE UNA PÁGINA
    $pdf->SetFont('Arial','B',16);//SE LE DA FORMATO A LA FUENTE
    $titulo='Reporte de emergencia número ';//TITULO DEL REPORTE
    $pdf->Ln(20);//ESPACIO VERTICAL
    $pdf->Cell(180,10,utf8_decode($titulo).$registro[$i]['id_reporte'],0,0,'C');//ID DEL REPORTE
    
    //SE REALIZA, UNA CONSULTA PARA RECUPERAR LOS DATOS DE LA EMERGENCIA A LA QUE EL REPORTE ESTÁ ASOCIADO
    $fecha_prioridad=pg_query("SELECT priority, start_date, address, id_emergency 
                                FROM emergency 
                                WHERE id_emergency='".$registro[$i]['id_emergency']."'");//$REGISTRO[$i][ID_EMERGENCY], SIGNIFICA QUE SE RECUPERARÁN LOS DATOS DE LA EMERGENCIA DEL REPORTE $i CON EL ID_EMERGENCY ASOCIADO A ESE REPORTE $i
    
    $fechaFetch=pg_fetch_assoc($fecha_prioridad);//SE ASIGNA EL VALOR DE LA FECHA A $FECHAFETCH
    $fecha=substr((string)$fechaFetch['start_date'],0,10);//SE CONVIERTE LA FECHA A UN STRING
    
    
    
    $stringArchivo="Reporte asociado.pdf";//LUEGO SE CREA EL NOMBRE DEL PDF A CREAR
    $pdf->Ln(20);
    $pdf->cell(1,10,'',11,0,'C');
    $pdf->SetFont('Arial','B',12); 
    $pdf->Cell(50,10,'Id emergencia',1,0,'C');
    $pdf->Cell(50,10,'Prioridad',1,0,'C');
    $pdf->Cell(90,10,utf8_decode('Dirección'),1,0,'C');//DESPUÉS SE AÑADEN LOS DATOS RELATIVOS A LA EMERGENCIA A LA QUE EL REPORTE ESTÁ ASOCIADO
    $pdf->Ln(10);
    $pdf->cell(1,10,'',11,0,'C');
    $pdf->Cell(50,10,$fechaFetch['id_emergency'],1,0,'C');
    $pdf->Cell(50,10,$fechaFetch['priority'],1,0,'C');
    $pdf->Cell(90,10,utf8_decode($fechaFetch['address']),1,0,'C');
    $pdf->Ln(10);
    
    //LUEGO, SE REALIZAN DOS CONSULTAS, CONTAR LA CANTIDAD DE CARROS BOMBA  Y BOMBEROS QUE APARECEN EN UN DETERMINADO REPORTE IDENTIFICADO CON ID_REPORTE, LAS QUE PERMITIRÁN DISTRIBUIR LAS TABLAS QUE SERÁN GENERADAS EN EL RESPALDO DEL REPORTE
    $consultaCantidadBomberos=pg_fetch_assoc(pg_query("
                                                    SELECT COUNT(firefighter_name) as maxbomb
                                                    FROM reporte_bomberos
                                                    WHERE id_reporte='".$registro[$i]['id_reporte']."'"));
    
    $consultaCantidadCarros=pg_fetch_assoc(pg_query("
                                                    SELECT COUNT(fire_truck_name) as maxcar
                                                    FROM reporte_carros
                                                    WHERE id_reporte='".$registro[$i]['id_reporte']."'"));
    //LUEGO SE CAPTURAN DICHAS CANTIDADES Y SE ASIGNAN A $CANTIDADBOMBEROSY $CANTIDADCARROS
    $cantidadBomberos=$consultaCantidadBomberos['maxbomb'];
    
    $cantidadCarros=$consultaCantidadCarros['maxcar'];
    
    $pdf->cell(1,10,'',11,0,'C');
    $pdf->Cell(190,10,'Comandante Incidente',1,0,'C');
    $pdf->Ln(10);
    
    //SE CONULSTAN LOS BOMBEROS PARTICIPANTES EN EL REPORTE $i
    $consulta_repBomberos=pg_query("SELECT r.firefighter_name, f.firefighter_type
                            FROM reporte_bomberos r, firefighter f
                            WHERE id_reporte='".$registro[$i]['id_reporte']."'
                            AND r.firefighter_name= f.firefighter_name");
    
    while($regg=pg_fetch_assoc($consulta_repBomberos)){
       
        $registroBomberos[]=$regg;//SE ASIGNA A $REGISTROBOMBEROS LO RECUPERADO DE LA CONSULTA
    }
    
    for($f=0;$f<$cantidadBomberos;$f++){//LUEGO SE EJECUTA UN FOR QUE ITERARÁ TANTAS VECES COMO CANTIDAD DE BOMBEROS APAREZCAN EN EL REPORTE ALMACENADO
        if($registroBomberos[$f]['firefighter_type']=="Comandante Incidente"){//SI EL TIPO DE BOMBERO ES COMANDANTE INCIDENTE, SE AÑADE UNA CELDA CON EL NOMBRE DEL COMANDANTE INCIDENTE
          $pdf->cell(1,10,'',11,0,'C');
          $pdf->Cell(190,10,$registroBomberos[$f]['firefighter_name'],1,0,'C');  
          $pdf->Ln(10); 
        }
    }
    
    $pdf->cell(1,10,'',11,0,'C');
    $pdf->Cell(95,10,'Bomberos participantes',1,0,'C');//LUEGO SE AÑADIRÁN LOS BOMBEROS Y CARROS BOMBA PRESENTES EN EL REPORTE
    $pdf->Cell(95,10,'Carros bomba',1,0,'C');
    
    //SE CNSULTA POR LOS CARRPOS BOMBA PRESENTES EN EL REPORTE $i
    $consulta_repCarros=pg_query("SELECT fire_truck_name 
                            FROM reporte_carros 
                            WHERE id_reporte='".$registro[$i]['id_reporte']."'");
    
    while($regg=pg_fetch_assoc($consulta_repCarros)){
        
        $registroCarros[]=$regg;//SE REALIZA UN FETCH DE LA CONSUTLA $CONSULTA_REPCARROS
       
    }        

    
    if($cantidadCarros<$cantidadBomberos){//SI LA CANTIDAD DE CARROS ENCONTRADOS ES MENOR A LA DE BOMBEROS ENCONTRADOS, SUCEDE LO SIGUIENTE
        echo"holi<br>";
        for($x=0;$x<$cantidadBomberos;$x++){
            
            $pdf->Ln(10);
            $pdf->cell(1,10,'',11,0,'C');
            $pdf->Cell(95,10,$registroBomberos[$x]['firefighter_name'],1,0,'C');
            if($x>=$cantidadCarros){
            $pdf->Cell(95,10,' ',1,0,'C');      
            }else{
                $pdf->Cell(95,10,$registroCarros[$x]['fire_truck_name'],1,0,'C');
                
            }
            
            
        }
        $pdf->Ln(10);
    }if($cantidadBomberos<$cantidadCarros){//SI LA CANTIDAD DE BOMBEROS ES MENOR A LA CANTIDAD DE CARROS ENCONTRADOS, SUCEDE LO SIGUIENTE
        
        echo "chaito<br>";
        for($j=0;$j<$cantidadCarros;$j++){
                $pdf->Ln(10);
                $pdf->cell(1,10,'',11,0,'C');  
            if($j>=$cantidadBomberos){
                $pdf->Cell(95,10,' ',1,0,'C');      
            }
            else{
                $pdf->Cell(95,10,$registroBomberos[$j]['firefighter_name'],1,0,'C');
            }
                $pdf->Cell(95,10,$registroCarros[$j]['fire_truck_name'],1,0,'C');
            
        }
        $pdf->Ln(10);
    }if($cantidadCarros==$cantidadBomberos){//SI LA CANTIDAD DE BOMBEROS ES LA MISMA CANTIDAD DE CARROS BOMBA ENCONTRADOS, SUCEDE LO SIGUIENTE
        
        echo "jiji<br>";
        for($y=0;$y<$cantidadCarros;$y++){
                $pdf->Ln(10);
                $pdf->cell(1,10,'',11,0,'C');  
                $pdf->Cell(95,10,$registroBomberos[$y]['firefighter_name'],1,0,'C');
                $pdf->Cell(95,10,$registroCarros[$y]['fire_truck_name'],1,0,'C');
            
        }
        $pdf->Ln(10);
    }
        //FINALMENTE SE AÑADEN LOS DATOS RELATIVOS AL REPORTE
        $pdf->Cell(1,10,'',11,0,'C');
        $pdf->Cell(190,10,'Contenido de reporte',1,0,'C');
        $pdf->Ln(10);
        $pdf->Cell(1,10,'',11,0,'C');
        $pdf->MultiCell(190,10,utf8_decode($registro[$i]['reporte']),1,'C');
        $pdf->Cell(1,10,'',11,0,'C');
        $pdf->Cell(190,10,'Fecha de reporte',1,0,'C');
        $pdf->Ln(10);
        $pdf->Cell(1,10,'',11,0,'C');
        $pdf->Cell(190,10,$registro[$i]['fecha_reporte'],1,0,'C');
        $pdf->Ln(10);
        $pdf->Image('img/logo.png',170,20,20);//SE AÑADE UN LOGO AL REPORTE
    $stringFecha=" ".$fecha;//SE CREARÁ DIRECTORIO EN QUE SE ALMACENARÁ EL REPORTE
    $stringRuta="Emergencia ".$fechaFetch['id_emergency'].$stringFecha;//SE TERMINA DE CREAR RUTA DE REPORTE RESPALDADO
    $mypath="respaldosEMG/$stringRuta/";//SE CREA RUTA DE CARPETA CONTENEDORA DE LA PRIMERA RUTA DESCRITA
    if (!is_dir($mypath)) {//SE VERIFICA SI LA RUTA EXISTE, SI NO EXISTE, SE CREA
            mkdir('respaldosEMG/'.$stringRuta.'/', 0777, true);         
      }
    //SI LA RUTA EXISTE, SE ESCRIBE DIRECTAMENTE EL ARCHIVO
    $pdf->Output($mypath."/".$stringArchivo,'F');
    
   
}


}
//SE LLAMA A LA FUNCIÓN RESPALDOREPORTES()
respaldoReportes();








pg_close($dbconn3);

?>