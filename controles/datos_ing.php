<table id="tblDatos" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0">
            	<tr>
            		<td colspan="4">
            			<?php echo $pres->crearSeparador("Data"); ?>
            		</td>
            	</tr>
            	<tr>
                    <td id="lblTipoEquipo" class="Detalle" width="100px">
                    	Tipo de Equipo:
                    </td>
                    <td width="130px">
                    	<select id="cmbTipoEquipo" name="cmbTipoEquipo" class="Detalle" style="WIDTH: 100px" onchange="xajax_crearComboEquipos('cmbEquipo', document.getElementById('cmbTipoEquipo').value);xajax_crearComboCombustibles('cmbTipoCombustible',document.getElementById('cmbTipoEquipo').value);Filtrar();">
	                        <?php
	                            $datos = $clad->obtenerTiposEquipoRefineria();
	                            echo $pres->crearCombo($datos, "codigo", "nombre");
	                        ?>
                    	</select>
                    </td> 
                    <td id="lblEquipo" class="Detalle" width="100px">
                    	Equipos:
                    </td>
                    <td>
                    	<select id="cmbEquipo" name="cmbEquipo" class="Detalle" style="WIDTH: 250px" onchange="xajax_obtenerFechaSqlLims(document.getElementById('cmbEquipo').value, 'txtFecha');">
                    	</select>
                    </td>
            	</tr>
                <tr>
                    <td height="6px">
                    </td>
                </tr>
            	<tr>
            		<td id="lblTipoCombustible" class="Detalle">
            			Combustible:
            		</td>
                    <td>
                    	<select id="cmbTipoCombustible" name="cmbTipoCombustible" class="Detalle" style="WIDTH: 100px" onchange="Filtrar();">
                    	</select>
                    </td>
                    <td id="lblFecha" class="Detalle">
                    	Fecha:
                    </td>
                    <td colspan="2">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                                	<input id="txtFecha" name="txtFecha" type="text" value="" size="9" maxlength="10">
                                </td>
                                <td>
                                	<a href="javascript:abrirCalendario('txtFecha')"><img src="../imagenes/calendario.gif" border="0"></a>
                                </td>
                                <td width="20px"></td>
                                <td>
                                	<?php echo $pres->crearBoton("btnObtener", "Obtener", "button", "onclick=\"xajax_obtenerDataSistemas(document.getElementById('cmbEquipo').value, document.getElementById('cmbTipoCombustible').value, document.getElementById('txtFecha').value);\""); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
            	</tr>
            </table>
		</td>
	</tr>
    <!-- Datos del Combustible Gas para Horno-->
    <tr>
        <td id="trCombustible0" style="display:none">
        	<?php echo $pres->crearSeparador2("Combustible"); ?>
        </td>
    </tr>
    <tr>
        <td id="trCombustible1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td colspan="6" style="display:none"><input id="txtResto" type="hidden"> </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblMetano" class="Detalle" height="26">
                    	Metano:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMetano" name="txtMetano" size="6" maxlength="10" type="text">&nbsp;%
                    </td>
                    <td id="lblC6" class="Detalle">
                    	C6+:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtC6" name="txtC6" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblHidrogeno" class="Detalle">
                    	Hidrógeno:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtHidrogeno" name="txtHidrogeno" size="6" maxlength="10">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno2" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>

                <tr>
                    <td id="lblEtano" class="Detalle" height="26">
                    	Etano:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtEtano" name="txtEtano" size="6" maxlength="10" type="text">&nbsp;%
                    </td>
                    <td id="lblEteno" class="Detalle">
                    	Eteno:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtEteno" name="txtEteno" size="6" maxlength="10" type="text">&nbsp;%
                    </td>
                    <td id="lblOxigeno" class="Detalle">
                    	Oxígeno:&nbsp;
                   	</td>
                    <td class="Texto-Identificador">
                    	<input id="txtOxigeno" name="txtOxigeno" size="6" maxlength="10" type="text">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno3" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblPropano" class="Detalle" height="26">
                    	Propano:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtPropano" name="txtPropano" size="6" maxlength="10" type="text">&nbsp;%
                    </td>
                    <td id="lblPropileno" class="Detalle">
                    	Propileno:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtPropileno" name="txtPropileno" size="6" maxlength="10" type="text">&nbsp;%
                    </td>
                    <td id="lblNitrogeno" class="Detalle">
                    	Nitrógeno:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtNitrogeno" name="txtNitrogeno" size="6" maxlength="10" type="text">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno4" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblNButano" class="Detalle" height="26">
                    	N-Butano:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtNButano" name="txtNButano" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblCO2" class="Detalle">
                    	CO<sub>2</sub>:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtCO2" name="txtCO2" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblNPentano" class="Detalle">
                    	N-Pentano:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtNPentano" name="txtNPentano" size="6" maxlength="10">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno5" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblIsoButano" class="Detalle" height="26">
                    	IsoButano:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtIsoButano" name="txtIsoButano" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblCO" class="Detalle">
                    	CO:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtCO" name="txtCO" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblIsoPentano" class="Detalle">
                    	IsoPentano:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtIsoPentano" name="txtIsoPentano" size="6" maxlength="10">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno6" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblOlefinasC5" class="Detalle" height="26">
                    	Olefinas C5:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtOlefinasC5" name="txtOlefinasC5" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblTotalButeno" class="Detalle">
                    	Total Buteno:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtTotalButeno" name="txtTotalButeno" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblH2S" class="Detalle">
                    	H<sub>2</sub>S:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtH2S" name="txtH2S" size="6" maxlength="10">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno8" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td class="Detalle">
                    	Porcent. total:&nbsp;
                    </td>
                    <td>
                    	<input id="txtPorcentajesCombustible" type="text" size="6" value="0" READONLY>
                    </td>
                    <td class="Texto-Identificador">
                    	%=% Molar
                    </td>
                    <td colspan="3">
                        <table cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="65px">
                                </td>
                                <td>
                            	<?php
                            		echo $pres->crearBoton("btnComposicionPredeterminada", "Predetermin.", "button", "title=\"Obtener la composición predeterminada\" onclick=\"xajax_obtenerDataSqlLimsPredeterminada(document.getElementById('cmbEquipo').value, '01/12/2004');\"");
                            		echo $pres->crearBoton("btnNormalizar1", "Normalizar", "button", "title=\"Normalizar los componentes del combustible\" onclick=\"Normalizar();\"");
                            	?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    
    
    <tr>
        <td id="trCombustible2" style="display:none">
        	<?php echo $pres->crearSeparador2("Propiedades del combustible"); ?>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasHorno7" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblGE" class="Detalle" height="26">
                    	G. Especifica:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtGE" name="txtGE" size="6" maxlength="10">&nbsp;
                    </td>
                    <td id="lblHHV1" class="Detalle">
                    	HHV:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtHHV1" name="txtHHV1" size="6" maxlength="10">&nbsp;BTU/pie<sup>3</sup>
                    </td>
                    <td id="lblLHV" class="Detalle">
                    	LHV:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtLHV" name="txtLHV" size="6" maxlength="10">&nbsp;BTU/pie<sup>3</sup>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Fin Datos del Combustible Gas para Horno-->
    <!-- Datos del Combustible Liquido para Horno y Caldera-->

    <tr>
        <td id="trCombustibleLiquidoHorno1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblHHVHornoHL" class="Detalle" height="26">
                    	HHV:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtHHVHornoHL" name="txtHHVHornoHL" size="6" maxlength="10">&nbsp;BTU/lb
                    </td>
                    <td id="lblRelacionCH" class="Detalle">
                    	Relación C/H:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtRelacionCH" name="txtRelacionCH" size="6" maxlength="10">&nbsp;
                    </td>
                    <td class="Detalle">
                    </td>
                    <td class="Texto-Identificador">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleLiquidoHorno2" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblCenizas" class="Detalle" height="26">
                    	Cenizas:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtCenizas" name="txtCenizas" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblAzufreHL" class="Detalle">
                    	Azufre:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtAzufreHL" name="txtAzufreHL" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblSodio" class="Detalle">
                    	Sodio:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtSodio" name="txtSodio" size="6" maxlength="10">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleLiquidoHorno3" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblOtros" class="Detalle" height="26">
                    	Otros:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtOtros" name="txtOtros" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td class="Detalle">
                    </td>
                    <td class="Texto-Identificador">
                    	%=%Peso
                    </td>
                    <td class="Detalle">
                    	Total:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtOtrosTotal" name="txtOtrosTotal" size="6" maxlength="10" READONLY>&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td id="trCombustibleLiquidoHornoCaldera1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblCarbono" class="Detalle" height="26">
                    	Carbono:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtCarbono" name="txtCarbono" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblHidrogenoLiquido" class="Detalle">
                    	Hidrógeno:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtHidrogenoLiquido" name="txtHidrogenoLiquido" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td id="lblAzufre" class="Detalle">
                    	Azufre:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtAzufre" name="txtAzufre" size="6" maxlength="10">&nbsp;%
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleLiquidoHornoCaldera2" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblGradoAPI" class="Detalle" height="26">
                    	°API:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtGradoAPI" name="txtGradoAPI" size="6" maxlength="10">&nbsp;
                    </td>
                    <td class="Detalle">
                    	Porcent. total:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtPorcentajesCombustible2" type="text" size="6">&nbsp;%=% Peso
                    </td>
                    <td colspan="2">
                        <table cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td width="35px">
                                </td>
                                <td>
                            	<?php
                            		echo $pres->crearBoton("btnNormalizar2", "Normalizar", "button", "title=\"Normalizar los componentes del combustible\" onclick=\"Normalizar();\"");
                            	?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Fin Datos del Combustible Liquido para Horno-->
    <!-- Datos del Combustible Gas - Liquido para Caldera-->
    <tr>
        <td id="trCombustibleGasLiquidoCaldera1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblMfCaldera" class="Detalle" height="26">
                    	Mf:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMfCaldera" name="txtMfCaldera" size="6" maxlength="10">&nbsp;Mlb/h
                    </td>
                    <td id="lblHHV2" class="Detalle">
                    	HHV:&nbsp;
                    </td>
                    <td id="lblHHV1" class="Texto-Identificador">
                    	<input id="txtHHV2" name="txtHHV2" size="6" maxlength="10">&nbsp;BTU/pie<sup>3</sup>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trDatosVaporAguaCaldera" style="display:none">
        	<?php echo $pres->crearSeparador2("Vapor de agua"); ?>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasLiquidoCaldera2" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblPresionVapor" class="Detalle" height="26">
                    	P. Vapor:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtPresionVapor" name="txtPresionVapor" size="6" maxlength="10">&nbsp;psi
                    </td>
                    <td id="lblTemperaturaVapor" class="Detalle">
                    	T. Vapor:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtTemperaturaVapor" name="txtTemperaturaVapor" size="6" maxlength="10">&nbsp;°F
                    </td>
                    <td id="lblTemperaturaAgua" class="Detalle">
                    	T. Agua:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtTemperaturaAgua" name="txtTemperaturaAgua" size="6" maxlength="10">&nbsp;°F
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCombustibleGasLiquidoCaldera3" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblMrSt" class="Detalle" height="26">
                    	MrSt:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMrSt" name="txtMrSt" size="6" maxlength="10">&nbsp;Mlbr/h
                    </td>
                    <td id="lblMagua" class="Detalle">
                    	Magua:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMagua" name="txtMagua" size="6" maxlength="10">&nbsp;Mlbr/h
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Fin Datos del Combustible Gas -Liquido para Caldera-->
    <!-- Datos operacionales para Horno (Combustible Gas - Liquido para Horno)-->
    <tr>
        <td id="trDatosOperacionalesCombustibleGasLiquidoHorno0" style="display:none">
        	<?php echo $pres->crearSeparador2("Variables de proceso"); ?>
        </td>
    </tr>


    <tr>
        <td id="trDatosOperacionalesCombustibleLiquidoHorno" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblMcomb" class="Detalle" height="26">
                    	Mcomb:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMcomb" name="txtMcomb" size="6" maxlength="10">&nbsp;lb/h
                    </td>
    				<td id="lblTCombustibleHL" class="Detalle" align="left">
                    	T.P. Comb.:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtTCombustibleHL" name="txtTCombustibleHL" size="6" maxlength="10">&nbsp;°F
                    </td>
                    <td class="Detalle" align="left">
                    </td>
                    <td class="Texto-Identificador">
                    </td>
                </tr>
            </table>
        </td>
    </tr>


    <tr>
        <td id="trDatosOperacionalesCombustibleGasLiquidoHorno1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblMfHorno" class="Detalle" height="26">
                    	Qf:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<table cellspacing="0" cellpadding="0" border="0">
                    		<tr>
                    			<td>
                    				<input id="txtMfHorno" name="txtMfHorno" size="6" maxlength="10">
                    			</td>
                    			<td>
                    				<div id="lblUnidadQf" name="lblUnidadQf" class="Texto-Identificador">&nbsp;PCSH</div>
                    				<input id="txtUnidadQf" name="txtUnidadQf" type="hidden">
                    			</td>
                    		</tr>
                    	</table>
                    </td>
                    <td id="lblMa" class="Detalle"><!--Ma (Mlbr/h):-->
	                    <select id="cmbMa" name="cmbMa" width="50px" class="Detalle" style="WIDTH: 73px" onchange="flujoMa();">
	                        <option value="Teorica">Ma téorico</option>
	                        <option value="Real">Ma real:</option>
	                    </select>
	                </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMa" name="txtMa" maxlength="10" size="6" style="display: none">&nbsp;Mlbr/h
                    </td>
                    <td id="lblTCombustible" class="Detalle" align="left">
                    	T.P. Comb.:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtTCombustible" name="txtTCombustible" size="6" maxlength="10">&nbsp;°F
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trDatosOperacionalesCombustibleGasLiquidoHorno2" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblTChimenea" class="Detalle" height="26">
                    	T. Chimenea:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtTChimenea" name="txtTChimenea" size="6" maxlength="10">&nbsp;°F
                    </td>
                    <td id="lblExcesoAire" class="Detalle">
                    	Exceso O<sub>2</sub>:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtExcesoAire" name="txtExcesoAire" size="6" maxlength="10">&nbsp;%
                    </td>
                    <td colspan="2">
                    	<table cellspacing="0" cellpadding="0" border="0">
                    		<tr>
	                    		<td class="Texto-Identificador">
			                    	<input type="radio" name="rblBaseOxigeno" value="BaseSeca">Base Seca
			                    </td>
			                    <td class="Texto-Identificador">
			                    	<input type="radio" name="rblBaseOxigeno" value="BaseHumeda" checked>Base Húmeda
			                    </td>
                    		</tr>
                    	</table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Fin Datos operacionales para hornos (Combustible Gas - Liquido para Horno)-->
    <!-- Datos por defecto (Combustible Gas -Liquido para Horno)-->
    <tr>
        <td id="trDatosDefectoCombustibleGasLiquidoHorno0" style="display:none">
        	<?php echo $pres->crearSeparador2("Condiciones atmosféricas"); ?>
        </td>
    </tr>
    <tr>
        <td id="trDatosDefectoCombustibleGasLiquidoHorno1" style="display:none">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td id="trDatosDefectoCombustibleHorno" style="display:none" width="100%">
                        <table cellspacing="0" cellpadding="0" border="0">
                        	<?php echo $pres->separador(); ?>
                            <tr>
                                <td id="lblTemperaturaAmbiente" class="Detalle" height="26">
                                	T. Ambiente:&nbsp;
                                </td>
                                <td class="Texto-Identificador">
                                	<input id="txtTemperaturaAmbiente" name="txtTemperaturaAmbiente" size="6" maxlength="10" value="">&nbsp;°F
                                </td>
                                <td id="lblHumedadRelativa" class="Detalle">
                                	H. Relativa:&nbsp;
                                </td>
                                <td class="Texto-Identificador">
                                	<input id="txtHumedadRelativa" name="txtHumedadRelativa" size="6" maxlength="10" value="">&nbsp;%
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                    <td id="lblEficienciaTarget" class="Detalle">
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtEficienciaTarget" name="txtEficienciaTarget" size="6" style="display:none">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Fin Datos por defecto (Combustible Gas Liquido para Horno)-->
    <!-- Datos Datos de Vapor de Atomizaci?n (Combustible Liquido para Horno - Caldera) -->
    <tr>
        <td id="trDatosVaporAtomizacionCombustibleLiquidoHornoCaldera0" style="display:none">
        	<?php echo $pres->crearSeparador2("Condiciones atmosféricas"); ?>
        </td>
    </tr>

    <tr>
        <td id="trDatosVaporAtomizacionCombustibleLiquidoHorno" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblMvatomHL" class="Detalle" height="26">
                    	Mvatom:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMvatomHL" name="txtMvatomHL" size="6">&nbsp;lb/h
                    </td>
                    <td class="Detalle">
                    </td>
                    <td class="Texto-Identificador">
                    </td>
                    <td class="Detalle">
                    </td>
                    <td class="Texto-Identificador">
                    </td>
                </tr>
            </table>
        </td>
    </tr>


    <tr>
        <td id="trDatosVaporAtomizacionCombustibleLiquidoHornoCaldera1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblMvatom" class="Detalle" height="26">
                    	Mvatom:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtMvatom" name="txtMvatom" size="6">&nbsp;lb/h
                    </td>
                    <td id="lblPresion" class="Detalle">
                    	Presión:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtPresion" name="txtPresion" size="6">&nbsp;Psig
                    </td>
                    <td id="lblTemperaturaVaporAtomizacion" class="Detalle">
                    	Temperatura:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtTemperaturaVaporAtomizacion" name="txtTemperaturaVaporAtomizacion" size="6">&nbsp;°F
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Fin Datos de Vapor de Atomizaci?n (Combustible Liquido para Horno - Caldera)-->
    <!-- Datos de la Composici?n de los gases (Combustible Gas - Liquido para Horno)-->
    <tr>
        <td id="trDatosComposicionCombustibleHorno0" style="display:none">
        	<?php echo $pres->crearSeparador2("Gases de combusti&oacute;n"); ?>
        </td>
    </tr>
    <tr>
        <td id="trDatosComposicionCombustibleHorno1" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td class="Detalle" colspan="2">
                    	Composición de los Gases:&nbsp;
                    </td>
                    <td>
                    	<select id="cmbComposicionGases" name="cmbComposicionGases" class="Detalle" style="WIDTH: 70px" onchange="ComposicionGases();">
	                        <option value="Teorica">Teórica</option>
	                        <option value="Real">Real</option>
                    	</select>
                    </td>
                    <td colspan="3">
                        <table id="trBase" cellspacing="0" cellpadding="0" border="0" style="display:none">
                            <tr>
                                <td class="Texto-Identificador" width="90px">
                                	<input type="radio" name="rblComposicionGases" value="BaseSeca" class="Texto-Identificador" checked>Base Seca
                                </td>
                                <td class="Texto-Identificador" width="90px">
                                	<input type="radio" name="rblComposicionGases" value="BaseHumeda" class="Texto-Identificador">Base Húmeda
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td height="10px">
    	</td>
    </tr>
    <tr>
        <td id="trDatosComposicionCombustibleHorno2" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblRealCO2" class="Detalle" height="26">
                    	CO2:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtRealCO2" name="txtRealCO2" size="6" maxlength="10">&nbsp;%Vol
                    </td>
                    <td id="lblRealCO" class="Detalle">
                    	CO:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtRealCO" name="txtRealCO" size="6" maxlength="10">&nbsp;ppm
                    </td>
                    <td id="lblRealSO2" class="Detalle">
                    	SO2:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtRealSO2" name="txtRealSO2" size="6" maxlength="10">&nbsp;ppm
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trDatosComposicionCombustibleHorno3" style="display:none">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td id="lblRealO2" class="Detalle" height="26">
                    	O2:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtRealO2" name="txtRealO2" size="6" maxlength="10">&nbsp;%Vol
                    </td>
                    <td id="lblRealNO" class="Detalle">
                    	NO:&nbsp;
                    </td>
                    <td class="Texto-Identificador">
                    	<input id="txtRealNO" name="txtRealNO" size="6" maxlength="10">&nbsp;ppm
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="px">
        	<?php echo $pres->crearSeparador("Calcular"); ?>
        </td>
    </tr>
    <tr>
        <td id="trTipoCalculo">
            <table cellspacing="0" cellpadding="0" border="0">
                <?php echo $pres->separador(); ?>
                <tr>
                    <td class="Detalle" colspan="2">Tipo de Cálculo:&nbsp;</td>
                    <td colspan="3">
	                    <select id="cmbTipoCalculo" name="cmbTipoCalculo" class="Detalle" style="WIDTH: 225px" onchange="seleccionarCalculo();">
	                        <option value="1">Cálculo de eficiencia</option>
	                        <option value="2">Cálculo de eficiencia por rango de fechas</option>
	                    </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="5px"></td>
    </tr>
    <tr>
        <td id="trCalcular" align="right" height="25px">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td align="right">
                    	<?php echo $pres->crearBoton("btnCalcular", "Calcular", "button", "onclick=\"calcular();\""); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td id="trCalcularFechas" height="25px" style="display: none">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td class="Detalle" width="75px" height="26">
                    	Fecha Inicio:&nbsp;
                    </td>
                    <td width="85px">
                        <table cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td>
                                	<input id="txtFechaInicio" name="txtFechaInicio" value="" size="6" maxlength="10">
                                </td>
                                <td>
                                	<a href="javascript:abrirCalendario('txtFechaInicio')"><img src="../imagenes/calendario.gif" border="0"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="Detalle" width="65px">
                    	Fecha Fin:&nbsp;
                    </td>
                    <td colspan="2" width="80px">
                        <table cellspacing="0" cellpadding="0" border="0">
                            <tr>
                                <td>
                                	<input id="txtFechaFin" name="txtFechaFin" value="" size="6" maxlength="10">
                                </td>
                                <td>
                                	<a href="javascript:abrirCalendario('txtFechaFin')"><img src="../imagenes/calendario.gif" border="0"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                    	<input type="checkbox" id="chbMostrarEficiencia" checked onclick="actualizarGrafico()">
                    </td>
                    <td width="155px">
                    	<label class="Texto-Identificador" for="chbMostrarEficiencia">Mostrar eficiencia diseño</label>
                    </td>
                    <td align="right">
                    	<?php echo $pres->crearBoton("btnCalcularHistorio", "Calcular rango", "button", "onclick=\"calcularRango();\""); ?><input type="hidden" id="txtEficiencias" name="txtEficiencias" class="Detalle">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="100%">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td id="lblError" align="center" width="100%" class="errorEtiqueta">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- Fin Datos de los Datos de la composicion de los Gases (Combustible Gas - Liquido para Horno)-->
</table>
<script language="JavaScript" type="text/javascript" src="../js/efiterm.js"></script>
  
<script language="JavaScript" type="text/javascript">
    fechasIniciales();
    
    function calcularRango(){
        fechai = document.getElementById('txtFechaInicio').value;
        fechaf = document.getElementById('txtFechaFin').value;
        
        resetearGrafico();
        
        if(document.getElementById('cmbEquipo').value != "" && document.getElementById('cmbEquipo').value != "-"){
            if(convertirFechaObjeto(fechai) < convertirFechaObjeto(fechaf)){
                if(activarValidaciones(document.getElementById('cmbTipoEquipo').value, document.getElementById('cmbTipoCombustible').value, document.getElementById('cmbComposicionGases').value, document.getElementById('cmbMa').value, document.forms[0])){
                    xajax_calcularEficienciaRango(xajax.getFormValues('frmEfiterm'));
                    actualizarGrafico();
                }
            }else{
                alert('Las fechas deben estar en el formato dd/mm/yyyy, Fecha Inicio debe ser menor que Fecha Fin')
            }
        }else{
            alert('Debe seleccionar un equipo');
        }
    }

    function calcular(){
        if(activarValidaciones(document.getElementById('cmbTipoEquipo').value, document.getElementById('cmbTipoCombustible').value, document.getElementById('cmbComposicionGases').value, document.getElementById('cmbMa').value, document.forms[0])){
        	calcularPorcentajes();
            xajax_calcularEficiencia(xajax.getFormValues('frmEfiterm'));
		}
    }
    
    function actualizarGrafico(){
        ruta = "../controles/graficoEficiencia.php?datos=" + document.getElementById('txtEficiencias').value;

        if(document.getElementById('chbMostrarEficiencia').checked)
            ruta += "&efi=" + document.getElementById('txtEficienciaTarget').value;

        document.getElementById('ifrGrafico').src = ruta;
    }

    function resetearGrafico(){
        document.getElementById('txtEficiencias').value = "";
        document.getElementById('ifrGrafico').src = "../controles/graficoEficiencia.php?datos=0";
        document.getElementById('tdPuntosGrafico').innerHTML = "";
    }
    
    function cargarValores(campos){
        filas = campos.split(";");
        
        c = filas.length;
        
        for(i=0;i<c;i++){
            valores = filas[i].split("=");
            if(document.getElementById(valores[0])){
                document.getElementById(valores[0]).value = valores[1];
            }
        }

        moverScroll();
    }

    function moverScroll(){
        if(document.all)
            currentpos=document.body.scrollTop;
        else
            currentpos=window.pageYOffset;

        destpos = 303;
        
        for(i=currentpos; i>destpos; i=i-3)
            window.scroll(0,i);
    }
</script>