<?php
	header('Content-Type: text/html; charset=utf-8');
?>
<?php	
	include_once "../clases/cargarLog.php";
	$log = new Log();
	 
	$error = '<b>Error:</b> ¡Parámetros incorrectos!';

	$excluir = array("txtClave");

	$illegal = 'script|http|<|>|%3c|%3e|SELECT|UNION|UPDATE|AND|exe|exec|INSERT|tmp';
	$illegalPOST = 'script,http,<,>,%3c,%3e,%,(,),#,?,#,;,=,$';
	$illegalPOSTArray = explode(',', $illegalPOST);
	
	// Solution 1
	#
	// This will manually check the URL of the page for injections
	#
	$url = $_SERVER['REQUEST_URI'];
	#
	$params = explode("=", $url);
	#
	foreach ($params as $param) {
		if (preg_match("/$illegal/i", $param)){
			$log->guardarLog($log->logAccesos, "INYECCION", "URL, NO");
			die($error);
		}
	}
	// Solution 2
	//This picks up the GET variables which might work slightly better than the first solution...
	foreach ($_GET as $key => $param){
		if (preg_match("/$illegal/i", $param)){
			$log->guardarLog($log->logAccesos, "INYECCION", "GET, NO");
			die($error);
		}
	}

	// Revisión de los parametros POST
	foreach ($_POST as $key => $param){
		if(array_search($key, $excluir)===false){
			$nuevoValor = str_replace($illegalPOSTArray, "", $param);

			if($nuevoValor!==$param){
				$log->guardarLog($log->logAccesos, "INYECCION", "POST, NO");
				$_POST[$key] = $nuevoValor;
			}
		}
	}
?>