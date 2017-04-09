<?php

class DB_CONNECT{

    //__construct + __destruct: A partir de PHP 5 es posible declarar constructores y destructores en la clases utilizando tales nomenclaturas 
    function __construct() { // Lo que se inicializa aqui...
  
        $this->connect();
		
    }
 
    function __destruct() { // Se destruye acá.

        $this->disconnect();
		
    }
	
    function connect() {
        
		//require: funciona igual que la función include, sólo que si el archivo no existe, el resto del código no se ejecuta. Además, "once" hace que sólo se incluya si no se ha incluído antes
		require_once __DIR__ .'/../config/db_config.php';// incluyendo la clase "db_config"
 
 		$con = pg_connect("host=".DB_SERVER.
						" dbname=".DB_DATABASE.
						" user=".DB_USER.
						" password=".DB_PASSWORD." ") or die("No se ha podido establecer la conexión..."); // die: termina la ejecución del script PHP
 
        return $con; //retorna el cursor de conexion
    }
 
    function disconnect() {
        
        pg_close();
		
    }
 
}

?>
