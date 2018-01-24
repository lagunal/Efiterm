<?php
	include_once "../clases/clad.php";
	
    class autenticacion{
        function autenticar(){
        	$clad = new clad();

            if(!isset($_SESSION["id"]) || trim($_SESSION["id"]) == "" || $clad->buscarUsuario($_SESSION["id"])==0)
                header("Location: ../paginas/login.php");
        }
        
        function estaAutenticado(){
            if(!isset($_SESSION["id"]) || trim($_SESSION["id"]) == "")
                return true;
            else
                return false;
        }
        
    }
?>
