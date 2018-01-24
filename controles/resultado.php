<table id="tblResultado" style="display: none" cellspacing="0" cellpadding="0" width="100%" border="0" height="100%" style="display:none">
	<tr>
		<td colspan="5">
			<?php echo $pres->crearSeparador("Resultado"); ?>
		</td>
	</tr>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="160px" class="Detalle">
                    	Eficiencia Térmica&nbsp;(%):
                    </td>
                    <td>
                    	<input id="txtEficienciaTermica" type="text" class="detallePlano" size="12">
                    </td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table id="trCalorPerdido" cellspacing="0" cellpadding="0" width="100%" border="0">
                <tr>
                    <td width="150px" class="Detalle" height="26">Calor Perdido Chimenea:</td>
                    <td width="100px" class="Detalle">Teórico&nbsp;(BTU/lb):</td>
                    <td width="100px" class="Detalle"><input id="txtCalorPerdidoTeorico" type="text" class="detallePlano" size="12"> </td>
                    <td width="100px" class="Detalle" id="tdPerdidoReal1">Real&nbsp;(BTU/lb):</td>
                    <td width="100px" class="Detalle"><input id="txtCalorPerdidoReal" type="text" class="detallePlano" size="12"> </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
    	<td height="10px"></td>
    </tr>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="0" width="100%" border="0">
                <tr>
                    <td width="370px"></td>
                    <td align="right">
                    	<?php echo $pres->crearBoton("btnNuevoCalculo", "Nuevo cálculo", "button", "onclick=\"habilitarCampos(true);\""); ?>
                    </td>
                    <td align="right" colspan="2">
                    	<?php echo $pres->crearBoton("btnReporte", "Reporte", "button", "onclick=\"javascript:window.open('reporte.php');\""); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>