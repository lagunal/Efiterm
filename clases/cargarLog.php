<?php
include_once "../config.php";

class Log{
	private $rutaAccesos = "";
	private $rutaQuery = "";
	private $rutaError = "";
	
	public $logAccesos = "logAccesos";
	public $logQuery = "logQuery";
	public $logError = "logError";
	
	function Log(){
		$conf = new config();
		
		$this->rutaAccesos = $conf->ruta . $conf->rutaAccesos;
		$this->rutaQuery = $conf->ruta . $conf->rutaQuery;
		$this->rutaError = $conf->ruta . $conf->rutaError;
	}
	
	public function guardarLog($log, $modulo, $info=""){
		$ruta = "";
	
		switch($log){
			case $this->logAccesos:
				$ruta = $this->rutaAccesos;
			break;
			case $this->logQuery:
				$ruta = $this->rutaQuery;
			break;
			case $this->logError:
				$ruta = $this->rutaError;
			break;
		}
		
		$fp = fopen($ruta, "a");

		if($fp){
			$tipoLog = "";
			$id = "";

			if(isset($_SESSION["id"]))
				$id = $_SESSION["id"];
			else
				$id = "DESCONOCIDO";
				
			$linea = @date("d/m/Y h:i:s a") . " | " . $_SERVER["REMOTE_ADDR"] . " | " . $id  . " | " . $modulo . " | " . $info . "\r";
			
			@fwrite($fp, $linea);
			@fclose($fp);
		}
	}
	
	public function cargarLog($archivo){
		$datos = array();
		$ruta = "";

		switch($archivo){
			case $this->logAccesos:
				$ruta = $this->rutaAccesos;
			break;
			case $this->logQuery:
				$ruta = $this->rutaQuery;
			break;
			case $this->logError:
				$ruta = $this->rutaError;
			break;
		}

		$fp = @fopen($ruta, "r");
		
		if($fp){
			$largo = filesize($ruta);
			
			if($largo > 0){
				$arch = fread($fp, $largo);
				$datos = explode("\r", $arch);
			}
		}
		
		return $datos;
	}
}
?>