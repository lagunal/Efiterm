<?php
    include_once "../config.php";
    include_once "presentacion.php";
    
    class cladWS{
        private $config;
        private $datosRef;
        private $urlLaboratorio;
        private $urlProceso;
        
        function cladWS($codigoRefineria){
            $this->config = new config();

            $this->codigoRefineria = $codigoRefineria;
            $this->datosRef = $this->config->obtenerRefineria($this->codigoRefineria);
            
            $this->urlProceso = $this->datosRef["proceso"];
            $this->urlLaboratorio = $this->datosRef["laboratorio"];
        }
        
    //Interfaces **********************************************************************
    
        //Proceso
        function obtenerDatosTag($tag, $fechaInicio, $fechaFin, $frecuencia = ""){
            $ret = "";
            
            switch ($this->codigoRefineria){
                case 1:
                    $ret = $this->obtenerDatosTagPLC($tag, $fechaInicio, $fechaFin, $frecuencia);
                break;
                case 2:
                    $ret = $this->obtenerDatosTagCRP($tag, $fechaInicio, $fechaFin);
                break;
            }

            return $ret;
        }
        
        //Laboratorio
        function obtenerFechaRecienteBase($muestras, $fecha){
            $ret = "";
            
            switch ($this->codigoRefineria){
                case 1:
                    $ret = $this->obtenerFechaRecienteBasePLC($muestras, $fecha);
                break;
                case 2:
                    $ret = $this->obtenerFechaRecienteBaseCRP($muestras, $fecha);
                break;
            }

            return $ret;
        }

        function obtenerFechaReciente($muestra){
            $ret = "";

            switch ($this->codigoRefineria){
                case 1:
                    $ret = $this->obtenerFechaRecientePLC($muestra);
                break;
                case 2:
                    $ret = $this->obtenerMuestrasUltimaFechaCRP($muestra);
                break;
            }

            return $ret;
        }

        function obtenerMuestras($muestra, $fechaInicio, $fechaFin, $analisis){
            $ret = "";
            
            switch ($this->codigoRefineria){
                case 1:
                    $ret = $this->obtenerMuestrasPLC($muestra, $fechaInicio, $fechaFin, $analisis);
                break;
                case 2:
                    $ret = $this->obtenerMuestraAnalisisResultadosCRP($muestra, $fechaInicio, $fechaFin, $analisis);
                break;
            }

            return $ret;
        }

        
    //MATRIC (PROCESO) ****************************************************************************
        function obtenerUltimaFechaCRP($tag){
        	try{
	            $client = new SoapClient($this->urlProceso);
	            
	            $result = $client->obtenerUltimaFecha($tag);
	    
	            return $result;
        	}catch (Exception $e){
        		return "";
        	} 
        }
    
        function obtenerDatosTagCRP($tag, $fechaInicio, $fechaFin){
        	try{
	            $client = new SoapClient($this->urlProceso);
	
	            $result = $client->obtenerDatosTag($tag, $fechaInicio, $fechaFin);
	            $result = $this->cargarArregloWsPHP($result);
	    
	            return $result;
        	}catch (Exception $e){
        		return "";
        	} 
        }
        
        
    //SILA (LABORATORIO) ****************************************************************************
    
        function obtenerFechaRecienteBaseCRP($muestras, $fecha){
        	try{
	            $client = new SoapClient($this->urlLaboratorio);
	
	            $result = $client->obtenerMuestrasUltimaFechaBase($muestras, $fecha);
	            $result = $this->cargarArregloWsPHP($result);
	    
	            return $result;
        	}catch (Exception $e){
        		return "";
        	} 
        }

        function obtenerAnalisisMuestrasCRP($muestra, $fechaInicio, $fechaFin){
        	try{
	            $client = new SoapClient($this->urlLaboratorio);
	            
	            $result = $client->obtenerAnalisisMuestra($muestra, $fechaInicio, $fechaFin);
	            $result = $this->cargarArregloWsPHP($result);
	    
	            return $result;
        	}catch (Exception $e){
        		return "";
        	} 
        }
        
        function obtenerMuestraAnalisisResultadosCRP($muestra, $fechaInicio, $fechaFin, $analisis){
        	try{
	            $client = new SoapClient($this->urlLaboratorio);
	            
	            //echo "$muestra, $fechaInicio, $fechaFin, $analisis";
	            
	            $result = $client->obtenerMuestraAnalisisResultados($muestra, $fechaInicio, $fechaFin, $analisis);
	            $result = $this->cargarArregloWsPHP($result);
	
	            return $result;
        	}catch (Exception $e){
        		return "";
        	} 
        }

        function obtenerMuestrasUltimaFechaCRP($muestra){
        	try{
	            $client = new SoapClient($this->urlLaboratorio);
	            
	            $result = $client->obtenerMuestrasUltimaFecha($muestra);
	    
	            return $result;
        	}catch (Exception $e){
        		return "";
        	} 
        }

    //INFOPLUS ****************************************************************************
        function obtenerDatosTagPLC($tag, $fechaInicio, $fechaFin, $frecuencia){
            $client = new SoapClient($this->urlProceso);

            $params->tag = $tag;
            $params->fecha_inicio = $fechaInicio;
            $params->fecha_fin = $fechaFin;
            $params->frecuencia = $frecuencia;
                    
            $resultado = $client->obtenerDatosTag($params);
        
            if(isset($resultado->obtenerDatosTagResult)){
                $res = $resultado->obtenerDatosTagResult;

                $res = $this->cargarArregloWS2(substr($res->any, strpos($res->any,"<InfoPlusDS")));
            }else{
                $res = array();
            }
            
            return $res;
        }

        function obtenerValorTagPLC($tag, $fecha){
            $client = new SoapClient($this->urlProceso);
            
            $params->tag = $tag;
            $params->fecha = $fecha;
            
            $resultado = $client->obtenerValorTag($params);
        
            $res = $resultado->obtenerValorTagResult;
            return $res;
        }

    //SQLLIMS ******************************************************************************

        function obtenerFechaRecienteBasePLC($muestra, $fecha){
            $client = new SoapClient($this->urlLaboratorio);
        
            $params->muestra = $muestra;
            $params->fecha = $fecha;
            
            $resultado = $client->obtenerFechaRecienteBase($params);
        
            $res []["FECHA"]= $resultado->obtenerFechaRecienteBaseResult;
            
            return $res;
        }

        function obtenerFechaRecientePLC($muestra){
            $client = new SoapClient($this->urlLaboratorio);

            $params->muestra = $muestra;

            $resultado = $client->obtenerFechaReciente($params);
        	
            $res = $resultado->obtenerFechaRecienteResult;

            return $res;
        }

        function obtenerMuestrasPLC($muestra, $fechaInicio, $fechaFin, $analisis){
            $client = new SoapClient($this->urlLaboratorio);
            
            $params->muestra = $muestra;
            $params->fecha_ini = $fechaInicio;
            $params->fecha_fin = $fechaFin;
            $params->analisis = $analisis;

            $resultado = $client->obtenerValorConjunto($params);
        
            $res = $resultado->obtenerValorConjuntoResult;
            
            $res = $this->cargarArregloWS($res);
            
            return $res;
        }
        
        function obtenerAnalisisPLC($muestra, $fecha){
            $client = new SoapClient($this->urlLaboratorio);
            
            $params->muestra = $muestra;
            $params->fecha_ini = $fecha;
            $params->fecha_fin = $fecha;
            
            $resultado = $client->obtenerAnalisisIntervalo($params);
            
            $res = $resultado->obtenerAnalisisIntervaloResult;
            
            $res = $this->cargarArregloWS($res);
            
            $c = count($res);
            $analisis = "";
            
            for($i = 0; $i < $c; $i++)
                $analisis .= "'" . $res[$i]["COMPONENT"] . "',";
            
            $analisis = substr($analisis, 0, strlen($analisis) -1 );
            
            return $analisis;
        }
    
    
    //Com�n **************************************************************************************
        function cargarArregloWsPHP($datos){
            $res = array();
            $c = count($datos);
            
            foreach($datos as $llave=>$valor){
                $fila = array();

                foreach($valor as $llave2=>$valor2) $fila [$llave2]= $valor2;
                    
                $res [] = $fila;
            }
            
            return $res;
        }
        
        function cargarArregloWS($res){
            $p = xml_parser_create();
            
            xml_parse_into_struct($p, $res->any, $vals, $index);
            xml_parser_free($p);
            
            $c = count($vals);
            $res = array();

            for($i= 0 ; $i < $c; $i++){
                if($vals[$i]["level"]==3){
                    $i++;
                    $fila = array();
        
                    for($j = $i; $vals[$j]["level"] == 4; $j++)
                        $fila[$vals[$j]["tag"]] = $vals[$j]["value"];
        
                    $res[] = $fila;
                    $i = $j;
                }
            }

            return $res;
        }
        
        function cargarArregloWS2($res){
            $pres = new presentacion();
             
            $p = xml_parser_create();
            
            xml_parse_into_struct($p, $res, $vals);
            xml_parser_free($p);

            $c = count($vals);
            $res = array();

            for($i=0 ; $i<$c; $i++){
                if($vals[$i]["level"]==3){
                    //$i++;
                    $fila = array();
        
                    for($j = $i; $vals[$j]["level"] == 3; $j++)
                        if(isset($vals[$j]["value"])){
                            $fila[$vals[$j]["tag"]] = $vals[$j]["value"];

                            if($vals[$j]["tag"] == "FE_FECHA")
                                $fila[$vals[$j]["tag"]] = $pres->convertirFechaInfoplus($vals[$j]["value"]);
                        }
        
                    $res[] = $fila;
                    $i = $j;
                }
            }

            return $res;
        }
        
    }

    //$clad = new cladWS(2);
    //$datos = $clad->obtenerDatosTag("FIC04008.PV", "15/05/2008", "30/05/2008", "DIA");
    //$datos = $clad->obtenerFechaReciente("SGX990");
    //$datos = $clad->obtenerMuestras("SGX990;LFX402;", "15/05/2008", "30/05/2008", "'TOTAL BUTENO','OLEFINAS C5','HIDROGENO','CO2','ETENO','ETANO','OXIGENO','NITROGENO','METANO','CO','C6+','PROPANO','PROPILENO','H2S','ISOBUTANO','N-BUTANO','ISOPENTANO','N-PENTANO','GRAVEDAD ESPECIFICA','VALOR CALORIF.NETO','VALOR CALORIF.BRUTO';'TOTAL BUTENO','OLEFINAS C5','HIDROGENO','CO2','ETENO','ETANO','OXIGENO','NITROGENO','METANO','CO','C6+','PROPANO','PROPILENO','H2S','ISOBUTANO','N-BUTANO','ISOPENTANO','N-PENTANO','GRAVEDAD ESPECIFICA','VALOR CALORIF.NETO','VALOR CALORIF.BRUTO';");
    
    //print_r($datos);
?>
