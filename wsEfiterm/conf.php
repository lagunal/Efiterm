<?php
    class conf{
        public $ruta = "";
        public $rutaWSDL = "";

        public function conf(){
            $this->rutaWSDL = $this->ruta . "wsEfiterm.php?wsdl";
            $this->ruta = "http://" . $_SERVER["HTTP_HOST"] . "/efiterm/wsEfiterm/";
        }
    }
?>
