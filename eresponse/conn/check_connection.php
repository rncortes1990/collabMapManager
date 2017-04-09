<?php

	$response = array();

	require_once __DIR__ .'/../config/db_config.php';
 
 	$con = pg_connect("host=".DB_SERVER.
					" dbname=".DB_DATABASE.
					" user=".DB_USER.
					" password=".DB_PASSWORD." ");

	if($con != null){
	
		$response["success"] = 1;
		echo json_encode($response);

	}
	else{

		$response["success"] = 0;
		echo json_encode($response);
	
	}

?>
