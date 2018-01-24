<?php
include_once "../clases/cargarLog.php";

class postgresqlHelper{
    private $con;
    
    function postgresqlHelper($conString){
        $this->con = $conString;
    }
    
    private function guardarLog($query){
    	$tipos = array("INSERT", "UPDATE", "DELETE");
    	$excluir = array("t_sesion");
    	$guardar = false;
    	$exclu = false;
    	
    	foreach($excluir as $llave=>$valor){
    		if((strrpos($query, $valor)!==false)){
    			$exclu = true;
    			break;
    		}
    	}
    	
    	if(!$exclu){
	    	foreach($tipos as $llave=>$valor){
	    		if((strrpos($query, $valor)!==false)){
	    			$guardar = true;
	    			break;
	    		}
	    	}
    	}
    		
    	return $guardar;
    }
        
    private function ejecutarQuery($query, $param = array()){
    	$log = new Log();
    	
        $con = @pg_connect($this->con);
        
        if(count($param) == 0)
            $res = pg_query($con, $query);
        else
            $res = pg_query_params($con, $query, $param);
        
        
        if($this->guardarLog($query)){
        	$log->guardarLog($log->logQuery, "QUERY", "$query");
        }
        
        pg_close($con);
        
        return $res;
    }
    
    private function cargarArreglo($datos){
        $filas = pg_num_rows($datos);
        $res = array();
        
        for($i = 0; $i < $filas; $i++)
            $res[] = pg_fetch_array($datos, $i, PGSQL_ASSOC);
        
        return $res;
    }

    private function cargarArregloIndices($datos){
        $filas = pg_num_rows($datos);
        $res = array();
        
        for($i = 0; $i < $filas; $i++)
            $res[] = pg_fetch_row($datos);

        return $res;
    }
    
    public function obtenerDatos($query, $param = array()){
        return $this->cargarArreglo($this->ejecutarQuery($query, $param));
    }
    
    public function obtenerDatosIndices($query, $param = array()){
        return $this->cargarArregloIndices($this->ejecutarQuery($query, $param));
    }

    public function obtenerDatosXML($query){
        $datos = $this->ejecutarQuery($query);

        $filas = pg_num_rows($datos);

        for($i = 0; $i < $filas; $i++)
            $res[] = pg_fetch_object($datos);

        return $res;
    }

    public function actualizarDatos($query, $param = array()){
        return $this->ejecutarQuery($query, $param);
    }

    public function obtenerEscalar($query, $param = array()){
        return pg_result($this->ejecutarQuery($query, $param), 0);
    }
}
?>