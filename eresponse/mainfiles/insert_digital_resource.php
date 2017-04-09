<?php

	$response = array();
	 

	if (isset($_POST['format']) && ($_FILES['file']['size'] != 0) && isset($_POST['description']) &&  isset($_POST['related_emergency']) && isset($_POST['sender']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['addressees'])) {
		
		$format = $_POST['format'];
		$file = file_get_contents($_FILES['file']['tmp_name']); // El archivo se envia como tal y no como una cadena de texto o una cadena binaria, ya que en archivos de mayor tamaño pueden ocurrir errores al trata de enviarlos
		$file_in_base64 = base64_encode($file);
		$description = $_POST['description'];
		$related_emergency = $_POST['related_emergency'];
	    $sender =  $_POST['sender'];
	    $latitude =  $_POST['latitude'];
	    $longitude =  $_POST['longitude'];
		
	 	$tmp_addressees =  $_POST['addressees'];
		$addressees = "{";
		
		for($i = 0; $i < count($tmp_addressees); $i++){
			
			if($i < (count($tmp_addressees) - 1))
				$addressees .= $tmp_addressees[$i].", ";
			
			else
				$addressees .= $tmp_addressees[$i]."}";
			
		}

	    require_once __DIR__ .'/../conn/db_connect.php';// incluyendo la clase "db_connect"
		$db = new DB_CONNECT();

	
		pg_query("SET client_encoding TO 'latin1'"); // Para permitir el ingreso de caracteres especiales en la BD

		if(!empty($description))
		$result = pg_query("select * from insert_digital_resource('".$format."','".$file_in_base64."','".$description."',".$related_emergency.",'".$sender."',".$latitude.",".$longitude.",'".$addressees."');");

		else
		$result = pg_query("select * from insert_digital_resource('".$format."','".$file_in_base64."',null,".$related_emergency.",'".$sender."',".$latitude.",".$longitude.",'".$addressees."');");		
	
		pg_query("SET client_encoding TO 'UTF8'");



		if($result === false){ //Ocurrió un error al intentar realizar la consulta

			ob_clean(); //Limpia los errores posiblemente mostrados via PHP

			$response["success"] = 0;
			$response["message"] = pg_last_error();	//Obtiene los errores anteriores directamente de la consulta fallida
			echo json_encode($response);

		}
		else{

			$row = pg_fetch_array($result); // Siempre retornará solo una fila
			$res = $row['insert_digital_resource'];
		
			if ($res == 'ok') {
			
				$response["success"] = 1;
				echo json_encode($response);
			
			} 		

			else { 

				$response["success"] = 0;
				$response["message"] = $res;	//Errores retornados por la función SQL
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
