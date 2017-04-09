<?php

	$response = array();
	
	
	require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
	$db = new DB_CONNECT();
	 
	$result = pg_query("select * from view_digital_resource_list;");

	if($result === false){ //Ocurrió un error al intentar realizar la consulta

		ob_clean(); //Limpia los errores posiblemente mostrados via PHP

		$response["success"] = 0;
		$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
		echo json_encode($response);
	
	}
	else{

		$num_rows = pg_num_rows($result);
		 
		if ($num_rows > 0) {

			$response["digital_resource"] = array();
		 
			while ($row = pg_fetch_array($result)) {	

				$digital_resource = array(); // Array temporal - Para que se conozca los elementos que van en cada fila
				
				//Los nombres de los elementos del array $digital_resource deben ser iguales a los valores de las constantes respectivas en la clase LocalRepositorySchema
				$digital_resource["digital_resource_name"] = $row["digital_resource_name"];
				$digital_resource["format"] = $row["format"];
				$digital_resource["file_size_in_bytes"] = $row["file_size_in_bytes"];
				$digital_resource["description"] = $row["description"];
				$digital_resource["date_sent"] = $row["date_sent"];
				$digital_resource["related_emergency"] = $row["related_emergency"];
				$digital_resource["sender"] = $row["sender"];
				$digital_resource["latitude"] = $row["latitude"];
				$digital_resource["longitude"] = $row["longitude"];

				$digital_resource["addressees"] = $row["addressees"];


				array_push($response["digital_resource"], $digital_resource); // ubica el recurso estático actual al final de la matriz
		
			}

			$response["success"] = 1;
			echo json_encode($response); // remitiendo la respuesta a través de JSON
		
		} 
	
		else {

			$response["success"] = 0;
			$response["message"] = "No se han encontrado recursos digitales";
			echo json_encode($response);

		}

	}

?>
