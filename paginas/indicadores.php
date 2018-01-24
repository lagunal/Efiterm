<?php
	include "../controles/header.php";

	include_once "../clases/cargarLog.php";
	$log = new Log();

	include_once "../clases/clad.php";
	$clad = new clad();

	require("../clases/efitermAjax.php");

	//$log->guardarLog($log->logAccesos, "INDICADORES", "OK");
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
	<script language="JavaScript" type="text/javascript">
		var forma;
	
		function verDetalleVentana(form){
			ocultarFondo();
			
			ventana = document.getElementById("divAbrir");
			forma = form;
			ventana.style.display = "block";
		}
		
		function mostrarDetalle(respuesta){
			ventana = document.getElementById("divAbrir").style.display = "none";
			document.getElementById('divFondo').style.display = 'none';
			
			if(respuesta != "cancelar"){
				verDetalle(forma, respuesta);
			}
		}
	</script>
</head>
<body>
<div id="divFondo" style="Z-INDEX: 2; DISPLAY: none; OVERFLOW: auto; WIDTH: 100%; HEIGHT: 100%; POSITION: absolute; left: 0px; top: 0px; background-color:#333333; zoom: 1;">
</div>
<table width="760px" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<?php echo $pres->crearEncabezado($config->nombreAplicacion); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
				echo $pres->crearVentanaInicio("SEGUIMIENTO");
				include "../controles/menu.php";
				echo $pres->crearVentanaIntermedia();
			?>
			<form id="form1" method="post" style="margin:0px">
	    		<table cellpadding="0" cellspacing="0" border="0" width="590px">
					<tr>
						<td colspan="3" height="0px">
							<div id="divAbrir" style="DISPLAY: none; Z-INDEX: 2; LEFT: 35%; OVERFLOW: auto; WIDTH: 400px; HEIGHT: 110px; POSITION: absolute; TOP: 40%;">
								<table width="100%" cellpadding="0" cellspacing="0" height="100%" class="popUP">
									<tr>
										<td height="4px"></td>
									</tr>
									<tr>
										<td class="Sub-Titulo" align="center">
											¿Desea ver el detalle del cálculo en otra ventana?
										</td>
									</tr>
									<tr>
										<td height="50px" align="center">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td>
														<?php echo $pres->crearBoton("", "Si", "button", "style=\"width: 70px\" onclick=\"mostrarDetalle('si');\""); ?>
														<?php echo $pres->crearBoton("", "No", "button", "style=\"width: 70px\" onclick=\"mostrarDetalle('no');\""); ?>
														<?php echo $pres->crearBoton("", "Cancelar", "button", "style=\"width: 70px\" onclick=\"mostrarDetalle('cancelar');\""); ?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td valign="top">
										<?php
											include "../controles/indicadores.php";
										?>
									</td>
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