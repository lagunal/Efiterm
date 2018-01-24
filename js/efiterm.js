function LimpiarCampos(){
	document.getElementById('txtMetano').value = '';
	document.getElementById('txtC6').value = '';
	document.getElementById('txtHidrogeno').value = '';
	document.getElementById('txtEtano').value = '';
	document.getElementById('txtEteno').value = '';
	document.getElementById('txtOxigeno').value = '';
	document.getElementById('txtPropano').value = '';
	document.getElementById('txtPropileno').value = '';
	document.getElementById('txtNitrogeno').value = '';
	document.getElementById('txtNButano').value = '';
	document.getElementById('txtCO2').value = '';
	document.getElementById('txtNPentano').value = '';
	document.getElementById('txtIsoButano').value = '';
	document.getElementById('txtCO').value = '';
	document.getElementById('txtIsoPentano').value = '';
	document.getElementById('txtOlefinasC5').value = '';
	document.getElementById('txtTotalButeno').value = '';
	document.getElementById('txtH2S').value = '';
	document.getElementById('txtGE').value = '';
	document.getElementById('txtHHV1').value = '';
	document.getElementById('txtLHV').value = '';
	document.getElementById('txtCarbono').value = '';
	document.getElementById('txtHidrogenoLiquido').value = '';
	document.getElementById('txtAzufre').value = '';
	document.getElementById('txtGradoAPI').value = '';
	document.getElementById('txtMfCaldera').value = '';
	document.getElementById('txtHHV2').value = '';
	document.getElementById('txtPresionVapor').value = '';
	document.getElementById('txtTemperaturaVapor').value = '';
	document.getElementById('txtTemperaturaAgua').value = '';
	document.getElementById('txtMrSt').value = '';
	document.getElementById('txtMagua').value = '';
	document.getElementById('txtMfHorno').value = '';
	document.getElementById('txtMa').value = '';
	document.getElementById('txtTCombustible').value = '';
	document.getElementById('txtTChimenea').value = '';
	document.getElementById('txtExcesoAire').value = '';
	document.getElementById('txtMvatom').value = '';
	document.getElementById('txtPresion').value = '';
	document.getElementById('txtTemperaturaVaporAtomizacion').value = '';
	document.getElementById('txtRealCO2').value = '';
	document.getElementById('txtRealCO').value = '';
	document.getElementById('txtRealSO2').value = '';
	document.getElementById('txtRealO2').value = '';
	document.getElementById('txtRealNO').value = '';
	document.getElementById('txtPorcentajesCombustible').value = '';
	document.getElementById('txtPorcentajesCombustible2').value = '';
}

function Limpiar(){
	MostrarOcultarCelda('trCombustible', 0, 2, false);
	MostrarOcultarCelda('trCombustibleGasHorno', 1, 8, false);
	MostrarOcultarCelda('trCombustibleLiquidoHornoCaldera', 1, 2, false);
	MostrarOcultarCelda('trCombustibleGasLiquidoCaldera', 1, 1, false);
	MostrarOcultarCelda('trDatosVaporAguaCaldera', -1, -1, false);
	MostrarOcultarCelda('trCombustibleGasLiquidoCaldera', 2, 3, false);
	MostrarOcultarCelda('trDatosOperacionalesCombustibleGasLiquidoHorno', 0, 2, false);
	MostrarOcultarCelda('trDatosDefectoCombustibleGasLiquidoHorno', 0, 1, false);
	MostrarOcultarCelda('trDatosVaporAtomizacionCombustibleLiquidoHornoCaldera', 0, 1, false);
	MostrarOcultarCelda('trDatosComposicionCombustibleHorno', 0, 3, false);
	MostrarOcultarCelda('trDatosOperacionalesCombustibleLiquidoHorno',-1,-1,false);
	MostrarOcultarCelda('trDatosVaporAtomizacionCombustibleLiquidoHorno',-1,-1,false);
	MostrarOcultarCelda('trCombustibleLiquidoHorno',1,3,false);
	//MostrarOcultarCelda('lblHHV',1,2,true);

	if(document.getElementById('tblResultado'))
		MostrarOcultarCelda('tblResultado', -1, -1, false);

	ComposicionGases();
}

/*
Horno 1
Caldera 2
*/
function obtenerTipoEquipo(combo){
	return (combo.substring(0,1));
}

function Filtrar(){
	Limpiar();
	
	//alert(document.getElementById('cmbTipoCombustible').value.length);
	
	if(document.getElementById('cmbTipoCombustible').value.length > 1){
		//CALDERA
		if(obtenerTipoEquipo(document.getElementById('cmbTipoEquipo').value) == 2){
		
			MostrarOcultarCelda('trCombustible',0,1,true);
			MostrarOcultarCelda('trCombustibleGasLiquidoCaldera',1,3,true);
			MostrarOcultarCelda('trDatosVaporAguaCaldera',-1,-1,true);
			//MostrarOcultarCelda('trDatosDefectoCombustibleGasLiquidoHorno',0,1,true);

			if(document.getElementById('cmbTipoCombustible').value == "Gas"){
		
				document.getElementById('lblMfCaldera').innerText="Qf (Mpie3/h):";
		
			}else if(document.getElementById('cmbTipoCombustible').value == "Liquido"){
	
				//MostrarOcultarCelda('lblHHV',1,2,false);
				MostrarOcultarCelda('trCombustibleLiquidoHornoCaldera',1,2,true);
				MostrarOcultarCelda('trDatosVaporAguaCaldera',-1,-1,true);
/*
				document.getElementById('AhorroResultado0').innerText="Bs./Dia";
				document.getElementById('AhorroResultado1').innerText="US$/Dia";
				document.getElementById('AhorroResultado2').innerText="lb/Dia";
*/
				document.getElementById('lblMfCaldera').innerText="Mf (Mlb/h):";
	
			}

			if(document.getElementById('btnCalcular'))
				MostrarOcultarCelda('btnCalcular', -1, -1, true);

			if(document.getElementById('btnCalcular')){
				MostrarOcultarCelda('trCalorPerdido',-1,-1,false);
				MostrarOcultarCelda('trDatosDefectoCombustibleHorno',-1,-1,false);
			}

		//HORNO
		}else if(obtenerTipoEquipo(document.getElementById('cmbTipoEquipo').value) == 1){
	
			if(document.getElementById('btnCalcular')){
				xajax_obtenerVariable('txtTemperaturaAmbiente', 'temperaturaAmbiente');
				xajax_obtenerVariable('txtHumedadRelativa', 'humedadRelativa');
			}

			MostrarOcultarCelda('trCombustible',0,1,true);
			MostrarOcultarCelda('trDatosDefectoCombustibleGasLiquidoHorno',0,1,true);						
			MostrarOcultarCelda('trDatosDefectoCombustibleHorno',-1,-1,true);
			
			if(document.getElementById('btnCalcular'))
				MostrarOcultarCelda('trCalorPerdido',-1,-1,true);
	
			if(document.getElementById('cmbComposicionGases').value=="Real"){
				MostrarOcultarCelda('trDatosComposicionCombustibleHorno',2,3,'visible');
			}else{
				if(document.getElementById('btnCalcular'))
					MostrarOcultarCelda('tdPerdidoReal',1,1,false);
			}

			if(document.getElementById('cmbTipoCombustible').value == "Gas"){
						
				MostrarOcultarCelda('trDatosComposicionCombustibleHorno',0,1,true);
				MostrarOcultarCelda('trDatosOperacionalesCombustibleGasLiquidoHorno',0,2,true);
				MostrarOcultarCelda('trCombustibleGasHorno',1,8,true);
				MostrarOcultarCelda('trCombustible2',-1,-1,true);
				
			}else{

				document.getElementById('cmbComposicionGases').selectedIndex = 0;
				MostrarOcultarCelda('trDatosOperacionalesCombustibleGasLiquidoHorno',0,0,true);
				MostrarOcultarCelda('trDatosOperacionalesCombustibleGasLiquidoHorno',2,2,true);
				MostrarOcultarCelda('trDatosOperacionalesCombustibleLiquidoHorno',-1,-1,true);
				
				MostrarOcultarCelda('trCombustibleLiquidoHorno',1,3,true);
				MostrarOcultarCelda('trDatosVaporAtomizacionCombustibleLiquidoHornoCaldera',0,0,true);
				MostrarOcultarCelda('trDatosVaporAtomizacionCombustibleLiquidoHorno',-1,-1,true);
			}

			if(document.getElementById('btnCalcular'))
				MostrarOcultarCelda('btnCalcular', -1, -1, true);

		}
	}else{
		Limpiar();
	}
}

function ComposicionGases(){
    if(document.getElementById('cmbComposicionGases').value == "Real"){
		/*
		if(document.getElementById('btnCalcular'))
	    	MostrarOcultarCelda('trBase',-1,-1,true);
	    */
	    MostrarOcultarCelda('trDatosComposicionCombustibleHorno',2,3,true);
    }else{
    	/*
		if(document.getElementById('btnCalcular'))
	    	MostrarOcultarCelda('trBase',-1,-1,false);
	    */
	    MostrarOcultarCelda('trDatosComposicionCombustibleHorno',2,3,false);
	}
}

function flujoMa(){
    if(document.getElementById('cmbMa').value == "Real"){
		if(document.getElementById('btnCalcular'))
	    	MostrarOcultarCelda('txtMa',-1,-1,true);
    }else{
		if(document.getElementById('btnCalcular'))
	    	MostrarOcultarCelda('txtMa',-1,-1,false);
	}
}

function seleccionarCalculo(){
	tipo = document.getElementById('cmbTipoCalculo');

	if(tipo.value=='1'){
		MostrarOcultarCelda('trCalcular',-1,-1,true);
		MostrarOcultarCelda('trCalcularFechas',-1,-1,false);
		resetearGrafico();
	}else{
		MostrarOcultarCelda('trCalcular',-1,-1,false);
		MostrarOcultarCelda('trCalcularFechas',-1,-1,true);
	}
}

function MostrarOcultarCelda(idCelda, RangoInicio, RangoFin, Visibilidad){
	if(Visibilidad)
		ver = "inline";
	else
		ver = "none";

//alert(idCelda);
	
	if(RangoInicio==-1 && RangoFin==-1)
		document.getElementById(idCelda).style.display = ver;
	else
		for(i=RangoInicio; i<=RangoFin; i++){
			celda = idCelda + i;
			document.getElementById(celda).style.display = ver;
		}
}

function Normalizar(){
	var total;
	var resto;
	resto=0;
	total=0;

	document.getElementById('txtGE').value = document.getElementById('txtGE').value.replace(".",",");
	document.getElementById('txtHHV1').value = document.getElementById('txtHHV1').value.replace(".",",");
	document.getElementById('txtLHV').value = document.getElementById('txtLHV').value.replace(".",",");
	document.getElementById('txtCarbono').value = document.getElementById('txtCarbono').value.replace(".",",");
	document.getElementById('txtHidrogenoLiquido').value = document.getElementById('txtHidrogenoLiquido').value.replace(".",",");
	document.getElementById('txtAzufre').value = document.getElementById('txtAzufre').value.replace(".",",");
	document.getElementById('txtGradoAPI').value = document.getElementById('txtGradoAPI').value.replace(".",",");
	document.getElementById('txtMfCaldera').value = document.getElementById('txtMfCaldera').value.replace(".",",");
	document.getElementById('txtHHV2').value = document.getElementById('txtHHV2').value.replace(".",",");

	document.getElementById('txtPresionVapor').value = document.getElementById('txtPresionVapor').value.replace(".",",");
	document.getElementById('txtTemperaturaVapor').value = document.getElementById('txtTemperaturaVapor').value.replace(".",",");
	document.getElementById('txtTemperaturaAgua').value = document.getElementById('txtTemperaturaAgua').value.replace(".",",");
	document.getElementById('txtMrSt').value = document.getElementById('txtMrSt').value.replace(".",",");
	document.getElementById('txtMagua').value = document.getElementById('txtMagua').value.replace(".",",");
	document.getElementById('txtMfHorno').value = document.getElementById('txtMfHorno').value.replace(".",",");
	document.getElementById('txtMa').value = document.getElementById('txtMa').value.replace(".",",");
	document.getElementById('txtTCombustible').value = document.getElementById('txtTCombustible').value.replace(".",",");
	document.getElementById('txtTChimenea').value = document.getElementById('txtTChimenea').value.replace(".",",");
	document.getElementById('txtExcesoAire').value = document.getElementById('txtExcesoAire').value.replace(".",",");
	document.getElementById('txtMvatom').value = document.getElementById('txtMvatom').value.replace(".",",");
	document.getElementById('txtPresion').value = document.getElementById('txtPresion').value.replace(".",",");
	document.getElementById('txtTemperaturaVaporAtomizacion').value = document.getElementById('txtTemperaturaVaporAtomizacion').value.replace(".",",");
	document.getElementById('txtRealCO2').value = document.getElementById('txtRealCO2').value.replace(".",",");
	document.getElementById('txtRealCO').value = document.getElementById('txtRealCO').value.replace(".",",");
	document.getElementById('txtRealSO2').value = document.getElementById('txtRealSO2').value.replace(".",",");
	document.getElementById('txtRealO2').value = document.getElementById('txtRealO2').value.replace(".",",");
	document.getElementById('txtRealNO').value = document.getElementById('txtRealNO').value.replace(".",",");

	if(document.getElementById('cmbTipoCombustible').value == "Gas"){
	
		total=total+ parseFloat(document.getElementById('txtMetano').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtC6').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtHidrogeno').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtEtano').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtEteno').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtOxigeno').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtPropano').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtPropileno').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtNitrogeno').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtNButano').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtCO2').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtNPentano').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtIsoButano').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtCO').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtIsoPentano').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtOlefinasC5').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtTotalButeno').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtH2S').value.replace(",","."));

		if (document.getElementById('txtResto').value){
			total=total + parseFloat(document.getElementById('txtResto').value.replace(",","."));
		}

		if (total){
			document.getElementById('txtMetano').value = eval((parseFloat(document.getElementById('txtMetano').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtC6').value = eval((parseFloat(document.getElementById('txtC6').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtHidrogeno').value = eval((parseFloat(document.getElementById('txtHidrogeno').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtEtano').value = eval((parseFloat(document.getElementById('txtEtano').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtEteno').value = eval((parseFloat(document.getElementById('txtEteno').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtOxigeno').value = eval((parseFloat(document.getElementById('txtOxigeno').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtPropano').value = eval((parseFloat(document.getElementById('txtPropano').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtPropileno').value = eval((parseFloat(document.getElementById('txtPropileno').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtNitrogeno').value = eval((parseFloat(document.getElementById('txtNitrogeno').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtNButano').value = eval((parseFloat(document.getElementById('txtNButano').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtCO2').value = eval((parseFloat(document.getElementById('txtCO2').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtNPentano').value = eval((parseFloat(document.getElementById('txtNPentano').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtIsoButano').value = eval((parseFloat(document.getElementById('txtIsoButano').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtCO').value = eval((parseFloat(document.getElementById('txtCO').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtIsoPentano').value = eval((parseFloat(document.getElementById('txtIsoPentano').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtOlefinasC5').value = eval((parseFloat(document.getElementById('txtOlefinasC5').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtTotalButeno').value = eval((parseFloat(document.getElementById('txtTotalButeno').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtH2S').value = eval((parseFloat(document.getElementById('txtH2S').value.replace(",","."))*100)/total).decimal(4);
			resto=resto + (eval((parseFloat(document.getElementById('txtMetano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtMetano').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtC6').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtC6').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtHidrogeno').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtHidrogeno').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtEtano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtEtano').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtEteno').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtEteno').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtOxigeno').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtOxigeno').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtPropano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtPropano').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtPropileno').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtPropileno').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtNitrogeno').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtNitrogeno').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtNButano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtNButano').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtCO2').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtCO2').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtNPentano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtNPentano').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtIsoButano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtIsoButano').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtCO').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtCO').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtNPentano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtNPentano').value.replace(",","."))*100)/total).decimal(4))
			resto=resto + (eval((parseFloat(document.getElementById('txtIsoPentano').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtIsoPentano').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtOlefinasC5').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtOlefinasC5').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtTotalButeno').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtTotalButeno').value.replace(",","."))*100)/total).decimal(4));
			document.getElementById('txtResto').value = resto;
			document.getElementById('txtMetano').value = document.getElementById('txtMetano').value.replace(".",",");
			document.getElementById('txtC6').value = document.getElementById('txtC6').value.replace(".",",");
			document.getElementById('txtHidrogeno').value = document.getElementById('txtHidrogeno').value.replace(".",",");
			document.getElementById('txtEtano').value = document.getElementById('txtEtano').value.replace(".",",");
			document.getElementById('txtEteno').value = document.getElementById('txtEteno').value.replace(".",",");
			document.getElementById('txtOxigeno').value = document.getElementById('txtOxigeno').value.replace(".",",");
			document.getElementById('txtPropano').value = document.getElementById('txtPropano').value.replace(".",",");
			document.getElementById('txtPropileno').value = document.getElementById('txtPropileno').value.replace(".",",");
			document.getElementById('txtNitrogeno').value = document.getElementById('txtNitrogeno').value.replace(".",",");
			document.getElementById('txtNButano').value = document.getElementById('txtNButano').value.replace(".",",");
			document.getElementById('txtCO2').value = document.getElementById('txtCO2').value.replace(".",",");
			document.getElementById('txtNPentano').value = document.getElementById('txtNPentano').value.replace(".",",");
			document.getElementById('txtIsoButano').value = document.getElementById('txtIsoButano').value.replace(".",",");
			document.getElementById('txtCO').value = document.getElementById('txtCO').value.replace(".",",");
			document.getElementById('txtIsoPentano').value = document.getElementById('txtIsoPentano').value.replace(".",",");
			document.getElementById('txtOlefinasC5').value = document.getElementById('txtOlefinasC5').value.replace(".",",");
			document.getElementById('txtTotalButeno').value = document.getElementById('txtTotalButeno').value.replace(".",",");
			document.getElementById('txtH2S').value = document.getElementById('txtH2S').value.replace(".",",");				
		}
		
		document.getElementById('txtPorcentajesCombustible').value = calcularPorcentaje(total);
	}else{
		total=total+ parseFloat(document.getElementById('txtCarbono').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtHidrogenoLiquido').value.replace(",","."));
		total=total+ parseFloat(document.getElementById('txtAzufre').value.replace(",","."));

		if(total){
			document.getElementById('txtCarbono').value = eval((parseFloat(document.getElementById('txtCarbono').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtHidrogenoLiquido').value = eval((parseFloat(document.getElementById('txtHidrogenoLiquido').value.replace(",","."))*100)/total).decimal(4);
			document.getElementById('txtAzufre').value = eval((parseFloat(document.getElementById('txtAzufre').value.replace(",","."))*100)/total).decimal(4);
			resto=resto + (eval((parseFloat(document.getElementById('txtCarbono').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtCarbono').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtHidrogenoLiquido').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtHidrogenoLiquido').value.replace(",","."))*100)/total).decimal(4));
			resto=resto + (eval((parseFloat(document.getElementById('txtAzufre').value.replace(",","."))*100)/total) - eval((parseFloat(document.getElementById('txtAzufre').value.replace(",","."))*100)/total).decimal(4));
			document.getElementById('txtResto').value = resto;
			document.getElementById('txtCarbono').value = document.getElementById('txtCarbono').value.replace(".",",");
			document.getElementById('txtHidrogenoLiquido').value = document.getElementById('txtHidrogenoLiquido').value.replace(".",",");
			document.getElementById('txtAzufre').value = document.getElementById('txtAzufre').value.replace(".",",");
		}
		
		document.getElementById('txtPorcentajesCombustible2').value = calcularPorcentaje(total);
	}
}



function calcularPorcentajes(){
	total=0;

	total=total+ parseFloat(document.getElementById('txtMetano').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtC6').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtHidrogeno').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtEtano').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtEteno').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtOxigeno').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtPropano').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtPropileno').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtNitrogeno').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtNButano').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtCO2').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtNPentano').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtIsoButano').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtCO').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtIsoPentano').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtOlefinasC5').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtTotalButeno').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtH2S').value.replace(",","."));
	
	document.getElementById('txtPorcentajesCombustible').value = calcularPorcentaje(total);

	total=0;

	total=total+ parseFloat(document.getElementById('txtCenizas').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtAzufreHL').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtSodio').value.replace(",","."));
	total=total+ parseFloat(document.getElementById('txtOtros').value.replace(",","."));

	document.getElementById('txtOtrosTotal').value = calcularPorcentaje(total);
}

function calcularPorcentaje(total){
	porcentajeTotal = decimales(total, '.', 3);

	if(porcentajeTotal.length>0)
		porcentajeTotal = porcentajeTotal.replace(".",",");
		//porcentajeTotal = porcentajeTotal.replace(".",",") + ' %';
		
	return porcentajeTotal;
}

function decimales(valor, separador, decimales){
	if(valor){
		valor = valor.toString();
		return valor.substr(0, valor.indexOf(separador)+(decimales+1));
	}else{
		return "";
	}
}

Number.prototype.decimal = function (num) {
	pot = Math.pow(10,num);
	return parseInt(this * pot) / pot;
}