<?php

	$response = array();
	
	
	require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
	$db = new DB_CONNECT();
	 
	$result = pg_query("select * from view_static_resource;");

	if($result === false){ //Ocurrió un error al intentar realizar la consulta

		ob_clean(); //Limpia los errores posiblemente mostrados via PHP

		$response["success"] = 0;
		$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
		echo json_encode($response);
	
	}
	else{

		$num_rows = pg_num_rows($result);
		 
		if ($num_rows > 0) {

			$response["static_resource"] = array();
		 
			while ($row = pg_fetch_array($result)) {

				$static_resource = array(); // Array temporal - Para que se conozca los elementos que van en cada fila
				
				//Los nombres de los elementos del array $static_resource deben ser iguales a los valores de las constantes respectivas en la clase LocalRepositorySchema
				$static_resource["name"] = $row["static_resource_name"];
				$static_resource["address"] = $row["address"];
				$static_resource["description"] = $row["description"];
				$static_resource["latitude"] = $row["latitude"];
				$static_resource["longitude"] = $row["longitude"];
				$static_resource["type"] = $row["static_resource_type"];
		 
				array_push($response["static_resource"], $static_resource); // ubica el recurso estático actual al final de la matriz
		
			}

			$response["success"] = 1;
			echo json_encode($response); // remitiendo la respuesta a través de JSON
		
		} 
		else {

			$response["success"] = 0;
			$response["message"] = "No se han encontrado recursos estáticos";
			echo json_encode($response);

		}

	}

?>
