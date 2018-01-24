<?php
    class autorizar{
        function estaAutorizado($id){
            include_once "../clases/clad.php";
            include_once "../config.php";
            
            $config = new config();
            
            $clad = new clad($config->queryString);
            $res = $clad->obtenerAutorizacion($id);

            if(!isset($res) || $res == 0){
                return false;
            }else{
                return true;
            }
        }
        
        function autorizar($id){
            if($id != "")
                if(!$this->estaAutorizado($id))
                    exit;
        }
    }
?>
