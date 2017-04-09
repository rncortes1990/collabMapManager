<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/MIOS/Prototipo/icon/ic_mobile_map_logo.ico" type="image/x-icon" /><!--SE AÑADE FAVICON-->
    <link rel="stylesheet" type="text/css" href="css/emergente.css"><!--SE AÑADE  CSS-->
    <link rel="stylesheet" type="text/css" href="css/normalize.css"><!--SE AÑADE NORMALIZADOR CSS-->
    <title>Gestor de reportes</title>
</head>

<body>
    <div class="contenedor-reportes">
        
        
        <div class="tabs-reportes"><!--CONTENEDOR DE LOS BOTONES GENERAR REPORTE, REVISAR REPORTES Y GALERÍA DE IMÁGENES-->
            <button class="tab generar-reporte tab-1">Generar reporte</button>
            <button class="tab leer-reporte  tab-2">Revisar reportes</button>
            <button class="tab imagen  tab-3"></button>
            <button class="tab"id="titulo-reportes">Reportes e Im&aacute;genes <br>de Emergencias</button>
        </div>
        
        
        <div class="generador-lector-reportes"><!--CONTENEDOR DE SECCIONES GENERAR REPORTE, REVISAR REPORTES Y GALERÍA DE IMÁGENES-->
                <div class="generador-reportes campo-reporte"><!--DIV DIV DESTINADO A LA GENERACIÓN/ACTUALIZACIÓN DE REPORTES-->
                    
                <form class="forma-gestor"id="forma-gestor">  <!--FORMULARIO DEL GENERADOR DE REPORTES-->
                    <p>Datos emergencia</p>
                    <hr>    
                    <div id="datos-emergencia"><!--datosemergencia-->
                        <div><p>Id emergencia:</p><input id="id-emergencia" type="text" disabled></div>
                        <div><p>Direcci&oacute;n:</p><input id="dir-emergencia" type="text" disabled></div>
                        <div><p>Prioridad:</p><input type="text" id="prioridad" disabled></div>
                    </div>
                    <hr>
                    <div id="reporte-bomberos"><!--DIV QUE CONTIENE LOS DATOS DE LOS BOMBEROS ASISTENTES Y DATOS RELATIOS AL REPORTE-->
                            <p>Datos recursos </p>
                            <hr>
                            <div class="bomberos">
                                <div><p>Comandante incidente:</p><input id="comandante" type="text" disabled></div>
                                <div><p>Nro bomberos:</p><input id="bomberos"type="text" disabled></div>
                                <div><p>Nro de Carros bomba:</p><input type="text" id="carros"disabled></div>
                            </div><!--datosbmberos-->
                            <div class="reporte">
                                
                                <textarea rows="4" cols="50" class="texto-reporte" placeholder="Descripción del reporte de emergencia....." maxlength="200"></textarea>
                            </div><!--CONTENIDO DE REPORTE-->
                    </div><!--cuerpo-->
                    <div class="submit-reporte"><input type="submit"id="submit-reporte" value="Enviar"></div><!--submit-->
                
                    
                </form>  
                </div>
            
                <div class="lector-reportes campo-reporte"><!--CONTIENE AL FORMULARIO DE VISUALIZACIÓN DE REPORTES-->
                    <div id="cont-lista"><!--DIV QUE CONTIENE A LOS LISTADOS DE REPORTES GENERADORS, SE UTILIZA PARA REVISAR REPORTES-->
                        <p class="tit">Reportes</p>
                        <ul id="lista-reportes">
                        </ul>
                    </div>
                    <div id="cont-reporte"><!--DIV UTILIZADO PARA VISUALIZAR LOS REPORTES ALMACENADOS EN EL SISTEMA-->
                        <p class="tit">Contenido</p>
                        <div id="cont-tabla">
                        <table id="display-reporte">
                                <tr>
                                    <th>Id emergencia</th>
                                    <th>Direcci&oacute;n emergencia</th>
                                    <th>Prioridad</th>
                                </tr>
                                <tr id="datos-de-emergencia"><!--DATOS RELATIVOS A LA EMERGENCIA-->
                                    

                                </tr>
                                <tr>
                                    <th>Comandante incidente</th>
                                    <th>bomberos</th>
                                    <th>Veh&iacute;culos</th>
                                </tr>
                                <tr class="datos-bomberos"><!--DATOS RELATIVOS A LOS BOMBEROS Y CARROS BOMBA-->
                                    <td id="nombre-comandante"><div class="scroll-bomberos"><ul></ul></td>
                                    <td id="lista-bomberos"><div class="scroll-bomberos"><ul></ul></div></td>
                                    <td id="lista-carros"><div class="scroll-bomberos"><ul></ul></div></td>
                                </tr>
                        </table>
                        <div id="contenedor-texto-reporte"><!--DATOS RELATIVOS AL REPORTE-->
                        
                        <p id="nro-reporte"></p>
                        <textarea readonly rows="4" cols="50" class="Texto-reporte"style="resize:none">
                            
                        </textarea>
                        <p id="fecha"></p>
                        </div>        
                            
                        
                        </div>
                    </div>
                </div>
            <!--////////////////////////////////////////-->
            <div class="imagenes-emg campo-reporte"><!--DIV UTILIZADO PARA LA GALERÍA DE LOS RECURSOS DIGITALES-->
                    <div id="lista-emg"><!--CONTIENE A LA LISTA DE EMERGENCIAS EXISTENTES EN EL SISTEMA, INDEPENDIENTE DE SI TIENEN RECURSOS DIGITALES O NO-->
                        <p class="tit">Emergencias</p>
                        <ul id="lista-emergencias">
                        </ul>
                    </div>
                    <div id="galeria"><!--DIV UTILIZADO PARA DESPLEGAR GALERÍA DE IMÁGENES POR EMERGENCIA-->
                        <div id="cargando"></div>
                        <p id="no-existen">No se han recibido im&aacute;genes asociadas a la emergencia.</p>
                    </div>
                </div>
            
        </div>
    
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script><!--SE AÑADE LIBRERIA DE JQUERY-->
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> <!--SE AÑADE UNA LIBRERIA OPCIONAL DE JQUERY-->
<script>

    
    var sharedObject = window.opener.sharedObject;//SE RECIBE EL ID_EMERGENCY DE LA VENTANA CORRESPONDIENTE A MAPA.PHP
function inicializar(){
    $('.tab-2, .tab-3').removeClass("tab").addClass("tab-ocupado");
    $('.lector-reportes, .imagenes-emg').hide();
    }//INICIALIZA LAS CARACTERISTICAS CSS DE LOS BOTONES DE GENERAR REPORTE, REVISAR REPORTES Y GALERÍA DE IMÁGENES
 
    
    $(document).ready(function(){
        
        inicializar();//SE LLAMA ALA FUNCIÓN DE INICIALIZACIÓN
                          });
    
  //ESTA FUNCIONALIDAD PERMITE LISTAR A LAS EMERGENCIAS ALMACENADAS EN EL SISTEMA
function listarEmergencias(){
    $.ajax({
        url:'listarEmgGaleria.php',
        data:{},
        method:'POST',
        dataType:'json',
        success:function(datos_emergencia){
        var cont=0;
        for(var i in datos_emergencia){
        $('#lista-emergencias').append('<li data-id='+datos_emergencia[i].id_emergency+'><div id="elemento"><div id="datos"><p>Id emergencia:'+datos_emergencia[i].id_emergency+'</p></div><div id="direccion"><p>'+datos_emergencia[i].address+'</p></div></div></li>');
        
        }//SE AÑADEN NUEVOS NODOS A LA LISTA DE EMERGENCIAS DE LA GALERÍA
          },
        error:function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError);
        
      }
        
        });
    
}
    
    
   //ESTA FUNCIÓN SE ENCARGA DE MOSTRAR LOS DATOS QUE SERÁN ASOCIADOS AL REPORTE A ALMACENAR
function mostrarDatos(){
    var id_emergencia=sharedObject;//SE RECUPERA EL IDENTIFICADOR DE LA EMERGENCIA
   
    var carros=0;
    var bomberos=0; //SE CREAN CONTADORES PARA COMPROBAR SI LA EMERGENCIA TIENE RECURSOS DINÁMICOS ASIGNADOS
   
    $.ajax({
    url:'identificarEMG.php',
    data:{id_emergency:id_emergencia},
    method:'POST',
    dataType:'json',
    success:function(datos_emergencia){
    for(var i in datos_emergencia){    //SE REALIZA UN FOR PARA LISTAR LOS DATOS ENCONTRADOS DE LA EMERGENCIA
        $('#id-emergencia').val(""+datos_emergencia[i].id_emergency);
        $('#dir-emergencia').val(""+datos_emergencia[i].address);
        $('#prioridad').val(""+datos_emergencia[i].priority);
    }
    
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError);
        
      }
    });//FIN AJAX DATOS DE EMERGENCIA
   
    $.ajax({//SE REALIZA LLAMADA AJAX PARA RECUPERAR DATOS DE BOMBEROS ASISTENTES A LA EMERGENCIA
    url:'bomberosReporte.php',
    data:{id_emergency:id_emergencia},
    method:'POST',
    dataType:'json',
    success:function(datos_bomberos){
        var cadena="";
        var cadenaCom="";
    for(var i in datos_bomberos){    
        
        if(datos_bomberos[i].answer==1){
                if(datos_bomberos[i].firefighter_type=='Comandante Incidente'){
                $('#comandante').val(""+datos_bomberos[i].firefighter_name);
                cadenaCom+=datos_bomberos[i].firefighter_name+"/";
                cadena+=datos_bomberos[i].firefighter_name+"/";
                }else{
                cadena+=datos_bomberos[i].firefighter_name+"/";
                }
        }else if(datos_bomberos[i].answer==0){
           // NO HAY BOMBEROS ASIGNADOS A LA EMERGENCIA
            bomberos=1;                                 
                                             }
        
    }cadena+="";
    $('#bomberos').val(""+cadena);
    $('#comandante').val(""+cadenaCom);
    
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+" "+thrownError+"error bomberos");
        
      }
    });//FIN AJAX DE BOMBEROS PARTICIPANTES
    
    $.ajax({
    url:'carrosReporte.php',
    data:{id_emergency:id_emergencia},
    method:'POST',
    dataType:'json',
    success:function(datos_carros){
        var cadena="";
            for(var i in datos_carros){
                if(datos_carros[i].answer==1){
                    cadena+=datos_carros[i].fire_truck_name+"/";
                }else if(datos_carros[i].answer==0){
                //NO HAY CARROS BOMBA ASIGNADOS A LA EMERGENCIA
                carros=1;
                
                }
    }$('#carros').val(""+cadena);
    
    },
    error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError);
        
      }
    });//FIN AJAX CARROS BOMBA PARTICIPANTES
    

}//Funcion que muestra los datos de la emergencia seleccionada en mapa.php
    mostrarDatos();//SE LLAMA A LA FUNCIONALIDAD QUE RELLENARÁ EL FORMULARIO DE INGRESO DE REPORTE
    
$('.forma-gestor').on('submit',function(e){//ESTA FUNCIONALIDAD SE ENCARGA DE RECOPILAR LOS DATOS DEL REPORTE A ALMACENAR 
        
        
        var id_emergencia=$('#id-emergencia').val();
        var direccion=$('#dir-emergencia').val();
        var prioridad=$('#prioridad').val();
        var comandante=$('#comandante').val();
        var Qbomberos=$('#bomberos').val();
        var Qcarros=$('#carros').val();
        var texto=$('.texto-reporte').val();
        
    e.preventDefault();
    
    if(texto=="" ||comandante=="undefined"||Qbomberos==0||Qcarros==0){
    
    alert("Imposible generar reporte con campos vacíos");//SI ALGUNO DE LOS CAMPOS ES VACÍO SE DESPLIEGA ESTE MENSAJE
    }else{
        
        
        
   $.ajax({
    url:'insertarReporte.php',//SE INGRESA EL NUEVO REPORTE AL SISTEMA
    data:{  id_emergency:$('#id-emergencia').val(),
            address:$('#dir-emergencia').val(),
            priority:$('#prioridad').val(),
            comandante:$('#comandante').val(),
            bomberos:$('#bomberos').val(),
            carros:$('#carros').val(),
            reporte:$('.texto-reporte').val()},
        
    method:'POST',
    dataType:'json',
    async:false,
    success:function(datos){
        
    if(datos=="NO"){//ESTE IF SE ENCARGA DE VERIFICAR SI EL REPORTE YA EXISTE EN EL SISTEMA, SI EXISTE EL REPORTE SE ACTUAIZA, NO SE INGRESA DE NUEVO(REVISAR PHP)
        
       alert("Reporte actualizado!"); 
        window.close();//SE CIERRA VENTANA EMERGENTE
    }else if(datos=='LLENO'){
       alert("Ha sobrepasado el límite del contenido, no se pueden ingresar más caracteres");
   }else if(datos[0]=='FALTA'){
           
       alert("Ha sobrepasado el límite del contenido, se pueden ingresar hasta "+datos[1]+" nuevos caracteres");
   }else{   //CASO CONTRARIO, SI L REPORTE AÚN NO ES ALMACENADO, SE INGRESARÁ AL SISTEMA 
    
        alert("Reporte ingresado!");
        window.close();//SE CIERRA VENTANA EMERGENTE
            }
        
        },
    error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError);
        
      }
    });  
    }
    
    
    });
//ESTA FUNCIÓN SE ENCARGA DE DESPLEGAR UN REPORTE AL SER SELECCIONADO DE LA LISTA
$('#lista-reportes').on('click','li',function(){
    $('#cont-reporte').fadeOut(100);//SE OCULTA EL DIV QUE CONTIENE AL REPORTE
    $('#nro-reporte').text("");
    $('#fecha').text("");//SE INICIALIZAN LOS CAMPOS DEL REPORTE
    $('#datos-de-emergencia td').remove();
    $('#datos-bomberos td').remove();
    $('.scroll-bomberos ul li').remove();//SE INICIALIZAN LOS CAMPOS QUE CONTIENEN LOS DATOS DE LOS RECURSOS QUE ASISTEN LA EMERGENCIA DEL REPORTE
     $('#cont-reporte').fadeIn('fast');//LUEGO SE MUESTRA EL REPORTE Y SU CONTENIDO
    var id_reporte=$(this).attr("data-id");                       
        //alert($(this).attr("data-id"));
    $.ajax({
    url:'identificarReporte.php',//A TRAVÉS DEL ID DEL REPORTE SE ACCEDEN A LOS DATOS DEL MISMO
    data:{id_reporte:id_reporte},
    method:'POST',
    dataType:'json',
    success:function(datos_reporte_recuperado){
    
    for(var i in datos_reporte_recuperado){//SE EJECUTA UN FOR PARA INSERTAR LOS DATOS RELATIVOS AL REPORTE DE LA TABLA REPORTE
    $('#datos-de-emergencia').append('<td>'+datos_reporte_recuperado[i].id_emergency+'</td><td>'+datos_reporte_recuperado[i].address+'</td><td>'+datos_reporte_recuperado[i].priority+'</td>').fadeIn();//SE MUESTRAN LOS DATOS DE LA EMERGENCIA A LA QUE EL REPORTE ESTÁ ASOCIADO
        
    $('.Texto-reporte').val(''+datos_reporte_recuperado[i].reporte);//SE MUESTRA EL TEXTO DEL REPORTE
    $('#nro-reporte').text('Reporte nro '+datos_reporte_recuperado[i].id_reporte+':');//SE MUESTRA EL NUMERO DEL REPORTE(ID_REPORTE)
    $('#fecha').text("Fecha reporte: "+datos_reporte_recuperado[i].fecha_reporte+"");//SE MUESTRA LA FECHA DEL REPORTE
            }

        },
     error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError+"error emergencia");
        
      }
      });//FIN AJAX REPORTE        
    
    $.ajax({
    url:'nombreBomberos.php',//ESTA LLAMADA AJAX SE ENCARGA DE MOSTRAR LOS BOMBEROS PARTICIPANTES EN EL INCENDIO Y QUE ESTÁN EN EL REPORTE
    data:{id_reporte:id_reporte},
    method:'POST',
    dataType:'json',
    success:function(datos_bomberos){
    var a=1;//CONTADOR QUE PERMITE LLEVAR LA CUENTA DE LOS POSIBLES COMANDANTES INCIDENTES CON RESPECTO A LA ITERACIÓN PRINCIPAL DEL FOR
    var b=1;//CONTADOR QUE PERMITE LLEVAR LA CUENTA DE LOS BOMEROS PARTICIPANTES CON RESPECTO A LA ITERACIÓN PRINCIPAL DEL FOR
        for(var i in datos_bomberos){
            
            if(datos_bomberos[i].firefighter_type=='Comandante Incidente'){
                $('#nombre-comandante ul').append('<li>'+b+': '+datos_bomberos[i].firefighter_name+'</li>');//SE AÑADE AL COMANDANTE INCIDENTE EN EL ESPACIO DE COMANDANTE INCIDENTE
                $('#lista-bomberos ul').append('<li>'+a+': '+datos_bomberos[i].firefighter_name+'</li>');
                b++;//SE AÑADE AL COMANDANTE INCIDENTE EN EL ESPACIO DE BOMBEROS PARTICIPANTES
                
            }else{
                $('#lista-bomberos ul').append('<li>'+a+': '+datos_bomberos[i].firefighter_name+'</li>');
                //SE AÑADE AL RESTO DE BOMBEROS PARTICIPANTES
            }
            a++;
            
        }
        },
     error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError+"error bomberos");
        
      }
      });//FIN AJAX BOMBEROS
    
    $.ajax({
    url:'nombreCarros.php',//LLAMADA AJAX UTILIZADA PARA RECUPERAR LOS DATOS DE LOS CARROS BOMBA QUE ASISTEN LA EMERGENCIA A LA QUE EL REPORTE ESTÁ ASOCIADO
    data:{id_reporte:id_reporte},
    method:'POST',
    dataType:'json',
    success:function(datos_carros){
    var a=1;//CONTADOR QUE PERMITE LLEVAR LA CUENTA DE LOS CARROS BOMBA PARTICIPANTES CON RESPECTO A LA ITERACIÓN PRINCIPAL DEL FOR
        for(var i in datos_carros){
            $('#lista-carros ul').append('<li>'+a+': '+datos_carros[i].fire_truck_name+'</li>');
            
            a++;
        }
        },
     error: function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError+"error carros");
        
      }
      });//FIN AJAX CARROS BOMBA
     }); //FIN REVISAR REPORTES
    
//ESTA FUNCIONALIDAD DESPLIEGA LOS RECURSOS DIGITALES AL SER SELECCIONADA UNA EMERGENCIA DEL LISTADO IZQUIERDO
$('#lista-emergencias').on('click','li',function(){
    $('#no-existen').hide();
    $('#galeria img').remove();
    $('#galeria iframe').remove();
    $('#galeria video').remove();//SE INICALIZAN LOS CAMPOS
    var id_emergencia=$(this).attr("data-id");//SE RECUPERA EL IDENTIFICADOR DE LA EMERGENCIA ENCONTRADO EN LA FUNCION LISTAR EMERGENCIAS
    $.ajax({
            url:'listarImagenes.php',//SE REALIZA LLAMADA AJAX QUE SE ENCARGA DE LISTAR LOS RECURSOS DIGITALES ASOCIADOS A LA EMERGENCIA
            data:{id_emergency:id_emergencia},
            method:'POST',
            dataType:'json',
            beforeSend:function(){
                
                $('#cargando').show();
                
                
            },
            success: function(data){
                
                $('#cargando').hide();
                
                if(data==0){
                    $('#no-existen').show();//SI NO EXISEN RECURSOS DIGITALES SE DESPLIEGA UN MENSAJE
                }
                else{
                    $('#no-existen').hide();//SI EXISTEN RECURSOS DIGITALES ASOCIADOS SE OCULTA EL MENSAJE DE INEXISTENCIA
                for(var i in data)    //LUEGO SE AÑADEN LOS NODOS QUE CORRESPONDEN A LAS IMÁGENES DE LOS RECURSOS DIGITALES A DESPLEGAR
                    
                    $('#galeria').append(data[i]);
                    
                    
                }
                
            },
            error:function (xhr, ajaxOptions, thrownError) {
                alert("error"+xhr.status+""+thrownError);
        
            }
        
        
        });
    
    
});
    
    $('.tab').on('click',function(){
    $('.tab').removeClass("tab").addClass("tab-ocupado");
    $( this).removeClass( "tab-ocupado" ).addClass( "tab" );
    
    });//ESTO ACTUALIZA LA APARIENCIA Y ATRIBUTOS DE LOS BOTONES SUPERIORES DE GENERAR REPORTE, REVISAR REPORTES Y GALERPIA DE IMÁGEES
    
  
    $('.tab-3').click(function(){//AL SELECCIONAR EL BOTÓN DE GALERÍA SE OCULTAN/BORRAN LAS SECCIONES DE GENERAR REPORTE, REVISAR REPORTES Y SE INICIALIZA LA SECCIÓN DE GALERÍA
        document.getElementById("forma-gestor").reset();
        $(this).prop("disabled",true);
        $('.tab-2').prop("disabled",false);
        $('.tab-1').prop("disabled",false);
        $('.lector-reportes').hide();
        $('.generador-reportes').hide();
        $('.imagenes-emg').show();
        $('#lista-reportes li').remove();
        listarEmergencias();
    });
    $('.tab-2').click(function(){//AL SELECCIONAR EL BOTÓN DE GALERÍA SE OCULTAN/BORRAN LAS SECCIONES DE GENERAR REPORTE Y GALERÍA DE IMÁGENES
        $('.tab-1').prop("disabled",false);
        $('.tab-3').prop("disabled",false);
        $('.tab-2').prop("disabled",true);
        $('.imagenes-emg').hide();
        $('.generador-reportes').hide();
        $('.lector-reportes').show();
        $('#lista-emergencias li').remove();
        document.getElementById("forma-gestor").reset();
        
        $.ajax({
        url:'recuperarReportes.php',
        data:{},
        method:'POST',
        dataType:'json',
        success:function(datos_reporte){
        var cont=0;
        for(var i in datos_reporte){
        $('#lista-reportes').append('<li data-id='+datos_reporte[i].id_reporte+'><div id="elemento"><div id="datos"><p>Id reporte:'+datos_reporte[i].id_reporte+'</p><p>Id emergencia:'+datos_reporte[i].id_emergency+'</p></div><div id="direccion"><p>'+datos_reporte[i].address+'</p></div></div></li>');
        
        }
          },
        error:function (xhr, ajaxOptions, thrownError) {
        alert("error"+xhr.status+""+thrownError);
        
      }
        
        });
        
    
    });
    $('.tab-1').click(function(){//AL SELECCIONAR EL BOTÓN DE GENERAR REPORTES SE OCULTAN/BORRAN LAS SECCIONES DE REVISAR REPORTES Y GALERÍA DE IMÁGENES
         $('.lector-reportes').hide();
         $('.imagenes-emg').hide();
         $('.generador-reportes').show();
         $('#lista-reportes li').remove();
         $('#lista-emergencias li').remove();
         $('.tab-3').prop("disabled",false);
         $('.tab-2').prop("disabled",false);
         $('.tab-1').prop("disabled",true);
         $('#cont-reporte').hide();
            mostrarDatos();
    });
    
    //ESTA FUNCIONALIDAD SE ENCARGA DE ABRIR UNA VENTANA EMERGENTE AL SELECCIONAR UNA DE LAS IMÁGENES DE LA GALERÍA
    $('#galeria').on('click','img',function(){
        
        var url=$(this).attr("src");
        var imagenVentana=window.open(url,'Mi ventana','width=600,height=600,resizable=0,titlebar=0,menubar=0');
            
    });
    //ESTA FUNCIONALIDAD SE ENCARGA DE ABRIR UNA VENTANA EMERGENTE AL SELECCIONAR UNO DE LOS VIDEOS DE LA GALERÍA
    $('#galeria').on('click','iframe',function(){
        
        var url=$(this).attr("src");
        var imagenVentana=window.open(url,'Mi ventana','width=600,height=600,resizable=0,titlebar=0,menubar=0');
            
    });
</script>    
</body>

</html>
