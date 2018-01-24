<?php
include "../controles/header.php";

	include_once "../clases/clad.php";
	$clad = new clad();

	require("../clases/efitermAjax.php");

	if($clad->buscarUsuarioAdministrador($_SESSION["id"])!="1"){
		exit;
	}
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
				echo $pres->crearVentanaInicio("USUARIOS");
				include "../controles/menu.php";
				echo $pres->crearVentanaIntermedia();
			?>
			<form id="form1" method="post" style="margin:0px">
	    		<table cellpadding="0" cellspacing="0" border="0" width="590px" height="100%">
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
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td height="5px">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="center">
                                    	<img src="../controles/grafico.php">
                                    </td>
                                </tr>
                                <tr>
                                    <td height="5px">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="linea">
                                    </td>
                                </tr>
                                <tr>
                                    <td height="5px">
                                    </td>
                                </tr>
                                <tr>
                                    <td width="70px" class="Titulo">
                                    </td>

                                    <td width="150px" class="Titulo">ID</td>

                                    <td width="250px" class="Titulo">Fecha</td>
                                </tr>
                                <?php
                                    $datos = $clad->obtenerLog();
                                    
                                    echo $pres->crearTabla($datos, "Lista-Fondo1", "Lista-Fondo2");
                                ?>
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
    
        if(convertirFechaObjeto(fechai) < convertirFechaObjeto(fechaf)){
            xajax_obtenerDatosEquipoTags(document.getElementById('cmbEquipo').value, document.getElementById('cmbTagsEquipo').value, 'Gas', document.getElementById('txtFechaInicio').value, document.getElementById('txtFechaFin').value);     
        }else{
            alert('Las fechas deben estar en el formato dd/mm/yyyy, Fecha Inicio debe ser menor que Fecha Fin');
        }
    }
    
    function verGrafico(){
        ruta = "../controles/graficoEficiencia.php?datos=" + document.getElementById('txtValores').value;
        ruta += "&ancho=350&alto=250&link=no&valorlinea=no";
        
        document.getElementById('ifmGrafico').src = ruta;
    }
</script>
</body>