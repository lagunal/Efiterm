<?php
include "../controles/header.php";

	include_once "../clases/clad.php";
	$clad = new clad();

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
				echo $pres->crearVentanaInicio("AYUDA");
				include "../controles/menu.php";
				echo $pres->crearVentanaIntermedia();
			?>
			<form id="form1" method="post" style="margin:0px">
	    		<table cellpadding="0" cellspacing="0" border="0" width="590px" height="100%">
					<tr>
						<td colspan="3" height="0px">
						</td>
					</tr>
					<tr>
						<td>

                        <table id="tblEncabezado" cellpadding="0" cellspacing="0" border="0" width="590px" height="100%">
                            <tr>
                                <td colspan="3" height="0px">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table height="100%" cellspacing="0" cellpadding="0" width="100%" border="0">
                                        <tr>
                                            <td valign="middle" align="center" height="60"><img src="../imagenes/logoEFITERM250px.jpg"></td>
                                        </tr>

                                        <tr height="100%">
                                            <td valign="top">
                                                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                                                    <tr>
                                                        <td class="DetalleNegrita"><a class="DetalleNegrita" href="ayuda.php#Informacion">-Información de EfiTerm</a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="DetalleNegrita"><a class="DetalleNegrita" href="ayuda.php#Caracteristicas">-Características Técnicas</a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="DetalleNegrita"><a class="DetalleNegrita" href="ayuda.php#Notas">-Notas para usar la aplicación</a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="DetalleNegrita"><a class="DetalleNegrita" href="ayuda.php#Datos">-Datos de entrada</a> </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="DetalleNegrita"><a class="DetalleNegrita" href="ayuda.php#Contacto">-Contactos</a> </td>
                                                    </tr>
                                                </table>
                                                <p class="DetalleNegrita" id="Informacion" align="justify">&nbsp;</p>
                                                <p class="DetalleNegrita" align="justify">INFORMACIÓN DE EFITERM:</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">EfiTerm</a> es una aplicación informática que permite calcular:</p>
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr height="30">
                                                        <td>
                                                            <p class="Detalle" align="justify">Eficiencia térmica de hornos de proceso y calderas acuotubulares, basado en las prácticas recomendadas API-532 y ASME PTC 4.1 respectivamente.</p>
                                                        </td>
                                                    </tr>
                                                    <tr height="30">
                                                        <td>
                                                            <p class="Detalle" align="justify">Ahorros Potenciales por la reducción del consumo de combustible en MMBs./año, MUS$/año y MPie3/año.</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <p class="Detalle" align="justify"><a class="Detalle" href="ayuda.php#tblEncabezado">volver</a></p>
                                                <p class="Detalle" align="justify">&nbsp;</p>
                                                <p class="DetalleNegrita" id="Caracteristicas" align="justify">CARACTERÍTICAS TÉCNICAS:&nbsp;</p>
                                                <p class="Detalle" align="justify">Aplicación propia amigable, que permite la determinación de la eficiencia térmica de equipos de fuego directo según las prácticas internacionales.</p>
                                                <p class="Detalle" align="justify">Adquisición automática de la data para cálculos de eficiencia (variables de procesos y resultados de laboratorio) para cualquier fecha y hora deseada, de acuerdo a información existente en el repositorio de datos en sala de control.</p>
                                                <p class="Detalle" align="justify">Puede ser particularizada de acuerdo a preferencias o requerimientos del custodio de la instalación.</p>
                                                <p class="Detalle" align="justify">Permite la realización de estudios de sensibilidad respecto a variaciones en la composición del combustible o cambios en las condiciones de operación.</p>
                                                <p class="Detalle" align="justify">Generación de Reportes con presentación de los datos de entrada y los resultados obtenidos.</p>
                                                <p class="Detalle" align="justify"><a class="Detalle" href="ayuda.php#tblEncabezado">volver</a></p>
                                                <p class="Detalle" align="justify">&nbsp;</p>
                                                <p class="DetalleNegrita" id="Notas" align="justify">NOTAS PARA USAR LA APLICACION:</p>
                                                <p class="Detalle" align="justify">Los datos que contegan decimales deben colocarse con ",".</p>
                                                <p class="Detalle" align="justify">El formato de los datos es numérico, no se deben ingresar caracteres alfabéticos ni de ningún otro tipo.</p>
                                                <p class="Detalle" align="justify"><b>&nbsp;</b>El sistema mostraró&nbsp;el mensaje "<font color="#ff0000"><strong><span class="error" id="lblError">Debe revisar estos valores</span></strong></font>" só el dato falta o tiene formato invólido, seóalado con un arterisco al lado de la variable.</p>
                                                <p class="Detalle" align="justify">La fecha inicial corresponde a la última fecha en la cual se realizaron anólisis de laboratorio para el combustible y el equipo seleccionado.</p>
                                                <p class="Detalle" align="justify">&nbsp;Si cambia la fecha inicial, el sistema mostraró los datos de procesos y de laboratorio del dóa seleccionado y de no haber datos de laboratorio para esa fecha el sistema mostraró el mensaje "<span class="error" id="Span1">No hay datos para la fecha seleccionada<font color="#000000">".</font></span></p>
                                                <p class="Detalle" align="justify">Existen variables que no pueden ser obtenidas de las bases de datos correspondientes (procesos y laboratorio), en estos casos hay que ingresar el valor manualmente.</p>
                                                <p class="Detalle" align="justify">La precisión de los resultados dependeró de la precesión de las mediciones, mientras mós incertidumbre haya en los datos de entrada mayor seró la incertidumbre en los resultados.</p>
                                                <p class="Detalle" align="justify"><a class="Detalle" href="ayuda.php#tblEncabezado">volver</a></p>
                                                <p class="Detalle" align="justify">&nbsp;</p>
                                                <p class="DetalleNegrita" id="Datos" align="justify">DATOS DE ENTRADA:</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">HHV:</a> Poder Calorófico Superior del Combustible [BTU/pie2]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">LHV:</a> Poder Calorófico Inferior del Combustible [BTU/pie2]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">T.P. Comb.:</a> Temperatura Promedio del Combustible [°F]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Mf:</a> Flujo mósico del Combustible [Mlb/h]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Qf:</a> Flujo volumótrico del Combustible [Mpie3/h]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Ma:</a> Flujo mósico del aire de combustión [Mlb/h]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">T. Ambiente:</a> Temperatura Ambiente [°F]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">H. Relativa:</a> Humedad Relativa del Aire [%]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">G. Especófica:</a> Gravedad Especófica del Combustible [adim]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">T. Chimenea:</a> Temperatura de los gases de combustión en la chimenea [°F]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">MrSt:</a> Flujo másico del vapor a la salida de la Caldera [Mlb/h]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Magua:</a> Flujo másico del agua de alimentación de la Caldera [Mlb/h]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Mvatom:</a> Flujo másico de vapor de atomización [lb/h]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">P. Vapor:</a> Presión del vapor a la salida de la Caldera [psig]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">T. Vapor:</a> Temperatura del vapor a la salida de la Caldera [°F]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">T. Agua:</a> Temperatura del agua de alimentación de la Caldera [°F]</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Costo Comb.:</a> Costo del Combustible (Bs./pie3)</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Paridad:</a> Paridad cambiaria (Bs./US$)</p>
                                                <p class="Detalle" align="justify"><a class="Detallee">Eficiencia de Diseóo:</a> Eficiencia de Diseóo del equipo (Caldera / Horno) (%).</p>
                                                <p class="Detalle" align="justify"><a class="Detalle" href="ayuda.php#tblEncabezado">volver</a></p>
                                                <p class="Detalle" align="justify">&nbsp;</p>
                                                <p class="DetalleNegrita" id="Contacto" align="justify">CONTACTOS:</p>
                                                <p class="Detalle" align="justify">Para mayor información sobre esta aplicación o para cualquier otro tipo de duda por favor consulte a:.</p>
                                                <p class="Detalle">- Nathalie Díaz,&nbsp;RIRF - INTEVEP, <a class="Detalle" href="mailto:DIAZNC@PDVSA.COM">DIAZNC@PDVSA.COM</a>, Extensión 93-56073.</p>
                                                <p class="Detalle" align="justify"><a class="Detalle" href="ayuda.php#tblEncabezado">volver</a></p>
                                            </td>
                                        </tr>
                                        <tr valign="top" height="100%">
                                            <td valign="top" height="100%">
                                                <table id="table2" height="100%" cellspacing="0" cellpadding="0" width="100%" border="0">
                                                    <tr>
                                                        <td valign="bottom" align="right" width="100%" height="100%">
                                                        </td>
                                                    </tr>
                                                </table>
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
</body>