<?php
	include "../controles/header.php";
    
    include_once "../clases/cargarLog.php";
	$log = new Log();
    
    include "../clases/directorioActivo.php";
    
    $da = new directorioActivo();
    $pres = new presentacion();

	$log->guardarLog($log->logAccesos, "REPORTE", "OK");
?>

<html>
    <head>
        <meta name="generator" content="HTML Tidy, see www.w3.org">
        <link rel="shortcut icon" href="../favicon.ico">
        <meta http-equiv="Content-Language" content="en">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="../css/main-aplicacion.css" rel="stylesheet" type="text/css">
        <title>Efiterm</title>
		<script language="JavaScript" type="text/javascript" src="../js/efiterm.js"></script>
		<script language="JavaScript" type="text/javascript">
		    function cargar(){
		        forma = window.opener.document.forms[0];
		        
		        document.getElementById('cmbTipoEquipo').value = forma.cmbTipoEquipo.options[forma.cmbTipoEquipo.selectedIndex].value;
		        document.getElementById('cmbEquipo').value = forma.cmbEquipo.options[forma.cmbEquipo.selectedIndex].text;
		        document.getElementById('cmbTipoCombustible').value = forma.cmbTipoCombustible.options[forma.cmbTipoCombustible.selectedIndex].text;
		        document.getElementById('cmbComposicionGases').value = forma.cmbComposicionGases.options[forma.cmbComposicionGases.selectedIndex].text;
		
		        document.getElementById('txtTipoEquipo').innerHTML += forma.cmbTipoEquipo.options[forma.cmbTipoEquipo.selectedIndex].text;
		        document.getElementById('txtEquipo').innerHTML += forma.cmbEquipo.options[forma.cmbEquipo.selectedIndex].text;
		        document.getElementById('txtTipoCombustible').innerHTML += forma.cmbTipoCombustible.options[forma.cmbTipoCombustible.selectedIndex].text;
		    
		        if(forma.cmbTipoEquipo.options[forma.cmbTipoEquipo.selectedIndex].text != "Horno")
		            document.getElementById('trCalorPerdido').style.display = "none";
		        
		        document.getElementById('txtMetano').innerHTML += forma.txtMetano.value + " %";
		        document.getElementById('txtC6').innerHTML += forma.txtC6.value + " %";
		        document.getElementById('txtHidrogeno').innerHTML += forma.txtHidrogeno.value + " %";
		        document.getElementById('txtEtano').innerHTML += forma.txtEtano.value + " %";
		        document.getElementById('txtEteno').innerHTML += forma.txtEteno.value + " %";
		        document.getElementById('txtOxigeno').innerHTML += forma.txtOxigeno.value + " %";
		        document.getElementById('txtPropano').innerHTML += forma.txtPropano.value + " %";
		        document.getElementById('txtPropileno').innerHTML += forma.txtPropileno.value + " %";
		        document.getElementById('txtNitrogeno').innerHTML += forma.txtNitrogeno.value + " %";
		        document.getElementById('txtNButano').innerHTML += forma.txtNButano.value + " %";
		        document.getElementById('txtCO2').innerHTML += forma.txtCO2.value + " %";
		        document.getElementById('txtNPentano').innerHTML += forma.txtNPentano.value + " %";
		        document.getElementById('txtIsoButano').innerHTML += forma.txtIsoButano.value + " %";
		        document.getElementById('txtCO').innerHTML += forma.txtCO.value + " %";
		        document.getElementById('txtIsoPentano').innerHTML += forma.txtIsoPentano.value + " %";
		        document.getElementById('txtOlefinasC5').innerHTML += forma.txtOlefinasC5.value + " %";
		        document.getElementById('txtTotalButeno').innerHTML += forma.txtTotalButeno.value + " %";
		        document.getElementById('txtH2S').innerHTML += forma.txtH2S.value + " %";
		        document.getElementById('txtGE').innerHTML += forma.txtGE.value;
		        document.getElementById('txtHHV1').innerHTML += forma.txtHHV1.value + " BTU/pie<sup>3<\/sup>";
		        document.getElementById('txtLHV').innerHTML += forma.txtLHV.value + " BTU/pie<sup>3<\/sup>";
		        document.getElementById('txtCarbono').innerHTML += forma.txtCarbono.value + " %";
		        document.getElementById('txtHidrogenoLiquido').innerHTML += forma.txtHidrogenoLiquido.value + " %";
		        document.getElementById('txtAzufre').innerHTML += forma.txtAzufre.value + " %";
		        document.getElementById('txtGradoAPI').innerHTML += forma.txtGradoAPI.value;
		        document.getElementById('txtMfCaldera').innerHTML += forma.txtMfCaldera.value + " Mlb/h ";
		        document.getElementById('txtHHV2').innerHTML += forma.txtHHV2.value + " BTU/pie<sup>3<\/sup>";
		        document.getElementById('txtPresionVapor').innerHTML += forma.txtPresionVapor.value + " psi";
		        document.getElementById('txtTemperaturaVapor').innerHTML += forma.txtTemperaturaVapor.value + " &deg;F";
		        document.getElementById('txtTemperaturaAgua').innerHTML += forma.txtTemperaturaAgua.value + " &deg;F";
		        document.getElementById('txtMrSt').innerHTML += forma.txtMrSt.value + " Mlbr/h";
		        document.getElementById('txtMagua').innerHTML += forma.txtMagua.value + " Mlbr/h";
		        document.getElementById('txtMfHorno').innerHTML += forma.txtMfHorno.value + forma.txtUnidadQf.value;
		
		        if(forma.cmbMa.selectedIndex == 0)
		            document.getElementById('txtMa').innerHTML += "Ma te&oacute;rico";
		        else
		            document.getElementById('txtMa').innerHTML += forma.txtMa.value + " Mlbr/h";
		
		        document.getElementById('txtTCombustible').innerHTML += forma.txtTCombustible.value + " &deg;F";
		        document.getElementById('txtExcesoAire').innerHTML += forma.txtExcesoAire.value  + " %";
		        document.getElementById('txtEficienciaTarget').innerHTML += forma.txtEficienciaTarget.value + " %";
		        document.getElementById('txtTemperaturaAmbiente').innerHTML += forma.txtTemperaturaAmbiente.value + " &deg;F";
		        document.getElementById('txtHumedadRelativa').innerHTML += forma.txtHumedadRelativa.value + " %";
		        document.getElementById('txtMvatom').innerHTML += forma.txtMvatom.value + " lb/h";
		        document.getElementById('txtPresion').innerHTML += forma.txtPresion.value + "  Psig";
		        document.getElementById('txtTemperaturaVaporAtomizacion').innerHTML += forma.txtTemperaturaVaporAtomizacion.value + " &deg;F";
		        document.getElementById('txtRealCO2').innerHTML += forma.txtRealCO2.value + " %Vol";
		        document.getElementById('txtRealCO').innerHTML += forma.txtRealCO.value + " ppm";
		        document.getElementById('txtRealSO2').innerHTML += forma.txtRealSO2.value + " ppm";
		        document.getElementById('txtRealO2').innerHTML += forma.txtRealO2.value + " %Vol";
		        document.getElementById('txtRealNO').innerHTML += forma.txtRealNO.value + " ppm";
		        
		        document.getElementById('txtHHVHornoHL').innerHTML += forma.txtHHVHornoHL.value + " BTU/lb";
		        document.getElementById('txtRelacionCH').innerHTML += forma.txtHHVHornoHL.value + " ";
		        document.getElementById('txtCenizas').innerHTML += forma.txtCenizas.value + " %";
		        document.getElementById('txtAzufreHL').innerHTML += forma.txtAzufreHL.value + " %";
		        document.getElementById('txtSodio').innerHTML += forma.txtSodio.value + " %";
		        document.getElementById('txtOtros').innerHTML += forma.txtOtros.value + " %";
		        document.getElementById('txtOtrosTotal').innerHTML += forma.txtOtrosTotal.value + " %";
		        document.getElementById('txtMcomb').innerHTML += forma.txtMcomb.value + " lb/h";
		        document.getElementById('txtTCombustibleHL').innerHTML += forma.txtTCombustibleHL.value + " &deg;F";
		        document.getElementById('txtTChimenea').innerHTML += forma.txtTChimenea.value + " &deg;F";
		        document.getElementById('txtMvatomHL').innerHTML += forma.txtMvatomHL.value + " lb/h";
		        
		        obtenerValor(forma.rblBaseOxigeno, 'rblBaseOxigeno');
		        document.getElementById('txtComposicionGases').innerHTML += forma.cmbComposicionGases.options[forma.cmbComposicionGases.selectedIndex].text;
		
		        document.getElementById('txtEficienciaTermica').innerHTML += forma.txtEficienciaTermica.value + " %";
		        document.getElementById('tdPerdidoReal1').innerHTML += forma.txtCalorPerdidoTeorico.value;
		        Filtrar();
		    }
		    
		    function obtenerValor(origen, destino){
		        destino = document.getElementById(destino);
		
		        for(i=0; i<origen.length; i++)
		            if(origen[i].checked){
		                destino.innerHTML += origen[i].value;
		                break;
		            }
		    }
		</script>
    </head>
    <body leftmargin="0" rightmargin="0" topmargin="0" bottommargin="0" onload="cargar();" class="">
        <form id="form1" action="get" style="margin:0px">
            <div style="OVERFLOW: auto; POSITION: relative;">
                <table cellpadding="0" cellspacing="0" border="0" width="665px" height="100%">
                    <?php echo $pres->separadorReporte(); ?>

                    <tr>
                        <td colspan="6">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td><img src="../imagenes/aplicacion/logorojo.png"></td>

                                    <td align="right"><img src="../imagenes/logoEFITERM180px.jpg"> </td>
                                </tr>

                                <tr>
                                    <td class="Titulo" align="center" colspan="2">Eficiencia Térmica de Hornos y Calderas</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="10px">
                        </td>
                    </tr>
                    <tr height="26">
                        <td class="DetalleNegrita">Usuario:&nbsp;</td>
                        <td class="Detalle" colspan="3">
                        	<?php
                                $nombre = $da->obtenerValor(strtoupper($_SESSION["id"]), array($da->nombre));
                                echo $pres->convertirNombre($nombre[0]);
                            ?>
                        </td>
                        <td class="DetalleNegrita">Fecha:&nbsp;</td>
                        <td class="Detalle" align="left">
                        	<?php
		                        echo date("d/m/Y");
		                    ?>
		                </td>
                    </tr>
<!--
                    <tr height="26">
                        <td class="DetalleNegrita">
                        	Localidad:&nbsp;
                        </td>
                        <td class="Detalle" colspan="3">
                        	Refinería de Puerto La Cruz
                        </td>
                    </tr>
-->
                    <!-- Equipo -->
                    <tr height="26">
                        <td class="DetalleNegrita">
                        	Tipo de Equipo:&nbsp;<input id="cmbTipoEquipo" type="hidden">
                        </td>
                        <td id="txtTipoEquipo" class="Detalle">
                        </td>
                        <td class="DetalleNegrita">
                        	Equipo:&nbsp;<input id="cmbEquipo" type="hidden">
                        </td>
                        <td id="txtEquipo" class="Detalle" colspan="3">
                        </td>
                    </tr>
                    <tr>
                        <td height="6px">
                        </td>
                    </tr>

                    <tr>
                        <td class="DetalleNegrita">
                        	Tipo de Comb.:&nbsp; <input id="cmbTipoCombustible" type="hidden">
                        </td>
                        <td id="txtTipoCombustible" class="Detalle">
                        </td>
                    </tr>

                    <tr>
                        <td height="5px">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="6">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td id="trCombustible0">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td class="Titulo" align="left" height="26">Combustible</td>
                                            </tr>

                                            <tr>
                                                <td class="linea" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustible1">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td colspan="6"><input id="txtResto" type="hidden"> </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno1">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">Metano:&nbsp;</td>

                                                <td id="txtMetano" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">C6+:&nbsp;</td>

                                                <td id="txtC6" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Hidrógeno:&nbsp;</td>

                                                <td id="txtHidrogeno" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno2">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">Etano:&nbsp;</td>

                                                <td id="txtEtano" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Eteno:&nbsp;</td>

                                                <td id="txtEteno" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Oxígeno:&nbsp;</td>

                                                <td id="txtOxigeno" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno3">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">Propano:&nbsp;</td>

                                                <td id="txtPropano" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Propileno:&nbsp;</td>

                                                <td id="txtPropileno" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Nitrógeno:&nbsp;</td>

                                                <td id="txtNitrogeno" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno4">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">N-Butano:&nbsp;</td>

                                                <td id="txtNButano" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">CO<sub>2</sub>:&nbsp;</td>

                                                <td id="txtCO2" size="12" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">N-Pentano:&nbsp;</td>

                                                <td id="txtNPentano" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno5">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">IsoButano:&nbsp;</td>

                                                <td id="txtIsoButano" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">CO:&nbsp;</td>

                                                <td id="txtCO" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">IsoPentano:&nbsp;</td>

                                                <td id="txtIsoPentano" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno6">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">Olefinas C5:&nbsp;</td>

                                                <td id="txtOlefinasC5" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Total Buteno:&nbsp;</td>

                                                <td id="txtTotalButeno" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">H<sub>2</sub>S:&nbsp;</td>

                                                <td id="txtH2S" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustible2">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td class="Titulo" align="left" height="26">Propiedades del combustible</td>
                                            </tr>

                                            <tr>
                                                <td class="linea" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno7">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">G. Especifica:&nbsp;</td>

                                                <td id="txtGE" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">HHV:&nbsp;</td>

                                                <td id="txtHHV1" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">LHV:&nbsp;</td>

                                                <td id="txtLHV" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasHorno8">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="Detalle" colspan="4" height="26">
                                                </td>

                                                <td class="Detalle" align="right">%=% Molar</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Fin Datos del Combustible Gas para Horno-->
                                <!-- Datos del Combustible Liquido para Horno y Caldera-->


                                <tr>
                                    <td id="trCombustibleLiquidoHorno1">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                            	<td class="DetalleNegrita" height="26">HHV:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtHHVHornoHL">
							                    </td>
							                    
							                    <td id="lblRelacionCH" class="DetalleNegrita">Relación C/H:&nbsp;</td>
							                    <td class="Detalle" id="txtRelacionCH">
							                    </td>

							                    <td class="Detalle"></td>
							                    <td class="Texto-Identificador">
							                    </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="trCombustibleLiquidoHorno2">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
							                    <td id="lblCenizas" class="DetalleNegrita" height="26">Cenizas:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtCenizas">
							                    </td>
							                    
							                    <td id="lblAzufreHL" class="DetalleNegrita">Azufre:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtAzufreHL">
							                    </td>
							                    
							                    <td id="lblSodio" class="DetalleNegrita">Sodio:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtSodio">
							                    </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="trCombustibleLiquidoHorno3">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
							                    <td id="lblOtros" class="DetalleNegrita" height="26">Otros:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtOtros">
							                    </td>
							                    
							                    <td class="DetalleNegrita"></td>
							                    
							                    <td class="Detalle">
							                    </td>
							                    
							                    <td class="DetalleNegrita">Total:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtOtrosTotal">
							                    </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>






                                <tr>
                                    <td id="trCombustibleLiquidoHornoCaldera1">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">Carbono:&nbsp;</td>

                                                <td id="txtCarbono" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Hidrógeno:&nbsp;</td>

                                                <td id="txtHidrogenoLiquido" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Azufre:&nbsp;</td>

                                                <td id="txtAzufre" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleLiquidoHornoCaldera2">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">°API:&nbsp;</td>

                                                <td id="txtGradoAPI" class="Detalle">
                                                </td>

                                                <td class="Detalle">
                                                </td>

                                                <td>
                                                </td>

                                                <td class="Detalle">%=% Peso</td>

                                                <td>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Fin Datos del Combustible Liquido para Horno-->
                                <!-- Datos del Combustible Gas - Liquido para Caldera-->

                                <tr>
                                    <td id="trCombustibleGasLiquidoCaldera1">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td id="lblMfCaldera" class="DetalleNegrita" height="26">Mf:&nbsp;</td>

                                                <td id="txtMfCaldera" class="Detalle">
                                                </td>

                                                <td id="lblHHV2" class="DetalleNegrita">HHV:&nbsp;</td>

                                                <td id="lblHHV1" class="Detalle"><a id="txtHHV2" class="Detalle"></a> </td>

                                                <td>
                                                </td>

                                                <td>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trDatosVaporAguaCaldera">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="Titulo" align="left" colspan="6" height="26">Vapor de Agua</td>
                                            </tr>

                                            <tr>
                                                <td class="linea" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasLiquidoCaldera2">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">P. Vapor:&nbsp;</td>

                                                <td id="txtPresionVapor" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">T. Vapor:&nbsp;</td>

                                                <td id="txtTemperaturaVapor" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">T. Agua:&nbsp;</td>

                                                <td id="txtTemperaturaAgua" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trCombustibleGasLiquidoCaldera3">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">MrSt:&nbsp;</td>

                                                <td id="txtMrSt" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Magua:&nbsp;</td>

                                                <td id="txtMagua" class="Detalle">
                                                </td>

                                                <td class="Detalle">
                                                </td>

                                                <td>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Fin Datos del Combustible Gas -Liquido para Caldera-->
                                <!-- Datos operacionales para Horno (Combustible Gas - Liquido para Horno)-->

                                <tr>
                                    <td id="trDatosOperacionalesCombustibleGasLiquidoHorno0">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="Titulo" align="left" colspan="6" height="26">Proceso</td>
                                            </tr>

                                            <tr>
                                                <td class="linea" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trDatosOperacionalesCombustibleLiquidoHorno">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
							                    <td id="lblMcomb" class="DetalleNegrita" height="26">Mcomb:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtMcomb">
							                    </td>
							                    
							    				<td id="lblTCombustibleHL" class="DetalleNegrita" align="left">T.P. Comb.:&nbsp;</td>
							    				
							                    <td class="Detalle" id="txtTCombustibleHL">
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
                                    <td id="trDatosOperacionalesCombustibleGasLiquidoHorno1">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">Qf:&nbsp;</td>

                                                <td id="txtMfHorno" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Ma:&nbsp;</td>

                                                <td id="txtMa" class="Detalle" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita" align="left">T.P. Comb.:&nbsp;</td>

                                                <td id="txtTCombustible" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td id="trDatosOperacionalesCombustibleGasLiquidoHorno2">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">T. Chimenea:&nbsp;</td>

                                                <td id="txtTChimenea" class="Detalle">
                                                </td>

                                                <td class="DetalleNegrita">Exceso O<sub>2</sub>:&nbsp;</td>

                                                <td id="txtExcesoAire" class="Detalle">
                                                </td>

                                                <td id="rblBaseOxigeno" class="Detalle" colspan="2">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Fin Datos operacionales para hornos (Combustible Gas - Liquido para Horno)-->
                                <!-- Datos por defecto (Combustible Gas -Liquido para Horno)-->
                                <tr>
                                    <td id="trDatosDefectoCombustibleGasLiquidoHorno0">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>
                                            <tr>
                                                <td class="Titulo" align="left" colspan="6" height="26">Valores Fijos</td>
                                            </tr>
                                            <tr>
                                                <td class="linea" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="trDatosDefectoCombustibleGasLiquidoHorno1" style="display:none">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td id="trDatosDefectoCombustibleHorno" style="display:none" colspan="4">
                                                    <table cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td id="lblTemperaturaAmbiente" class="DetalleNegrita" height="26" width="100px">
                                                            	T. Ambiente:&nbsp;
                                                            </td>
                                                            <td id="txtTemperaturaAmbiente" width="140px" class="Detalle">
                                                            </td>
                                                            <td id="lblHumedadRelativa" class="DetalleNegrita" width="100px">
                                                            	H. Relativa:&nbsp;
                                                            </td>
                                                            <td id="txtHumedadRelativa" width="140px" class="Detalle">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td id="lblEficienciaTarget" width="100px" class="DetalleNegrita">
                                                	Eficiencia Diseño:&nbsp;
                                                </td>
                                                <td id="txtEficienciaTarget" width="100px" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Fin Datos por defecto (Combustible Gas Liquido para Horno)-->
                                <!-- Datos Datos de Vapor de Atomizaci?n (Combustible Liquido para Horno - Caldera) -->
                                <tr>
                                    <td id="trDatosVaporAtomizacionCombustibleLiquidoHornoCaldera0">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>
                                            <tr>
                                                <td class="Titulo" align="left" colspan="6" height="26">Vapor de Atomización</td>
                                            </tr>
                                            <tr>
                                                <td class="linea" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
							    <tr>
							        <td id="trDatosVaporAtomizacionCombustibleLiquidoHorno">
							            <table cellspacing="0" cellpadding="0" border="0">
							                <?php echo $pres->separadorReporte(); ?>
							                <tr>
							                    <td id="lblMvatomHL" class="DetalleNegrita" height="26">Mvatom:&nbsp;</td>
							                    
							                    <td class="Detalle" id="txtMvatomHL"></td>
							                    
							                    <td class="Detalle">
							                    </td>
							                    <td class="Detalle">
							                    </td>
							                    <td class="Detalle">
							                    </td>
							                    <td class="Detalle">
							                    </td>
							                </tr>
							            </table>
							        </td>
							    </tr>
                                
                                <tr>
                                    <td id="trDatosVaporAtomizacionCombustibleLiquidoHornoCaldera1">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>
                                            <tr>
                                                <td class="DetalleNegrita" height="26">Mvatom:&nbsp;</td>
                                                <td id="txtMvatom" class="Detalle">
                                                </td>
                                                <td class="DetalleNegrita">Presión:&nbsp;</td>
                                                <td id="txtPresion" class="Detalle">
                                                </td>
                                                <td class="DetalleNegrita">Temperatura:&nbsp;</td>
                                                <td id="txtTemperaturaVaporAtomizacion" class="Detalle" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Fin Datos de Vapor de Atomizaci?n (Combustible Liquido para Horno - Caldera)-->
                                <!-- Datos de la Composici?n de los gases (Combustible Gas - Liquido para Horno)-->
                                <tr>
                                    <td id="trDatosComposicionCombustibleHorno0">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>
                                            <tr>
                                                <td class="Titulo" align="left" colspan="6" height="26">Gases de Combustión</td>
                                            </tr>
                                            <tr>
                                                <td class="linea" colspan="6">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="trDatosComposicionCombustibleHorno1">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>
                                            <tr>
                                                <td class="DetalleNegrita" align="left" height="26" colspan="2">Comp. de los Gases:&nbsp;<input id="cmbComposicionGases" type="hidden"> </td>
                                                <td colspan="4" class="Detalle">
                                                    <div id="txtComposicionGases" class="Detalle">
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="trDatosComposicionCombustibleHorno2">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>
                                            <tr>
                                                <td class="DetalleNegrita" height="26">CO2:&nbsp;</td>
                                                <td id="txtRealCO2" class="Detalle">
                                                </td>
                                                <td class="DetalleNegrita">CO:&nbsp;</td>
                                                <td id="txtRealCO" class="Detalle">
                                                </td>
                                                <td class="DetalleNegrita">SO2:&nbsp;</td>
                                                <td id="txtRealSO2" class="Detalle">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td id="trDatosComposicionCombustibleHorno3">
                                        <table cellspacing="0" cellpadding="0" border="0">
                                            <?php echo $pres->separadorReporte(); ?>

                                            <tr>
                                                <td class="DetalleNegrita" height="26">O2:&nbsp;</td>
                                                <td id="txtRealO2" class="Detalle">
                                                </td>
                                                <td class="DetalleNegrita">NO:&nbsp;</td>
                                                <td id="txtRealNO" class="Detalle">
                                                </td>
                                                <td>
                                                </td>
                                                <td>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Resultados -->
                    <tr>
                        <td class="Titulo" align="left" colspan="6" height="30">Resultados</td>
                    </tr>
                    <tr>
                        <td class="linea" colspan="6">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td class="DetalleNegrita" height="26" width="140px">Eficiencia Térmica:&nbsp;</td>

                                    <td id="txtEficienciaTermica" class="DetalleNegrita" colspan="3">
                                    </td>
                                </tr>

                                <tr id="trCalorPerdido">
                                    <td class="DetalleNegrita" height="26">Calor Perdido Chimenea:&nbsp;</td>

                                    <td class="DetalleNegrita" id="tdPerdidoReal1" colspan="5">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td height="10">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6"><?php include "../controles/pie.php"; ?></td>
                    </tr>
                    <tr>
                        <td height="100%">
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>