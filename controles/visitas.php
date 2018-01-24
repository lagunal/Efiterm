<?php
    extract($_GET);

    include_once "../config.php";
    include "../clases/autenticar.php";
    
    $aut = new autenticacion();
    $config = new config();
    
    if($aut->estaAutenticado()){
        if(isset($_GET["id"]) && isset($_GET["tipo"]) && $_GET["id"]!="" && $_GET["tipo"]!=""){
            include "../clases/clad.php";
            
            $config = new config();
            $clad = new clad($config->queryString);
    
            if($_GET["tipo"] == "a"){
                if($clad->existeUsuario($_GET["id"])==0)
                    $clad->agregarUsuario($_GET["id"], "");

                $clad->agregarVisita($_GET["id"]);
            }else if($_GET["tipo"] == "c"){
                $res = $clad->obtenerVisitas($_GET["id"]);
            }
            
            echo $clad->obtenerVisitas($_GET["id"]);
            
        }
    }
?>