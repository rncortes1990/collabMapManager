<?php

	$response = array();
	

	require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
	$db = new DB_CONNECT();
	 
	$result = pg_query("select * from view_emergency;");

	if($result === false){ //Ocurrió un error al intentar realizar la consulta

		ob_clean(); //Limpia los errores posiblemente mostrados via PHP

		$response["success"] = 0;
		$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
		echo json_encode($response);
	
	}
	else{

		$num_rows = pg_num_rows($result);
		 
		if ($num_rows > 0) {

			$response["emergency"] = array();
		 
			while ($row = pg_fetch_array($result)) {

				$emergency = array(); // Array temporal - Para que se conozca los elementos que van en cada fila
				
				//Los nombres de los elementos del array $emergency deben ser iguales a los valores de las constantes respectivas en la clase LocalRepositorySchema
				$emergency["id_emergency"] = $row["id_emergency"];
				$emergency["address"] = $row["address"];
				$emergency["priority"] = $row["priority"];
				$emergency["latitude"] = $row["latitude"];
				$emergency["longitude"] = $row["longitude"];
				$emergency["start_date"] = $row["start_date"];
		 
		 
				array_push($response["emergency"], $emergency); // ubica el recurso dinámico actual al final de la matriz
		
			}

			$response["success"] = 1;
			echo json_encode($response); // remitiendo la respuesta a través de JSON
		
		} 
		else{

			$response["success"] = 0;
			$response["message"] = "No se han encontrado emergencias";
			echo json_encode($response);

		}

	}

?>
