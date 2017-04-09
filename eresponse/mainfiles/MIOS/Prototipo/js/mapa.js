var tipo_usuario;//VARIABLE UTILIZADA PARA DESPLEGAR U OCULTAR CONTENIDO DEL SISTEMA WEB
function inicializaInterfaz(){
    $.ajax({
            url:'tipoUsuario.php',//SE CONULSTA EL TIPO DE USUARIO DEL USUARIO QUE INICIÓ SESIÓN
            data:{},
            method:'POST',
            dataType:'json',
            async:false,
            success:function(data){
                tipo_usuario=data;
                if(data=='Inhabilitado'){//SE DESPLIEGA CONTENIDO PARA USUARIO "INHABILITADO"
                    $('#mensaje-superior').text("Su cuenta está inhabilitada");
                    $('#espere').text("No tiene acceso a ninguna funcionalidad del sitema");
                    $('#loading-contenedor').show();
                    $('#map').css({'z-index':'-1'});
                    $('.boton').not('.btn-7').remove();
                    $('.btn-7').css({'margin-top':'400px'}).show();
                    
                }if(data=='Operador'){//SE DESPLIEGA CONTENIDO PARA USUARIO "OPERADOR"
                        $('.btn-2').remove();
                        $('.btn-4').remove();   
                        $('.btn-5').remove();
                        $('.controles').css({'margin-top':'60vh'}); 
                }
            }
        
        
    });
    
    
}



$('.menuizquierdo').delay(800).slideDown("slow");
$("#loading-contenedor").hide();
inicializaInterfaz();
$("#listo").hide();
$('#map').hide();
$('.modal').hide();
$('#MODAL2').hide();
$('#MODAL3').hide();
$('#contenedor-modificar').hide();
$('#listado-usuarios').hide();
$('#map').delay(600).fadeIn("slow");
$('.btn-salir-ocultar').click(esconderModalOcultar);//estas llamadas de jquery inicializan el estado de todos los elementos con los que el usuario interactua

$('.modal-err').click(function(event){
    if(event.target==this){
    $(this).hide();
    $('#listado-usuarios table tr').remove();
    $('#listado-usuarios').hide();
    OcultarMenuIzq();
    esconderModalInhabilitar();
    $('.btn-1, .btn-2, .btn-3').prop("disabled",false);
    }

});//esta funcion oculta o desvanece la zona gris que aparece cuando se desea modificar un usuario, clickeando encima de dicha zona


var contadorEmergencias=1;
//////////////////////
var contadorEMG=0;
var marcadorEMG;//contador para ingreso de marcadores
////////////////////////////////Para los marcadores de emergencias
//////////////////////
var contadorRE=0;//contador para ingreso de marcadores
var marcadorRE= new Array();
////////////////////////////////Para los marcadores de recursos estaticos
//////////////////////
var contadorRD=0;//contador para ingreso de marcadores
var marcadorRD= new Array();
////////////////////////////////Para los marcadores de recursos dinámicos
var markerRE= [];
var POP=new Array();

var marcadoresEmergencia=new Array();//arreglo de marcadores de emergencias
//////Estaticos
var marcadoresCarabineros=new Array();//arreglo de marcadores de cuartel de carabineros
var marcadoresBomberos=new Array();//arreglo de marcadores de cuarteles de bomberos
var marcadoresGrifos= new Array();// arreglo de marcadores de grifos
var marcadoresHospitales= new Array();//arreglo de marcadores de hospitales
var grupoCarabineros=L.layerGroup().addTo(map);//capa de cuarteles de carabineros
var grupoBomberos=L.layerGroup().addTo(map);//capa de cuarteles de bomberos
var grupoGrifos=L.layerGroup().addTo(map);//capa de marcadores de gridos
var grupoHospitales=L.layerGroup().addTo(map);//capa de marcadores Hospitales
var contadorCarabineros=0;//contadores para la inicalizacion de marcadores de recursos estaticos
var contadorBomberos=0;
var contadorGrifos=0;
var contadorHospitales=0;//fin contadores
////Dinamicos
var marcadoresBomberosDin=new Array();//arreglo de marcadores de recursos dinámicos carros bomba, bomberos, comandantes de incidente, bomberos F/S y lideres de equipo
var marcadoresComandanteIn=new Array();
var marcadoresCarros=new Array();
var marcadoresConductores=new Array();
var marcadoresLideres=new Array();
var marcadoresBomberosFS=new Array();//fin arreglos marcadores de recursos dinámicos
var grupoComandantes=L.layerGroup().addTo(map);//estas son las capas que corresponden a los recursos dinámicos
var grupoBomberosDin=L.layerGroup().addTo(map);
var grupoCarros=L.layerGroup().addTo(map);
var grupoConductores=L.layerGroup().addTo(map);
var grupoLideres=L.layerGroup().addTo(map);
var grupoBomberosFS=L.layerGroup().addTo(map);
//////
var grupoEmergencia=L.layerGroup().addTo(map);//capa de emergencias
var grupoDinamico=L.layerGroup().addTo(map);//capa de recursos dinamicos
var overlay = { 'Emergencias': grupoEmergencia,
                //'Estáticos':grupoEstatico,
                'Cuartel Carabineros':grupoCarabineros,
                'Cuartel Bomberos':grupoBomberos,
                'Grifos': grupoGrifos,
                'Hospitales':grupoHospitales,
                'Comandantes':grupoComandantes,
                'Bomberos': grupoBomberosDin,
                'Bomberos F/S':grupoBomberosFS,
                'Líderes Eq':grupoLideres,
                'Conductores':grupoConductores,
                'Carros Bomba':grupoCarros,
                };
 L.control.layers(null, overlay).addTo(map);// este es el menú gestor de capas

function centrarMapa(e){
            /*Esta funcionalidad permite centrar el mapa al seleccionar los marcadores situados en él*/    
                
                // e.target.dragging.enable();
                //console.log(e.layer.feature.properties.title);
                if($('#menuizquierdo').width()>100){
                    /*La referencia del centrado cambiará dependiendo del acho de #menuizquierdo*/
                    var vista=$('#map').width()/4; 
                    var altura=$('#map').height()/2; 
                    map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng),15);
                    map.panBy([-vista,0]);}
                else{
                    map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng),15);
                    map.panBy([-vista*2,altura]);
                }

}
function moverMarker(e){
    /* Esta funcionalidad permite modificar la ubicación de un marcador en el mapa, es decir, habilita el drag and drop del ícono*/
     var marker = e.target;  
    var result = marker.getLatLng();//se habilita el drag and drop del marcador seleccionado en el evento
    marker.dragging.enable();
    console.log("enable");
}
function soltarMarker(e){
    
/*esta funcion se encarga de llamar a la función de almacenar la nueva ubicación o no modificarla*/    
    var marker=e.target;
        marker.closePopup();
        marker.dragging.disable();
    var ubicacion=ubicacionActual(marker._myId);//se recuperan los valores de latitud y longitud de la base de datos en caso de no querer modifiar la ubicación
    var latitud=marker.getLatLng().lat;
    var longitud=marker.getLatLng().lng;
    var nuevaUbicacion={latitud:latitud,longitud:longitud};
    //console.log("latitud"+ubicacion.latitud+"longitud"+ubicacion.longitud);
    //console.log("latitud"+latitud+"longitud"+longitud);
    //console.log("disabled Id "+marker._myId+"latlng"+marker.getLatLng());
        if (confirm("¿Está seguro de esta nueva ubicación?") == true) {//si el if es true se almacena la nueva ubicación
            actualizarUbicacion(nuevaUbicacion,marker._myId);
            //alert("Ubicación Actualizada");
        } else {//sino se conserva la ubicación original
            marker.setLatLng([ubicacion.latitud,ubicacion.longitud]).update();
        }
    
    
    
}
function actualizarUbicacion(ubicacion,Id){
/*esta funcion se encarga de almacenar la nueva ubicación de los marcadores*/    
    
   var latitud=ubicacion.latitud;
   var longitud=ubicacion.longitud;
  // console.log("latitud"+ubicacion.latitud+"longitud"+ubicacion.longitud); 
    $.ajax({
        url:'actualizarUbicacion.php',
        data:{id_entrada:Id,latitud:latitud,longitud:longitud},
        method:'POST',
        dataType:'json',
        async:false,
        success:function(data){
           
            
        },
        error:function (xhr, ajaxOptions, thrownError) {
        alert("error nueva ubicacion"+xhr.status+""+thrownError);
        
        }
        
    });
    
    
}
function ubicacionActual(id){
    /*Esta funcion permite consultar la posicion inicial
    de un  marcador en caso de no querer moficiar su ubicación, sirve de respaldo*/
 console.log(id);   
var latitud=0;
var longitud=0;
    $.ajax({
        url:'ubicacionActual.php',
        data:{id_entrada:id},
        method:'POST',
        dataType:'json',
        async:false,
        success:function(data){
            for(var i in data){
            latitud=data[i].latitude;
            longitud=data[i].longitude;
                
            }
            
        },
        error:function (xhr, ajaxOptions, thrownError) {
        alert("error ubicacion actual"+xhr.status+""+thrownError);
        
        }
        
    });
  ubicacion={latitud:latitud,longitud:longitud}; 
    //console.log("latitud"+ubicacion.latitud+"longitud"+ubicacion.longitud);
  return ubicacion;
}

//esta funcion inicializa los puntos de interes ya almacenados en la base de datos a traves de tres
//llamadas de AJAX, donde cada una inicializa cada tipo de punto de interes, los estáticos, los dinámicos y las emergencias


function inicializarMapa(){

$.ajax({                                      
      url: 'consulta4.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',
    
      success: function(data)          
      {   
        for(var i in data)
        {   
            
            var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Prioridad: '+data[i].priority+'</b><p>Id emergencia:'+data[i].id_emergency+'</p></div><div class="contenedor-btn"><button class="emg" value="'+data[i].id_emergency+'">Modificar</button><button class="reporte" value="'+data[i].id_emergency+'">Reporte</button><button class="asistida" value="'+data[i].id_emergency+'">Finalizar</button></div><div class="contenedor-emg"><form class="formamin-emg">Dirección:<input type="text" id="direccion-emg-min" maxlength="50">Prioridad<select id="prioridad-emg-min"></select>Tipo punto interés<select id="tipo-pto-interes-emg-min"></select><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
            
            
            var LeafIcon = L.Icon.extend({//se define la estructura del icono que representa al punto
                options: {

                    iconSize:     [50, 50],

                    iconAnchor:   [25,50],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon(Estructura de leaflet Js)
            
               
                
               var greenIcon = new LeafIcon({iconUrl: data[i].imagen});//variable utilizada en Leaficon
            
                marcadoresEmergencia[i]=L.marker([data[i].latitude,data[i].longitude], {icon: greenIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
             
            //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresEmergencia[i]._myId=data[i].id_emergency;
            //console.log(""+marcadoresEmergencia[i]._myId);
            
            //Se asigna el siguiente callback sobre el marcador una vez que se ha creado.Se realiza de esta forma ya que si se inicializaran todos y luego se "asigna" este evento, solo funcionaria con el ultimo marcador creado".
                
            
                //este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
                marcadoresEmergencia[i].on('click',centrarMapa);
                //marcadoresEmergencia[i].dragging.enable();
                marcadoresEmergencia[i].on('mouseover',moverMarker);
                marcadoresEmergencia[i].on('dragend',soltarMarker);
               grupoEmergencia.addLayer(marcadoresEmergencia[i]);//se añaden uno a uno los marcadores al grupo, esto es identico con las emergencias y los recursos estaticos
                
          }  
      //contadorEMG=marcadoresEmergencia.length;
      }
    
 });//Fin ajax emergencias
//////////////////////////////////////////////////////

$.ajax({                                      
      url: 'consulta5.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      {     var g=0;
            var b=0;    
            var c=0;
            var h=0;
        for(var i in data)
        {     
            var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+data[i].static_resource_name+'</b><p id="'+data[i].address+'">Dirección:'+data[i].address+'</p></div><div class="contenedor-btn"><button  class="res" value="'+data[i].static_resource_name+'">Modificar</button><button id="borrar-re" value="'+data[i].static_resource_name+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
          
                
               var LeafIcon = L.Icon.extend({//se define la estructura del icono
                options: {

                    iconSize:     [12,12],

                    iconAnchor:   [5,5],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon
            
               
                
               var blueIcon = new LeafIcon({iconUrl: data[i].imagen});//variable utilizada en leaficon
            
         if(data[i].tipo_pto_interes==1){//if para grifos
                marcadoresGrifos[g]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresGrifos[g]._myId=data[i].static_resource_name;
                
                marcadoresGrifos[g].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
                marcadoresGrifos[g].on('mouseover',moverMarker);
                marcadoresGrifos[g].on('dragend',soltarMarker);
               grupoGrifos.addLayer(marcadoresGrifos[g]);//se añaden uno a uno los marcadores al grupo
                g++;
         }//fin Grifos         
         if(data[i].tipo_pto_interes==2){//if para bomberos
                marcadoresBomberos[b]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresBomberos[b]._myId=data[i].static_resource_name;
                //console.log(''+marcadoresBomberos[i]._myId);
         
                marcadoresBomberos[b].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
                marcadoresBomberos[b].on('mouseover',moverMarker);
                marcadoresBomberos[b].on('dragend',soltarMarker);
               grupoBomberos.addLayer(marcadoresBomberos[b]);//se añaden uno a uno los marcadores al grupo
             b++;
         }
         if(data[i].tipo_pto_interes==3){//if para Caraineros
                marcadoresCarabineros[c]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresCarabineros[c]._myId=data[i].static_resource_name;
            
         
                marcadoresCarabineros[c].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
                marcadoresCarabineros[c].on('mouseover',moverMarker);
                marcadoresCarabineros[c].on('dragend',soltarMarker);
               grupoCarabineros.addLayer(marcadoresCarabineros[c]);//se añaden uno a uno los marcadores al grupo
                c++;
         }
         if(data[i].tipo_pto_interes==4){//if para Hospitales
                marcadoresHospitales[h]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresHospitales[h]._myId=data[i].static_resource_name;
            
         
                marcadoresHospitales[h].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
                marcadoresHospitales[h].on('mouseover',moverMarker);
                marcadoresHospitales[h].on('dragend',soltarMarker);
               grupoHospitales.addLayer(marcadoresHospitales[h]);//se añaden uno a uno los marcadores al grupo
                h++;
         } 
          }
            
      }
});/////FIN AJAX RECURSOS ESTATICOS

$.ajax({                                      
      url: 'consultaRD.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      {     var c=0;
            var b=0;//ESTOS SON LOS CONTADORES QUE RECORREN LOS ARREGLOS DE LOS MARCADORES DE LOS RECURSOS DINAMICOS
            var noCa=0;
            var le=0;
            var bfs=0;
            var ccam=0;
            var latRD;
            var lngRD;
        for(var i in data)//EL CONTADOR i RECORRE EL ARREGLO GENERAL, ES DECIR, EL REGISTRO COMPLETO DE LA CONSULTA A LA BASE DE DATOS
        {   //console.log(''+data[i].tipo_pto_interes); 
            latRD=data[i].latitude;
            lngRD=data[i].longitude;
        
            if(latRD==null || lngRD==null){
                data[i].latitude=0;
                data[i].longitude=0;
            
            }else{
                var contenedorPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Nombre:'+data[i].dynamic_resource_name+'</b><p id="'+data[i].id_device+'">Id dispositivo:'+data[i].id_device+'</p></div><div class="contenedor-btn"><button class="rd" value="'+data[i].dynamic_resource_name+'">Modificar</button><button id="borrar-rd">Eliminar</button></div><div class="contenedor-rd"><form class="formamin-rd">Id dispositivo<input type="text" id="id-dispositivo-rd-min" maxlength="16" autocomplete="off"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
                
                var LeafIcon = L.Icon.extend({//se define la estructura del icono
                    options: {

                        iconSize:     [20,32],

                        iconAnchor:   [18,35],
                        popupAnchor:  [-6, -25]
                    }
                    });


                L.icon = function (options) {
                    return new L.Icon(options);
                };//se añade una nueva propiedad a leaficon
            
               
                
               var yellowIcon = new LeafIcon({iconUrl: data[i].imagen});//variable utilizada en leaficon
                
               
            if(data[i].tipo_pto_interes==5){//para carros bomba
                marcadoresCarros[noCa]=L.marker([data[i].latitude,data[i].longitude], {icon: yellowIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
                marcadoresCarros[noCa]._myId=data[i].dynamic_resource_name;
                
                marcadoresCarros[noCa].on('click',centrarMapa);
               grupoCarros.addLayer(marcadoresCarros[noCa]);//se añaden uno a uno los marcadores al grupo
                noCa++;
                    }
                
            if(data[i].tipo_pto_interes==6){//para bomeros
                marcadoresBomberosDin[b]=L.marker([data[i].latitude,data[i].longitude], {icon: yellowIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
                marcadoresBomberosDin[b]._myId=data[i].dynamic_resource_name;
                marcadoresBomberosDin[b].on('click',centrarMapa);
               grupoBomberosDin.addLayer(marcadoresBomberosDin[b]);//se añaden uno a uno los marcadores al grupo
                b++
                    }    
             if(data[i].tipo_pto_interes==7){//para comandantes
                marcadoresComandanteIn[c]=L.marker([data[i].latitude,data[i].longitude], {icon: yellowIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
                marcadoresComandanteIn[c]._myId=data[i].dynamic_resource_name;
                marcadoresComandanteIn[c].on('click',centrarMapa);
               grupoComandantes.addLayer(marcadoresComandanteIn[c]);//se añaden uno a uno los marcadores al grupo
                c++
                    }
                if(data[i].tipo_pto_interes==8){//para comandantes
                marcadoresConductores[ccam]=L.marker([data[i].latitude,data[i].longitude], {icon: yellowIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
                marcadoresConductores[ccam]._myId=data[i].dynamic_resource_name;
                marcadoresConductores[ccam].on('click',centrarMapa);
               grupoConductores.addLayer(marcadoresConductores[ccam]);//se añaden uno a uno los marcadores al grupo
                ccam++
                    }
                if(data[i].tipo_pto_interes==9){//para comandantes
                marcadoresBomberosFS[bfs]=L.marker([data[i].latitude,data[i].longitude], {icon: yellowIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
                marcadoresBomberosFS[bfs]._myId=data[i].dynamic_resource_name;
                marcadoresBomberosFS[bfs].on('click',centrarMapa);
               grupoBomberosFS.addLayer(marcadoresBomberosFS[bfs]);//se añaden uno a uno los marcadores al grupo
                bfs++
                    } 
                if(data[i].tipo_pto_interes==10){//para comandantes
                marcadoresLideres[le]=L.marker([data[i].latitude,data[i].longitude], {icon: yellowIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
                marcadoresLideres[le]._myId=data[i].dynamic_resource_name;
                marcadoresLideres[le].on('click',centrarMapa);
               grupoLideres.addLayer(marcadoresLideres[le]);//se añaden uno a uno los marcadores al grupo
                le++
                    }   
                
            }//Fin else
        
        }
              
      },error:function(jqXHR, textStatus, errorThrown){
            alert("Error, status = " + textStatus + ", " +
                  "error thrown: " + errorThrown
            );
            }
});//fin recurso dinamico    

   
        
}//Fin inicializar mapa
function borrarTodo(){
    /*Esta funcionalidad borra todos los marcadores del sistema web, no de la base de datos. 
    Es utilizada para actualizar la vista del sistema*/
 for(var i=0;i<marcadoresEmergencia.length;i++){
        grupoEmergencia.removeLayer(marcadoresEmergencia[i]);    
        contadorEMG=0;    

    }for(var g=0;g<marcadoresGrifos.length;g++){
            grupoGrifos.removeLayer(marcadoresGrifos[g]);    
            contadorGrifos=0;          
        }for(var b=0;b<marcadoresBomberos.length;b++){
              grupoBomberos.removeLayer(marcadoresBomberos[b]);    
              contadorBomberos=0;          
            }for(var c=0;c<marcadoresCarabineros.length;c++){
                grupoCarabineros.removeLayer(marcadoresCarabineros[c]);    
                contadorCarabineros=0;          
                }for(var h=0;h<marcadoresHospitales.length;h++){
                    grupoHospitales.removeLayer(marcadoresHospitales[h]);    
                    contadorHospitales=0;          
                    }for(var bomberoDin=0;bomberoDin<marcadoresBomberosDin.length;bomberoDin++){
                        grupoBomberosDin.removeLayer(marcadoresBomberosDin[bomberoDin]);    
                        //contador=0;          
                        }for(var comandante=0;comandante<marcadoresComandanteIn.length;comandante++){
                                grupoComandantes.removeLayer(marcadoresComandanteIn[comandante]);    
                                //contador=0;          
                            }for(var carro=0;carro<marcadoresCarros.length;carro++){
                                grupoCarros.removeLayer(marcadoresCarros[carro]);    
                                //contador=0;          
                                }for(var conductores=0;conductores<marcadoresConductores.length;conductores++){
                                    grupoConductores.removeLayer(marcadoresConductores[conductores]);    
                                    //contador=0;          
                                    }for(var lideres=0;lideres<marcadoresLideres.length;lideres++){
                                        grupoLideres.removeLayer(marcadoresLideres[lideres]);    
                                        //contador=0;          
                                        }for(var bomberosfs=0;bomberosfs<marcadoresBomberosFS.length;bomberosfs++){
                                            grupoBomberosFS.removeLayer(marcadoresBomberosFS[bomberosfs]);    
                                            //contador=0;          
                                            }       
}

function ActualizarVista(){
    /*console.log("registros de emg"+marcadoresEmergencia.length);
    console.log("registros de grifos"+marcadoresGrifos.length);
    console.log("registros de cuarteles bomberos"+marcadoresBomberos.length);
    console.log("registros de cuarteles carabineros"+marcadoresCarabineros.length);
    console.log("registros de hospitales"+marcadoresHospitales.length);*/ 
        borrarTodo();//se eliminan todos los marcadores
        inicializarMapa();//se reinicializa el mapa
        console.log("registros de emg"+marcadoresEmergencia.length);        
        }
$(document).on('click','.btn-6',ActualizarVista);//al presionar el boton 6 se llama a la funcion ActualizarVista

function RespaldosEmg(){
    /*Esta es la función que se encarga de respaldar imagenes y reportes del sistema*/
$.ajax({
url:'respaldos.php',
data:{},
method:'POST',
datatype:'html',
beforeSend:function(){
    
    $("#loading-contenedor").show();//se muestra este contenedor mientras se realiza la consulta a la base de datos
},
success:function(){
    
    

    $("#espere").hide();//una vez finalizada la cosulta se despliega esto
    $("#listo").show();
    
},
error:function(jqXHR, textStatus, errorThrown){
            alert("Error, status = " + textStatus + ", " +
                  "error thrown: " + errorThrown
            );
            }
});

    setTimeout(function(){
   
    $("#espere").show();//despues de unos segundos el mensaje se oculta
    $("#listo").hide();
     $("#loading-contenedor").hide();
},4000);
    

}
$('.btn-4').click(function(){

  RespaldosEmg();  //se llama la función de respaldado al presional el botón 4

    //console.log(''+marcadoresBomberos[0]._myId);

});//

function salirApp(){
    /*Esta función permite cerrar la sesi+on del usuario*/
$.ajax({
url:'cerrar_sesion.php',
data:{},
method:'POST',
datatype:'html',
success:function(){
    alert("Sesión terminada");
    window.location.href='index.php';
},
error:function(jqXHR, textStatus, errorThrown){
            alert("Error, status = " + textStatus + ", " +
                  "error thrown: " + errorThrown
            );
            }
});

}
$('.btn-7').click(salirApp);


//Muestra el modal de modificacion de usuarios
$('.btn-5').click(function(event){
if(event.target==this){
    $('.modal').hide();
    $('#MODAL2').hide();
    $('#MODAL3').hide();
    MenuLateralIzq();
    $('#modal-checkbox').hide();
    $('.modal-err').show();
    $('#contenedor-modificar').fadeIn();
    $('.btn-1, .btn-2, .btn-3').prop("disabled",true);
}
});
var cerrar=0;//iterador que ayuda a abrir o cierrar el buscador

function cerrarSearch(){//funcion utlizada para esconder o mostrar el buscador
            if(cerrar==0){
                $('#search').val("").slideUp();
                $('#cerrar-search').text(">");
                cerrar=1;
            }else{
            $('#search').val("").slideDown();
                cerrar=0;
                $('#cerrar-search').text("X");
            }


}
$('#cerrar-search').off().on('click',cerrarSearch);//Callback de cerrarSearch
$('#search').on('mousedown',function(e){e.stopPropagation(); });//Este evento evita que al usar click dentro del input se haga un Drag&drop sobre el mapa del sistema

//Esta funcion permite ocultar el menu izquierdo del sistema
function OcultarMenuIzq(){
            //$('#map').css({'width':'95%'});
            $('.menuizquierdo').css({'width':'68.3px'});
            $('.boton').css({'display':'block' });
            $('.botones').css({'margin':'0 '});
            $('.controles').css({'margin':'0'});
            $('.btn-5').css("background-color","#FCFCFC");
            $('.boton').css({'margin':'auto','margin-top':'15px','margin-left':'9px'});
        if(tipo_usuario=='Operador')
            $('.controles').css({'margin-top':'60vh'});
        if(tipo_usuario=='Administrador')
            $('.controles').css({'margin-top':'30vh'});
}

//Funcion que permite esconder el modal de recurso estatico   
function salirModalRecursoEstatico(){

        $('.modal').hide();
        $('#MODAL2').hide();
        $('#MODAL3').hide();
        $('.btn-2').prop('disabled', false);
        $('.btn-3').prop('disabled', false);
        document.getElementById("forma-recurso").reset();
        OcultarMenuIzq();
    
        return false;
}
//Funcion que permite esconder el modal de recurso dinamico
function salirModalRecursoDinamico(){
        $('#MODAL3').hide();
        $('#MODAL2').hide();
        $('.modal').hide();
        $('.btn-1').prop('disabled', false);
        $('.btn-3').prop('disabled', false);
        $('#cuartel-rd option').remove();
        document.getElementById("forma-recurso-dinamico").reset();
        OcultarMenuIzq();
        return false;

}
//Funcion que permite esconder el modal de emergencias 
function salirModalEmergencia(){
        $('#MODAL2').hide();
        $('.modal').hide();
        $('#MODAL3').hide();
        $('.btn-1').prop('disabled', false);
        $('.btn-2').prop('disabled', false);
        document.getElementById("forma-emergencia").reset();
        OcultarMenuIzq();
        return false;

}

function esconderModalOcultar(){
$('#modal-checkbox').fadeOut();
}//MENU DONDE APARECEN LOS CHECKBOX//AUN SIN USO



var boton1=false;//cambio de color dependiendo del boton seleccionado en el menu lateral
var boton2=false;//ESTOS VALORES SON PARA CONTROLAR LA APARICION DE LOS MODALS CUANDO SE REALICE DOBLE CLICK EN EL MAPA
var boton3=false;//ESTO ES VISIBLE EN LA FUNCION  <<mostrarModal>>
var boton5=false;//EN EL BOTON 5 ESTE VALOR ES ALGO INNECESARIO 

    //Los valores de los botones solamente son para controlar la aparicion de modals y al mismo tiempo cambiar sus colores al ser seleccionados por el usuario dependiendo de lo que desee insertar en el mapa.

/*inicio de los botones, estos eventos controlan los colores de los botones al ser seleccionados
    En el caso de los botones 1, 2 y 3, tambien son utilizados para verificar que botón ha sido selccionado para posteriormente
    determinar si se quiere añadir un recurso estático, dinpámico o emergencia.*/
    $('.btn-1').click(function(){
    boton1=true;
        $(this).css("background-color","#CACACA");
        $('.btn-2').css("background-color","#FCFCFC");
        $('.btn-3').css("background-color","#FCFCFC");
        $('.btn-5').css("background-color","#FCFCFC");
    boton2=false;
    boton3=false;
    });

    $('.btn-2').click(function(){
    boton2=true;
    $(this).css("background-color","#CACACA");
    $('.btn-1').css("background-color","#FCFCFC");
    $('.btn-3').css("background-color","#FCFCFC");
    $('.btn-5').css("background-color","#FCFCFC");
    boton1=false;
    boton3=false;
        //añadir funcion de recurso dinámico
    });

    $('.btn-3').click(function(){
    boton3=true;
    $(this).css("background-color","#CACACA");
    $('.btn-1').css("background-color","#FCFCFC");
    $('.btn-2').css("background-color","#FCFCFC");
    $('.btn-5').css("background-color","#FCFCFC");
    boton1=false;
    boton2=false;
    });

    $('.btn-5').click(function(){
    boton5=true;
    $(this).css("background-color","#CACACA");
    $('.btn-1').css("background-color","#FCFCFC");
    $('.btn-2').css("background-color","#FCFCFC");
    $('.btn-3').css("background-color","#FCFCFC");
    boton1=false;
    boton2=false;
    boton3=false;
    });
$('.btn-1,.btn-2,.btn-3,.btn-4,.btn-5,.btn-6,.btn-7').on('mousedown',function(){
    $(this).css("background-color","#CACACA");
}
                                                         
 );
$('.btn-1,.btn-2,.btn-3,.btn-4,.btn-5,.btn-6,.btn-7').on('mouseup',function(){
    $(this).css("background-color","#FCFCFC");
}
                                                         
 );//fin de los botones

//Esta funcion expande el menu izquierdo para poder realizar la aparicion de los modals de cada recurso
function MenuLateralIzq(){
    //$('#map').css({'width':'766px'});
    $('.menuizquierdo').css({'width':'600px'});
    $('.boton').css({'display':'inline-block' });
    $('.botones').css({'margin':'0 auto'});
    $('.controles').css({'margin':'0 auto'});

}

function estacionesBomberosAjax(){
    /*Esta funcion actualiza la lista de cuarteles de bomberos, 
    útil para cuando se agregan nuevos recursos dinámicos a nuevos cuarteles de bomberos añadidos*/
$.ajax({                                      
      url: 'cuartelesBomberos.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(dato)          
      {
          for(var i in dato){
              

              
              $('#cuartel-rd').append("<option value="+dato[i].fire_station_name+">"+dato[i].fire_station_name+"</option>");
              
                
          }
          
      
      }
        });///FIN AJAX 



}




//Funcion que muestra los modals una vez que el menu izquierdo ha sido expandido
function mostrarModal(e){
   //Se captura la ubicacion realizada por el doble click y luego se almacena como arreglo asociativo o javascript object para luego ser retornado y utilizado en un una llamada AJAX.
    var latitud= e.latlng.lat;
    var longitud= e.latlng.lng;
    var latlng={latitud:latitud, longitud:longitud};
    //alert(latitud+','+longitud);   
    $('.latlng').val('['+latitud.toFixed(7)+","+longitud.toFixed(7)+']');//Se asigna el valor de la ubicacion en los inputs de los modals, su aparicion dependera del boton seleccionado en el menu izquierdo.
    $('.latlng-din').val('['+latitud.toFixed(7)+","+longitud.toFixed(7)+']');
    $('.latlng-emg').val('['+latitud.toFixed(7)+","+longitud.toFixed(7)+']');
    
//En los proximos 3 if, se habilitan o inhabilitan todos los botones menos el que fue seleccionado.    
if(boton1==true){
    $('.modal').fadeIn();
    $('.btn-2').prop('disabled', true);
    $('.btn-3').prop('disabled', true);
    MenuLateralIzq();
   
}
if(boton2==true){
    $('#MODAL2').fadeIn();
    $('.btn-1').prop('disabled', true);
    $('.btn-3').prop('disabled', true);
    estacionesBomberosAjax();
    //aqui va el ajax de las estaciones de bomberos
    MenuLateralIzq();
   
}
if(boton3==true){
    $('#MODAL3').fadeIn();
    $('.btn-1').prop('disabled', true);
    $('.btn-2').prop('disabled', true);
   
    MenuLateralIzq();//Se llama a la funcion de expandir el menu izquierdo
    
}
if(boton1==true ||boton3==true){
    
    
   setTimeout(
     //funcion del popup generado al seleccionar una ubicacion para un nuevo punto de interes
        function (){
                 var centerPoint = map.getCenter();
                 var holi=map.getSize();
                     map.setView(new L.LatLng(e.latlng.lat, e.latlng.lng),15);
                     map.panBy([-308,0]);
                var marcador=L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
                    marcador.bindPopup('<div id="contenedor-marker-input"><p>El nuevo punto se situará aquí</p><button id="btn-marker-input">Aceptar</buton></div>',{className:'popup'});
                    marcador.openPopup();
                    $('#map').on('click','#btn-marker-input',function(e){map.removeLayer(marcador);});
                 //marcador.on('popupclose',function(){map.removeLayer(marcador);});
                    $('#forma-recurso input,#forma-recurso-dinamico input,#forma-emergencia input').on('focus',function(){
                            map.removeLayer(marcador);
                    });

        }
    ,700);
}
    return latlng;//Se retorna la ubicacion

    
}


inicializarMapa();//se inicializa el mapa




var count=0;
var count2=0;//ESTOS SON CONTADORES QUE APARECEN EN LA CONSOLA DE CHROME, SON UTILIZADOS PARA VERIFICAR QUE LOS SUBMIT SE DISPAREN UNA SOLA VEZ
var count3=0;
var contadorEnter=0;

//esta funcionalidad únicamente oculta el menú lateral expandido si se realiza un click en el mapa
map.on('click',function ocultarMenusLaterales(){
        salirModalEmergencia();
        salirModalRecursoEstatico();
        salirModalRecursoDinamico();
        OcultarMenuIzq();
        $('#lista-emergencias ul li').remove();
            $('#lista-emergencias ul hr').remove();
            $('#lista-emergencias').hide();
        esconderModalOcultar();
});
//Esta función permite añadir recursos o emergencias en el sistema
map.on('dblclick',function insertarPuntosInteresMouse(e){//ingresar recursos a traves de modals
    contadorEMG=marcadoresEmergencia.length;
    contadorGrifos=marcadoresGrifos.length;
    contadorBomberos=marcadoresBomberos.length;
    contadorCarabineros=marcadoresCarabineros.length;//contadores para llevar el control de los marcadores
    contadorHospitales=marcadoresHospitales.length;
    console.log('emergencias'+contadorEMG);
    
   
    $('.btn-salir').on('click',salirModalRecursoEstatico);
    $('.btn-salir-din').off().on('click',salirModalRecursoDinamico);
    $('.btn-salir-emg').off().on('click',salirModalEmergencia);//eventos de mouse al seleccionar salir de cada uno de los menús.
    

       var coordenada= mostrarModal(e);    
        /*var latitud= e.latlng.lat;
        var longitud= e.latlng.lng;
        var coordenada={latitud:latitud, longitud:longitud};*/
    //alert(coordenada.latitud+''+coordenada.longitud); 
    //estas lineas sirven para probar el valor de las coordenadas obtenidas en mostrarModal


        console.log(coordenada.latitud+''+coordenada.longitud);

        

/*DESDE ESTE PUNTO SE EJECUTA LA FUNCION DE AÑADIR RECURSOS ESTATICOS*/
        $('#forma-recurso').off().on('submit',coordenada,function(event){
        event.preventDefault();
        count++;
        var nombre_recurso=$('.nombre-recurso').val();
        var direccion_recurso=$('.direccion-recurso').val();
        var descripcion_recurso=$('.descripcion-recurso').val();
        var tipo_pto_interes=$('.tipo-pto-interes').val();
        console.log(coordenada.latitud+''+coordenada.longitud+'count'+count);
        if(nombre_recurso=="" || direccion_recurso==""){
            alert('Faltan campos por llenar');
        }else{
       $.post('ingreso_re.php',{nombre_recurso:nombre_recurso,
                            direccion_recurso:direccion_recurso,
                           descripcion_recurso:descripcion_recurso,
                           tipo_pto_interes:tipo_pto_interes,
                           latitud:coordenada.latitud,
                           longitud:coordenada.longitud },function (data){

            if(data[0].answer==1){
        alert('el recurso ya existe');


        }else if(data[0].answer==2){
            alert('dirección utilizada en otro recurso estático');
            
        }
            else if(data[0].answer==0){
                   var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+data[0].nombre_recurso+'</b><p id="'+direccion_recurso+'">Dirección:'+direccion_recurso+'</p></div><div class="contenedor-btn"><button  class="res" value="'+data[0].nombre_recurso+'">Modificar</button><button id="borrar-re" value="'+data[0].nombre_recurso+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
            
                   var LeafIcon = L.Icon.extend({//se define la estructura del icono
                    options: {

                        iconSize:     [12,12],

                        iconAnchor:   [5,5],
                        popupAnchor:  [0, -25]
                    }
                    });


                L.icon = function (options) {
                    return new L.Icon(options);
                };//se añade una nueva propiedad a leaficon



                   var blueIcon = new LeafIcon({iconUrl: data[0].imagen_recurso});//variable utilizada en leaficon

            if(tipo_pto_interes==1){//para grifos
                
                    marcadoresGrifos[contadorGrifos]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenidoPopup,{
                    closeButton: true,
                       minWidth: 300,
                        maxwidth:300
                    }).addTo(map);
                    marcadoresGrifos[contadorGrifos]._myId=data[0].nombre_recurso;
                    marcadoresGrifos[contadorGrifos].on('click',centrarMapa);
                    marcadoresGrifos[contadorGrifos].on('mouseover',moverMarker);
                    marcadoresGrifos[contadorGrifos].on('dragend',soltarMarker);
                   grupoGrifos.addLayer(marcadoresGrifos[contadorGrifos]);
                }
            if(tipo_pto_interes==2){//para bomberos
                
                    marcadoresBomberos[contadorBomberos]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenidoPopup,{
                    closeButton: true,
                       minWidth: 300,
                        maxwidth:300
                    }).addTo(map);
                    marcadoresBomberos[contadorBomberos]._myId=data[0].nombre_recurso;
                    marcadoresBomberos[contadorBomberos].on('click',centrarMapa);
                    marcadoresBomberos[contadorBomberos].on('mouseover',moverMarker);
                    marcadoresBomberos[contadorBomberos].on('dragend',soltarMarker);
                   grupoBomberos.addLayer(marcadoresBomberos[contadorBomberos]);
                }
            if(tipo_pto_interes==3){//para carabineros
                
                    marcadoresCarabineros[contadorCarabineros]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenidoPopup,{
                    closeButton: true,
                       minWidth: 300,
                        maxwidth:300
                    }).addTo(map);
                    marcadoresCarabineros[contadorCarabineros]._myId=data[0].nombre_recurso;
                    marcadoresCarabineros[contadorCarabineros].on('click',centrarMapa);
                    marcadoresCarabineros[contadorCarabineros].on('mouseover',moverMarker);
                    marcadoresCarabineros[contadorCarabineros].on('dragend',soltarMarker);
                   grupoCarabineros.addLayer(marcadoresCarabineros[contadorCarabineros]);
                }
            if(tipo_pto_interes==4){//para Hospitales
                
                    marcadoresHospitales[contadorHospitales]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenidoPopup,{
                    closeButton: true,
                       minWidth: 300,
                        maxwidth:300
                    }).addTo(map);
                    marcadoresHospitales[contadorHospitales]._myId=data[0].nombre_recurso;
                    marcadoresHospitales[contadorHospitales].on('click',centrarMapa);
                    marcadoresHospitales[contadorHospitales].on('mouseover',moverMarker);
                    marcadoresHospitales[contadorHospitales].on('dragend',soltarMarker);
                   grupoHospitales.addLayer(marcadoresHospitales[contadorHospitales]);
                }
            
        salirModalRecursoEstatico();
        alert('el recurso se almacenó');

        }
     },"json").fail(function(jqXHR, textStatus, errorThrown){
            alert("Error, status = " + textStatus + ", " +
                  "error thrown: " + errorThrown
            );
            });   





        }
    });//FIN FORMULARIO RECURSO ESTATICO,FINALIZA FUNCIÓN DE INGRESO DE RECURSOS ESTÁTICOS


    /*AQUÍ SE OCULTA O SE MUESTRA EL CAMPO DE RUT SI 
    ES QUE EL RECURSO DINÁMICO A ALMACENAR ES UN VEHÍCULO O UNA PERSONA*/
    
    
    $('.tipo-pto-interes-din').on('change',function(){
            if($('.tipo-pto-interes-din option:selected').text()=="Carro Bomba")
            {
                $('.run-id-disp').hide();
            }
            else{
                $('.run-id-disp').show();
                }
    });
    if($('.tipo-pto-interes-din option:selected').text()=="Carro Bomba")
    {
             $('.run-id-disp').hide();
    }//AQUÍ TAMBIÉN SE OCULTA EL CAMPO RUT, PERO AL MOMENTO DE APARECEL EL FORMULARIO
    
    /*DESDE ESTE PUNTO SE PUEDE AÑADIR UN NUEVO RECURSO DINÁMICO*/
    $('#forma-recurso-dinamico').off().on('submit',coordenada,function(event){
        event.preventDefault();
        count2++;
        var nombre_recurso_din=$('.nombre-recurso-din').val();
        var id_dispositivo=$('.id-dispositivo-din').val();
        var tipo_pto_interes=$('.tipo-pto-interes-din').val();
        var rut=$('#run-id-disp').val();
        var cuartel=$("#cuartel-rd option:selected").text();
        console.log($("#cuartel-rd option:selected").text());
        var length = Math.log(rut) * Math.LOG10E + 1 | 0;
        console.log(''+nombre_recurso_din+', '+id_dispositivo+', '+rut+', '+cuartel+', '+tipo_pto_interes+', count'+count2);
        if(nombre_recurso_din=="" || id_dispositivo=="")
        {  
            alert('Faltan campos por llenar');
        }
        
        else
        {
       $.post('ingreso_rd.php',{nombre_recurso_din:nombre_recurso_din,
                            id_dispositivo:id_dispositivo,
                           tipo_pto_interes:tipo_pto_interes,
                            rut:rut,
                            cuartel:cuartel
                           /*latitud:coordenada.latitud,
                           longitud:coordenada.longitud*/ },function (data){

           // coordenada.latitud="";
            //coordenada.longitud="";
        if(data[0].answer==1){
            alert('el recurso ya existe');


        }
        if(data[0].answer==2)
        {
            alert('el dispositivo ya existe');
            
        }
        if(data[0].answer==3)
        {
            alert('Debe ingresar un rut para el bombero');
            
        }
        if(data[0].answer==4)
        {
            alert('El rut ingresado ya existe');
            
        }if(data[0].answer==5)
        {
            alert('Rut ingresado incompleto o inválido');
            
        }
        else if(data[0].answer==0){

                        contadorRD++;
        salirModalRecursoDinamico();
        alert('el recurso se almacenó');

        }
     },"json").fail(function(jqXHR, textStatus, errorThrown){
            alert("Error, status = " + textStatus + ", " +
                  "error thrown: " + errorThrown
            );
            });   





        }
    });//FIN FORMULARIO RECURSODINAMICO, SE TERMINA FUNCIÓN DE AÑADIR NUEVO RECURSO DINÁMICO

    /*DESDE ESTE PUNTO SE  PUEDE AÑADIR UNA NUEVA EMERGENCIA*/
        $('#forma-emergencia').off().on('submit',coordenada,function(event){
        event.preventDefault();
        count3++;
        var direccion =$('.direccion-emg').val();
        //var prioridad=$('.prioridad-emg').val();
        var tipo_pto_interes=$('.tipo-pto-interes-emg').val();
        //console.log(""+direccion+""+prioridad+""+tipo_pto_interes+""+count3);
        if (direccion=="")
        {
        alert("Faltan campos por llenar");
        }
        else 
        {   
          $.post('ingreso_emg.php',{  direccion:direccion,
                                        //prioridad:prioridad,
                                        tipo_pto_interes:tipo_pto_interes,
                                        latitud:coordenada.latitud,
                                        longitud:coordenada.longitud},function(data){
    ////////////////////////////////////////////////////////////////////////////////////////////          
                    
                    if(data[0].answer==1){
                        alert('la dirección ya está asignada a una emergencia activa!');
                        
                    }
                   else{var LeafIcon = L.Icon.extend({//se define la estructura del icono
                        options: {

                            iconSize:     [50, 50],

                            iconAnchor:   [25,50],
                            popupAnchor:  [0, -25]
                        }
                        });


                    L.icon = function (options) {
                        return new L.Icon(options);
                    };//se añade una nueva propiedad a leaficon



                   var greenIcon = new LeafIcon({iconUrl: data[0].imagen});//variable utilizada en leaficon
                    marcadoresEmergencia[contadorEMG]=L.marker([data[0].latitud,data[0].longitud], {icon: greenIcon}).bindPopup('<div class="contenedor-min-principal"><div class="contenedor-info"><b>Prioridad: '+data[0].priority+'</b><p>Id emergencia:'+data[0].id_emergency+'</p></div><div class="contenedor-btn"><button class="emg" value="'+data[0].id_emergency+'">Modificar</button><button class="reporte" value="'+data[0].id_emergency+'">Reporte</button><button class="asistida" value="'+data[0].id_emergency+'">Finalizar</button></div><div class="contenedor-emg"><form class="formamin-emg">Dirección:<input type="text" id="direccion-emg-min" maxlength="50">Prioridad<select id="prioridad-emg-min"></select>Tipo punto interés<select id="tipo-pto-interes-emg-min"></select><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>',{
                    closeButton: true,
                       minWidth: 300,
                        maxwidth:300
                    }).addTo(map);
                        marcadoresEmergencia[contadorEMG]._myId=data[0].id_emergency;
                        marcadoresEmergencia[contadorEMG].on('click',centrarMapa);
                        marcadoresEmergencia[contadorEMG].on('mouseover',moverMarker);
                        marcadoresEmergencia[contadorEMG].on('dragend',soltarMarker);
                   grupoEmergencia.addLayer(marcadoresEmergencia[contadorEMG]);


                   
    /////////////////////////////////////////////////////////////////////////////////////////////////          
            alert("Emergencia almacenada");
            salirModalEmergencia();
                       }//FIN ELSE

          },"json").fail(function(jqXHR, textStatus, errorThrown){
            alert("Error, status = " + textStatus + ", " +
                  "error thrown: " + errorThrown
            );
            });//fin post y fail

            }//fin else emergencia
        });//FIN FORMULARIO EMERGENCIA, SE TERMINA FUNCIÓN DE AÑADIR NUEVA EMERGENCIA EN EL SISTEMA


   
});

/////////////////////////////////////////////////////////////////////
var COORDENADARE;
var COORDENADARD;
var COORDENADAEMG;
var tipo_pto_interes_re;

//MODIFICACION RECURSO ESTATICO
$('#map').on('click',".res",function modificarRecursoEstatico(){

var id_recursoEstatico=$(this).val();    //Se captura el identificador del recurso
 //console.log(id_recursoEstatico);
/*Se realiza llamada AJAX para recuperar todos los datos del recurso estático identificado*/
$.ajax({                                      
      url: 'identificarRE.php',                  
      data:{static_resource_name:id_recursoEstatico
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      { 
          
        console.log(data[0].static_resource_name);
        $('#direccion-re-min').val(""+data[0].address);
        $('#descripcion-re-min').val(""+data[0].description);//se despliegan los datos encontrados
          if(data[0].description==null)//si descripcion es null en la base de datos.
        {
            $('#descripcion-re-min').val("");
        }else
        {
            $('#descripcion-re-min').val(""+data[0].description);
        }
            
            
                var lat=parseFloat(data[0].latitude);//estas
                var lng=parseFloat(data[0].longitude);//ultimas
                COORDENADARE={latitud:lat, longitud:lng};//4
            
          
            $('.latlng').val('['+lat.toFixed(7)+","+lng.toFixed(7)+']');/*no son utilizadas pero pueden
            servir más adelante.*/
      
     
          
    
      }    
});//FIN AJAX PRINCIPAL identificacion de punto interes
      
    
        console.log($(this).val());

       
        $('.contenedor-re').fadeIn();//se muestra el formulario con los datos del recurso identificado
        $('.res').prop("disabled",true);//se desactiva temporalmente el botón de modificar
    
    $('.formamin-re').off().on('submit',function (evento){
        /*Al seleccionar submit, se almacenarán los datos modificados*/
        evento.preventDefault();
        //console.log(id_recursoEstatico+''+$('#direccion-re-min').val()+' :'+$('#tipo-pto-interes-re-min option:selected').val());

    $.ajax({
    url:"actualizarRE.php",
    data:{  static_resource_name:id_recursoEstatico,
            address:$('#direccion-re-min').val(),
            description:$('#descripcion-re-min').val(),
            },
    method:"POST",
    dataType:'json',
    success:function(data)
        {   if(data==2){
            
            alert("esta dirección está siendo utilizada por otro recurso!");
            
            }
            else
            {
            var b=0;
            var g=0;
            var c=0;
            var h=0;
//////////////////////////////////////////////////////////////////////////////////////////
            var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+id_recursoEstatico+'</b><p id="'+$('#direccion-re-min').val()+'">Dirección:'+$('#direccion-re-min').val()+'</p></div><div class="contenedor-btn"><button  class="res" value="'+id_recursoEstatico+'">Modificar</button><button id="borrar-re" value="'+id_recursoEstatico+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
//////////////////////////////////////////////////////////////////////////////////////////
         /*Aquí se recorren todos los arreglos de marcadores utilizando el identificador del recurso encontrado inicialmente
         , si no existe dentro del grupo de marcadores, se pasa a la siguiente categoria.*/
         //ESTO ES PARA MODIFICAR EL CONTENIDO DEL POPUP
            for( b=0;b<marcadoresBomberos.length;b++){
                if(marcadoresBomberos[b]._myId==id_recursoEstatico){
              
                marcadoresBomberos[b]._popup.setContent(contenidoPopup);
                    b++;
                    }
            }for(g=0;g<marcadoresGrifos.length;g++){
                if(marcadoresGrifos[g]._myId==id_recursoEstatico){
               
                marcadoresGrifos[g]._popup.setContent(contenidoPopup);
                    }
                
            }for(c=0;c<marcadoresCarabineros.length;c++){
                if(marcadoresCarabineros[c]._myId==id_recursoEstatico){
               
                marcadoresCarabineros[c]._popup.setContent(contenidoPopup);
                    }
                
            }for(h=0;h<marcadoresHospitales.length;h++){
                if(marcadoresHospitales[h]._myId==id_recursoEstatico){
               
                marcadoresHospitales[h]._popup.setContent(contenidoPopup);
                    }
                
            }
         
        $('.contenedor-re').hide();
        $('.res').prop("disabled",false);//se vuelve a habilitar el boton de modificar
        $('#tipo-pto-interes-re-min option').remove();//se esconde el formulario de modificación
        if(data==0){
            
        }else{
            alert("datos actualizados!");
        }   
            }
        },
    error: function(jqXHR, textStatus, errorThrown){
            alert("Error, status = " + textStatus + ", " +
                  "error thrown: " + errorThrown
            );
            }
    });

        });
    
    
});//FIN MODIFICACION RECURSO ESTATICO

///////////////////////////////////////////////////////////////////////
//MODIFICACIÓN DE EMERGENCIA
$('#map').on('click',".emg",function modificarEmergencia(e){

    
var id_emergencia=$(this).val(); //se identifica a la emergencia
    
//alert(id_emergencia);
$.ajax({                                      
      url: 'identificarEMG.php',                  
      data:{id_emergency:id_emergencia
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      { 
        $('#direccion-emg-min').val(""+data[0].address);
            var lat=parseFloat(data[0].latitude);
              var lng=parseFloat(data[0].longitude);
            COORDENADAEMG={latitud:lat, longitud:lng};//se recuperan los datos de la emergencia.
          
       
     /*En la siguiente llamada AJAX se mostrarán todas las prioridades que existe, 
     marcando como selccionada la prioridad con la que se almacenó la emergencia identificada*/
        $.ajax({                                      
      url: 'prioridadesEMG.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(dato)          
      {
          for(var i=0; i<Object.keys(dato).length;i++){
              

              if(data[0].priority==dato[i].priority){
              $('#prioridad-emg-min').append("<option value="+dato[i].priority+" selected>"+data[0].priority+"</option>");//se pone la prioridad almacenada como seleccionada
              
              }else{
              $('#prioridad-emg-min').append("<option value="+dato[i].priority+">"+dato[i].priority+"</option>");//se añaden los valores de prioridad
              
              }
                
          }
          
      
      }
        });///FIN AJAX DE PRIORIDADES
     
/*En la siguiente llamada ajax se añaden a un select de 
forma dinámica los tipos de puntos de interés relacionados a las emergencias, por ahora solo existe un tipo*/          
   $.ajax({                                      
      url: 'ptos_interes_RE_RD_EMG.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(dato_pto_interes)          
      {
          for(var i=0; i<Object.keys(dato_pto_interes).length;i++)
          {
              
              
              if(dato_pto_interes[i].tipo_pto_interes==13)
              {
              if(data[0].tipo_pto_interes==dato_pto_interes[i].tipo_pto_interes)
              {
              $('#tipo-pto-interes-emg-min').append("<option value="+data[0].tipo_pto_interes+" selected>"+dato_pto_interes[i].tipo_pto+"</option>");
              
              }
                else
                {
                $('#tipo-pto-interes-emg-min').append("<option value="+dato_pto_interes[i].tipo_pto_interes+">"+dato_pto_interes[i].tipo_pto+"</option>");
                }
              }
          }
          
      
      }
        });////FIN AJAX TIPOS PTOS INTERES   
          
    
      }    
    });//FIN AJAX PRINCIPAL
    
        $('.contenedor-emg').fadeIn();//se muestra el formulario de modificación
        $('.emg').prop("disabled",true);//se inhabilita temporalmente el botón de modificar
    
    /*DESDE ESTE PUNTO SE DA INICIO A LA FUNCIÓN DE MODIFICAR EMERGENCIA*/
    $('.formamin-emg').off().on('submit',function(evento){
        
        evento.preventDefault();
        var tipo_pto_interes=$('#tipo-pto-interes-emg-min option:selected').val();
        var prioridad_seleccionada=$('#prioridad-emg-min option:selected').text();//SE CAPTURAN LOS VALORES DEL FORMULARIO
        console.log("prioridad:"+prioridad_seleccionada); 
        
        /*EN LA SIGUIENTE LLAMADA DE AJAX SE ACUTALIZARÁ LA EMERGENCIA*/
        $.ajax({
        url:"actualizarEMG.php",
        data:{  id_emergency:id_emergencia,
                address:$('#direccion-emg-min').val(),
                priority:prioridad_seleccionada,
                tipo_pto_interes:tipo_pto_interes
                },
        method:"POST",
        dataType:'json',
        success:function(data)
        {   if(data[0].answer==0){
            
            alert("esta dirección está siendo utilizada por otra emergencia!");
        }
         else{var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Prioridad: '+prioridad_seleccionada+'</b><p>Id emergencia:'+id_emergencia+'</p></div><div class="contenedor-btn"><button class="emg" value="'+id_emergencia+'">Modificar</button><button class="reporte" value="'+id_emergencia+'">Reporte</button><button class="asistida" value="'+id_emergencia+'">Finalizar</button></div><div class="contenedor-emg"><form class="formamin-emg">Dirección:<input type="text" id="direccion-emg-min" maxlength="50">Prioridad<select id="prioridad-emg-min"></select>Tipo punto interés<select id="tipo-pto-interes-emg-min"></select><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
           
       //EN EL SIGUIENTE CÓDIGO SE MODIFICA EL CONTENIDO DEL POPUP        
            for(var i=0; i<marcadoresEmergencia.length;i++){
            //console.log("id marcador:"+marcadoresEmergencia[i]._myId+"- Id boton:"+id_emergencia);
            
            if(marcadoresEmergencia[i]._myId==id_emergencia){    
            var myIcon = L.icon({
            iconUrl: data[0].imagen_recurso,
            iconSize:     [50, 50],
            iconAnchor:   [25,50],
            popupAnchor:  [0, -25]
            });
            console.log("toma el valor");
       marcadoresEmergencia[i].setIcon(myIcon);
       marcadoresEmergencia[i]._popup.setContent(contenidoPopup);     
            
            }else{
                 
                 }
               
            }
                $('.contenedor-emg').hide();
                $('.emg').prop("disabled",false);
                $('#tipo-pto-interes-emg-min option').remove();
                $('#prioridad-emg-min option').remove();}
                
        }
    });
    
    
    });//FIN AJAX DE MODIFICACIÓN
    
    
    
});//FIN MODIFICAR EMERGENCIA

/*ESTA FUNCIONALIDAD NO TERMINADA, LA DEJO LIBRE PARA ACTUALIZACIÓN*/
$('#map').on('click',".rd",function ModificarRecursoDinamico(){
        var nombre_recurso_rd=$(this).val();
    //console.log(nombre_recurso_rd);
    $.ajax({
    url:"identificarRD.php",
    data:{dynamic_resource_name:nombre_recurso_rd},
    method:"POST",
    dataType:"json",
    success:function(data)
    {
        $('#id-dispositivo-rd-min').val(""+data[0].id_device);
 
        
    }
    });//FIN AJAX DE IDENTIFICACION
    
    
        $('.contenedor-rd').fadeIn();
        $('.rd').prop("disabled",true);
    
    
    $('.formamin-rd').off().on('submit',function(evento){
        evento.preventDefault();
        $.ajax({
        url:"actualizarRD.php",
        data:{dynamic_resource_name:nombre_recurso_rd,
              id_device:$('#id-dispositivo-rd-min').val(),
             },
        method:"POST",
        dataType:"json",
        success:function(dato_mod)
        {   
            
            
                if(dato_mod==1){
                    alert("Inserte un Id");
                
                }else if(dato_mod==2){
                    
                   alert("El Id está asignado a otro recurso"); 
                    
                }
                else{
                    
                    
                    
                    
                alert(dato_mod);
                $('.contenedor-rd').hide();
                $('.rd').prop("disabled",false);
                $('#tipo-pto-interes-rd-min option').remove();
                
                }
                
                
                
        }
        });//AJAX DE MOFIDICACION
        
    });//FIN FORMAMIN

});//FIN MODIFICAR RECURSO DINÁMICO
//ESTA FUNCIONALIDAD PERMITE FINALIZAR UNA EMERGENCIA ASISTIDA
$('#map').on('click',".asistida",function finalizarEmergencia(){
    
console.log('Antes: '+marcadoresEmergencia.length);    
if(confirm("Desea finalizar la emergencia?(IRREVERSIBE)") == true){
 /*SE PREGUNTA SI REALMENTE SE DESEA FINALIZAR LA EMERGENCIA*/
    
//SI LA RESPUESTA ES SÍ, SE REALIZA LA MODIFICACIÓN SOBRE LA EMERGENCIA IDENTIFICADA    
var id_emergencia=$(this).val();     
$.ajax({
url:'finalizarEmergencia.php',
data:{id_emergencia:id_emergencia},
method:'POST',
dataType:'json',
success:function(data){
 for(var i=0; i<marcadoresEmergencia.length;i++){
            //console.log("id marcador:"+marcadoresEmergencia[i]._myId+"- Id boton:"+id_emergencia);
            
            if(marcadoresEmergencia[i]._myId==id_emergencia){  
               grupoEmergencia.removeLayer(marcadoresEmergencia[i]);
              alert("Emergencia Finalizada");  
            
            }     
               
            }
        
    
    console.log('despues: '+marcadoresEmergencia.length);  
    },
error:function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError);
        
      }
});
    
    
}else{//SI LA RESPUESTA ES CANCELAR, NO SE FINALIZA LA EMERGENCIA
    alert("Operación cancelada");
}

});//FIN FINALIZACIÓN DE EMERGENCIA
//ESTA FUNCIÓN PERMITE ELIMINAR UN RECURSO ESTÁTICO DE LA BASE DE DATOS Y DE LAS CAPAS
$('#map').on('click',"#borrar-re",function eliminarRecursoEstatico(){
    
    var id_recursoEliminable=$(this).val();//SE IDENTIFICA AL RECURSO ESTÁTICO
    console.log("uf"+id_recursoEliminable);
    
    var tipo_pto_interes=recuperarPtoInteres(id_recursoEliminable)//SE CAPTURA EL TIPO DE PUNTO DE INTERES DEL RECURSO
   
        /*SI EL TIPO DE PUNTO DE INTERES PERTENECE A ALGUNO DE LOS IF'S SIGUIENTES
        SE REALIZARÁ LA ELIMINACION DEL RECURSO ESTÁTICO*/
    if(tipo_pto_interes==1){
        for(var g=0;g<marcadoresGrifos.length;g++){
             if(marcadoresGrifos[g]._myId==id_recursoEliminable){
                deleteRecursoEstatico(id_recursoEliminable);
                grupoGrifos.removeLayer(marcadoresGrifos[g]);
                borrarGrifos();
                inicializarGrifos(); 
             }
         }
    }
    if(tipo_pto_interes==2){
        for(var b=0;b<marcadoresBomberos.length;b++){
             if(marcadoresBomberos[b]._myId==id_recursoEliminable){
                var resp=deleteRecursoEstatico(id_recursoEliminable);
            if(resp=="NO"){
                
                alert("No se puede eliminar");
            }else{     
                grupoBomberos.removeLayer(marcadoresBomberos[b]);
                borrarBomberos();
                inicializarBomberos();} 
             }
         }
    }
    if(tipo_pto_interes==3){    
        for(var c=0;c<marcadoresCarabineros.length;c++){
             if(marcadoresCarabineros[c]._myId==id_recursoEliminable){
                deleteRecursoEstatico(id_recursoEliminable);
                grupoCarabineros.removeLayer(marcadoresCarabineros[c]);
                borrarCarabineros();
                inicializarCarabineros(); 
             }
         }
    }
    if(tipo_pto_interes==4){
        for(var h=0;h<marcadoresHospitales.length;h++){
             if(marcadoresHospitales[h]._myId==id_recursoEliminable){
                deleteRecursoEstatico(id_recursoEliminable);
                grupoHospitales.removeLayer(marcadoresHospitales[h]);
                borrarHospitales();
                inicializarHospitales(); 
             }
         }
    }

});//FUNCIÓN PARA ELIMINAR RECURSOS ESTÁTICOS
$('#map').on('click','#cerrar',function cerrarPopupRE(e){
    map.closePopup();
});//FUNCIÓN PARA CERRAR POPUP DE RECURSO ESTÁTICO
////////////////////////////////////////////////////////////////////  


//FUNCIÓN DE BUSCADOR PARA INGRESAR RECURSOS ESTÁTICOS Y EMERGENCIAS A TRAVÉS DE TECLADO
function showMap(err, data) {
    
    contadorGrifos=marcadoresGrifos.length;
    contadorBomberos=marcadoresBomberos.length;
    contadorCarabineros=marcadoresCarabineros.length;
    contadorHospitales=marcadoresHospitales.length;
    contadorEMG=marcadoresEmergencia.length;
    console.log(''+contadorEMG);
    
  $('#search').blur();
    var latitud=data.latlng[0];
    var longitud=data.latlng[1];
    
    map.panTo([latitud,longitud], 15);//EL MAPA SE UBICA EN LA COORDENADA ENCONTRADA
    map.panBy([latitud,longitud], 15);
   // return coordenadaBuscador;
    var coordenadaBuscador={latitud:data.latlng[0],longitud:data.latlng[1]};//SE CREA VARIABLE QUE ALMACENA COORDENADA
    var markerEncontrado=L.marker([data.latlng[0], data.latlng[1]]).addTo(map) .bindPopup('<p id="lat-mark">'+data.latlng[0]+','+data.latlng[1]+'</p><p>'+$('#search').val()+'</p><b id="respuesta"></b><button class="trigger">Ingresar Nuevo punto</button>');//SE AÑADE UN MARCADOR CON LA COORDENADA ENCONTRADA
    
        $('#map').on('click','.trigger',function(e){//AL SELECCIONAR EL BOTON PRESENTE EN EL MARCADOR  
        map.panTo([latitud,longitud], 15);//SE CENTRA EL MAPA A DICHO MARCADOR
        var vista=$('#map').width()/4; 
        var altura=$('#map').height()/2; 
        map.panBy([-vista,0]);
        
        //DEPENDIENDO SI EL BOTON SELECCIONADO ES EL PRIMERO O EL TERCO SE DESPLEGARÁ LO QUE APARECE MÁS ABAJO   
            if(boton1==true){
            $('.modal').fadeIn();
            MenuLateralIzq();
            $('.latlng').val('['+data.latlng[0].toFixed(7)+","+data.latlng[1].toFixed(7)+']');
            setTimeout(function(){map.removeLayer(markerEncontrado);},700);
            }
            if(boton3==true){
            $('#MODAL3').fadeIn();
            MenuLateralIzq();
            $('.latlng-emg').val('['+data.latlng[0].toFixed(7)+","+data.latlng[1].toFixed(7)+']');
            setTimeout(function(){map.removeLayer(markerEncontrado);},700);
            }
            if(boton1==false && boton3==false){
            $('#respuesta').text("Presione el botón de Recurso estático o Emergencias para continuar");
            }
       
        
        
        });//fin map query
        $('#search').val("");
  
  /*AQUÍ SE REPITE EL MISMO PROCESO QUE AL REALIZAR DOBLE CLIC EN EL MAPA PARA AÑADIR RECURSOS. 
  EN ESTE CASO RECURSOS ESTÁTICOS Y EMERGENCIAS*/   
     $('.btn-salir').on('click',salirModalRecursoEstatico); 
     $('.btn-salir-emg').on('click',salirModalEmergencia);
    
    
    $('#forma-recurso').off().on('submit',function(evento){
    evento.preventDefault();
    count++;
    var coordenada={ latitud:data.latlng[0], longitud:data.latlng[1]};
    var nombre_recurso=$('.nombre-recurso').val();
    var direccion_recurso=$('.direccion-recurso').val();
    var descripcion_recurso=$('.descripcion-recurso').val();
    var tipo_pto_interes=$('.tipo-pto-interes').val();
    console.log(coordenada.latitud+''+coordenada.longitud+'count'+count);
   $.post('ingreso_re.php',{nombre_recurso:nombre_recurso,
                        direccion_recurso:direccion_recurso,
                       descripcion_recurso:descripcion_recurso,
                       tipo_pto_interes:tipo_pto_interes,
                       latitud:coordenada.latitud,
                       longitud:coordenada.longitud },function (data){
     
        if(data[0].answer==1){
            
    alert('el recurso ya existe');
            
    }else if(data[0].answer==2){
        
        alert('dirección utilizada en otro recurso estático');
        
    }else if(data[0].answer!=1){
        var contenedorPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+data[0].nombre_recurso+'</b><p id="'+direccion_recurso+'">Dirección:'+direccion_recurso+'</p></div><div class="contenedor-btn"><button  class="res" value="'+data[0].nombre_recurso+'">Modificar</button><button id="borrar-re" value="'+data[0].nombre_recurso+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
        
        var LeafIcon = L.Icon.extend({//se define la estructura del icono
                options: {

                    iconSize:     [12,12],

                    iconAnchor:   [5,5],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon
            
               
                
               var blueIcon = new LeafIcon({iconUrl: data[0].imagen_recurso});//variable utilizada en leaficon
            
        if(tipo_pto_interes==1){//para grifos
                marcadoresGrifos[contadorGrifos]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);
                marcadoresGrifos[contadorGrifos]._myId=data[0].nombre_recurso;
                marcadoresGrifos[contadorGrifos].on('click',centrarMapa);
                marcadoresGrifos[contadorGrifos].on('mouseover',moverMarker);
                marcadoresGrifos[contadorGrifos].on('dragend',soltarMarker);
               grupoGrifos.addLayer(marcadoresGrifos[contadorGrifos]);
        }
        if(tipo_pto_interes==2){//para bomberos
                marcadoresBomberos[contadorBomberos]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);
                marcadoresBomberos[contadorBomberos]._myId=data[0].nombre_recurso;
                marcadoresBomberos[contadorBomberos].on('click',centrarMapa);
                marcadoresBomberos[contadorBomberos].on('mouseover',moverMarker);
                marcadoresBomberos[contadorBomberos].on('dragend',soltarMarker);
               grupoBomberos.addLayer(marcadoresBomberos[contadorBomberos]);
        }
        if(tipo_pto_interes==3){//para carabineros
                marcadoresCarabineros[contadorCarabineros]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);
                marcadoresCarabineros[contadorCarabineros]._myId=data[0].nombre_recurso;
                marcadoresCarabineros[contadorCarabineros].on('click',centrarMapa);
                marcadoresCarabineros[contadorCarabineros].on('mouseover',moverMarker);
                marcadoresCarabineros[contadorCarabineros].on('dragend',soltarMarker);
               grupoCarabineros.addLayer(marcadoresCarabineros[contadorCarabineros]);
        }
        if(tipo_pto_interes==4){//para carabineros
                marcadoresHospitales[contadorHospitales]=L.marker([data[0].latitud,data[0].longitud], {icon: blueIcon}).bindPopup(contenedorPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);
                marcadoresHospitales[contadorHospitales]._myId=data[0].nombre_recurso;
                marcadoresHospitales[contadorHospitales].on('click',centrarMapa);
                marcadoresHospitales[contadorHospitales].on('mouseover',moverMarker);
                marcadoresHospitales[contadorHospitales].on('dragend',soltarMarker);
               grupoHospitales.addLayer(marcadoresHospitales[contadorHospitales]);
        }
        
    salirModalRecursoEstatico();
    alert('el recurso se almacenó');
    
    }
 },"json");   


}); //fin formulario RE
    
$('#forma-emergencia').off().on('submit',function(event){
  
    event.preventDefault();
    count3++;
    var coordenada={ latitud:data.latlng[0], longitud:data.latlng[1]};
    var direccion =$('.direccion-emg').val();
    var prioridad=$('.prioridad-emg').val();
    var tipo_pto_interes=$('.tipo-pto-interes-emg').val();
    console.log(""+direccion+""+prioridad+""+tipo_pto_interes+""+count3);
    if (direccion=="")
    {
    alert("Faltan campos por llenar");
    }
    else 
    {   
      $.post('ingreso_emg.php',{  direccion:direccion,
                                    prioridad:prioridad,
                                    tipo_pto_interes:tipo_pto_interes,
                                    latitud:data.latlng[0],
                                    longitud:data.latlng[1]},function(data){
////////////////////////////////////////////////////////////////////////////////////////////          
         
          if(data[0].answer==1){
              
              alert('la dirección ya está asignada a una emergencia activa!');
          }   
          else{var LeafIcon = L.Icon.extend({//se define la estructura del icono
                options: {

                    iconSize:     [50, 50],

                    iconAnchor:   [25,50],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon
            
               
                
               var greenIcon = new LeafIcon({iconUrl: data[0].imagen});//variable utilizada en leaficon
            
                marcadoresEmergencia[contadorEMG]=L.marker([data[0].latitud,data[0].longitud], {icon: greenIcon}).bindPopup('<div class="contenedor-min-principal"><div class="contenedor-info"><b>Prioridad: '+data[0].priority+'</b><p>Id emergencia:'+data[0].id_emergency+'</p></div><div class="contenedor-btn"><button class="emg" value="'+data[0].id_emergency+'">Modificar</button><button class="reporte" value="'+data[0].id_emergency+'">Reporte</button><button class="asistida" value="'+data[0].id_emergency+'">Finalizar</button></div><div class="contenedor-emg"><form class="formamin-emg">Dirección:<input type="text" id="direccion-emg-min" maxlength="50">Prioridad<select id="prioridad-emg-min"></select>Tipo punto interés<select id="tipo-pto-interes-emg-min"></select><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>',{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);
                marcadoresEmergencia[contadorEMG]._myId=data[0].id_emergency;
                marcadoresEmergencia[contadorEMG].on('click',centrarMapa);
                marcadoresEmergencia[contadorEMG].on('mouseover',moverMarker);
                marcadoresEmergencia[contadorEMG].on('dragend',soltarMarker);
               grupoEmergencia.addLayer(marcadoresEmergencia[contadorEMG]);
/////////////////////////////////////////////////////////////////////////////////////////////////          
        alert("Emergencia almacenada");
        salirModalEmergencia();
             }//FIN ELSE
    
      },"json").fail(function(jqXHR, textStatus, errorThrown){
        alert("Error, status = " + textStatus + ", " +
              "error thrown: " + errorThrown
        );
        });//fin post y fail
       
        }//fin else emergencia
    });//FIN formulario emergencia
    

     
}


//ESTA FUNCIÓN SE ENCARGA DE ENCONTRAR LOS DATOS GEOESPACIALES DE LOS INGRESADO EN LA BARRA BUSCADORA
$("#search").keyup(function (e) {
    if ($("#search").is(":focus") && (e.keyCode == 13)) {
        var text = document.getElementById('search').value;
        contadorEnter=1;
if(text!=""){//SI EL TEXTO NO ES VACÍO SE REALIZARÁ LO SIGUIENTE
        geocoder.query(text, showMap);
    console.log(geocoder.query(text, showMap));
        }
        else 
        {//SINO SE PIDE INGRESAR ALGO A LA BARRA BUSCADORA
            alert('Debe ingresar una dirección');
        
        }
    }
});



//ESTA FUNCIÓN MUESTRA UN LISTADO DE LOS USUARIOS QUE ACTUALMENTE PUEDEN UTILIZAR EL SISTEMA
$('#encuentra-usuario').on('submit',function(evento){

evento.preventDefault();
$('#listado-usuarios').slideDown();
var run_usuario=$('.run-usuario').val();
$('#listado-usuarios table tr').remove();
$('#listado-usuarios table').append("<tr id='top-tabla'><td>Id usuario</td><td>Nombre usuario</td>");        
console.log(run_usuario);
/*LA LLAMADA AJAX QUE SE MUESTRA A CONTINUACIÓN REALIZA UNA CONSULTA A LA BASE DE DATOS
CON RESPECTO A LO QUE SE INGRESÓ PARA IDENTIFICAR AL MISMO*/
    $.ajax({
    url:"identificarUsuario.php",
    data:{id_usuario:run_usuario},
    method:"POST",
    dataType:"json",
    success: function(dato_usuario)
    {
        for(var i=0; i<Object.keys(dato_usuario).length;i++){
        $('#listado-usuarios table').append('<tr class="run-elegido"><td class="holi">'+dato_usuario[i].id_usuario+'</td><td>'+dato_usuario[i].nombre_usuario+'</td></tr>');
            //SE LISTAN LAS POSIBLES COINCIDENCIAS
        }
        $('.run-elegido').click(function(){
            $('#run-encontrado').val(""+$(this).find("td").first().text());//SE CAPTURA EL VALOR DEL USUARIO SELECCIONADO DE LA LISTA
            //console.log($(this).first().text());

        });
    }
    });//fin ajax encontrar usuario   
});




function esconderModalInhabilitar(){
    $('.modal-err').hide();
    document.getElementById("encuentra-usuario").reset();
    document.getElementById("inhabilita-usuario").reset();
    $('#listado-usuarios table tr').remove();
    $('#listado-usuarios').hide();
    $('#contenedor-modificar').hide();
    $('.btn-1, .btn-2, .btn-3').prop("disabled",false);
}//FUNCION PARA OCULTAR EL MODAL DE INHABILITAR USUARIO

$('#inhabilita-usuario').off().on('submit',function(evento){
    
evento.preventDefault();
if($('#run-encontrado').val()==""|| $('.clave-admin').val()=="")
{console.log(""+$('#run-encontrado').val()+""+$('.clave-admin').val());
    alert("faltan campos");//SE AVISA QUE NO SE HA SELECCIONADO UN USUARIO PARA INHABILITAR
}else{  
    
    /*AL SELECCTIONARLO SE UTILIZA EL VALOR usuario Y LUEGO SE REALIZA UNA LLAMADA
    AJAX PARA CAMBIAR EL TIPO DE USUARIO EN LA BASE DE DATOS AL USUARIO QUE SE DESEA INHABILITAR*/
    var usuario=$('#run-encontrado').val()
    $.ajax({
    url:"inhabilitarUsuario.php",
    data:{id_usuario_inhab:usuario,clave_admin:$('.clave-admin').val()},
    method:"POST",
    success:function(data)
    {
        
    if(data==1){
        alert("usuario inhabilitado");
        esconderModalInhabilitar();
        OcultarMenuIzq();
    }else{
        alert("Clave incorrecta");  
    }

    }
   
    });//fin ajax 
   
    
    

}
});//ESTA FUNCIÓN FINALIZA EL SUBMIT DE INHABILITAR USUARIO

var menu_abierto=false;/*ES UNA VARIABLE DE CONTROL PARA EL MENU QUE 
SE DESPLIEGA AL LISTAR EMERGENCIAS, SI ES FALSE SIGNIFICA QUE EL MENÚ ESTA CERRADO, SI ES TRUE SIGNIFICA
QUE ESTÁ ABIERTO*/

//ESTA FUNCIÓN LISTA EMERGENCIAS ALMACENADAS EN EL SISTEMA
$(document).on('keydown',function(e){
//SI EL USUARIO ES DE TIPO INHABILITADO NO TIENE ACCESO A ESTA FUNCIÓN 
if(tipo_usuario!='Inhabilitado'){
        if( e.target.tagName.toUpperCase() != 'INPUT' ){ 
            
            if(e.which==69 && e.shiftKey && menu_abierto==false){
                //COMO EL MENU ESTABA CERRADO AHORA SE LISTAN LAS EMERGENCIAS EN EL MENÚ DESPLEGADO
                e.preventDefault();
                $('.btn-1,.btn-2 ,.btn-3').prop('disabled',true);    
                $('#lista-emergencias').show();
                MenuLateralIzq();
                listarEmergencias();
                menu_abierto=true;
            } else if(e.which==69 && e.shiftKey){
                //COMO EL MENÚ ESTABA ABIERTO, AHORA SE OCULTA EL LISTADO DE EMERGENCIAS.
                e.preventDefault();
                $('.btn-1 ,.btn-2, .btn-3').prop("disabled",false);
                $('#lista-emergencias ul li').remove();
                $('#lista-emergencias ul hr').remove();
                $('#lista-emergencias').hide();
                OcultarMenuIzq();
                menu_abierto=false;
            }
        }
    }
});//Funcion que permite listar las emergencias y centrar el mapa una vez que se ha seleccionado alguna de ellas.
/*ESTA FUNCION RECUPERA LOS DATOS NECESARIOS PARA PODER LISTAR LAS EMERGENCIAS*/
function listarEmergencias(){
    
    /*EN LA SIGUIENTE LLAMADA AJAX SE RECUPERAN DATOS DE LAS EMERGENCIAS Y SE AÑADEN COMO CODIGO HTML*/
    $.ajax({
    url:'listarEmergencias.php',
    data:{},
    method:'POST',
    dataType:'json',
    success: function(datosEmergencia){
    for(var i in datosEmergencia){
      /*EL CÓDIGO HTML MOSTRADO TIENE UN TAG DE CLASS=ALTA, 
      ESTO DESPLIEGA UN ÍCONO EN EL LISTADO DE LAS EMERGENCIAS, ASOCIADO A LA PRIORIDAD
      DE LA MISMA, ES POR STO QUE SE REALIZAN IF'S CON RESPECTO A LA PRIORIDAD'*/
        if(datosEmergencia[i].priority=="Alta"){
    $('#lista-emergencias ul').append('<li value="100" data-lat='+datosEmergencia[i].latitude+' data-long='+datosEmergencia[i].longitude+'><div id="contenedor-lista-emergencias"><div id="datos-emergencia" ><p>Id emergencia: '+datosEmergencia[i].id_emergency+'</p>Dirección: '+datosEmergencia[i].address+'</p></div><div id="imagen-prioridad" class="Alta">'+datosEmergencia[i].priority+'</div></div></li><hr>');    
     
    }
    if(datosEmergencia[i].priority=="Desconocida"){
    $('#lista-emergencias ul').append('<li value="100" data-lat='+datosEmergencia[i].latitude+' data-long='+datosEmergencia[i].longitude+'><div id="contenedor-lista-emergencias"><div id="datos-emergencia" ><p>Id emergencia: '+datosEmergencia[i].id_emergency+'</p>Dirección: '+datosEmergencia[i].address+'</p></div><div id="imagen-prioridad" class="Desconocida">'+datosEmergencia[i].priority+'</div></div></li><hr>');    
      
    }
    if(datosEmergencia[i].priority=="Muy Alta"){
    $('#lista-emergencias ul').append('<li value="100" data-lat='+datosEmergencia[i].latitude+' data-long='+datosEmergencia[i].longitude+'><div id="contenedor-lista-emergencias"><div id="datos-emergencia" ><p>Id emergencia: '+datosEmergencia[i].id_emergency+'</p>Dirección: '+datosEmergencia[i].address+'</p></div><div id="imagen-prioridad" class="MuyAlta">'+datosEmergencia[i].priority+'</div></div></li><hr>');    
      
    }
    if(datosEmergencia[i].priority=="Media"){
    $('#lista-emergencias ul').append('<li value="100" data-lat='+datosEmergencia[i].latitude+' data-long='+datosEmergencia[i].longitude+'><div id="contenedor-lista-emergencias"><div id="datos-emergencia" ><p>Id emergencia: '+datosEmergencia[i].id_emergency+'</p>Dirección: '+datosEmergencia[i].address+'</p></div><div id="imagen-prioridad" class="Media">'+datosEmergencia[i].priority+'</div></div></li><hr>');    
      
    }
    if(datosEmergencia[i].priority=="Baja"){
    $('#lista-emergencias ul').append('<li value="100" data-lat='+datosEmergencia[i].latitude+' data-long='+datosEmergencia[i].longitude+'><div id="contenedor-lista-emergencias"><div id="datos-emergencia" ><p>Id emergencia: '+datosEmergencia[i].id_emergency+'</p>Dirección: '+datosEmergencia[i].address+'</p></div><div id="imagen-prioridad" class="Baja">'+datosEmergencia[i].priority+'</div></div></li><hr>');    
    
    }    
        
    }
        $('#lista-emergencias ul li').click(function(){
            /*ESTA FUNCIÓN PERMITE QUE AL SELECCIONAR UNA DE LAS EMERGENCIAS DE LA LISTA,
            EL MAPA SE CENTRE CON RESPECTO A LA MISMA*/
         //console.log('lat'+$(this).attr('data-lat')+'long'+$(this).attr('data-long'));
            var lat=$(this).attr('data-lat');
            var long=$(this).attr('data-long');
        map.panTo([lat,long],15,{animation:true});
        map.panBy([-308,0]);
        
        
        });
        
        },
    error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError);
        
      }
    });
}

//posible funcion de actualizar recurso dinamico en posición
//ESTA FUNCIÓN PERMITE VISUALIZAR LA POSICIÓN DE LOS RECURSOS DINÁMICOS A MEDIDA QUE ESTOS SE MUEVEN(TIENEN QUE HABER ACTUALIZADO STATUS)
window.setInterval( function actualizarPosRecursos() {
   
  $.ajax({
     url:'consultaRD.php', //SE CONSULTAN TODOS LOS RECURSOS DINÁMICOS
     data:{},
     method:'POST',
     dataType:'json',
     success:function(datos_dinamicos){
        var c=0;
        var b=0;
        var noCa=0;
        var bfs=0;
        var le=0;
        var ccam=0;//SON LOS MISMOS CONTADORES QUE PERMITEN RECORRER LOS ARRELGOS DE MARCADORES REFERENTES A CADA TIPO DE RECURSO DINÁMICO
       for(var i in datos_dinamicos){//NUEVAMENTE EL CONTADOR i CON EL QUE SE RECORRE  EL ARREGLO GENERAL DE LA CONSULTA DE LA BASE DE DATOS
        
          if(marcadoresComandanteIn[c]==undefined){//SI NO HAY MARCADORES EN LOS COMANDANTES INCIDENTES NO PASA NADA
              
              //no pasa nada
          }else{
            if(marcadoresComandanteIn[c]._myId==datos_dinamicos[i].dynamic_resource_name){/*SI EL IDENTIFICADOR DEL MARCADOR COINCIDE CON EL DEL RECURSO, SE ENTRA AL IF*/
                if(datos_dinamicos[i].latitude==undefined || datos_dinamicos[i].longitude==undefined){ /*SI LA LATITUD Y LA LONGITUD DEL RECURSO DINAMICO NO ESTAN DEFINIDOS, NO PASA NADA*/
                //no pasa nada
                }else{
                    //SI EXISTEN, ESTOS SON MOSTRADOS DESPLEGANDO EL MARCADOR EN DICHA UBICACIÓN.
                marcadoresComandanteIn[c].setLatLng(L.latLng(datos_dinamicos[i].latitude,datos_dinamicos[i].longitude)).update();
                 //console.log(''+marcadoresComandanteIn[c].getLatLng());
                }
            }
          }//ES IDÉNTICO CON EL RESTO DE LOS RECURSOS DINÁMICOS
           
          if(marcadoresBomberosDin[b]==undefined){
              
              //no pasa nada
          }else{ 
            if(marcadoresBomberosDin[b]._myId==datos_dinamicos[i].dynamic_resource_name){
                    if(datos_dinamicos[i].latitude==undefined || datos_dinamicos[i].longitude==undefined){
                        //no pasa nada    
                    }
                else{
                marcadoresBomberosDin[b].setLatLng(L.latLng(datos_dinamicos[i].latitude,datos_dinamicos[i].longitude)).update();
                 //console.log(''+marcadoresBomberosDin[b].getLatLng());
                }
            }
          }
           if(marcadoresCarros[noCa]==undefined){
              
              //no pasa nada
          }else{
            if(marcadoresCarros[noCa]._myId==datos_dinamicos[i].dynamic_resource_name){
                 if(datos_dinamicos[i].latitude==undefined || datos_dinamicos[i].longitude==undefined){
                        //no pasa nada    
                    }
                else{
                marcadoresCarros[noCa].setLatLng(L.latLng(datos_dinamicos[i].latitude,datos_dinamicos[i].longitude)).update();
                 //console.log(''+marcadoresComandanteIn[c].getLatLng());
                }
            }
          }
           if(marcadoresConductores[ccam]==undefined){
              
              //no pasa nada
          }else{
            if(marcadoresConductores[ccam]._myId==datos_dinamicos[i].dynamic_resource_name){
                 if(datos_dinamicos[i].latitude==undefined || datos_dinamicos[i].longitude==undefined){
                        //no pasa nada    
                    }
                else{
                marcadoresConductores[ccam].setLatLng(L.latLng(datos_dinamicos[i].latitude,datos_dinamicos[i].longitude)).update();
                 //console.log(''+marcadoresComandanteIn[c].getLatLng());
                }
            }
          }
           if(marcadoresBomberosFS[bfs]==undefined){
              
              //no pasa nada
          }else{
            if(marcadoresBomberosFS[bfs]._myId==datos_dinamicos[i].dynamic_resource_name){
                 if(datos_dinamicos[i].latitude==undefined || datos_dinamicos[i].longitude==undefined){
                        //no pasa nada    
                    }
                else{
                marcadoresBomberosFS[bfs].setLatLng(L.latLng(datos_dinamicos[i].latitude,datos_dinamicos[i].longitude)).update();
                 //console.log(''+marcadoresComandanteIn[c].getLatLng());
                }
            }
          }
           if(marcadoresLideres[le]==undefined){
              
              //no pasa nada
          }else{
            if(marcadoresLideres[le]._myId==datos_dinamicos[i].dynamic_resource_name){
                 if(datos_dinamicos[i].latitude==undefined || datos_dinamicos[i].longitude==undefined){
                        //no pasa nada    
                    }
                else{
                marcadoresLideres[le].setLatLng(L.latLng(datos_dinamicos[i].latitude,datos_dinamicos[i].longitude)).update();
                 //console.log(''+marcadoresComandanteIn[c].getLatLng());
                }
            }
          } 
     }//fin for
  }//fin success
      
      
  });//fin ajax recuperacion ubicacion
    
}, 5000);

//LAS PRÓXIMAS 8 FUNCIONES SON UTILIZADAS 
//PARA RESETEAR LOS GRUPOS DE MARCADORES UNA VEZ QUE SE ELIMINA UN RECURSO ESTÁTICO
function inicializarGrifos(){

    $.ajax({                                      
      url: 'consulta5.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      {     var g=0;
          for(var i in data){
          
          
        var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+data[i].static_resource_name+'</b><p id="'+data[i].address+'">Dirección:'+data[i].address+'</p></div><div class="contenedor-btn"><button  class="res" value="'+data[i].static_resource_name+'">Modificar</button><button id="borrar-re" value="'+data[i].static_resource_name+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
      var LeafIcon = L.Icon.extend({//se define la estructura del icono
                options: {

                    iconSize:     [12,12],

                    iconAnchor:   [5,5],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon
            
               
                
               var blueIcon = new LeafIcon({iconUrl: data[i].imagen});//variable utilizada en leaficon
            
         if(data[i].tipo_pto_interes==1){//if para grifos
                marcadoresGrifos[g]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresGrifos[g]._myId=data[i].static_resource_name;
                
                marcadoresGrifos[g].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
            
               grupoGrifos.addLayer(marcadoresGrifos[g]);//se añaden uno a uno los marcadores al grupo
                g++;
         }
      
        }
      }
});    
    
}
function borrarGrifos(){
for(var g=0;g<marcadoresGrifos.length;g++){
   grupoGrifos.removeLayer(marcadoresGrifos[g]); 
    contadorGrifos=0;
    
}    
    
}
function inicializarBomberos(){
$.ajax({                                      
      url: 'consulta5.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      {     var b=0;
          for(var i in data){
          
          
        var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+data[i].static_resource_name+'</b><p id="'+data[i].address+'">Dirección:'+data[i].address+'</p></div><div class="contenedor-btn"><button  class="res" value="'+data[i].static_resource_name+'">Modificar</button><button id="borrar-re" value="'+data[i].static_resource_name+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
      var LeafIcon = L.Icon.extend({//se define la estructura del icono
                options: {

                    iconSize:     [12,12],

                    iconAnchor:   [5,5],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon
            
               
                
               var blueIcon = new LeafIcon({iconUrl: data[i].imagen});//variable utilizada en leaficon
            
         if(data[i].tipo_pto_interes==2){//if para grifos
                marcadoresBomberos[b]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresBomberos[b]._myId=data[i].static_resource_name;
                
                marcadoresBomberos[b].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
            
               grupoBomberos.addLayer(marcadoresBomberos[b]);//se añaden uno a uno los marcadores al grupo
                b++;
         }
      
        }
      }
});    
    
}
function borrarBomberos(){
for(var b=0;b<marcadoresBomberos.length;b++){
   grupoBomberos.removeLayer(marcadoresBomberos[b]); 
    contadorBomberos=0;
    
}    
    
}
function inicializarCarabineros(){
$.ajax({                                      
      url: 'consulta5.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      {     var c=0;
          for(var i in data){
          
          
        var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+data[i].static_resource_name+'</b><p id="'+data[i].address+'">Dirección:'+data[i].address+'</p></div><div class="contenedor-btn"><button  class="res" value="'+data[i].static_resource_name+'">Modificar</button><button id="borrar-re" value="'+data[i].static_resource_name+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
      var LeafIcon = L.Icon.extend({//se define la estructura del icono
                options: {

                    iconSize:     [12,12],

                    iconAnchor:   [5,5],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon
            
               
                
               var blueIcon = new LeafIcon({iconUrl: data[i].imagen});//variable utilizada en leaficon
            
         if(data[i].tipo_pto_interes==3){//if para grifos
                marcadoresCarabineros[c]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresCarabineros[c]._myId=data[i].static_resource_name;
                
                marcadoresCarabineros[c].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
            
               grupoCarabineros.addLayer(marcadoresCarabineros[c]);//se añaden uno a uno los marcadores al grupo
                c++;
         }
      
        }
      }
});    
    
}
function borrarCarabineros(){
for(var c=0;c<marcadoresCarabineros.length;c++){
   grupoCarabineros.removeLayer(marcadoresCarabineros[c]); 
    contadorBomberos=0;
    
}    
    
}
function inicializarHospitales(){
$.ajax({                                      
      url: 'consulta5.php',                  
      data:{
            },                        
      method: 'POST',                               
      dataType:'json',                
      success: function(data)          
      {     var h=0;
          for(var i in data){
          
          
        var contenidoPopup='<div class="contenedor-min-principal"><div class="contenedor-info"><b>Recurso:'+data[i].static_resource_name+'</b><p id="'+data[i].address+'">Dirección:'+data[i].address+'</p></div><div class="contenedor-btn"><button  class="res" value="'+data[i].static_resource_name+'">Modificar</button><button id="borrar-re" value="'+data[i].static_resource_name+'">Eliminar</button><button id="cerrar">Cerrar</button></div><div class="contenedor-re"><form class="formamin-re">Dirección:<input type="text" id="direccion-re-min" maxlength="50">Descripción:<input type="text" id="descripcion-re-min" maxlength="100"><div class="btn-final-aceptar"><input type="submit" value="Aceptar"></div></form></div></div>';
      var LeafIcon = L.Icon.extend({//se define la estructura del icono
                options: {

                    iconSize:     [12,12],

                    iconAnchor:   [5,5],
                    popupAnchor:  [0, -25]
                }
                });
            
            
            L.icon = function (options) {
                return new L.Icon(options);
            };//se añade una nueva propiedad a leaficon
            
               
                
               var blueIcon = new LeafIcon({iconUrl: data[i].imagen});//variable utilizada en leaficon
            
         if(data[i].tipo_pto_interes==4){//if para grifos
                marcadoresHospitales[h]=L.marker([data[i].latitude,data[i].longitude], {icon: blueIcon}).bindPopup(contenidoPopup,{
                closeButton: true,
                   minWidth: 300,
                    maxwidth:300
                }).addTo(map);//Aqui se crea un popup con los datos asociados al punto de interes creado junto con su resolucion.
            
                //en esta linea se asigna un Id al marcador creado para poder realizar posteriormente modificaciones sobre sus propiedades.
                marcadoresHospitales[h]._myId=data[i].static_resource_name;
                
                marcadoresHospitales[h].on('click',centrarMapa);//este evento simplemente centra la pantalla, para el caso en que el menu izquerdo este abierto o cerrado.
            
               grupoHospitales.addLayer(marcadoresHospitales[h]);//se añaden uno a uno los marcadores al grupo
                h++;
         }
      
        }
      }
});    
    
}
function borrarHospitales(){
for(var h=0;h<marcadoresHospitales.length;h++){
   grupoHospitales.removeLayer(marcadoresHospitales[h]); 
    contadorHospitales=0;
    
}    
    
}
//ESTA FUNCION ELIMINA A UN RECURSO ESTÁTICO DE LA BASE DE DATOS DEL SISTEMA WEB
function deleteRecursoEstatico(id_recurso){
var respuesta;    
    $.ajax({
        url:'eliminarRE.php',
        data:{static_resource_name:id_recurso},
        method:'POST',
        dataType:'json',
        async:false,
        success:function(data){
          
        if(data=="NO"){
           respuesta="NO";/*SU VALOR NO ES IMPORTANTE, 
           SOLAMENTE SIRVE PARA VERIFICAR SI SE ELIMINÓ O NO EL RECURSO ESTÁTICO*/ 
        }
        else{
           
        alert("Recurso eliminado!");}
        },
        error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+"error eliminar"+thrownError);
        
      }
        
    });
    console.log(""+respuesta);
  return respuesta;  
}

/*ESTA FUNCIÓN PERMITE RECUPERAR EL TIPO DE PUNTO DE INTERÉS DE UN RECURSO ESTÁTICO
A TRAVÉS DE SU IDENTIFICADOR ANTES DE ELIMINARLO*/
function recuperarPtoInteres(id_recurso){
var pto_interes=0;
    
    $.ajax({
        url:'identificarRE.php',
        data:{static_resource_name:id_recurso},
        method:'POST',
        dataType:'json',
        async:false,
        success:function(data){
        pto_interes=data[0].tipo_pto_interes;
        
        },
        error:function (xhr, ajaxOptions, thrownError) {
        alert("error recurso"+xhr.status+""+thrownError);
        
        }
        
    });
    
 return pto_interes;   
    
}
//FIN PROGRAMA
