<?php

	$response = array();
	

	if (isset($_POST["id_device"])) {
		
		$id_device = $_POST["id_device"];
		
		
		require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
		$db = new DB_CONNECT();
	 
		$result = pg_query("select * from get_user_data('".$id_device."');");

		if($result === false){ //Ocurrió un error al intentar realizar la consulta

			ob_clean(); //Limpia los errores posiblemente mostrados via PHP

			$response["success"] = 0;
			$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
			echo json_encode($response);
	
		}
		else{

			$num_rows = pg_num_rows($result);
		 
			if ($num_rows > 0) {
		 
		 			$user_data = array();
				
					$row = pg_fetch_array($result); // Sólo debe retornar 1 fila, por lo que no hay que hacer un while
					
					//Los nombres de los elementos del array $user_data deben ser iguales a los valores de las constantes respectivas en la clase LocalRepositorySchema
					$user_data["name"] = $row['dynamic_resource_name']; 
		 			$user_data["institution"] = $row['institution']; 
					$user_data["type"] = $row['dynamic_resource_type']; 
				
					$response["user_data"] = array();
					array_push($response["user_data"], $user_data); 
				
				
					$response["success"] = 1;
					echo json_encode($response); // remitiendo la respuesta a través de JSON (formato ligero de intercambio de datos)

			}
		
			else {

				$response["success"] = 0;
				$response["message"] = "Usted no es usuario";
				echo json_encode($response);

			}
		
		}

	}
	
	else {

		$response["success"] = 0;
		$response["message"] = "No se han completado los campos requeridos";
		echo json_encode($response);

	}

?>
