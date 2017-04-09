$('.contenedor-campos').hide();//SE OCULTAN AMBOS CONTENDORES, REGISTRO E INGRESO
$('.btn-ingreso').hide();//EL BOTON PARA ACCEDER AL FORMULARIO DE INGRESO SE OCULTA
$('.cont1').fadeIn("slow");//SE MUESTRA EL FORMULARI DE INGRESO.


$('.id-usuario').keyup(function(){
            
  
         //AQUI PUEDE AÑADIRSE ALGUNA LÓGICA PARA CUADO SE ESTÉ ESCRIBIENDO DENTRO DEL INPUT DE IGRESO DE USUARIO   
            
            });
$('#forma-login').submit(function(e){ //ESTA FUNCIÓN LLAMA AL PHP QUE VERIFICA QUE LOS DATOS DEL USUARIO SEAN CORRECTOS O NO CORRECTOS PARA EFECTUAR EL LOG IN
    
    e.preventDefault();//PREVENT DEFAULT ES UNA CARACTERISTICA DE JQUERY PARA MODIFICAR EL COMPORTAMIENTO ESTANDAR QUE TIENE SUBMIT PARA RECARGAR LA PAGINA PHP O HTML.
    var id1=$('.id-usuario').val();
    var clave1=$('.clave-usuario').val();
     $('.aviso').hide();
    if(id1!="" &&clave1!="")//SI EL ID Y LA CLAVE SON DIFERENTES DE VACÍO SUCEDE LA LLAMADA AJAX DE MÁS ABAJO
    {
        $.ajax({
            url:'login.php',
            data:{id_usuario:id1, clave_usuario:clave1},//DATOS DE ENTRADA DE LA LLAMADA AJAX
            method:'POST',
            dataType:'json',
            async:false,
            success:function (data){
                //alert(""+data.answer);
                if(data.answer==0)
                {       
                    window.location.href='mapa.php';
                }
                if(data.answer==1){//SI EN EL RUT SE INGRESÓ UN VALOR DIFERENTE DE ENTERO, SE DESPLEGA EL MENSAJE
                        $('.aviso').css("background","rgba(255,0,0,0.8)").css("color","white");
                        $('.aviso').slideDown("slow").html('Valores ingresados no válidos'); 
                }
                if(data.answer==2){//SI NO HAY COINCIDENCIAS DE CLAVE O IDENTIFICADOR, SE DESPLIEGA EL MENSAJE
                        $('.aviso').css("background","rgba(255,0,0,0.8)").css("color","white");
                        $('.aviso').slideDown("slow").html('El usuario o la contraseña son incorrectos'); 
                }
        
                },
        error:function (xhr, ajaxOptions, thrownError) {
        alert(""+xhr.status+","+thrownError);
        
      }
        });

    }else//FALTA ALGUNO DE LOS CAMPOS COMO ID O CONTRASEÑA, SE DESPLIEGA EL MENSAJE
        {
                $('.aviso').css("background","rgba(255,255,0,0.8)").css("color","black");
                $('.aviso').slideDown("slow").html('Faltan campos por llenar');
            
        }


                         });

//ESTA FUNCIÓN SE ENCARGA DE LLAMAR AL PHP QUE SE ENCARGA DE REGISTRAR NUEVOS USUARIOS EN EL SISTEMA
$('#forma-registro').submit(function(e){
    
    var id1=$('.id-usuario-registro').val();
    var clave1=$('.clave-usuario-registro').val();
    var nombre=$('.nombre-usuario-registro').val();
    var tipo=$('.tipo-usuario-registro').val();
    var admin=$('.clave-admin').val();//SE CAPTURAN LOS DATOS DE INGRESO
    e.preventDefault();
    //alert(tipo);
    $('.aviso').hide();
        if(id1!="" && clave1!="" && nombre!="" && tipo!=null)//SI NINGUN CAMPO DEL FORMULARIO DE REGISTRO ES VACÍO, SUCEDE LA SIGUIENTE LLAMADA AJAX.
        {
            $.post('registroUsuario.php',{id_usuario:id1,
                                          clave_usuario:clave1,//DATOS DE ENTRADA DE LA LLAMADA AJAX
                                          nombre_usuario:nombre,
                                          tipo_usuario:tipo,
                                          clave_admin:admin},function (data){
    
            if(data==1)//SI EL RUN INGRESADO YA EXTISTE
            {
                $('.aviso').css("background","rgba(255,0,0,0.8)").css("color","white");
                $('.aviso').slideDown("slow").html('El RUN solicitado ya existe...Intente con otro');

            }
            else if(data==0)//SI LOS DATOS INGRESADOS SON TODOS VÁLIDOS
            {
                $('.aviso').css("background","rgba(0,255,0,0.8)").css("color","white");
                $('.aviso').slideDown("slow").html('Usuario registrado exitosamente');
                mostrarIngreso();//SE MUESTRA EL FORMULARIO DE INGRESO LUEGO DE REGISTRAR EXITOSAMENTE AL NUEVO USUARIO
                $('.cont2').delay(500).hide();
                $('.cont1').delay(800).fadeIn("slow");
                
            }else if(data==2){//SI SE INGRESA UN RUN NO ENTERO,SE DESPLIEGA EL MENSAJE
                $('.aviso').css("background","rgba(255,0,0,0.8)").css("color","white");
                $('.aviso').slideDown("slow").html('El RUN ingresado no es válido');  
            }else if(data==3){//SI LA CLAVE ADMINISTRADOR ES INCORRACTA SE DESPLIEGA EL MENSAJE
                
                $('.aviso').css("background","rgba(255,0,0,0.8)").css("color","white");
                $('.aviso').slideDown("slow").html('Clave de administrador incorrecta');  
            }

            },"json").fail(function (jqXHR, textStatus, error) {
        alert("Post error: " + error);
    });   
        }else//SI ALGUNO DE LOS CAMPOS ES VACÍO SE DESPLIEGA EL MENSAJE
            {

            $('.aviso').css("background","rgba(255,255,0,0.8)").css("color","black");
            $('.aviso').slideDown("slow").html('Faltan campos por llenar');

            }
});






function mostrarRegistro(){//FUNCIÓN QUE DESPLEGA EL FORMULARIO SIN RELLENAR
document.getElementById("forma-login").reset();
$('.id-usuario').val("");
$('.clave-usuario').val("");
$('.tipo-usuario-registro').val("");
$('.cont1').hide();
$('.cont2').fadeIn("fast");
$('.btn-registro').hide();
$('.btn-ingreso').show();
}
function mostrarIngreso(){//FUNCIÓN QUE DESPLIEGA EL FORMULARIO SIN RELLENAR
document.getElementById("forma-registro").reset();
$('.id-usuario-registro').val("");
$('.clave-usuario-registro').val("");
$('.nombre-usuario-registro').val("");
$('.cont1').fadeIn("fast");
$('.cont2').hide();
$('.btn-ingreso').hide();
$('.btn-registro').show();
}


$('.btn-registro').click(mostrarRegistro);//EVENTO QUE LLAMA A LA RESPECTIVA FUNCIÓN
$('.btn-ingreso').click(mostrarIngreso);//EVENTO QUE LLAMA A LA RESPECTIVA FUNCIÓN



