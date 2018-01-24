<?php
include "clases/class.ConfigMagik.php";

class config{
    public $queryString;
    public $temperaturaAmbiente;
    public $temperaturaReferencia;
    public $humedadRelativa;
    public $costoCombustibleGas;
    public $costoCombustibleLiquido;    
    public $paridadCambiaria;
    public $Cpaire;
    public $densidadAire;
    public $densidadAgua;
    public $eficienciaRadiacionGas;
    public $eficienciaRadiacionLiquido;
    public $nombreAplicacion;
	public $ruta;
    public $rutaAccesos;
    public $rutaQuery;
    
    public $refinerias = array(
                            array("codigo" => 1, 
                                  "proceso" => "http://plcgua14/wsinfoplus/wsinfoplus.asmx?wsdl", 
                                  "laboratorio" => "http://plcgua14/wssqllims/wssqllims.asmx?wsdl"),
                            array("codigo" => 2, 
								  "proceso" => "http://crplx02/wsProcesos/interfaz/wsProceso.php?wsdl", 
								  "laboratorio" => "http://crplx02/wsLaboratorio/interfaz/wsLaboratorio.php?wsdl")
                        );
    
     public function obtenerRefineria($codigo){
        $c = count($this->refinerias);
        
        for($i=0; $i<$c; $i++){
            if($this->refinerias[$i]["codigo"] == $codigo){
                return $this->refinerias[$i];
            }
        }
    }
    
    function config(){
    	$path = '../configEfiterm.ini';
    	
		$Config = new ConfigMagik(true, true, $path);

	    $this->queryString = $Config->get('queryString', 'configuracion');
	    $this->temperaturaAmbiente = (float) $Config->get( 'temperaturaAmbiente', 'configuracion');
	    $this->temperaturaReferencia = (float) $Config->get( 'temperaturaReferencia', 'configuracion');
	    $this->humedadRelativa = (float) $Config->get( 'humedadRelativa', 'configuracion');
	    $this->costoCombustibleGas = (float) $Config->get( 'costoCombustibleGas', 'configuracion');
	    $this->costoCombustibleLiquido = (float) $Config->get( 'costoCombustibleLiquido', 'configuracion');
	    $this->paridadCambiaria = (float) $Config->get( 'paridadCambiaria', 'configuracion');
	    $this->Cpaire = (float) $Config->get( 'Cpaire', 'configuracion');
	    $this->densidadAire = (float) $Config->get( 'densidadAire', 'configuracion');
	    $this->densidadAgua = (float) $Config->get( 'densidadAgua', 'configuracion');
	    $this->eficienciaRadiacionGas = (float) $Config->get( 'eficienciaRadiacionGas', 'configuracion');
	    $this->eficienciaRadiacionLiquido = (float) $Config->get( 'eficienciaRadiacionLiquido', 'configuracion');
	    $this->nombreAplicacion = $Config->get( 'nombreAplicacion', 'configuracion');
		$this->ruta = $Config->get( 'ruta', 'configuracion');
	    $this->rutaAccesos = $Config->get( 'rutaAccesos', 'configuracion');
	    $this->rutaQuery = $Config->get( 'rutaQuery', 'configuracion');
	    $this->rutaError = $Config->get( 'rutaError', 'configuracion');
	}
}
?>
