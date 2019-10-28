<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
$_SESSION['inicio_de_sesion'] = date('Y-m-d H:i:s');

date_default_timezone_set("America/La_Paz");

class PDO_database extends PDO{   
	var $pdo;
	public function __construct($pdo=NULL){
		if ($pdo==NULL){
	        $this->engine = 'mysql';		
			$this->host = 'localhost'; 		// mysql.zz.com.ve 			
			$this->port = '3306';
			$this->database =  'ebe_tracking'; 
			$this->user = 'root';   		
			$this->pass = '';				
			$dns = $this->engine.':dbname='.$this->database.";charset=utf8;host=".$this->host;
			
			try {
				$pdo = parent::__construct( $dns, $this->user, $this->pass );
			} catch (PDOException $e) {
				echo 'Conexin Fallida: <br/> Ha ocurrido un error al intentar establecer conexion con el servidor. Por favor contacte con el administrador o custodio de esta aplicacin para notificarle acerca de este error. '. $e->getMessage();
				exit;
			}
			
			$this->pdo= $this;
			$this->reg_padre=NULL;
			$this->reg_padre_detalle=NULL;
			
		}else{

			$this->pdo = $pdo;
			$this->reg_padre[$this->cont]=$pdo->reg_padre[$pdo->cont]; 
		}
	}

	public function _insert($tabla, $atributos){
		$comillas = "";
	    $columnas = array_keys($atributos);
		$insert = "INSERT INTO ".$tabla." (";
		
		for( $i = 0 ; $i < count( $columnas ) ; $i++){
			$insert .= ($i+1 == count($columnas) ) ? $columnas[$i].") VALUES (" : $columnas[$i].", ";

		}
				
		for($i=0 ; $i< count($atributos); $i++){
			$comillas = ( ( substr_count( $atributos[$columnas[$i]], '$-' ) ) >= 1 ) ? "" : "'";
			$insert .= ($i+1 == count($atributos)) ? $comillas.$atributos[$columnas[$i]].$comillas.");" : $comillas.$atributos[$columnas[$i]].$comillas.", ";

		}	
			$r = $this->pdo->exec($insert);
			if ( !$r ){
				$error = $this->pdo->errorInfo();
				return $error;
			}else{
				return $r;
			}
			
	}

	public function _query($sql){
		$r = $this->pdo->query($sql);
		if( $r != NULL){
	  		$result = $r->fetchALL(PDO::FETCH_ASSOC);
			return $result;
		}else{
			$error = $this->pdo->errorInfo();
			return $error;
		}
	}

	public function _delete($tabla, $condiciones){ 
		$columCond = array_keys($condiciones);
		$delete = "DELETE FROM ".$tabla;

	if(is_array($condiciones)){
			$delete .= ' WHERE ';

			for($i=0 ; $i< count($condiciones); $i++)

				$delete .= ($i+1 == count($condiciones)) ? $columCond[$i]."='".$condiciones[$columCond[$i]]."';" : $columCond[$i]."='".$condiciones[$columCond[$i]] ."' AND ";

		}
		$r = $this->pdo->exec($delete);
		if ( !$r ){
			$error = $this->pdo->errorInfo();
			return $error;
		}else{
			return $r;
		}
	}

	public function _update($tabla, $datos, $condiciones){ 
		$columCond = array_keys($datos);
		$update = "UPDATE ".$tabla;

	if(is_array($datos)){
		$update .= ' SET ';

		for( $i = 0 ; $i < count($datos) ; $i++ ){
			$update .= ( $i+1 == count($datos) ) ? $columCond[$i]."='".$datos[$columCond[$i]]."' " : $columCond[$i]."='".$datos[$columCond[$i]] ."' , ";
		}

		$update .= " where ".$condiciones;

		$r = $this->pdo->exec($update);
		if ( !$r ){
			$error = $this->pdo->errorInfo();
			return $error;
		}else{
			return $r;
		}
	}
	}
}

function clean_input($value){
	$bad_chars = array("{", "}", "(", ")", ";", ":", "<", ">", "/", "$", "\"", "'");
	$value = str_ireplace($bad_chars,"",$value);	// This part below is really overkill because the string replace above removed special characters
	//$value = htmlentities($value); // Removes any html from the string and turns it into &lt; format
	$value = strip_tags($value); // Strips html and PHP tags
	if (get_magic_quotes_gpc()){
		$value = stripslashes($value); // Gets rid of unwanted quotes
	}
	return $value;
}

foreach($_REQUEST as $nombre_campo => $valor_campo){
	$_REQUEST[$nombre_campo] = clean_input($valor_campo);
}

?>