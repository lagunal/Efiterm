<?php
	include "../controles/header.php";

	include_once "../clases/cargarLog.php";
	$log = new Log();

	include_once "../clases/clad.php";
	$clad = new clad();

    if(isset($_POST["btnGuardar"])){
        $clad->guardarOpciones(strtoupper($_SESSION["id"]), $_POST);
    }

    $datosConfiguracion = $clad->obtenerConfiguracion(strtoupper($_SESSION["id"]));

    if(count($datosConfiguracion)>0){
        $rangoGrafico = $datosConfiguracion[0]["rangografico"];
        $actualizacion = $datosConfiguracion[0]["actualizar"];
    }

	$log->guardarLog($log->logAccesos, "PERSONALIZAR", "OK");

	require("../clases/efitermAjax.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
    <title><?php echo $config->nombreAplicacion; ?></title>
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" title="Principal-Aplicaciones" type="text/css" href="../css/main-aplicacion.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>   
	<script language="JavaScript" type="text/javascript" src="../js/formulaire.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/efiterm.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/validaciones.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/fechas.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/fondo.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/injectionJS.js"></script>
	<?php
		$xajax->printJavascript("../");
	?>
</head>
<body>
<table width="760px" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php echo $pres->crearEncabezado($config->nombreAplicacion); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
				echo $pres->crearVentanaInicio("PERSONALIZAR");
				include "../controles/menu.php";
				echo $pres->crearVentanaIntermedia();
			?>
			<form id="form1" method="post" style="margin:0px">
	    		<table cellpadding="0" cellspacing="0" border="0" width="590px">
					<tr>
						<td colspan="3" height="0px">
						</td>
					</tr>
					<tr>
						<td>
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td height="5px">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Detalle" width="130px">Rango del gráfico (días):</td>
                                    <td class="Detalle" width="400px">
	                                    <select name="cmbRango" class="Detalle" style="width:50px">
	                                        <?php
	                                            $datos = array(3, 5, 7); 
	                                            echo $pres->crearComboArray($datos, $rangoGrafico);
	                                        ?>
	                                    </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="5px">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="Detalle" valign="top">Equipos:</td>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <?php
                                                $equipos = array();
                                                
                                                $c = count($datosConfiguracion);
                                                
                                                for($i=0;$i<$c;$i++)
                                                    $equipos[] = $datosConfiguracion[$i]["codigo_equipo"];

                                                $datosHorno = $clad->obtenerEquiposTodos();
                                                $c = count($datosHorno);
                                                $checked = "";
                                            
                                                for($i=0;$i<$c;$i++){
                                                    $mostrar = array_search($datosHorno[$i]["codigo_equipo"], $equipos);
                                                    
                                                    if($mostrar===false)
                                                        $checked = "";
                                                    else
                                                        $checked = "checked";
                                                
                                                /*
                                                    if($datosHorno[$i]["indicador"]==1)
                                                        $indicador = "";
                                                    else
                                                        $indicador = "(No tiene suficiente instrumentación)";
                                                */
                                                	if($datosHorno[$i]["indicador"]==1){
	                                                    $nombre = "chkHorno" . $datosHorno[$i]["codigo_equipo"];
	                                                    echo "<tr><td><input id=\"$nombre\" name=\"$nombre\" type=\"checkbox\" $checked/></td>";
	                                                    echo "<td><label class=\"Detalle\" for=\"$nombre\">" . $datosHorno[$i]["siglas"] . ", " . $datosHorno[$i]["equipo"] . " - " . $datosHorno[$i]["planta"] . ", " . $datosHorno[$i]["codigo"] . " </label></td></tr>\n";
	                                                    echo "<tr><td height=3px></td></tr>";
                                                	}
                                                }
                                            ?>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                    <table cellpadding="0" cellspacing="0" border="0">
                                    	<tr>
                                    		<td width="480px">
                                    		</td>
                                    		<td>
			                                	<?php 
			                                		echo $pres->crearBoton("btnGuardar", "Guardar", "submit", "style=\"width:80px\"");
			                                	?>
			                                </td>
			                            </tr>
									</table>
                                </tr>
                            </table>
						</td>
					</tr>
				</table>
			</form>
			<?php echo $pres->crearVentanaFin(); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $pres->crearPie(); ?>
		</td>
	</tr>
</table>
</body>