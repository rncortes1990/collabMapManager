<?php

	$response = array();		
		
		require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
		$db = new DB_CONNECT();
	 
		$result = pg_query("select * from view_sync_period_in_seconds;");

		if($result === false){ //Ocurrió un error al intentar realizar la consulta

			ob_clean(); //Limpia los errores posiblemente mostrados via PHP

			$response["success"] = 0;
			$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
			echo json_encode($response);
	
		}
		else{

			$num_rows = pg_num_rows($result);
		 
			if ($num_rows > 0) {
		 				
					$row = pg_fetch_array($result); // Sólo debe retornar 1 fila, por lo que no hay que hacer un while
					
					$response["sync_period_in_seconds"] = $row['sync_period_in_seconds']; 				
				
					$response["success"] = 1;
					echo json_encode($response); // remitiendo la respuesta a través de JSON (formato ligero de intercambio de datos)

			}
		
			else {

				$response["success"] = 0;
				$response["message"] = "Usted no es usuario";
				echo json_encode($response);

			}
		
		}

?>
