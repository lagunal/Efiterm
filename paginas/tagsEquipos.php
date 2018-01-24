<?php
	include "../controles/header.php";

	include_once "../clases/cargarLog.php";
	$log = new Log();

	include_once "../clases/clad.php";
	$clad = new clad();

	require("../clases/efitermAjax.php");

	//$log->guardarLog($log->logAccesos, "EQUIPOS", "OK");
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
	<script language="JavaScript" type="text/javascript" src="../js/calendario.js"></script>
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
				echo $pres->crearVentanaInicio("EQUIPOS");
				include "../controles/menu.php";
				echo $pres->crearVentanaIntermedia();
			?>
			<form id="form1" method="post" style="margin:0px">
	    		<table cellpadding="0" cellspacing="0" border="0" width="590px">
					<tr>
						<td colspan="3" height="0px" style="display:none">
							<input type="hidden" id="txtValores" name="txtValores" value=""/>
						</td>
					</tr>
					<tr>
						<td>
      						<?php echo $pres->crearSeparador("Equipos"); ?>
						</td>
					</tr>
					<tr>
						<td>
		                    <table cellpadding="0" cellspacing="0" border="0">
		                        <tr>
		                            <td height="6px">
		                            </td>
		                        </tr>
		                        <tr>
		                            <td class="Detalle" width="90px">
		                            	Tipo de Equipo:&nbsp;
		                            </td>
		                            <td width="160px">
		                            	<select id="cmbTipoEquipo" name="cmbTipoEquipo" class="Detalle" style="WIDTH: 90px" onchange="xajax_crearComboEquipos('cmbEquipo', document.getElementById('cmbTipoEquipo').value);cmbTagsEquipo.options.length=0">
		                                <?php
		                                    $datos = $clad->obtenerTiposEquipoRefineria();
		                                    echo $pres->crearCombo($datos, "codigo", "nombre");
		                                ?>
		                            	</select>
		                            </td>
		                            <td class="Detalle" width="70">
		                            	Equipos:&nbsp;
		                            </td>
		                            <td colspan="3" width="250px">
		                            	<select id="cmbEquipo" name="cmbEquipo" class="Detalle" style="WIDTH: 250px" onchange="xajax_obtenerTagsEquipo('cmbTagsEquipo', document.getElementById('cmbEquipo').value);">
		                            	</select>
		                            </td>
		                        </tr>
		                        <tr>
		                            <td height="6px">
		                            </td>
		                        </tr>
		                        <tr>
		                            <td colspan="2">
		                            </td>
		                            <td class="Detalle">
		                            	Tags:&nbsp;
		                            </td>
		                            <td colspan="2">
		                            	<select id="cmbTagsEquipo" name="cmbTagsEquipo" class="Detalle" style="WIDTH: 250px">
		                            	</select>
		                            </td>
		                        </tr>
		                        <tr>
		                            <td height="4px">
		                            </td>
		                        </tr>
		
		                        <tr>
		                            <td class="Detalle" height="26">
		                            	Fecha Inicio:&nbsp;
		                            </td>
		                            <td>
		                                <table cellspacing="0" cellpadding="0" border="0">
		                                    <tr>
		                                        <td>
		                                        	<input id="txtFechaInicio" name="txtFechaInicio" value="" class="Detalle" size="10" maxlength="10">
		                                        </td>
		                                        <td>
		                                        	<a href="javascript:abrirCalendario('txtFechaInicio')"><img src="../imagenes/calendario.gif" border="0"></a>
		                                        </td>
		                                    </tr>
		                                </table>
		                            </td>
		                            <td class="Detalle">
		                            	Fecha Fin:&nbsp;
		                            </td>
		                            <td>
		                                <table cellspacing="0" cellpadding="0" border="0">
		                                    <tr>
		                                        <td>
		                                        	<input id="txtFechaFin" name="txtFechaFin" value="" class="Detalle" size="10" maxlength="10">
		                                        </td>
		                                        <td>
		                                        	<a href="javascript:abrirCalendario('txtFechaFin')"><img src="../imagenes/calendario.gif" border="0"></a>
		                                        </td>
		                                    </tr>
		                                </table>
		                            </td>
		                            <td align="right">
		                            	<?php echo $pres->crearBoton("btnObtener", "Obtener", "button", "onclick=\"obtenerDatos();\""); ?>
		                            </td>
		                        </tr>
		                    </table>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $pres->crearSeparador("Información del equipo"); ?>
						</td>
					</tr>
					<tr>
						<td>
		                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
		                        <tr>
		                            <td id="tblResultado" name="tblResultado" width="250px" height="100%" valign="top">
		                            </td>
		                            <td>
		                            	<iframe id="ifmGrafico" src="" width="350px" height="290px" frameborder="0" marginheight="0" marginwidth="0" scrolling="no">
		                            	</iframe>
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

<script language="JavaScript" type="text/javascript">
    fechasIniciales(14);

    function obtenerDatos(){
        fechai = document.getElementById('txtFechaInicio').value;
        fechaf = document.getElementById('txtFechaFin').value;
        equipo = document.getElementById('cmbEquipo');
		
		if(equipo.selectedIndex>0){
	        if(convertirFechaObjeto(fechai) < convertirFechaObjeto(fechaf)){
	            xajax_obtenerDatosEquipoTags(document.getElementById('cmbEquipo').value, document.getElementById('cmbTagsEquipo').value, 'Gas', document.getElementById('txtFechaInicio').value, document.getElementById('txtFechaFin').value);     
	        }else{
	            alert('Las fechas deben estar en el formato dd/mm/yyyy, Fecha Inicio debe ser menor que Fecha Fin');
	        }
		}else{
			alert('Debe seleccionar un equipo');
		}
    }
    
    function verGrafico(){
        ruta = "../controles/graficoEficiencia.php?datos=" + document.getElementById('txtValores').value;
        ruta += "&ancho=350&alto=250&link=no&valorlinea=no";
        
        document.getElementById('ifmGrafico').src = ruta;
    }
</script>
</body>