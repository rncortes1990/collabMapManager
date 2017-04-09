<?php

	// Activa el buffer de salida. Esto es util para obtener el tamaño de los contenidos dinámicamente (Nota: Las salidas del script no serán enviadas hasta liberar el buffer de salida con ob_end_flush)
	ob_start();

	$response = array();
	

	if (isset($_POST["digital_resource_name"])) {
		
		$digital_resource_name = $_POST["digital_resource_name"];
		
		
		require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
		$db = new DB_CONNECT();
	 
		$result = pg_query("select * from get_digital_resource('".$digital_resource_name."');");
	 
		if($result === false){ //Ocurrió un error al intentar realizar la consulta
			
			ob_clean(); //Limpia los errores posiblemente mostrados via PHP

			header('Content-type: text/html');
			$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
			echo json_encode($response);
			
		}
		else{

		 	$row = pg_fetch_array($result); // Siempre retornará una fila
			$res = base64_decode($row['get_digital_resource']);
		
			if (strpos($res, 'error:') == false) {
			
				header('Content-type: application/octet-stream'); 	
				echo $res; // El archivo es enviado como una cadena binaria. No se utiliza JSON debido a que pueden ocurrir errores al intentar leer y/o convertir una cadena de texto de gran tamaño en JAVA (Nota: No es posible enviar una cadena binaria a través de JSON sin que ésta sea codificada como texto, por ejemplo, en base64).

			}
		
			else {

				header('Content-type: text/html');
				$response["message"] = $res;	//Errores retornados por la función SQL
				echo json_encode($response);

			}
		
		}
		
	}
	
	else {

		header('Content-type: text/html');				
		$response["message"] = "No se han completado los campos requeridos";
		echo json_encode($response);
	}

	
	$size = ob_get_length(); // Almacenando el tamaño de los contenidos en el buffer interno y copiando este valor en una variable
	header("Content-Length: ".$size); // Estableciendo el valor anterior en una de las cabeceras
	ob_end_flush(); // Liberando lo almacenado en el buffer interno para que la salidas del script sean enviadas

?>
