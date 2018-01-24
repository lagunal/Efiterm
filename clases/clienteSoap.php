<?php

	// Pull in the NuSOAP code
	require_once("../lib_soap/nusoap.php");
	
	/**
	Funci�n para iniciar la sesi�n de un usuario y evitar sesiones concurrentes
	@param indicador:codigo unico de usuario.
	@param contrase�a:
	@param ip:direcci�n ip de la maquina.
	*/
	function IniciarSesion($indicador,$contrasena,$ip){	
		
		// Create the client instance		
		$client = new soapclient2('../../WebService/ldap/ws_ldap.php?wsdl', true);		
		
		// Call the SOAP method	
		$result = $client->call('autenticaIPAplic', array('usuario' => "$indicador",'clave' => "$contrasena",'ip' => "$ip",'aplicacion' => 'SIMIP'));
	
		return $result;	
	
	}
	
	/**
	Funci�n para liberar la sesi�n de un usuario
	@param numero unico de sesion o token de base de datos.
	*/
	function CerrarSesion($sesion){
	
	// Create the client instance		
		$client = new soapclient2('../../WebService/ldap/ws_ldap.php?wsdl', true);		
		
		// Call the SOAP method		
		$result = $client->call('logout', array('token' => "$sesion"));
	
		
		return $result;	
		
	}	
	
	
	
	
	/*
	// Check for a fault
	if ($client->fault) {
		echo '<h2>Fault</h2><pre>';
		print_r($result);
		echo '</pre>';
	} else {
		// Check for errors
		$err = $client->getError();
		if ($err) {
			// Display the error
			echo '<h2>Error</h2><pre>' . $err . '</pre>';
		} else {
			// Display the result
			echo '<h2>Result</h2><pre>';
			print_r($result);
		echo '</pre>';
		}
	}
	
	
	
	// Display the request and response
	echo '<h2>Request</h2>';
	echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
	echo '<h2>Response</h2>';
	echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
	// Display the debug messages
	echo '<h2>Debug</h2>';
	echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
	*/
?>
