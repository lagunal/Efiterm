<?php
	include "../controles/header.php";

	include_once "../clases/cargarLog.php";
	$log = new Log();
	
	require("../clases/efitermAjax.php");
	include_once "../clases/clad.php";
	$clad = new clad();
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
	<script language="JavaScript" type="text/javascript" src="../js/grafico.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/fondo.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/calendario.js"></script>
	<script language="JavaScript" type="text/javascript" src="../js/injectionJS.js"></script>
	<?php
		$xajax->printJavascript("../");
	?>
	<script language="JavaScript" type="text/javascript">
		function desactivarCombos(activar){
			document.getElementById('cmbTipoEquipo').disabled = activar;
			document.getElementById('cmbEquipo').disabled = activar;
			document.getElementById('cmbTipoCombustible').disabled = activar;
			document.getElementById('cmbComposicionGases').disabled = activar;
			document.getElementById('cmbTipoCalculo').disabled = activar;
			document.getElementById('cmbMa').disabled = activar;
		}
		
	    function showLoading(){
	        xajax.$('loading').style.display = 'block';
	        document.getElementById('txtLoadingEstado').innerHTML = "Por favor espere...";
	
			desactivarCombos(true);
		
	        ocultarFondo();
	    }
	
	    function hideLoading(){
	        xajax.$('loading').style.display = 'none';
	        document.getElementById('divFondo').style.display = 'none';
		    desactivarCombos(false);
	    }
	
		function cancelarRequest(){
			hideLoading();
		}
		
	    xajax.loadingFunction = showLoading;
	    xajax.doneLoadingFunction = hideLoading;
	</script>
</head>
<body onload="Filtrar();">
<div id="divFondo" style="Z-INDEX: 2; DISPLAY: none; OVERFLOW: auto; WIDTH: 100%; HEIGHT: 100%; POSITION: absolute; TOP:0px; LEFT:0px; background-color:#333333; zoom: 1; border:1">
</div>
<form id="frmEfiterm" action="get" style="margin:0px">
	<table width="760px" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<?php echo $pres->crearEncabezado($config->nombreAplicacion); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php 
					echo $pres->crearVentanaInicio("EFITERM");
					include "../controles/menu.php";
					echo $pres->crearVentanaIntermedia();
				?>
				<form id="form1" method="post" style="margin:0px">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td colspan="3" height="0px">
								<div id="loading" style="DISPLAY: none; Z-INDEX: 2; LEFT: 35%; OVERFLOW: auto; WIDTH: 400px; HEIGHT: 110px; POSITION: absolute; TOP: 40%;">
									<table width="100%" cellpadding="0" cellspacing="0" height="100%" class="popUP" border="0">
										<tr>
											<td align="center">
												<table cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td class="Titulo">Cargando</td>
														<td width="15px"></td>
														<td><img src="../imagenes/wait.gif"></td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td height="15px" align="center" id="txtLoadingEstado" class="Titulo">abc</td>
										</tr>
										<tr>
											<td height="4px"></td>
										</tr>
										<tr>
											<td width="100%">
												<table cellpadding="0" cellspacing="0" border="0" width="100%">
													<tr>
														<td width="150px"></td>
														<td>
															<?php echo $pres->crearBoton("btnCancelar", "Cancelar", "button", "style=\"width: 80px\" onclick=\"cancelarRequest();\""); ?>
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
							<td id="tdEfitermDatos">
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td>
											<?php
												require "../controles/datos.php";
											?>
										</td>
									</tr>
									<tr>
										<td height="5px"></td>
									</tr>
									<tr>
										<td id="tdEfitermResultados">
											<?php
												require "../controles/resultado.php";
											?>
											<table cellpadding="0" cellspacing="0" border="0" width="100%">
												<tr>
													<td id="tdPuntosGrafico" align="center" class="TextoLink">
														<?php 
															if(isset($_POST["cmbEquipo"]))
																echo $_POST["frmEquipo" . $_POST["cmbEquipo"] . "txtPuntosGrafico"]; 
														?>
													</td>
												</tr>
												<tr>
													<td height="15px"></td>
												</tr>
												<tr>
													<td>
														<iframe id="ifrGrafico" src="" width="585px" height="260px" frameborder="0" marginheight="0" marginwidth="0" scrolling="no">
														</iframe>
													</td>
												</tr>
											</table>
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
</form>
</body>
</html>
<script language="JavaScript" type="text/javascript">
<?php
	if(isset($_POST["cmbEquipo"])){
		$res = $clad->obtenerEquipoRefineria($_POST["cmbEquipo"]);
		$codigoTipo = $res[0]["codigo_tipo"] . ";" . $res[0]["codigo_refineria"];
	}
?>
<?php if(isset($_POST["cmbEquipo"])){ ?>
	document.getElementById('txtEficiencias').value = "<?php echo $_POST["frmEquipo" .$_POST["cmbEquipo"] . "txtEficiencias"]; ?>";
	
	document.getElementById('cmbTipoEquipo').options.selectedIndex = buscarCombo("cmbTipoEquipo", "<?php echo $codigoTipo; ?>"); 
	xajax_crearComboEquipos('cmbEquipo', document.getElementById('cmbTipoEquipo').value, <?php echo $_POST["cmbEquipo"]; ?>);
	xajax_crearComboCombustibles('cmbTipoCombustible',document.getElementById('cmbTipoEquipo').value);
	Filtrar();	

	setTimeout("f1()",200);

	function f1(){	
		document.getElementById('cmbTipoCombustible').options.selectedIndex = buscarCombo("cmbTipoCombustible", "<?php echo $_POST["cmbTipoCombustible"]; ?>");
		Filtrar();
	}

	document.getElementById('cmbTipoCalculo').options.selectedIndex = 1;
	seleccionarCalculo();
	document.getElementById('txtFechaInicio').value = "<?php echo $_POST["txtFechaInicio"]; ?>";
	document.getElementById('txtFechaFin').value = "<?php echo $_POST["txtFechaFin"]; ?>";

	document.getElementById('chbMostrarEficiencia').checked = false;
	actualizarGrafico();

	control = document.getElementById('lnkPuntoGrafico0');
	if(control){
		control = control.toString();
		eval(control.substr(11));
	}
<?php } ?>
</script>