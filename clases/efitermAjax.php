<?php
    include_once "../clases/clad.php";
    include_once "../config.php";
    include_once "../clases/cargarLog.php";
    require_once("../../xajax_0.2.4/xajax.inc.php");

    $xajax = new xajax("../clases/presentacionAjax.php");
    //$xajax = new xajax("http://129.90.73.165:8084/efiterm/clases/presentacionAjax.php?DBGSESSID=1@localhost:10001");

    $xajax->debugOff();
    //$xajax->debugOn();
    $xajax->statusMessagesOff();
    $xajax->errorHandlerOn();
    //$xajax->setLogFile("logError.txt"); 

    $xajax->registerFunction("crearComboEquipos");
    $xajax->registerFunction("obtenerVariable");
    $xajax->registerFunction("obtenerFechaSqlLims");
    $xajax->registerFunction("obtenerDataSistemas");
    $xajax->registerFunction("obtenerDataSqlLimsPredeterminada");
    $xajax->registerFunction("calcularEficiencia");
    $xajax->registerFunction("calcularEficienciaRango");
    $xajax->registerFunction("obtenerDatosEquipoTags");
    $xajax->registerFunction("obtenerTagsEquipo");
    $xajax->registerFunction("calcularEficienciaRangoIndicadores");
    $xajax->registerFunction("crearComboCombustibles");

    //MANEJO DE ERRORES
    error_reporting(E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
    
    // funcion de gestion de errores
    function miGestorErrores($num_err, $cadena_err="", $archivo_err="", $linea_err="", $errcontext=""){
        if (error_reporting() == 0 || $num_err == 2048) {
            return;
        }
		
		$log = new Log();
        
        $log->guardarLog($log->logError, "ERRORES", "$num_err, $cadena_err, $archivo_err, $linea_err");
    }

    $gestor_errores_anterior = set_error_handler("miGestorErrores");
?>