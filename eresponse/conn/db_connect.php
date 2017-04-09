<?php

class DB_CONNECT{

    //__construct + __destruct: A partir de PHP 5 es posible declarar constructores y destructores en la clases utilizando tales nomenclaturas 
    function __construct() { // Lo que se inicializa aqui...
  
        $this->connect();
		
    }
 
    function __destruct() { // Se destruye ac�.

        $this->disconnect();
		
    }
	
    function connect() {
        
		//require: funciona igual que la funci�n include, s�lo que si el archivo no existe, el resto del c�digo no se ejecuta. Adem�s, "once" hace que s�lo se incluya si no se ha inclu�do antes
		require_once __DIR__ .'/../config/db_config.php';// incluyendo la clase "db_config"
 
 		$con = pg_connect("host=".DB_SERVER.
						" dbname=".DB_DATABASE.
						" user=".DB_USER.
						" password=".DB_PASSWORD." ") or die("No se ha podido establecer la conexi�n..."); // die: termina la ejecuci�n del script PHP
 
        return $con; //retorna el cursor de conexion
    }
 
    function disconnect() {
        
        pg_close();
		
    }
 
}

?>
