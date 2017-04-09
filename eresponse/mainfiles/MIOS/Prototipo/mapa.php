<?php
session_start();
$rut=$_SESSION['id_usuario'];

if(empty($rut)){
    header('Location:index.php');
    exit();
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/MIOS/Prototipo/icon/ic_mobile_map_logo.ico" type="image/x-icon" /><!--RUTA DE LOS ICONOS-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous"><!--RUTA DE BOOTSTRAP POR SI SE DESEA AÑADIR A LA IMPLEMENTACIÓN FUTURA-->
    <link rel="stylesheet" type="text/css" href="css/normalize.css"><!--NORMALIZADOR DE CSS-->
    <link rel="stylesheet" type="text/css" href="css/styles.css"><!--CSS PARA EL MAPA DE TRABAJO-->
    <link href='https://api.mapbox.com/mapbox.js/v2.2.2/mapbox.css' rel='stylesheet' /><!--CSS DE MAPBOX-->
    

    
    <title>Gestor de datos</title>
</head>
<body>

<div class="main-contenedor">
    <div id="loading-contenedor"><!--SE CONTIENE TODOS LOS MENSAJES RELACIONADOS AL RESPALDO DE ARCHIVOS-->
        <p id="mensaje-superior">Respaldando im&aacute;genes y reportes de emergencias</p>
        <div id="loading-imagen"></div>
        <p id="espere">Espere porfavor....</p>
        <p id="listo">Operaci&oacute;n finalizada</p>
    </div>      
         
        <div class='custom-popup' id="map"><!--CONTENEDOR DE LA BARRA BUSCADORA-->
       <div id="contenedor-search" class="contenedor-search">
            <button id="cerrar-search">X</button><input type='text'  id='search' placeholder='Ingrese ubicacion...'autocomplete="off">
            
        </div>  
        </div>
    
       
        <div id="menuizquierdo" class="menuizquierdo lado">
            <div class="botones"><!--CONTENEDOR DE LOS PRIMEROS 4 BOTONES DEL MENÚ IZQUIERDO-->
            <button class="btn-1 boton" value="20" title="Ingresar recurso est&aacute;tico"></button>
            <button class="btn-2 boton" value="19" title="Ingresar recurso din&aacute;mico"></button>
            <button class="btn-3 boton" value="18" title="Ingresar Emergencia"></button>
            <button class="btn-4 boton" title="Respaldar recursos digitales"></button>
                
            </div>
            
            
            <div class="modal">
      
   <!--//////////////////////////COMIENZO MODAL RECURSO  ESTÁTICO/////////////////////////////////////////////////////////////-->       
    <form id="forma-recurso">
        <p class="titulo-recurso">Nuevo recurso estático</p>
            <table >
                <tr>
                    <td><input type="text"  class="campo nombre-recurso" maxlength="50" placeholder="Nombre recurso"></td> 
                </tr>
                 <tr>
                    
                    <td><input type="text"  class="campo direccion-recurso" maxlength="50" placeholder="Dirección recurso"></td>
                </tr>   
                
                <tr>
                    
                   <td><input type="text"  class="campo descripcion-recurso" maxlength="100" placeholder="Descripción recurso"></td>
                </tr>
                <tr>
                    <td><p>Tipo punto de interés</p></td>
                </tr>
                <tr>
                    
                    <td><select type="text" class="campo tipo-pto-interes">
                        
                <?php
                
                $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
                $registros=pg_query("select tipo_pto, tipo_pto_interes from tipos_ptos_interes where tipo_pto_interes BETWEEN 1 AND 4");
                    while ($reg=pg_fetch_array($registros))
                    {
                    echo "<option value=\"$reg[tipo_pto_interes]\">$reg[tipo_pto]</option><br>";
                    }

                pg_close($conexion);
            ?>                         
                        
                        
                        
                        </select></td>
                </tr>
                <tr>
                    <td><p>Latitud/longitud</p></td>
                </tr>
                <tr>
                    <td><input type="text" class="latlng" disabled></td>
                </tr>
                <tr>
                    <td class="salida-btn"><button  class="btn-salir">Salir</button></td>
                    <td class="ingreso-boton"><input type="submit" class="ingresar-recurso"value="Ingresar"></td>
                </tr>
               
            
        </table>
        
    </form>    
    
    
    </div>
    <!--//////////////////////////COMIENZO MODAL RECURSO  DINAMICO/////////////////////////////////////////////////////////////-->        
            <div id="MODAL2">
        
    <form id="forma-recurso-dinamico">
        <p class="titulo-recurso">Nuevo recurso din&aacute;mico</p>
            <table >
              <tr>
                
                <td><input type="text"  class="campo nombre-recurso-din" maxlength="50" placeholder="Nombre Recurso"></td> 
                </tr>
                
                 <tr>
                    
                    <td><input type="text"  class="campo id-dispositivo-din" maxlength="16" placeholder="IMEI del dispositivo"></td>
                </tr>
                <tr>
                    
                    <td><input type="text" id="run-id-disp" class="run-id-disp" maxlength="8" autocomplete="off" placeholder="Ingrese RUN"></td>
                </tr>
                <tr>
                    <td><p>Cuartel:</p></td>
                </tr>
                <tr>
                    <td><select id="cuartel-rd" class="cuartel-rd"></select></td>
                </tr>
 
                <tr>
                    <td><p>Tipo de punto de inter&eacute;s:</p></td>
                </tr>
                <tr>
                    <td><select type="text" class="campo tipo-pto-interes-din">
                        
                <?php
                
                $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
                $registros=pg_query("select tipo_pto, tipo_pto_interes from tipos_ptos_interes where tipo_pto_interes BETWEEN 5 AND 10");
                    while ($reg=pg_fetch_array($registros))
                    {
                    echo "<option value=\"$reg[tipo_pto_interes]\">$reg[tipo_pto]</option><br>";
                    }

                pg_close($conexion);
            ?>                         
                        
                        
                        
                        </select></td>
                </tr>
                <!--<tr>
                    <td><p>Latitud/longitud</p></td>
                    <td><input type="text" class="latlng-din" disabled></td>
                </tr>-->
                
                <tr>
                    <td class="salida-btn"><button  class="btn-salir-din">Salir</button></td>
                    <td class="ingreso-boton"> <input type="submit" class="ingresar-recurso-din"value="Ingresar"></td>
                </tr>
               
            
        </table>
        
    </form>    
  
    
    </div><!--MODAL 2-->
    <!--/////////////////////////////////COMIENZO MODAL DE EMERGENCIAS//////////////////////////////////////////////////////-->  
            
    
     <div id="MODAL3">
       
    <form id="forma-emergencia" > 
             <p class="titulo-recurso">Nueva emergencia</p><br>
            <table >
              <tr>
                
                <td><input type="text"  class="campo direccion-emg" maxlength="50" placeholder="Dirección de emergencia"></td> 
                </tr>
                 <!--<tr>
                    <td><p>Prioridad:</p></td>
                    <td><select type="text" class="campo prioridad-emg">
                        
                <?php
                
                $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
                $registros=pg_query("SELECT priority FROM img_prioridades ORDER BY priority");
                    while ($reg=pg_fetch_array($registros))
                    {
                    echo "<option value=\"$reg[priority]\">$reg[priority]</option><br>";
                    }

                pg_close($conexion);
            ?>                         
                        
                        
                        
                        </select></td>
                </tr> -->  
            
                <tr>
                    <td><p>Tipo de punto de inter&eacute;s:</p></td>
                </tr>
                <tr>
                    <td><select type="text" class="campo tipo-pto-interes-emg">
                        
                <?php
                
                $conexion=pg_connect("host= localhost port=5432 dbname=EmergenciesResponse user=postgres password=asdf");
                $registros=pg_query("select tipo_pto, tipo_pto_interes from tipos_ptos_interes where tipo_pto_interes=13");
                    while ($reg=pg_fetch_array($registros))
                    {
                    echo "<option value=\"$reg[tipo_pto_interes]\">$reg[tipo_pto]</option><br>";
                    }

                pg_close($conexion);
            ?>                         
                        
                        
                        
                        </select></td>
                </tr>
                <tr>
                    <td><p>Latitud/longitud</p></td>
                </tr>
                <tr>
                    <td><input type="text" class="latlng-emg" disabled></td>
                </tr>
                <tr>
                    <td class="salida-btn"><button  class="btn-salir-emg">Salir</button></td>
                    <td class="ingreso-boton"> <input type="submit" class="ingresar-emg"value="Ingresar"></td>
                </tr>
               
            
        </table>
        
    </form>    
      
    
    </div><!--MODAL 3-->
    <!--#########################COMIENZO DE MODAL DE MODIFICACION DE USUARIO################################################################-->
            <div id="contenedor-modificar">
        <div id="main-modificar">
        <div id="tmodificar"><p>Inhabilitar usuario del sistema</p></div>   
        <div id="contenedor-modificar-usuario">
        <form id="encuentra-usuario">
        
        <input type="text" maxlength="45" class="run-usuario" placeholder="Ingrese RUN o nombre"><input id="btn-encontrar"type="submit" value="Buscar">
        <br><div id="listado-usuarios"><table style="width:100%"></table></div>
        </form>
        
        <form id="inhabilita-usuario">
        <div id="contenedor-datos-finales">
        <br>
        Usuario a inhabilitar:    
        <input id="run-encontrado" type="text" disabled><br>
        
        <input type="password" maxlength="15" class="clave-admin" placeholder="Confirme con su clave">
        </div>
            <br>
        <div id="aceptar-inhabilitar"><input id="btn-inhabilitar"type="submit" value="Inhabilitar"></div>
        </form>
        </div> 
        </div>
       
    </div>
            
            
    <!--$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$COMIENZO LISTAR EMERGENCIAS$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$-->
            
            <div id="lista-emergencias">
                <p class="titulo-recurso">Emergencias</p>
                <ul>
                </ul>
            </div>
            
     <!--$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$FIN LISTAR EMERGENCIAS$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$-->
            <div class="controles"><!--CONTENEDOR DE LOS ULTIMOS 4 BOTONES DEL MENÚ IZQUIERDO-->
               
            <button class="btn-5 boton boton-ctrl" title="Inhabilitar usuario"></button>
            <button class="btn-6 boton boton-ctrl" title="Actualizar vista"></button>
            <button class="btn-7 boton boton-ctrl" title="Abandonar aplicaci&oacute;n"></button> 
            
            </div>
            </div>


    
  
</div>
   
    
    
    
    
   
    
    
    <div class="modal-err"><!--FONDO GRIS QUE APARECE EL USAR INHABILITAR USUARIO-->
    
    
    </div> 

    
    <script src='https://api.mapbox.com/mapbox.js/v2.2.2/mapbox.js'></script><!--SE AÑADE API DE MAPBOX-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script><!--SE AÑADE LIBRERÍA DE JQUERY-->
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script><!--SE AÑADE OTRA LIBRERIA DE JQUERY, OPCIONAL-->
 <script type="text/javascript">
     
     //LLAVE DE ACCESO PARA INGRESAR A MAPBOX
     L.mapbox.accessToken = 'pk.eyJ1IjoiY29ydGVzcmF1IiwiYSI6ImNpZmR6NWFqbDY2cWFzZW03b2d6dTZ4bTEifQ.N0juLxJkjo3KKaUiiuy1tw';
     

     //SE ESTABLECE UN PUNTO INICIAL EN EL MAPA DE TRABAJO
     var map = L.mapbox.map('map', 'mapbox.streets').setView([-36.821119, -73.041084], 15);

    //SE ACTIVA EL GEOCODER PARA EL MAPA DE TRABAJO
    var   geocoder=L.mapbox.geocoder('mapbox.places');

    


  
    
       
        map.doubleClickZoom.disable();//SE DESACTIVA EL ZOOM POR DOBLE CLICK
     
     
 
     </script> 
        <script>
            //SCRIPT UTILIZADO PARA LA VENTANA EMERGENTE DE LOS REPORTES
              var sharedObject;//VARIABLE QUE SE UTILIZARÁ PARA CAPTURAR EL ID DE LA EMERGENCIA
              
              //FUNCIÓN QUE SE EJECUTARÁ AL PRESIONAR EL BOTÓN REPORTE DE ALGÚN MARCADOR
            
            $('#map').on("click",".reporte",function(){
                
            sharedObject=$(this).val();//SE ASIGNA EL VALOR DE "VALUE" DEL BOTON REPORTE
            //alert(sharedObject);
            var mywindow=window.open("ventana_emergente.php","ue ","width=800, height=600,screenX=0,left=0,screenY=0,top=0,directories=0,location=0,menubar=0,resizable=false,scrollbars=0,status=0,toolbar=0" );
            });//SE ABRE NUEVA VENTANA EMERGENTE

        </script>
    <script src="js/mapa.js"></script><!--SE AÑADE MAPA.JS AL HTML-->

</body>
</html>
