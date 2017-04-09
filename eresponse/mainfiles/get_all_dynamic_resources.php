<?php

	$response = array();
	
	
	require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
	$db = new DB_CONNECT();
	 
	$result = pg_query("select * from view_dynamic_resource;");

	if($result === false){ //Ocurrió un error al intentar realizar la consulta

		ob_clean(); //Limpia los errores posiblemente mostrados via PHP

		$response["success"] = 0;
		$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
		echo json_encode($response);
	
	}
	else{

		$num_rows = pg_num_rows($result);
		 
		if ($num_rows > 0) {

			$response["dynamic_resource"] = array();
		 
			while ($row = pg_fetch_array($result)) {

				$dynamic_resource = array(); // Array temporal - Para que se conozca los elementos que van en cada fila
				
				//Los nombres de los elementos del array $dynamic_resource deben ser iguales a los valores de las constantes respectivas en la clase LocalRepositorySchema
				$dynamic_resource["name"] = $row["dynamic_resource_name"];
				$dynamic_resource["latitude"] = $row["latitude"];
				$dynamic_resource["longitude"] = $row["longitude"];
				$dynamic_resource["institution"] = $row["institution"];
				$dynamic_resource["type"] = $row["dynamic_resource_type"];
				$dynamic_resource["assisted_emergency"] = $row["assisted_emergency"];
				$dynamic_resource["status"] = $row["status"];
				$dynamic_resource["date_sent_of_status"] = $row["date_sent_of_status"];
		 		$dynamic_resource["date_sent_of_location"] = $row["date_sent_of_location"];

		 
				array_push($response["dynamic_resource"], $dynamic_resource); // ubica el recurso dinámico actual al final de la matriz
		
			}

			$response["success"] = 1;
			echo json_encode($response); // remitiendo la respuesta a través de JSON
		
		}
		else{

			$response["success"] = 0;
			$response["message"] = "No se han encontrado recursos dinamicos";
			echo json_encode($response);

		}
	
	}
	
?>
