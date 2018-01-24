<?php
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);

	if(!isset($_SERVER["HTTP_REFERER"]) || $_SERVER["HTTP_REFERER"]==""){
		header("Location: ../paginas/login.php");
		die();
	}
	
    session_start();
    extract($_POST);
    extract($_GET);

	header("Cache-Control: private");
    
    include_once "../clases/injectionPHP.php";
    include_once "../clases/autenticar.php";
    include_once "../clases/presentacion.php";
    include_once "../config.php";

    $aut = new autenticacion();
    $aut->autenticar();
    
    $pres = new presentacion();
    $config = new config();
    $clad = new clad();
    
    //SESIONES ACTIVAS
    if($clad->consultarSesionNombre($_SESSION["id"], $_SESSION["nombre"])==0){
    	header("Location: ../paginas/login.php");
    	die();
    }
?>
<script language="JavaScript" type="text/javascript">
if(window.history.forward(1) != null){
	window.history.forward(1);
}
</script>