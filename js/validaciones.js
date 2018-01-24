/*
Horno 1
Caldera 2
*/
function obtenerTipoEquipo(combo){
	return (combo.substring(0,1));
}

function habilitarCampos(habilitar){
	habilitarControles(habilitar, "input");
	habilitarControles(habilitar, "select");
	habilitarControles(habilitar, "button");

	document.getElementById('btnObtener').disabled = !habilitar;
	document.getElementById('btnCalcular').disabled = !habilitar;
	document.getElementById('btnComposicionPredeterminada').disabled = !habilitar;
	document.getElementById('btnNormalizar1').disabled = !habilitar;
	document.getElementById('btnNormalizar2').disabled = !habilitar;

	document.getElementById('btnReporte').disabled = habilitar;
	document.getElementById('btnNuevoCalculo').disabled = habilitar;

	if(habilitar) document.getElementById('tblResultado').style.display = 'none';
}

function habilitarControles(habilitar, tipo){
	var forma = document.forms[0];
	controles = forma.getElementsByTagName(tipo);
	c=controles.length;

	for(i=0;i<c;i++){
		if(controles[i].name !=""){
			control=forma[controles[i].name];
			control.disabled = !habilitar;
			
			if(!control.length)
				control.disabled = !habilitar;
			else{
				c2=control.length;

				for(j=0;j<c2;j++)
					control[j].disabled = !habilitar;
			}
		}
	}
}

function limpiarCssClass(){
	clase = 'Detalle';
	
	document.getElementById('lblMetano').className = clase;
	document.getElementById('lblC6').className = clase;
	document.getElementById('lblHidrogeno').className = clase;
	document.getElementById('lblEtano').className = clase;
	document.getElementById('lblEteno').className = clase;
	document.getElementById('lblOxigeno').className = clase;
	document.getElementById('lblPropano').className = clase;
	document.getElementById('lblPropileno').className = clase;
	document.getElementById('lblNitrogeno').className = clase;
	document.getElementById('lblNButano').className = clase;
	document.getElementById('lblCO2').className = clase;
	document.getElementById('lblNPentano').className = clase;
	document.getElementById('lblIsoButano').className = clase;
	document.getElementById('lblCO').className = clase;
	document.getElementById('lblIsoPentano').className = clase;
	document.getElementById('lblOlefinasC5').className = clase;
	document.getElementById('lblTotalButeno').className = clase;
	document.getElementById('lblH2S').className = clase;
	document.getElementById('lblGE').className = clase;
	document.getElementById('lblHHV1').className = clase;
	document.getElementById('lblLHV').className = clase;
	document.getElementById('lblCarbono').className = clase;
	document.getElementById('lblHidrogenoLiquido').className = clase;
	document.getElementById('lblAzufre').className = clase;
	document.getElementById('lblGradoAPI').className = clase;
	document.getElementById('lblMfCaldera').className = clase;
	document.getElementById('lblHHV2').className = clase;
	document.getElementById('lblPresionVapor').className = clase;
	document.getElementById('lblTemperaturaVapor').className = clase;
	document.getElementById('lblTemperaturaAgua').className = clase;
	document.getElementById('lblMrSt').className = clase;
	document.getElementById('lblMagua').className = clase;
	document.getElementById('lblMfHorno').className = clase;
	document.getElementById('lblMa').className = clase;
	document.getElementById('lblTCombustible').className = clase;
	document.getElementById('lblTChimenea').className = clase;
	document.getElementById('lblExcesoAire').className = clase;
	//document.getElementById('lblCostoCombustible').className = clase;
	//document.getElementById('lblParidad').className = clase;
	//document.getElementById('lblEficienciaTarget').className = clase;
	document.getElementById('lblTemperaturaAmbiente').className = clase;
	document.getElementById('lblHumedadRelativa').className = clase;
	document.getElementById('lblMvatom').className = clase;
	document.getElementById('lblPresion').className = clase;
	document.getElementById('lblTemperaturaVaporAtomizacion').className = clase;
	document.getElementById('lblRealCO2').className = clase;
	document.getElementById('lblRealCO').className = clase;
	document.getElementById('lblRealSO2').className = clase;
	document.getElementById('lblRealO2').className = clase;
	document.getElementById('lblRealNO').className = clase;
	document.getElementById('lblTipoEquipo').className = clase;
	document.getElementById('lblTipoCombustible').className = clase;
	document.getElementById('lblError').innerHTML = '';

	document.getElementById('lblHHVHornoHL').className = clase;
	document.getElementById('lblRelacionCH').className = clase;
	document.getElementById('lblCenizas').className = clase;
	document.getElementById('lblAzufreHL').className = clase;
	document.getElementById('lblSodio').className = clase;
	document.getElementById('lblOtros').className = clase;
	document.getElementById('lblMcomb').className = clase;
	document.getElementById('lblTCombustibleHL').className = clase;
	document.getElementById('lblMvatomHL').className = clase;
}

function validarNumero(control, etiqueta){
	if((!TextIsEmpty(control)) && (!TextIsNotDecimal(control)))
		return "";
	else	
		return etiqueta + ",";
}

function activarValidaciones(tipoEquipo, combustible, composicionGases, Ma, form){
	limpiarCssClass();
	msg="";
	formulaire=form;
	
	tipoEquipo = obtenerTipoEquipo(tipoEquipo);

	if(tipoEquipo==1){
		msg += validarNumero('txtTemperaturaAmbiente','lblTemperaturaAmbiente');
		msg += validarNumero('txtHumedadRelativa','lblHumedadRelativa');
	
		if(combustible=="Gas"){

			msg += validarNumero('txtMetano','lblMetano');
			msg += validarNumero('txtC6','lblC6');
			msg += validarNumero('txtHidrogeno','lblHidrogeno');
			msg += validarNumero('txtEtano','lblEtano');
			msg += validarNumero('txtEteno','lblEteno');
			msg += validarNumero('txtOxigeno','lblOxigeno');
			msg += validarNumero('txtPropano','lblPropano');
			msg += validarNumero('txtPropileno','lblPropileno');
			msg += validarNumero('txtNitrogeno','lblNitrogeno');
			msg += validarNumero('txtNButano','lblNButano');
			msg += validarNumero('txtCO2','lblCO2');
			msg += validarNumero('txtNPentano','lblNPentano');
			msg += validarNumero('txtIsoButano','lblIsoButano');
			msg += validarNumero('txtCO','lblCO');
			msg += validarNumero('txtIsoPentano','lblIsoPentano');
			msg += validarNumero('txtOlefinasC5','lblOlefinasC5');
			msg += validarNumero('txtTotalButeno','lblTotalButeno');
			msg += validarNumero('txtH2S','lblH2S');
			msg += validarNumero('txtGE','lblGE');
			msg += validarNumero('txtHHV1','lblHHV1');
			msg += validarNumero('txtLHV','lblLHV');

			msg += validarNumero('txtMfHorno','lblMfHorno');
			msg += validarNumero('txtTCombustible','lblTCombustible');
			msg += validarNumero('txtTChimenea','lblTChimenea');
			msg += validarNumero('txtExcesoAire','lblExcesoAire');

		}else if(combustible=="Liquido"){
		
			msg += validarNumero('txtTChimenea','lblTChimenea');
			msg += validarNumero('txtExcesoAire','lblExcesoAire');
			msg += validarNumero('txtHHVHornoHL','lblHHVHornoHL');
			msg += validarNumero('txtRelacionCH','lblRelacionCH');
			msg += validarNumero('txtCenizas','lblCenizas');
			msg += validarNumero('txtAzufreHL','lblAzufreHL');
			msg += validarNumero('txtSodio','lblSodio');
			msg += validarNumero('txtOtros','lblOtros');
			msg += validarNumero('txtMcomb','lblMcomb');
			msg += validarNumero('txtTCombustibleHL','lblTCombustibleHL');
			msg += validarNumero('txtMvatomHL','lblMvatomHL');
			msg += validarNumero('txtTemperaturaAmbiente','lblTemperaturaAmbiente');
			msg += validarNumero('txtHumedadRelativa','lblHumedadRelativa');

		}else{
			msg+="lblTipoCombustible,";
		}

		if(composicionGases=="Real"){
			msg += validarNumero('txtRealCO2','lblRealCO2');
			msg += validarNumero('txtRealCO','lblRealCO');
			msg += validarNumero('txtRealSO2','lblRealSO2');
			msg += validarNumero('txtRealO2','lblRealO2');
			msg += validarNumero('txtRealNO','lblRealNO');
		}
		
		if(Ma=="Real"){
			msg += validarNumero('txtMa','lblMa');		
		}
		
	}else if(tipoEquipo==2){
		if(combustible=="Gas"){
			msg += validarNumero('txtMfCaldera','lblMfCaldera');
			msg += validarNumero('txtHHV2','lblHHV2');
			msg += validarNumero('txtPresionVapor','lblPresionVapor');
			msg += validarNumero('txtTemperaturaVapor','lblTemperaturaVapor');
			msg += validarNumero('txtTemperaturaAgua','lblTemperaturaAgua');
			msg += validarNumero('txtMrSt','lblMrSt');
			msg += validarNumero('txtMagua','lblMagua');
		}else if(combustible=="Liquido"){
			msg += validarNumero('txtCarbono','lblCarbono');
			msg += validarNumero('txtHidrogenoLiquido','lblHidrogenoLiquido');
			msg += validarNumero('txtAzufre','lblAzufre');
			msg += validarNumero('txtGradoAPI','lblGradoAPI');
			msg += validarNumero('txtMfCaldera','lblMfCaldera');

			msg += validarNumero('txtPresionVapor','lblPresionVapor');
			msg += validarNumero('txtTemperaturaVapor','lblTemperaturaVapor');
			msg += validarNumero('txtTemperaturaAgua','lblTemperaturaAgua');
			msg += validarNumero('txtMrSt','lblMrSt');
			msg += validarNumero('txtMagua','lblMagua');
		}else{
			msg+="lblTipoCombustible,";
		}
	}else{
		msg+="lblTipoEquipo,";
	}

	if(combustible==""){
		msg+="lblTipoCombustible,";
	}

    if(msg!=""){
    	etiquetas = msg.split(',');
    	c = etiquetas.length;
    	
    	for(i=0; i<c; i++){
    		if(etiquetas[i]!='')
	    		document.getElementById(etiquetas[i]).className = 'errorEtiqueta';
    	}
    	document.getElementById('lblError').innerHTML = 'Debe revisar estos valores';
		return false;
    }else{
    	return true;
    }
}