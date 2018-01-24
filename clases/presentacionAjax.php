<?php
include_once "../clases/presentacion.php";
include_once "../config.php";
include_once "../clases/clad.php";
include_once "../clases/conversor.php";
include "../clases/cladWS.php";
include "../clases/calcularEficiencia.php";

class presentacionAjax{
    public $pres;
    public $config;
    public $clad;
    
    public $hornoGas = array("txtMetano", "txtC6", "txtHidrogeno", "txtEtano", "txtEteno", "txtOxigeno", "txtPropano", "txtPropileno", "txtNitrogeno", "txtNButano", "txtCO2", "txtNPentano", "txtIsoButano", "txtCO", "txtIsoPentano", "txtOlefinasC5", "txtTotalButeno", "txtH2S", "txtGE", "txtHHV1", "txtLHV", "txtMfHorno", "txtTCombustible", "txtTChimenea", "txtExcesoAire", "txtTemperaturaAmbiente", "txtHumedadRelativa");
    public $hornoLiquido = array("txtHHVHornoHL", "txtRelacionCH", "txtCenizas", "txtAzufreHL", "txtSodio", "txtOtros", "txtMcomb", "txtTCombustibleHL", "txtTChimenea", "txtExcesoAire", "txtTemperaturaAmbiente", "txtHumedadRelativa", "txtMvatomHL");
    public $hornoReal = array("txtRealCO2", "txtRealCO", "txtRealSO2", "txtRealO2", "txtRealNO");
    public $calderaGas = array("txtMfCaldera", "txtHHV2", "txtPresionVapor", "txtTemperaturaVapor", "txtTemperaturaAgua", "txtMrSt", "txtMagua");
    public $calderaLiquido = array("txtCarbono", "txtHidrogenoLiquido", "txtAzufre", "txtGradoAPI", "txtMfCaldera", "txtPresionVapor", "txtTemperaturaVapor", "txtTemperaturaAgua", "txtMrSt", "txtMagua");

    function presentacionAjax(){
        $this->config = new config();
        $this->pres = new presentacion();
        $this->clad = new clad($this->config->queryString);
    }

    function crearCombo($id, $datos, $texto ,$valor, $seleccionado){
        $objResponse = new xajaxResponse();
        
        $c = count($datos);

        $objResponse->addScript("document.getElementById('$id').options.length = 0;");

        $objResponse->addScript('xajax.$("' . $id .'").options[0] = new Option("- Seleccione -","-");');
        
        for($i = 0; $i < $c; $i++){
            $fila = $datos[$i];
            $objResponse->addScript('xajax.$("' . $id .'").options[' . ($i+1) . '] = new Option("'.$fila[$texto].'", "'.$fila[$valor].'");');
            if($seleccionado!="" && $seleccionado==$fila[$valor]) $objResponse->addScript('xajax.$("'. $id . '").selectedIndex  = ' . ($i + 1));
        }
        
        return $objResponse;
    }

    function crearMensaje($mensaje, $objResponse = null){
    	if(!isset($objResponse)){
        	$objResponse = new xajaxResponse();
    	}
        
        $objResponse->addScript("alert('$mensaje');");
        
        return $objResponse;
    }

    function asignarValor($id, $valor){
        $objResponse = new xajaxResponse();

        $objResponse->addAssign($id, "value", $valor);

        return $objResponse;
    }

    function cargarValoresSQLLims($datos, $datosAnalisis, $objResponse, $devolverArreglo = false){
        $res = array();
        $datosCombinados = array();
        $analisisResultados = array();

        $c = count($datos);

        for($i=0; $i<$c; $i++){
            if(isset($datos[$i]["NUMBER_VALUE"])){
                $valor = $this->pres->convertirStringFloat($datos[$i]["NUMBER_VALUE"]);
                
                $elemento = &$analisisResultados[$datos[$i]["COMPONENT"]];
                
                if(isset($elemento)){
                    $elemento["valor"] += $valor;
                }else{
                    $elemento["valor"] = $valor;

                    if(isset($datos[$i]["TIMESTAMP"]) && $datos[$i]["TIMESTAMP"]!="")
                    	$elemento["fecha"] = $this->pres->convertirFechaSqlLims($datos[$i]["TIMESTAMP"]);
                }
            }
        }

        $c = count($datosAnalisis);

        for($i=0; $i<$c; $i++){
            $campo = "";
            $analisis = "";
            $valor = 0;
            $fecha = "";
            
            if(isset($analisisResultados[$datosAnalisis[$i]["analisis"]])){
                $analisis = $datosAnalisis[$i]["nombre_analisis"];
                $valor = $analisisResultados[$datosAnalisis[$i]["analisis"]]["valor"];

                if(isset($analisisResultados[$datosAnalisis[$i]["analisis"]]["fecha"]))
                	$fecha = $analisisResultados[$datosAnalisis[$i]["analisis"]]["fecha"];

                switch($analisis){
                    case "TOTAL BUTENO": $campo = "txtTotalButeno";
                    break;
                    case "OLEFINAS C5": $campo = "txtOlefinasC5";
                    break;
                    case "HIDROGENO": $campo = "txtHidrogeno";
                    break;
                    case "CO2": $campo = "txtCO2";
                    break;
                    case "ETENO": $campo = "txtEteno";
                    break;
                    case "ETANO": $campo = "txtEtano";
                    break;
                    case "OXIGENO": $campo = "txtOxigeno";
                    break;
                    case "NITROGENO": $campo = "txtNitrogeno";
                    break;
                    case "METANO": $campo = "txtMetano";
                    break;
                    case "CO": $campo = "txtCO";
                    break;
                    case "C6+": $campo = "txtC6";
                    break;
                    case "PROPANO": $campo = "txtPropano";
                    break;
                    case "PROPILENO": $campo = "txtPropileno";
                    break;
                    case "H2S": $campo = "txtH2S";
                    break;
                    case "ISOBUTANO": $campo = "txtIsoButano";
                    break;
                    case "N-BUTANO": $campo = "txtNButano";
                    break;
                    case "ISOPENTANO": $campo = "txtIsoPentano";
                    break;
                    case "N-PENTANO": $campo = "txtNPentano";
                    break;
                    case "GRAVEDAD ESPECIFICA": $campo = "txtGE";
                    break;
                    case "VALOR CALORIF.NETO": $campo = "txtLHV";
                    break;
                    case "VALOR CALORIF.BRUTO":
                        $campo = "txtHHV1";
                        $this->cargarValor("txtHHV2", $objResponse, $valor, $fecha, $res, $devolverArreglo);
                        break;
                }
     
                $this->cargarValor($campo, $objResponse, $valor, $fecha, $res, $devolverArreglo);
            }
        }

        if($devolverArreglo){
            if(isset($res)) 
                return $res;
            else
                return array();
        }else
            return $objResponse;
    }
    
    function cargarValor($campo, &$objResponse, &$number_value, &$timestamp, &$res, &$devolverArreglo){
        if($campo!="" && isset($number_value)){
            $number_value = $this->pres->formatearSeparadorDecimales($number_value);
            if(substr($number_value,0,1) == ",")
                $number_value =  "0" . $number_value;

            if($devolverArreglo){
                $res[$timestamp][$campo] = $number_value;
            }else{
                $objResponse->addAssign($campo, "value", $number_value);
            }
        }
    }
    
    function cargarValoresInfoPlus($datos, $objResponse){
        foreach($datos as $llave=>$valor)
            $objResponse->addAssign($llave, "value", $this->pres->formatearDecimales($valor));
        
        return $objResponse;
    }
}


//Funciones que consumen la clase *****************************************

function obtenerRefineriaEquipo($datos){
    $codigoRefineria = 0;

    if(isset($datos) && count($datos)>0) $codigoRefineria = (int) $datos[0]["codigo_refineria"];
    
    return $codigoRefineria;
}

//Boton Obtener
function obtenerDataSistemas($equipo, $tipoCombustible, $fecha){
    $presAjax = new presentacionAjax();
    $objResponse = new xajaxResponse();
    $fechaInicio = "";
    $fechaFin = "";
    $diasDatos = "";
    
    $objResponse->addScript("LimpiarCampos();");

	if(is_numeric($equipo)){
        if($fecha!=""){
            $datos = $presAjax->clad->obtenerEquipo($equipo);
            $codigoRefineria = obtenerRefineriaEquipo($datos);
            
            $cladWS = new cladWS($codigoRefineria);

            $muestras = obtenerVariableEquipo($datos, "muestra");
            $fechasMuestras = implode(";", $muestras);

            $fechas = $cladWS->obtenerFechaRecienteBase($fechasMuestras, $fecha);
            $c = count($fechas);
            
            if($c==1){
                $fechaInicio = $fechas[0]["FECHA"];
                $fechaFin = $fechas[0]["FECHA"];
                $diasDatos = $fechas[0]["FECHA"];
            }else{
                $fechaInicio = $fechas[0]["FECHA"];
                $fechaFin = $fechas[1]["FECHA"];
                $diasDatos = $fechas[0]["FECHA"] . " y " . $fechas[1]["FECHA"];
            }
            
            $objResponse = obtenerDatosSqlLims($equipo, $fechaInicio, $fechaFin, $objResponse);
            
            $fechaI = "";
            $fechaF = "";
            
            //Datos de proceso
            if($codigoRefineria==2){
	            $fechaI = $presAjax->pres->dateSum($fecha, -1);
	            $fechaF = $fecha;
            }else{
	            $fechaI = $fecha;
	            $fechaF = $presAjax->pres->dateSum($fecha, +1);
            }

            $objResponse = obtenerDatosInfoPlusRango($equipo, $tipoCombustible, $fechaI, $fechaF, $objResponse, false);
        }else{
            $objResponse = $presAjax->crearMensaje("Debe indicar una fecha");
        }
    }else{
        $objResponse = $presAjax->crearMensaje("Debe seleccionar un equipo");
    }

    $objResponse->addScript("Normalizar();");

    if($diasDatos!=$fecha)
        $objResponse = $presAjax->crearMensaje("Se obtuvo los datos de Laboratorio correspondientes al día $diasDatos", $objResponse);

    return $objResponse;
}

function obtenerDatosEquipoTags($equipo, $tag, $tipoCombustible, $fechaInicio, $fechaFin){
    $presAjax = new presentacionAjax();
    $objResponse = new xajaxResponse();
    
    $datos = $presAjax->clad->obtenerEquipo($equipo);
    
    if(isset($equipo) && $equipo != "-" && $equipo != ""){
        $cladWS = new cladWS(obtenerRefineriaEquipo($datos));
        $tagsString = "";

        if($tag != "-"){
            $res = $cladWS->obtenerDatosTag($tag, $fechaInicio, $fechaFin, "DIA");
            $c = count($res);
        
            if(isset($res) && isset($res[0]) && count($res[0]) > 0){
                for($i=0; $i<$c; $i++){
                    $tagsString .= $res[$i]["FE_FECHA"] . ":" . $res[$i]["VA_VALOR"] . ";";
                    
                    echo array_search($res[$i]["NB_TAG"], $datos);
                }
                
                $tagsString = substr($tagsString, 0, strlen($tagsString)-1);
                
                $objResponse->addAssign("txtValores", "value", $tagsString);

                $objResponse->addAssign("tblResultado", "innerHTML", $presAjax->pres->crearTablaTag($res, "Lista-Fondo1", "Lista-Fondo2"));
                $objResponse->addScript("verGrafico();");
            }else{
                $objResponse = $presAjax->crearMensaje("No existe datos para el Tag solicitado");
            }
        }else{
            $objResponse = $presAjax->crearMensaje("Debe seleccionar un Tag");
        }
    }else{
        $objResponse = $presAjax->crearMensaje("Debe seleccionar un equipo");
    }
    
    return $objResponse;
}

function obtenerTagsEquipo($id, $equipo, $seleccionado = ""){
    $presAjax = new presentacionAjax();

	if(is_numeric($equipo)){
    	$datos = $presAjax->clad->obtenerTagsDescripcionEquipo($equipo);
	}else{
		$datos = array();
	}

    return $presAjax->crearCombo($id, $datos, "texto", "nombre", $seleccionado);
}


//SQLLIMS **********************************************************************************************

function obtenerDataSqlLimsPredeterminada($equipo, $fecha){
    $objResponse = new xajaxResponse();
    $presAjax = new presentacionAjax();
    
    if(isset($equipo) && $equipo !="" && $equipo !="-" && isset($fecha) && $fecha !=""){
		$datos = $presAjax->clad->obtenerEquipo($equipo);
        $codigoRefineria = obtenerRefineriaEquipo($datos);

        $datos = $presAjax->clad->obtenerDataEquipos($equipo, $fecha);
		$analisis = "";
		
        if(isset($datos)){
        	$datosAnalisis = $presAjax->clad->obtenerAnalisisLaboratorio($codigoRefineria);
            $c = count($datosAnalisis);
            
            for($i=0; $i<$c; $i++)
            	if(isset($datosAnalisis[$i]["analisis"]) && $datosAnalisis[$i]["analisis"]!="")
            		$analisis .= "'" . $datosAnalisis[$i]["analisis"] . "',";
        	
            $objResponse = $presAjax->cargarValoresSQLLims($datos, $datosAnalisis, $objResponse);
    
            $objResponse->addScript("Normalizar();");
            
            return $objResponse;
        }
    }else{
        return $presAjax->crearMensaje("Debe seleccionar un equipo");
    }
}

function obtenerDataSqlLims($equipo, $fecha){
    $objResponse = new xajaxResponse();
    
    $objResponse->addScript("LimpiarCampos();");

    $objResponse = obtenerDatosSqlLims($equipo, $fecha, $fecha, $objResponse);

    return $objResponse;
}

function obtenerDatosSqlLims($equipo, $fechaInicio, $fechaFin, $objResponse, $devolverArreglo = false){
    $presAjax = new presentacionAjax();
    $datos = array();
    $codigoRefineria = 0;

    if(isset($equipo) && $equipo !="" && (isset($fechaInicio) && $fechaInicio !="") && (isset($fechaFin) && $fechaFin !="")){
        $hayDatos = true;
        $datos = $presAjax->clad->obtenerEquipo($equipo);

        $codigoRefineria = obtenerRefineriaEquipo($datos);

        $cladWS = new cladWS($codigoRefineria);

        if(isset($datos)){
            //Obtener análisis
            $analisis = "";
            $datosAnalisis = $presAjax->clad->obtenerAnalisisLaboratorio($codigoRefineria);
            $c = count($datosAnalisis);
            
            for($i=0; $i<$c; $i++) $analisis .= "'" . $datosAnalisis[$i]["analisis"] . "',";
            if($i>0) $analisis = substr($analisis, 0, strlen($analisis)-1);

            if(isset($analisis) && $analisis!=""){
                $mestrasEquipo = obtenerVariableEquipo($datos, "muestra");
                $muestra = "";
                $analisisEquipos = "";
                
                foreach($mestrasEquipo as $llave=>$valor){
                    $muestra .= $valor . ";";
                    $analisisEquipos .= $analisis . ";";
                }
                
                $muestras = $cladWS->obtenerMuestras($muestra, $fechaInicio, $fechaFin, $analisisEquipos);

                if(isset($muestras) && count($muestras)>1)
                    return $presAjax->cargarValoresSQLLims($muestras, $datosAnalisis, $objResponse, $devolverArreglo);
                else
                    $hayDatos = false;
            }else{
                $hayDatos = false;
            }
        }
        
        if(!$hayDatos && !$devolverArreglo) 
        	return $objResponse;
    }else{
        return $presAjax->crearMensaje("Debe seleccionar un equipo e indicar una fecha");
    }
}

function obtenerFechaSqlLims($equipo, $id){
    $presAjax = new presentacionAjax();
    $objResponse = new xajaxResponse();
    $pres = new presentacion();
    $eficiencia = "";
    $fecha = "";

	try{

	    if(is_numeric($equipo)){
	        $datos = $presAjax->clad->obtenerEquipo($equipo);
	    
	        $cladWS = new cladWS(obtenerRefineriaEquipo($datos));
	        
	        if(isset($equipo) && is_numeric($equipo)){
	            $presAjax = new presentacionAjax();
	    
	            $muestra = obtenerVariableEquipo($datos, "muestra");
	
	            if(isset($muestra[0])){
	                foreach($muestra as $llave=>$valor)
	                    $fechas []= $cladWS->obtenerFechaReciente($valor);
	                    
	                //trae la fecha más reciente
	                if(count($fechas)>1){
	                    $res = $presAjax->pres->dateDiff($fechas[0], $fechas[1]);
	
	                    if($res >= 0) $fecha = $fechas[1];
	                    else $fecha = $fechas[0];
	                }else{
	                    $fecha = $fechas[0];
	                }
	                $objResponse->addAssign($id, "value", $fecha);
	            }else
	                $objResponse->addAssign($id, "value", "");
	            
	            if(isset($datos) && $datos[0]["eficiencia"] != "")
	                $eficiencia = $presAjax->pres->formatearSeparadorDecimales($datos[0]["eficiencia"]);
	            else
	                $eficiencia = "";
	
	            $objResponse->addAssign("txtEficienciaTarget", "value", $eficiencia);
	
	    		asignarUnidades($datos, $objResponse);
	        }
		}

	}catch (Exception $e){
		$objResponse->addAssign($id, "value", "");
	} 
        
    return $objResponse;
}

function asignarUnidades($datos, &$objResponse){
	$unidad = "";

	if(isset($datos[0]["unidad_gas_combustible"])){
		if($datos[0]["unidad_gas_combustible"]==1000){
			$unidad = "MPCSH";
		}
	}else{
		$unidad = "PCSH";
	}
 
	$objResponse->addAssign("lblUnidadQf", "innerHTML", "&nbsp;$unidad");
	$objResponse->addAssign("txtUnidadQf", "value", "&nbsp;$unidad");
}

function obtenerVariableEquipo($datos, $variable){
    $c = count($datos);
    $ret = array();
    
    for($i=0; $i<$c; $i++)
        if($datos[$i]["codigo_variable"] == $variable)
            $ret []= $datos[$i]["nombre"];
    
    return $ret; 
}


//INFOPLUS *******************************************************************************************************************************************

function obtenerDatosInfoPlusRango($equipo, $tipoCombustible, $fechaInicio, $fechaFin, $objResponse, $devolverArreglo = false){
    $presAjax = new presentacionAjax();
    $componentePrefijos = new Prefijos();

    if(isset($equipo) && $equipo !="" && isset($tipoCombustible) && $tipoCombustible !="" && (isset($fechaInicio) && $fechaInicio !="") && (isset($fechaFin) && $fechaFin !="")){
        $datos = $presAjax->clad->obtenerTagsEquipo($equipo);

        if(isset($datos)){
            $cladWS = new cladWS(obtenerRefineriaEquipo($datos));
        
            $c = count($datos);
            $nombresTagsString = "";
            $tagsEquipo = array();
            
            for($i=0; $i<$c; $i++){
                $tagsEquipo[$datos[$i]["nombre"]] = $datos[$i]["codigo_variable"];
                $nombresTagsString .= $datos[$i]["nombre"] . ",";
            }

        //Buscar información en los servicios
            $resWS = $cladWS->obtenerDatosTag($nombresTagsString, $fechaInicio, $fechaFin, "DIA");

            $datosInfoPlus = cargarValoresInfoPlus($resWS, $tagsEquipo, $tipoCombustible, $datos[0]["codigo_tipo"]);
            
            if($devolverArreglo)
                return $datosInfoPlus;
            else{
                if(isset($datosInfoPlus[$fechaInicio]))
                    return $presAjax->cargarValoresInfoPlus($datosInfoPlus[$fechaInicio], $objResponse);
                else
                    return $objResponse;
            }
        }

    }else
        return $presAjax->crearMensaje("Debe seleccionar un equipo e indicar una fecha");
}

function datosCompletos($datosObtenidos, $datosRequeridos){
    $c = count($datosRequeridos);
    $completo = "";

    for($i=0; $i<$c; $i++)
        if((!isset($datosObtenidos[$datosRequeridos[$i]])) || $datosObtenidos[$datosRequeridos[$i]] == "")
            $completo .= $datosRequeridos[$i] . ",";

    $c = strlen($completo);
    if($c>0) $completo = substr($completo, 0, $c-1);
    
    return $completo;
}

function arregloAString($arreglo){
    $cadena = "";
    
    foreach($arreglo as $llave => $valor)
        if($valor!="")
            $cadena .= $llave . "=" . str_replace(".", ",", $valor) . ";";
    
    return substr($cadena, 0, strlen($cadena)-1);
}

function asignarValores($forma, $datos){
    foreach($datos as $llave => $valor)
        if(isset($valor) && $valor!="")
            $forma[$llave] = $valor;

    return $forma;
}

function filtarValor($arreglo, $columna, $valor){
    $res = array();
    $c = count($arreglo);
    
    for($i=0;$i<$c;$i++)
        if($arreglo[$i][$columna] == $valor)
            $res []= $arreglo[$i];
    
    return $res;
}

function cargarValoresInfoPlus($resWS, $tagsEquipo, $tipoCombustible, $tipoEquipo){
    $presAjax = new presentacionAjax();
    
    $resultados = array();
    $c = count($resWS);
    
    for($i=0; $i<$c; $i++){
    	if(count($resWS[$i])>0){
            $elemento = &$resultados[$resWS[$i]["FE_FECHA"]][$tagsEquipo[$resWS[$i]["NB_TAG"]]];

            if(isset($elemento)){
                $elemento["resultado"] += $resWS[$i]["VA_VALOR"];
                $elemento["cantidad"] += 1;
            }else{
                $elemento["resultado"] = $resWS[$i]["VA_VALOR"];
                $elemento["cantidad"] = 1;
            }
    	}
    }
    
    $datosInfoPlus = array();
    
    foreach($resultados as $llave => $valor){
        foreach($valor as $llave2 => $valor2){

            if($valor2["resultado"]!="")
                $valorTags = $presAjax->pres->formatearSeparadorDecimales($presAjax->pres->formatearNumero($valor2["resultado"], 3));
            
            switch ($llave2){
                case "mf":
                    if($tipoEquipo == 1 && $tipoCombustible == "Gas")
                        $datosInfoPlus[$llave]["txtMfHorno"] = $valorTags;
                    else if($tipoEquipo == 2 && $tipoCombustible == "Gas")
                        $datosInfoPlus[$llave]["txtMfCaldera"] = $valorTags;
                break;
                case "mfcl":
                    if($tipoEquipo == 2 && $tipoCombustible != "Gas")
                        $datosInfoPlus[$llave]["txtMfCaldera"] = $valorTags;
                break;
                case "ma":
                    $datosInfoPlus[$llave]["txtMa"] = $valorTags;
                break;
                case "tf":
                    $datosInfoPlus[$llave]["txtTChimenea"] = $valorTags;
                break;
                case "mrst":
                    $datosInfoPlus[$llave]["txtMrSt"] = $valorTags;
                break;
                case "tagua":
                    $datosInfoPlus[$llave]["txtTemperaturaAgua"] = $valorTags;
                break;
                case "tvapor":
                    $datosInfoPlus[$llave]["txtTemperaturaVapor"] = $valorTags;
                break;
                case "pvapor":
                    $datosInfoPlus[$llave]["txtPresionVapor"] = $valorTags;
                break;
                case "mfaacl":
                    $datosInfoPlus[$llave]["txtMagua"] = $valorTags;
                break;
                case "po2":
                    $datosInfoPlus[$llave]["txtExcesoAire"] = $presAjax->pres->formatearSeparadorDecimales($valor2["resultado"] / $valor2["cantidad"]);
                break;
            }
        }
    }

    return $datosInfoPlus;
}

function crearComboEquipos($id, $tipo, $seleccionado = ""){
    $presAjax = new presentacionAjax();
    
    $equipo = split(";", $tipo);

    if(isset($equipo) && count($equipo)==2)
        $datos = $presAjax->clad->obtenerEquipos($equipo[0], $equipo[1]);
    else
        $datos = array();
        
    $presAjax = new presentacionAjax();
    
    return $presAjax->crearCombo($id, $datos, "equipo", "codigo_equipo", $seleccionado);
}

function crearComboCombustibles($id, $tipo){
    $presAjax = new presentacionAjax();
    
    $equipo = split(";", $tipo);

    if(isset($equipo[0])){
    	switch ($equipo[0]){
			case "1":
            	$datos []= array("Gas", "Gas");
            	$datos []= array("Liquido", "Líquido");
			break;
			case "2":
        		$datos []= array("Gas", "Gas");
			break;
			default:
				$datos = array();
			break;
		}
    }else
        $datos = array();
        
    $presAjax = new presentacionAjax();
    
    return $presAjax->crearCombo($id, $datos, 1, 0, "");
}


function obtenerCamposRequeridos($tipoEquipo, $tipoCombustible){
    $presAjax = new presentacionAjax();
    $camposRequeridos = array();

    if($tipoEquipo == "Horno"){
        if($tipoCombustible == "Gas")
            $camposRequeridos = $presAjax->hornoGas;
        else
            $camposRequeridos = $presAjax->hornoLiquido;
        
        if($tipoCombustible == "Real")
            $camposRequeridos = array_merge($camposRequeridos, $presAjax->hornoReal);

    }else if($tipoEquipo == "Caldera"){
        if($tipoCombustible == "Gas")
            $camposRequeridos = $presAjax->calderaGas;
        else
            $camposRequeridos = $presAjax->calderaLiquido;
    }
    
    return $camposRequeridos;
}


function obtenerVariable($id, $variable){
    $presAjax = new presentacionAjax();

    switch ($variable){
        case "temperaturaAmbiente": $valor = $presAjax->config->temperaturaAmbiente;
        break;
        case "temperaturaReferencia": $valor = $presAjax->config->temperaturaReferencia;
        break;
        case "humedadRelativa": $valor = $presAjax->config->humedadRelativa;
        break;
        case "costoCombustibleGas": $valor = $presAjax->config->costoCombustibleGas;
        break;
        case "costoCombustibleLiquido": $valor = $presAjax->config->costoCombustibleLiquido;
        break;
        case "paridadCambiaria": $valor = $presAjax->config->paridadCambiaria;
        break;
        case "Cpaire": $valor = $presAjax->config->Cpaire;
        break;
        case "densidadAire": $valor = $presAjax->config->densidadAire;
        break;
        case "densidadAgua": $valor = $presAjax->config->densidadAgua;
        break;
        case "eficienciaRadiacionGas": $valor = $presAjax->config->eficienciaRadiacionGas;
        break;
        case "eficienciaRadiacionLiquido": $valor = $presAjax->config->eficienciaRadiacionLiquido;
        break;
    }
    
    $valor = $presAjax->pres->formatearSeparadorDecimales($valor);
    
    return $presAjax->asignarValor($id, $valor);
}

function obtenerTipoEquipo($tipo){
    $equipo = "";
    $codigo = substr($tipo,0,1);

    switch($codigo){
        case "1": $equipo = "Horno";
        break;
        case "2": $equipo = "Caldera";
        break;
    }

    return $equipo;
}

//CÁLCULOS DE EFICIENCIA ********************************************************************************************************

function calcularEficienciasRango($form, $dias, $datosLabEquipo, $datosProEquipo, $camposRequeridos){
    $presAjax = new presentacionAjax();
    $calcula = new calcularEficiencia();
    $fechasSqlLims = array();
    $fechaSqlLimsActual = 0;
    $resultado = array();
    $nuevaForma = array();
    
    if(isset($datosLabEquipo))
        foreach($datosLabEquipo as $llave => $valor) $fechasSqlLims[] = $llave;
    
    $cSqlLims = count($datosLabEquipo);

    for($i=0; $i<=$dias; $i++){
        $nuevaForma = $form;
        $nuevaForma["txtFecha"] = $presAjax->pres->dateSum($form["txtFechaInicio"], $i);

        //SqlLims
        if($cSqlLims > 0){
            $posicion = array_search($nuevaForma["txtFecha"], $fechasSqlLims);
            
            if($posicion) $fechaSqlLimsActual = $posicion;
            
            $nuevaForma = asignarValores($nuevaForma, $datosLabEquipo[$fechasSqlLims[$fechaSqlLimsActual]]);
        }

        //Infoplus
        if(isset($datosProEquipo[$nuevaForma["txtFecha"]]))
            $nuevaForma = asignarValores($nuevaForma, $datosProEquipo[$nuevaForma["txtFecha"]]);

        //Verificar que esten todos los datos
        $datosCompletos = datosCompletos($nuevaForma, $camposRequeridos);

        $eficiencia = 0;
        if($datosCompletos == "") $eficiencia = $calcula->calcular($nuevaForma);
        
        $resultado []= array("eficiencia" => $eficiencia, "fecha" => $nuevaForma["txtFecha"], "campos" => $datosCompletos, "forma" => $nuevaForma);
    }

    return $resultado;
}


function calcularEficienciaRangoIndicadores($equipos, $fechaInicio, $fechaFin, $servicio=false){
    if(!$servicio) $objResponse = new xajaxResponse();
    else $objResponse = null;
        
    $presAjax = new presentacionAjax();
    $datos = array();
    $tagsEquipo = array();
    $muestras = array();
    $tags = array();
    $analisis = "";
    $eficienciaServicio = "";
    
    $dias = $presAjax->pres->dateDiff($fechaInicio, $fechaFin);
    $datos = $presAjax->clad->obtenerEquiposIndicador($equipos);

    foreach($datos as $llave=>$valor)
        if($datos[$llave]["codigo_variable"]=="muestra")
            $muestras []= $datos[$llave]["nombre"];
        else
            $tags []= $datos[$llave]["nombre"];

    $muestras = array_unique($muestras);
    $tags = array_unique($tags);
    
    $muestrasLista = implode(";", $muestras);
    $tagsLista = implode(",", $tags);
    
    //Instanciar servicio web
    $cladWS = new cladWS(1);
    $datosAnalisis = $presAjax->clad->obtenerAnalisisLaboratorio(1);

    foreach($datosAnalisis as $llave=>$valor) $analisis .= "'" . $datosAnalisis[$llave]["analisis"] . "',";

    $analisis = substr($analisis, 0, strlen($analisis)-1);
    $analisisLista = "";

    foreach($muestras as $llave=>$valor) $analisisLista .= $analisis . ";";
    
//Buscar datos en los sistemas
//Laboratorio
    $fechaInicioLab = $presAjax->pres->fechaAnalisisProximo($fechaInicio, array(2,4));
    $datosLaboratorio = $cladWS->obtenerMuestras($muestrasLista, $fechaInicioLab, $fechaFin, $analisisLista);

//Proceso
    $fechaFinPro = $presAjax->pres->dateSum($fechaFin, 1);
    $datosProceso = $cladWS->obtenerDatosTag($tagsLista, $fechaInicio, $fechaFinPro, "DIA");

    $equiposArreglo = explode(",", $equipos);
    $c = count($equiposArreglo);

    for($i=0; $i<$c; $i++){
        $datosEquipo = filtarValor($datos, "codigo_equipo", $equiposArreglo[$i]);
        $c2 = count($datosEquipo);

        $datosLabEquipo = array();
        $datosProEquipo = array();
        
        for($j=0; $j<$c2; $j++)
            if($datosEquipo[$j]["codigo_variable"] == "muestra"){
                $datosLabEquipo = array_merge(filtarValor($datosLaboratorio, "MUESTRA", $datosEquipo[$j]["nombre"]), $datosLabEquipo);
            }else{
                $datosProEquipo = array_merge(filtarValor($datosProceso, "NB_TAG", $datosEquipo[$j]["nombre"]), $datosProEquipo);
                $tagsEquipo[$datosEquipo[$j]["nombre"]] = $datosEquipo[$j]["codigo_variable"];
            }

        $datosLabEquipo = $presAjax->cargarValoresSQLLims($datosLabEquipo, $datosAnalisis, $objResponse, true);

        $datosProEquipo = cargarValoresInfoPlus($datosProEquipo, $tagsEquipo, $datosEquipo[0]["combustible"], $datosEquipo[0]["codigo_tipo"]);

        $form["cmbEquipo"] = $datosEquipo[0]["codigo_equipo"];
        $form["cmbTipoEquipo"] = $datosEquipo[0]["equipo"];
        $form["cmbTipoCombustible"] = $datosEquipo[0]["combustible"];
        $form["txtEficienciaTermica"] = ""; $form["txtCalorPerdidoTeorico"] = ""; $form["txtCalorPerdidoReal"] = "";
        $form["txtFecha"] = ""; $form["txtFechaInicio"] = $fechaInicio; $form["txtFechaFin"] = $fechaFin;
        $form["cmbComposicionGases"] = "Teorica";

        $form["txtMetano"] = ""; $form["txtC6"] = ""; $form["txtHidrogeno"] = ""; $form["txtEtano"] = "";
        $form["txtEteno"] = ""; $form["txtOxigeno"] = ""; $form["txtPropano"] = ""; $form["txtPropileno"] = "";
        $form["txtNitrogeno"] = ""; $form["txtNButano"] = ""; $form["txtCO2"] = ""; $form["txtNPentano"] = "";
        $form["txtIsoButano"] = ""; $form["txtCO"] = ""; $form["txtIsoPentano"] = ""; $form["txtOlefinasC5"] = "";
        $form["txtTotalButeno"] = ""; $form["txtH2S"] = ""; $form["txtGE"] = ""; $form["txtHHV1"] = "900";
        $form["txtLHV"] = ""; $form["txtCarbono"] = ""; $form["txtHidrogenoLiquido"] = ""; $form["txtGradoAPI"] = "";
        $form["txtAzufre"] = ""; $form["txtMfCaldera"] = ""; $form["txtHHV2"] = "900"; 
        $form["txtPresionVapor"] = ""; $form["txtTemperaturaVapor"] = "";
        $form["txtTemperaturaAgua"] = ""; $form["txtMrSt"] = ""; $form["txtMagua"] = ""; $form["txtMfHorno"] = "";
        $form["cmbMa"] = "Teorica"; $form["txtMa"] = ""; $form["txtTCombustible"] = "85"; $form["txtTChimenea"] = "";
        $form["txtExcesoAire"] = ""; $form["rblBaseOxigeno"] = "BaseSeca"; $form["txtCostoCombustible"] = "";
        $form["txtParidad"] = ""; $form["txtEficienciaTarget"] = $datos[0]["eficiencia"];
        $form["txtTemperaturaAmbiente"] = $presAjax->config->temperaturaAmbiente; $form["txtHumedadRelativa"] = $presAjax->config->humedadRelativa;
        $form["txtMvatom"] = ""; $form["txtPresion"] = ""; $form["txtTemperaturaVaporAtomizacion"] = ""; 
        $form["rblComposicionGases"] = "BaseSeca"; $form["txtRealCO2"] = "";
        $form["txtRealCO"] = ""; $form["txtRealSO2"] = ""; $form["txtRealO2"] = ""; $form["txtRealNO"] = "";        

        $nombreForma = "frmEquipo" . $equiposArreglo[$i];
        $datosGrafico = "";
        $datosDetalle = "";

        //Obtener los campos necesarios para el cálculo
        $camposRequeridos = obtenerCamposRequeridos($form["cmbTipoEquipo"], $form["cmbTipoCombustible"]);
        
        $resultado = calcularEficienciasRango($form, $dias, $datosLabEquipo, $datosProEquipo, $camposRequeridos);
        $ce = count($resultado);

        $camposFaltantes = array();

        if($servicio){
            $eficiencias = "";

            for($k=0; $k<$ce; $k++)
                if($resultado[$k]["eficiencia"]["eficienciaTermica"]>0 && $resultado[$k]["campos"]=="")
                    $eficiencias .= $resultado[$k]["fecha"] . ";" . $presAjax->pres->formatearDecimales($resultado[$k]["eficiencia"]["eficienciaTermica"], 1, ".") . "_";

            if(isset($resultado[0]))
                $eficienciaServicio []= array("codigo_equipo"=>$resultado[0]["forma"]["cmbEquipo"], "eficiencia"=>$eficiencias);
            else
                $eficienciaServicio []= array();
        }else{
            for($k=0; $k<$ce; $k++){
                if($resultado[$k]["eficiencia"]["eficienciaTermica"]>0){
                    $datosGrafico .= $resultado[$k]["fecha"] . ":" . $presAjax->pres->formatearDecimales($resultado[$k]["eficiencia"]["eficienciaTermica"], 1, ".") . ";";
    
                    $separador = "";
                    if($k!=$dias) $separador = "|";
                    
                    $datosDetalle .= "<a id=\"lnkPuntoGrafico$k\" title=\"Eficiencia: ". $presAjax->pres->formatearMoneda($resultado[$k]["eficiencia"]["eficienciaTermica"]) . "\" href=\"javascript:cargarValores('" . arregloAString($resultado[$k]["forma"]) . "')\" class='Detalle '>" . $presAjax->pres->obtenerFechaGrafico($resultado[$k]["fecha"]) . "</a> $separador ";
                }else{
                    $camposFaltantes[] = $resultado[$k]["campos"];
                }
            }

            $camposFaltantes = implode(",", array_unique($camposFaltantes));

            $objResponse->addAssign($nombreForma . "tdPuntosGrafico", "innerHTML", $datosDetalle);
            $objResponse->addAssign($nombreForma . "txtCamposFaltantes", "value", $camposFaltantes);
            
            if(strlen($datosGrafico)>1) $datosGrafico = substr($datosGrafico, 0, strlen($datosGrafico)-1);

            $objResponse->addAssign($nombreForma . "txtEficiencias", "value", $datosGrafico);
            $objResponse->addScript("actualizarGrafico('$nombreForma');");
        }
    }

    if($servicio) return $eficienciaServicio;
    else return $objResponse;
}

    
function calcularEficienciaRango($form, $nombreForma = ""){
    $objResponse = new xajaxResponse();
    $calcula = new calcularEficiencia();
    $presAjax = new presentacionAjax();

    $objResponse->addScript("document.getElementById('tblResultadoGrafico').style.display = 'block';");
    $objResponse->addAssign("txtDatosEficiencias", "value", "");
    $datosGrafico = "";
    $fechasSqlLims = array();
    $fechaSqlLimsActual = 0;
    $datosDetalle = "";

    $form["cmbTipoEquipo"] = obtenerTipoEquipo($form["cmbTipoEquipo"]);
    
    //RANGO DE DIAS
    $dias = $presAjax->pres->dateDiff($form["txtFechaInicio"], $form["txtFechaFin"]);

    //TRAER DATOS DE LOS SISTEMAS
    $fechaInicioSQLLIms = $presAjax->pres->fechaAnalisisProximo($form["txtFechaInicio"], array(2,4));
    $datosSqlLims = obtenerDatosSqlLims($form["cmbEquipo"], $fechaInicioSQLLIms, $form["txtFechaFin"], $objResponse, true);

    $fechaFin = $presAjax->pres->dateSum($form["txtFechaFin"], 1);
    $datosInfoPlus = obtenerDatosInfoPlusRango($form["cmbEquipo"], $form["cmbTipoCombustible"], $form["txtFechaInicio"], $fechaFin, $objResponse, true);

    //Obtener los campos necesarios para el cálculo
    $camposRequeridos = obtenerCamposRequeridos($form["cmbTipoEquipo"], $form["cmbTipoCombustible"]);
    
    $resultado = calcularEficienciasRango($form, $dias, $datosSqlLims, $datosInfoPlus, $camposRequeridos, $nombreForma);
    
    $ce = count($resultado);

    for($k=0; $k<$ce; $k++){
        if($resultado[$k]["campos"] == ""){
            $datosGrafico .= $resultado[$k]["fecha"] . ":" . $presAjax->pres->formatearDecimales($resultado[$k]["eficiencia"]["eficienciaTermica"], 1, ".") . ";";

            $separador = "";
            if($k!=$dias) $separador = "|";
            
            $datosDetalle .= "<a id=\"lnkPuntoGrafico$k\" title=\"Eficiencia: ". $presAjax->pres->formatearMoneda($resultado[$k]["eficiencia"]["eficienciaTermica"]) . "\" href=\"javascript:cargarValores('" . arregloAString($resultado[$k]["forma"]) . "')\" class='TextoLink'>" .
            		"" . $presAjax->pres->obtenerFechaGrafico($resultado[$k]["fecha"]) . "</a> $separador ";
        }else{
            $datosDetalle = "";
            $datosGrafico = "";
            break;
        }
    }
    
    $objResponse->addAssign($nombreForma . "tdPuntosGrafico", "innerHTML", $datosDetalle);
    $objResponse->addAssign($nombreForma . "txtCamposFaltantes", "value", $resultado[0]["campos"]);

    if(strlen($datosGrafico)>1)
        $datosGrafico = substr($datosGrafico, 0, strlen($datosGrafico)-1);
    
    $objResponse->addAssign($nombreForma . "txtEficiencias", "value", $datosGrafico);
    $objResponse->addScript("actualizarGrafico($nombreForma);");

    return $objResponse;
}

function calcularEficienciaRangoIntegrador($form, $nombreForma = ""){
    $objResponse = "";
    $calcula = new calcularEficiencia();
    $presAjax = new presentacionAjax();

    $datosGrafico = "";
    $fechasSqlLims = array();
    $fechaSqlLimsActual = 0;
    
    //RANGO DE DIAS
    $dias = $presAjax->pres->dateDiff($form["txtFechaInicio"], $form["txtFechaFin"]);
    
    //TRAER DATOS DE LOS SISTEMAS
    $fechaInicioSQLLIms = $presAjax->pres->fechaAnalisisProximo($form["txtFechaInicio"], array(2,4));
    $datosSqlLims = obtenerDatosSqlLims($form["cmbEquipo"], $fechaInicioSQLLIms, $form["txtFechaFin"], $objResponse, true);

    $fechaFin = $presAjax->pres->dateSum($form["txtFechaFin"], 1);
    $datosInfoPlus = obtenerDatosInfoPlusRango($form["cmbEquipo"], $form["cmbTipoCombustible"], $form["txtFechaInicio"], $fechaFin, $objResponse, true);

    //Obtener los campos necesarios para el cálculo
    $camposRequeridos = obtenerCamposRequeridos($form["cmbTipoEquipo"], $form["cmbTipoCombustible"]);

    $resultado = calcularEficienciasRango($form, $dias, $datosSqlLims, $datosInfoPlus, $camposRequeridos);

    $ce = count($resultado);
    $datosGrafico = "";
    
    for($k=0; $k<$ce; $k++)
        if($resultado[$k]["eficiencia"]["eficienciaTermica"]>0 && $resultado[$k]["campos"]=="")
            $datosGrafico .= $resultado[$k]["fecha"] . ";" . $presAjax->pres->formatearDecimales($resultado[$k]["eficiencia"]["eficienciaTermica"], 1, ".") . "_";

    return $datosGrafico;
}

function calcularEficiencia($form){
    $objResponse = new xajaxResponse();
    $presAjax = new presentacionAjax();
    $calcula = new calcularEficiencia();
    
    $objResponse->addScript("document.getElementById('tblResultado').style.display = 'block';");
    $objResponse->addScript("habilitarCampos(false);");
    
    $form["cmbTipoEquipo"] = obtenerTipoEquipo($form["cmbTipoEquipo"]);

    $resultado = $calcula->calcular($form);

    $objResponse->addAssign("txtEficienciaTermica", "value", $presAjax->pres->formatearMoneda($resultado["eficienciaTermica"]));
    $objResponse->addAssign("txtCalorPerdidoTeorico", "value", $presAjax->pres->formatearMoneda($resultado["calorPerdidoTeorico"]));
    if(isset($resultado["calorPerdidoReal"]))
        $objResponse->addAssign("txtCalorPerdidoReal", "value", $presAjax->pres->formatearMoneda($resultado["calorPerdidoReal"]));
    
    $objResponse->addAssign("area", "innerHTML", print_r($form,true));

    return $objResponse;
}

require("efitermAjax.php");
$xajax->processRequests();
?>