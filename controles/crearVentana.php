<?php
include_once "../clases/presentacion.php";
include_once "../config.php";

function crearVentana($nombreVentana, $datos, $equipos, $rangoGrafico, $tipoEquipo, $tipoCombustible, $mensajeCalculo){
	$pres = new presentacion();

	echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" class=\"\"><tr><td>";
	echo $pres->crearSeparador($nombreVentana);
	echo "</td></tr><tr><td class=\"Texto-Identificador\" >";
	echo $mensajeCalculo;
	echo "</td></tr><tr><td>";
	
	$c = count($datos);
?>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td height="8px"></td>
		</tr>
		<tr>
			<td valign="top">
				<table cellpadding="0" cellspacing="0" border="0">
				<?php
					if(count($equipos)>0){
				?>
					<tr>
						<td width="190px" class="Sub-Titulo" height="35px" rowspan="2" colspan="2">Equipos</td>
						<td class="Sub-Titulo" colspan="2" align="center">% Eficiencia</td>
					</tr>
					<tr>
						<td width="43px" class="Sub-Titulo" align="center">Actual</td>
						<td width="43px" class="Sub-Titulo" align="center">Diseño</td>
					</tr>
				<?php
					}else{
				?>
					<tr>
						<td width="200px">
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td height="50px"></td>
								</tr>
								<tr>
									<td>
										<a href="personalizar.php" class="Detalle">
											<img src="../imagenes/mas.gif" border="0">
											Debe agregar los equipos que desee monitorear.
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				<?php
					}
				?>
					<tr>
						<td height="5px"></td>
					</tr>
<?php
	for($i=0; $i<$c; $i++){
		$mostrar = array_search($datos[$i]["codigo_equipo"], $equipos);

		if($mostrar !== false){
			$nombre = "frmEquipo".$datos[$i]["codigo_equipo"];
			$nombreEquipo = $datos[$i]["planta"] . " - " . $datos[$i]["codigo"];

			if($datos[$i]["indicador"] == 1){
				$link = " <a href=\"javascript:verDetalleVentana('$nombre')\" class=\"TextoLink\" title=\"" . $datos[$i]["siglas"] . ", " . $nombreEquipo . "\">$nombreEquipo</a>";
				$mensaje = "Espere";
				$link = "<a id=\"lnkVerGrafico$nombre\" href=\"javascript:verGrafico('ifmGrafico$tipoEquipo','$nombre"."txtEficiencias','txtTituloGrafico$tipoEquipo','$nombreEquipo');\" class=\"Detalle\" title=\"Ver gráfico\"><img src=\"../imagenes/grafico.gif\" border=\"0\"></a>".$link;
				$indicador = "<a id=\"$nombre"."IndicadorLink\" href=\"javascript:mostrarDetalleError('$nombre"."IndicadorDiv')\">" .
							"<img id=\"$nombre"."Indicador\" class=\"Detalle\" src=\"../imagenes/calculando.gif\" border=\"0\"></a>" .
							"<div id=\"$nombre"."IndicadorDiv\" style=\"DISPLAY: none; OVERFLOW: auto; WIDTH: 170; HEIGHT: 20; POSITION: absolute;\" class=\"toolTip\"></div>";

				echo "<tr>";

				//Equipo
				echo "<td class=\"Detalle\" height=\"16px\">$link</td>\r";

				//Indicador de mas o menos
				echo "<td class=\"Detalle\" width=\"14px\" align=\"center\">$indicador</td>\r";

				//Eficiencia actual
				echo "<td id=\"$nombre"."EficienciaActual\" class=\"eficienciaMenor\" align=\"center\">$mensaje"."</td>";

				//Eficiencia de dise�o
				$eficienciaDiseño = "";
				if($datos[$i]["eficiencia"] != "")  $eficienciaDiseño = $pres->formatearSeparadorDecimales($datos[$i]["eficiencia"]);
				else $eficienciaDiseño = "N/A";

				echo "<td id=\"$nombre"."EficienciaDiseno\" class=\"Detalle\" align=\"center\">$eficienciaDiseño</td>";

				echo "<td style=\"display:none\">";
				$fechaActual = $pres->obtenerFechaActual();

				crearForma($nombre, $datos[$i]["codigo_equipo"], $pres->dateSum($fechaActual, -$rangoGrafico), $fechaActual, $datos[$i]["eficiencia"], $tipoEquipo, $tipoCombustible);
	
				echo "</td>";
				echo "</tr>";
				//echo "<tr><td class=\"linea\" colspan=\"4\"></td></tr>";
			}
		}
	}
?>
				</table>
			</td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td align="center" height="30px">
							<label id="txtTituloGrafico<?php echo $tipoEquipo; ?>" class="Sub-Titulo"></label>
						</td>
					</tr>
					<tr>
						<td height="5px"></td>
					</tr>
					<tr>
						<td>
							<iframe id="ifmGrafico<?php echo $tipoEquipo; ?>" src="" width="310px" height="190px" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="5px"></td>
		</tr>
	</table>
<?php
	echo "</td></tr></table>";
}

	function crearForma($nombre, $equipo, $fechaInicio, $fechaFin, $eficienciaDiseno, $tipoEquipo, $tipoCombustible){
		$conf = new config();
?>
<form id="<?php echo $nombre; ?>" method="post" action="efiterm.php" target="_blank">
	<input id="cmbEquipo" name="cmbEquipo" value="<?php echo $equipo; ?>" type="hidden"/>
	<input id="cmbTipoEquipo" name="cmbTipoEquipo" value="<?php echo $tipoEquipo; ?>" type="hidden"/>
	<input id="cmbTipoCombustible" name="cmbTipoCombustible" value="<?php echo $tipoCombustible; ?>" type="hidden"/>
	<input id="txtEficienciaTermica" name="txtEficienciaTermica" type="hidden"/>
	<input id="txtCalorPerdidoTeorico" name="txtCalorPerdidoTeorico" type="hidden"/>
	<input id="txtCalorPerdidoReal" name="txtCalorPerdidoReal" type="hidden"/>
	<input id="txtFecha" name="txtFecha" value="" type="hidden"/>
	<input id="txtFechaInicio" name="txtFechaInicio" value="<?php echo $fechaInicio; ?>" type="hidden"/>
	<input id="txtFechaFin" name="txtFechaFin" value="<?php echo $fechaFin; ?>" type="hidden"/>
	
	<input id="cmbComposicionGases" name="cmbComposicionGases" value="Teorica" type="hidden"/>
	<input id="txtMetano" name="txtMetano" class="detalleDerecha" type="hidden"/>
	<input id="txtC6" name="txtC6" class="detalleDerecha" type="hidden"/>
	<input id="txtHidrogeno" name="txtHidrogeno" class="detalleDerecha" type="hidden"/>
	<input id="txtEtano" name="txtEtano" class="detalleDerecha" type="hidden"/>
	<input id="txtEteno" name="txtEteno" class="detalleDerecha" type="hidden"/>
	<input id="txtOxigeno" name="txtOxigeno" class="detalleDerecha" type="hidden"/>
	<input id="txtPropano" name="txtPropano" class="detalleDerecha" type="hidden"/>
	<input id="txtPropileno" name="txtPropileno" class="detalleDerecha" type="hidden"/>
	<input id="txtNitrogeno" name="txtNitrogeno" class="detalleDerecha" type="hidden"/>
	<input id="txtNButano" name="txtNButano" class="detalleDerecha" type="hidden"/>
	<input id="txtCO2" name="txtCO2" class="detalleDerecha" type="hidden"/>
	<input id="txtNPentano" name="txtNPentano" class="detalleDerecha" type="hidden"/>
	<input id="txtIsoButano" name="txtIsoButano" class="detalleDerecha" type="hidden"/>
	<input id="txtCO" name="txtCO" class="detalleDerecha" type="hidden"/>
	<input id="txtIsoPentano" name="txtIsoPentano" class="detalleDerecha" type="hidden"/>
	<input id="txtOlefinasC5" name="txtOlefinasC5" class="detalleDerecha" type="hidden"/>
	<input id="txtTotalButeno" name="txtTotalButeno" class="detalleDerecha" type="hidden"/>
	<input id="txtH2S" name="txtH2S" class="detalleDerecha" type="hidden"/>
	<input id="txtGE" name="txtGE" class="detalleDerecha" type="hidden"/>
	<input id="txtHHV1" name="txtHHV1" class="detalleDerecha" value="900" type="hidden"/>
	<input id="txtLHV" name="txtLHV" class="detalleDerecha" type="hidden"/>
	<input id="txtCarbono" name="txtCarbono" class="detalleDerecha" type="hidden"/>
	<input id="txtHidrogenoLiquido" name="txtHidrogenoLiquido" class="detalleDerecha" type="hidden"/>
	<input id="txtGradoAPI" name="txtGradoAPI" class="detalleDerecha" type="hidden"/>
	<input id="txtAzufre" name="txtAzufre" class="detalleDerecha" type="hidden"/>
	<input id="txtMfCaldera" name="txtMfCaldera" class="detalleDerecha" type="hidden"/>
	<input id="txtHHV2" name="txtHHV2" class="detalleDerecha" value="900" type="hidden"/>
	<input id="txtPresionVapor" name="txtPresionVapor" class="detalleDerecha" type="hidden"/>
	<input id="txtTemperaturaVapor" name="txtTemperaturaVapor" class="detalleDerecha" type="hidden"/>
	<input id="txtTemperaturaAgua" name="txtTemperaturaAgua" class="detalleDerecha" type="hidden"/>
	<input id="txtMrSt" name="txtMrSt" class="detalleDerecha" type="hidden"/>
	<input id="txtMagua" name="txtMagua" class="detalleDerecha" type="hidden"/>
	<input id="txtMfHorno" name="txtMfHorno" class="detalleDerecha" type="hidden"/>
	<input id="cmbMa" name="cmbMa" value="Teorica" type="hidden">
	<input id="txtMa" name="txtMa" class="detalleDerecha" type="hidden"/>
	<input id="txtTCombustible" name="txtTCombustible" class="detalleDerecha" value="85" type="hidden"/>
	<input id="txtTChimenea" name="txtTChimenea" class="detalleDerecha" type="hidden"/>
	<input id="txtExcesoAire" name="txtExcesoAire" class="detalleDerecha" type="hidden"/>
	<input id="rblBaseOxigeno" name="rblBaseOxigeno" value="BaseSeca" type="hidden"/>
	<input id="txtCostoCombustible" name="txtCostoCombustible" class="detalleDerecha" type="hidden"/>
	<input id="txtParidad" name="txtParidad" class="detalleDerecha" type="hidden"/>
	<input id="txtEficienciaTarget" name="txtEficienciaTarget" value="<?php echo $eficienciaDiseno; ?>" class="detalleDerecha" type="hidden"/>
	<input id="txtTemperaturaAmbiente" name="txtTemperaturaAmbiente" class="detalleDerecha" type="hidden" value="<?php echo $conf->temperaturaAmbiente; ?>"/>
	<input id="txtHumedadRelativa" name="txtHumedadRelativa" class="detalleDerecha" type="hidden" value="<?php echo $conf->humedadRelativa; ?>"/>
	<input id="txtMvatom" name="txtMvatom" class="detalleDerecha" type="hidden"/>
	<input id="txtPresion" name="txtPresion" class="detalleDerecha" type="hidden"/>
	<input id="txtTemperaturaVaporAtomizacion" name="txtTemperaturaVaporAtomizacion" class="detalleDerecha" type="hidden"/>
	<input id="rblComposicionGases" name="rblComposicionGases" value="BaseSeca" type="hidden"/>
	<input id="txtRealCO2" name="txtRealCO2" class="detalleDerecha" type="hidden"/>
	<input id="txtRealCO" name="txtRealCO" class="detalleDerecha" type="hidden"/>
	<input id="txtRealSO2" name="txtRealSO2" class="detalleDerecha" type="hidden"/>
	<input id="txtRealO2" name="txtRealO2" class="detalleDerecha" type="hidden"/>
	<input id="txtRealNO" name="txtRealNO" class="detalleDerecha" type="hidden"/>
	<input id="<?php echo $nombre; ?>txtEficiencias" name="<?php echo $nombre; ?>txtEficiencias" type="hidden">
	<input id="<?php echo $nombre; ?>txtPuntosGrafico" name="<?php echo $nombre; ?>txtPuntosGrafico" type="hidden"/>
	<input id="<?php echo $nombre; ?>txtCamposFaltantes" name="<?php echo $nombre; ?>txtCamposFaltantes" type="hidden"/>
	<table style="display:none">
		<tr>
			<td id="<?php echo $nombre; ?>tdPuntosGrafico"></td>
		</tr>
	</table>
</form>
<?php
	}
?>