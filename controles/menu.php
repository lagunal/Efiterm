<?php
$usuarios = array("", "");
$log = array("", "");

if(isset($_SESSION["id"]) && $clad->buscarUsuarioAdministrador($_SESSION["id"])=="1"){
	$usuarios = array("Usuarios", "usuarios.php");
	$log = array("Log", "logArchivo.php");
}

$opciones = array(
                array("Efiterm", "efiterm.php"),
                /*array("Efiterm English", "efiterm_ing.php"),*/ 
                array("Seguimiento", "indicadores.php"), 
                array("Equipos", "tagsEquipos.php"), 
                array("Metodología", "metodologia.php"),
                array("Personalizar", "personalizar.php"),
                array("Ayuda", "ayuda.php"),
                $usuarios,
                $log,
                array("", ""),
                array("Cerrar sesión", "login.php")
            );

$c = count($opciones);

for($i=0; $i<$c; $i++){
    echo "<a href=\"" . $opciones[$i][1] . "\" class=\"Contenedor-Texto-Menu\"><span class=\"Text-menu\">" . $opciones[$i][0] . "</span></a>";

    if($opciones[$i][0]!="")
        echo "<span class=\"PuntoHo_Cortico\"></span>";
    else
        echo "<span></span><span class=\"Text-menu\">&nbsp;</span>";
}
?>