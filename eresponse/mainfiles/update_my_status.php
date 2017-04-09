<?php

	$response = array();
	 

	if (isset($_POST['dynamic_resource_name']) && isset($_POST['assisted_emergency'])) {
	 
		$dynamic_resource_name = $_POST['dynamic_resource_name'];
	    $assisted_emergency = $_POST['assisted_emergency'];
	 	$status = $_POST['status'];
	 

	    require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
		$db = new DB_CONNECT();
	

		pg_query("SET client_encoding TO 'latin1'"); // Para permitir el ingreso de caracteres especiales en la BD

		if(!empty($status))
			$result = pg_query("select * from update_my_status('".$dynamic_resource_name."',".$assisted_emergency.",'".$status."');");
	 	else
			$result = pg_query("select * from update_my_status('".$dynamic_resource_name."',".$assisted_emergency.",null);");

		pg_query("SET client_encoding TO 'UTF8'");



		if($result === false){ //Ocurrió un error al intentar realizar la consulta

			ob_clean(); //Limpia los errores posiblemente mostrados via PHP

			$response["success"] = 0;
			$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
			echo json_encode($response);
	
		}
		else{

			$row = pg_fetch_array($result); // Siempre retornará solo una fila
			$res = $row['update_my_status'];
		
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
