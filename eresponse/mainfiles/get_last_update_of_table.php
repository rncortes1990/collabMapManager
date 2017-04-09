<?php

	$response = array();
	
	if (isset($_POST["table_name"])) {
		
		$table_name = $_POST["table_name"];
		
	    require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
		$db = new DB_CONNECT();
	 
		$result = pg_query("select * from get_last_update_of_table('".$table_name."');");

		if($result === false){ //Ocurrió un error al intentar realizar la consulta

			ob_clean(); //Limpia los errores posiblemente mostrados via PHP

			$response["success"] = 0;
			$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
			echo json_encode($response);
	
		}
		else{

			$num_rows = pg_num_rows($result);
		 
		 	if ($num_rows > 0) {
		 
					$row = pg_fetch_array($result); // Sólo debe retornar 1 fila
		 			$response["last_update_of_table"] = $row['last_update']; //Elemento retornado
				
					$response["success"] = 1;
					echo json_encode($response); // remitiendo la respuesta a través de JSON (formato ligero de intercambio de datos)

			}
		
			else {

				$response["success"] = 0;
				$response["message"] = "No se han encontrado registros sobre la tabla ".$table_name;
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
