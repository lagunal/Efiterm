<?php
include_once "conf.php";
include_once "negocio.php";
require_once "../../nusoap-0.7.3/lib/nusoap.php";

$conf = new conf();
$server = new soap_server();

$server->configureWSDL('Efiterm',$conf->ruta);
$server->wsdl->schemaTargetNamespace = $conf->ruta;

//$server->setHTTPContentTypeCharset("UTF-8");

//Lista de equipos
$server->wsdl->addComplexType(
    'EfitermEquipos', 'complexType', 'array', 'sequence', '',
    array(
        'codigo_equipo' => array('name'=>'codigo_equipo', 'type'=>'xsd:string'),
        'equipo' => array('name'=>'equipo', 'type'=>'xsd:string')
    )
);

//Eficiencia calculada
$server->wsdl->addComplexType(
    'EfitermEficienciaEquipo', 'complexType', 'array', 'sequence', '',
    array(
        'codigo_equipo' => array('name'=>'codigo_equipo', 'type'=>'xsd:string'),
        'eficiencia' => array('name'=>'idsolicitud', 'type'=>'xsd:int'),
    )
);

$server->register('obtenerEquipos', array(), array('return'=>'tns:EfitermEquipos'), $conf->ruta);
$server->register('obtenerEquipo', array(), array('return'=>'tns:EfitermEquipos'), $conf->ruta);
$server->register('obtenerEficienciaEquipo', array('codigo_equipo' => 'xsd:string', 'dias' => 'xsd:string'), array('return'=>'tns:EfitermEficienciaEquipo'), $conf->ruta);

//M�todos del servicio *******************

function obtenerEquipos(){
    $negocio = new negocio();

    return $negocio->obtenerEquipos();
}

function obtenerEquipo($codigo){
    $negocio = new negocio();

    return $negocio->obtenerEquipo($codigo);
}

//print_r(obtenerEficienciaEquipo("1,13","0"));

function obtenerEficienciaEquipo($codigo, $dias){
    $negocio = new negocio();

    return $negocio->obtenerEficienciaEquipo($codigo, $dias);
}

//print_r(obtenerEficienciaEquipo(13));
//print_r(obtenerEquipos());

//Creaci�n de p�gina de informaci�n **********************
if(isset($HTTP_RAW_POST_DATA)){
    $input = $HTTP_RAW_POST_DATA;
}else{
    $input = implode("\r\n", file('php://input'));
}
$server->service($input);
?>
