<?php 

Class Database {
	var $rs = NULL;   	//recordset de la ejecucion
	var $dbh = NULL;	// handler de la conexion a la base de datos
	var $numrows = 0;	// numero de filas del ultimo query ejecutado

	function Database() {		//constructor
		$this->connect();
	}

	function connect() {
		global $conf;
		$host =$conf['server']['host'];
		$user =  $conf['server']['user'];
		$pass =  $conf['server']['pswd']; 
		if ( $conf['server']['conexion_persistente'] ) {
        	$dbh = mssql_pconnect($host, $user, $pass);
	    } else  {
        	$dbh = mssql_connect($host, $user, $pass);
    	}

    	if (! is_resource($dbh)) {
        	echo "No es posible conectarse con el servidor de Base de Datos!.";
	        exit;
   		} else {
		    $rc = mssql_select_db ($conf['server']['default_db'], $dbh);
    		$this->dbh = $dbh;
		}
	}
	
	function execute ($sqlstring, $tran_type="notran") {
		if ($tran_type == "tran") {
			// todos los sp's involucrados 
			// en este sitio realizan control de transacciones cuando
			// corresponde (cta. personal, reservas, etc...)
			$this->rs = &mssql_query($sqlstring, $this->dbh);
		} else {
			$this->rs = &mssql_query($sqlstring, $this->dbh);
		}
		$this->check_numrows();
    }

	function check_numrows() {
		if ($this->rs == FALSE) {
			$this->numrows = 0;
		} elseif (is_resource($this->rs)) {
			$this->numrows = mssql_num_rows($this->rs);
		} else {
			$this->numrows = 0;
		} 
	}

	function get_row() {
		if ($this->rs == FALSE) {
			return array();
		} elseif (is_resource($this->rs)) {
			return mssql_fetch_assoc($this->rs);
		} else {
			return array();
		} 
	}

	function get_numrows() {
		return $this->numrows;
	}

	function close() {
		mssql_close($this->dbh);
	}
}		

?>
