<?php

	$response = array();
	 

	if (isset($_POST['dynamic_resource_name']) && isset($_POST['latitude']) && isset($_POST['longitude']) ) {
	 
		$dynamic_resource_name = $_POST['dynamic_resource_name'];
		$latitude = $_POST['latitude'];
		$longitude = $_POST['longitude'];
	 

	    require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
		$db = new DB_CONNECT();
	
		$result = pg_query("select * from update_my_location('".$dynamic_resource_name."',".$latitude.",".$longitude.");");
	 	
		if($result === false){ //Ocurrió un error al intentar realizar la consulta

			ob_clean(); //Limpia los errores posiblemente mostrados via PHP

			$response["success"] = 0;
			$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
			echo json_encode($response);
	
		}
		else{

			$row = pg_fetch_array($result); // Siempre retornará solo una fila
			$res = $row['update_my_location'];
		
			if ($res == 'ok') {
			
				$response["success"] = 1;
				echo json_encode($response);
			
			} 
		
			else {

				$response["success"] = 0;
				$response["message"] = $res;
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
