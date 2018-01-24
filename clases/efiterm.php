<?php
include_once "conversor.php";
include "termodinamica.php";
include_once "../config.php";
include_once "clad.php";
	
class EficienciaTermica{
	public $tipo = array("Gas" => 1,
						 "Liquido" => 2
						);

	//Atributos
	public $energia;
	public $masa;
	public $conf;
	public $ComponentePersistenciaPCombustible;
	public $conversionDensidad;
	public $conversionPrefijo;

	public function CalcularCalderaBalanceEnergia_6($CreditoAireSeco,$CreditoHumedadAire,$CreditoCalorSensibleCombustible,$CreditoEquipoVapor,$CreditoEquipoElectricidad,$CreditoHuemdadAdiciomnal){
		$perdidas = 0;
		$creditos = $CreditoAireSeco + $CreditoHumedadAire + $CreditoCalorSensibleCombustible + $CreditoEquipoVapor + $CreditoEquipoElectricidad + $CreditoHuemdadAdiciomnal;

		$eficienciaTermica = 100 - $creditos + $perdidas;

		return $eficienciaTermica;
	}

	public function CalcularCalderaBalanceEnergia_2($Creditos,$Perdidas){
		$eficienciaTermica = 100 - $Creditos + $Perdidas;

		return $eficienciaTermica;
	}

	private function CalcularCalderaEntradaSalida_2($EnergiaAbsorbida,$EnergiaCombustible){
		if($EnergiaCombustible>0)
			$eficienciaTermica = 100 * $EnergiaAbsorbida / $EnergiaCombustible;
		else
			$eficienciaTermica = 0;
			
		return $eficienciaTermica;
	}

	function CalcularCalderaEntradaSalida_9($MrF,$MrSt,$Magua,$pVapor,$tVapor,$tAgua,$tipoCombustible,$HHV=0,$fraccionMasicaCombustion=array()){
		$energia = new Energia();

		$QrF = $energia->CalcularCalderaEnergiaCombustible($MrF, $HHV);

		$QrO = $energia->CalcularCalderaEnergiaAbsorbida($MrSt, $Magua, $tVapor, $pVapor, $tAgua);

		$eficienciaTermica = $this->CalcularCalderaEntradaSalida_2($QrO, $QrF);

		return $eficienciaTermica;
	}

	public function CalcularHorno_6($PoderCalorificoInferiorCombustible,$CorreccionCalorSensibleAire,$CorreccionCalorSensibleCombustible,$CorreccionCalorSensibleMedioAtomizador,$PerdidaCalorRadiacion,$PerdidaCalorChimenea){
		$calorTotalEntrada = $PoderCalorificoInferiorCombustible + $CorreccionCalorSensibleAire + $CorreccionCalorSensibleCombustible + $CorreccionCalorSensibleMedioAtomizador;
		$perdidasCalorTotales = $PerdidaCalorRadiacion + $PerdidaCalorChimenea;

		$eficienciaTermica = (100 * ($calorTotalEntrada - $perdidasCalorTotales)) / $calorTotalEntrada;

		return $eficienciaTermica;
	}

	public function CalcularHornoLiquido($CodigoEquipo,$masaMedioAtomizador,$temperaturaCombustible,$temperaturaChimenea,$temperaturaAmbiente,
										$humedadRelativa,$porcentajeOxigeno,$composicionGases,$BaseSecaOxigeno,$API,$perdidaCalorChimeneaTeorico,$perdidaCalorChimeneaReal,
										$Mcomb,$HHV,$Mvatom,$relacionCH,$cenizas,$azufre,$sodio,$otros){
		
		$totalImpurezas = $cenizas + $azufre + $sodio + $otros;
		
		$porcentajeHidrogeno = (100 - $totalImpurezas) / ($relacionCH + 1);
		$porcentajeCarbono = 100 - ($porcentajeHidrogeno + $totalImpurezas);

		$LHV = $HHV - (9 * 1059.7) * ($porcentajeHidrogeno / 100);
		
		$pesoTotal["CO"] = $porcentajeCarbono / 100;
		$pesoTotal["H2"] = $porcentajeHidrogeno / 100;
		$pesoTotal["S"] = $azufre / 100;
		$pesoTotal["INERTES"] = $otros / 100;
		
		$pesoTotalTotal = $pesoTotal["CO"] + $pesoTotal["H2"] + $pesoTotal["S"] + $pesoTotal["INERTES"];
		 
		$H2OFormado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("H2OFormado", "liquido");
		$aireRequerido = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("AireRequerido", "liquido");
		$CO2Formado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("CO2Formado", "liquido");
		$N2Formado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("N2Formado", "liquido");
		
		$H2OFormadoTotal = ($H2OFormado[0]["H2OFormado"] * $pesoTotal["H2"]) + ($H2OFormado[1]["H2OFormado"] * $pesoTotal["S"]) + 
							($H2OFormado[2]["H2OFormado"] * $pesoTotal["INERTES"]);
		
		$H2OFormadoTotal = $H2OFormadoTotal / $pesoTotalTotal;

		$aireRequeridoTotal = ($aireRequerido[0]["AireRequerido"] * $pesoTotal["H2"]) + ($aireRequerido[1]["AireRequerido"] * $pesoTotal["CO"]) +
							  ($aireRequerido[2]["AireRequerido"] * $pesoTotal["S"]);
		
		$CO2FormadoTotal = ($CO2Formado[0]["CO2Formado"] * $pesoTotal["H2"]) +  ($CO2Formado[1]["CO2Formado"] * $pesoTotal["CO"]) +
						  ($CO2Formado[2]["CO2Formado"] * $pesoTotal["S"]);
		
		$CO2FormadoTotal = $CO2FormadoTotal / $pesoTotalTotal;

		$N2FormadoTotal = ($N2Formado[0]["N2Formado"] * $pesoTotal["H2"]) +  ($N2Formado[1]["N2Formado"] * $pesoTotal["CO"]) +
						  ($N2Formado[2]["N2Formado"] * $pesoTotal["S"]);
		
		$N2FormadoTotal = $N2FormadoTotal / $pesoTotalTotal;
		
		$PresionVapor = $this->energia->CalcularPresionDeVapor($temperaturaAmbiente);
		
		$humedadAire = ($PresionVapor / 14.696) * ($humedadRelativa / 100) * (18 / 28.85);
		$MasaAireHumedoMasaCombReq = $aireRequeridoTotal / (1 - $humedadAire);
		$masaHumedadMasaCombustible =  $MasaAireHumedoMasaCombReq - $aireRequeridoTotal; 
		
		$vaporDeAtomizacion = $Mvatom / $Mcomb;
		
		$masaAguaMasaComb = $masaHumedadMasaCombustible + $H2OFormadoTotal + $vaporDeAtomizacion;
		$masaExcesoAireMasaComb = 0 ;
		
		if($BaseSecaOxigeno)
			$masaExcesoAireMasaComb = ((28.85 * $porcentajeOxigeno) * (($N2FormadoTotal / 28) + ($CO2FormadoTotal / 44))) / ((20.95 - $porcentajeOxigeno) * ((1.6028 * 0 / $aireRequeridoTotal) + 1));
		else
			$masaExcesoAireMasaComb = ((28.85 * $porcentajeOxigeno) * (($N2FormadoTotal / 28) + ($CO2FormadoTotal / 44) + ($masaAguaMasaComb / 18))) / ((20.95 - $porcentajeOxigeno) * ((1.6028 * $masaHumedadMasaCombustible / $aireRequeridoTotal) + 1));

		$QS = $this->energia->CalcularQSHornoLiquido($temperaturaChimenea, $this->conf->temperaturaReferencia, $CO2FormadoTotal, $masaExcesoAireMasaComb, $N2FormadoTotal, $masaAguaMasaComb);
		
		$Ha = 0.24 * ($temperaturaAmbiente - 60) * ($aireRequeridoTotal + $masaExcesoAireMasaComb);
		$Hf = 0.48 * ($temperaturaCombustible - 60);
		$Qr = $LHV * 0.02;
		
		$eficiencia = ((($LHV + $Ha + $Hf) - ($Qr + $QS)) / ($LHV + $Hf + $Ha)) * 100;

		$eficienciaTermica[1] = $eficiencia;
		$eficienciaTermica[3] = $QS;
		
		return $eficienciaTermica;
	}
	
	public function CalcularHorno_20($CodigoEquipo, $MasaAire,$MasaMedioAtomizador,$FraccionMolarChimenea,$TemperaturaCombustible,
								  $TemperaturaChimenea,$TemperaturaAmbiente,$TemperaturaVapor,$PresionVapor,$HumedadRelativa,
								  $PorcentajeOxigeno,$TipoCombustible,$BaseSecaChimenea,$BaseSecaOxigeno,$gravedadEspecifica,
								  $API,$VolumenCombustible,$LHV,$gravedadEspecifica,$composicionGases,$masaAireReal,
								  $FraccionMolarCombustion = array(),$FraccionMasicaCombustion = array(),
								  $PerdidaCalorChimeneaTeorico = 0,$PerdidaCalorChimeneaReal = 0){

		$pesoMolecularChimenea = $this->ComponentePersistenciaPCombustible->ObtenerDataChimenea();   
		$eficienciaRadiacionLiquido = 0;
		$masaAireHumedo = 0;
		$masaAireExcesoAire = 0;
		$factorGasCombustible = 1;
		
		$Equipo = $this->ComponentePersistenciaPCombustible->obtenerEquipo($CodigoEquipo);

		if(isset($Equipo[0]["unidad_gas_combustible"]) && is_numeric($Equipo[0]["unidad_gas_combustible"]))
			$factorGasCombustible = $Equipo[0]["unidad_gas_combustible"];
		
		if($TipoCombustible == $this->tipo["Gas"]){
			$calorCombustionNeto = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("calorCombustionNeto", "gas");
			$CO2Formado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("CO2Formado", "gas");
			$H2OFormado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("H2OFormado", "gas");
			$N2Formado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("N2Formado", "gas");
			$aireRequerido = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("AireRequerido", "gas");
			$pesoMolecularCombustion = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("pesoMolecularCombustion", "gas");
			$molesHidrogenoCombustion = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("molesHidrogenoCombustion", "gas");

			$masaCombustible = $this->conversionDensidad->ConvertirGasPieCubico_Libra($VolumenCombustible, $this->conf->densidadAire, $gravedadEspecifica);
			$masaCombustible *= $factorGasCombustible;

		//$FraccionMolarCombustion viene de la interfaz
			$pesoTotal = array();
 			$LHV = $this->energia->CalcularLHV($masaCombustible, $pesoMolecularCombustion, $FraccionMolarCombustion, $calorCombustionNeto, $pesoTotal);
 			
 			$PoderCalorificoInferiorCombustible = $LHV; 

			//*********** Corrección del calor sensible del combustible ************
			//   Hf = Cpf * (Tf - Tref)
			$coeficiente1 = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("coeficiente1", "gas");
			$coeficiente2 = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("coeficiente2", "gas");
			$coeficiente3 = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("coeficiente3", "gas");
			$coeficiente4 = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("coeficiente4", "gas");
			$coeficiente5 = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("coeficiente5", "gas");
			
			$CorreccionCalorSensibleCombustible = $this->energia->CalcularHornoSensibleCombustibleGas($TemperaturaCombustible, $this->conf->temperaturaReferencia, $FraccionMolarCombustion, $pesoMolecularCombustion, $coeficiente1, $coeficiente2, $coeficiente3, $coeficiente4, $coeficiente5);

			//*********** Perdida de calor por la chimenea teórico ************
			// QsTeorico = QCO2 + QH2Ov + QN2 + QAire
			$masaAireRequerido = 0;
			$porcentajeExcesoAire=0;
			$masaAireHumedo = 0;
			
			$PerdidaCalorChimeneaTeorico = $this->energia->CalcularHornoPerdidaChimeneaTeorico_14($masaCombustible, $FraccionMolarCombustion, $pesoMolecularCombustion, $TemperaturaChimenea, $TemperaturaAmbiente, $this->conf->temperaturaReferencia, $MasaMedioAtomizador, $HumedadRelativa, $PorcentajeOxigeno, $CO2Formado, $H2OFormado, $N2Formado, $aireRequerido, $BaseSecaOxigeno, $masaAireHumedo, $masaAireExcesoAire, $masaAireRequerido, $porcentajeExcesoAire);

			//*********** Perdida de calor por la chimenea real ************
			// Q = m * Cp * (Tchi - Tref)
			// QsReal = QO2 + QCO + QCO2 + QNO + QSO2 + QN2 + QH2O
			if($composicionGases == "Real")
				$PerdidaCalorChimeneaReal = $this->energia->CalcularHornoPerdidaChimeneaReal_14($masaCombustible, $FraccionMolarChimenea, $BaseSecaChimenea, $masaAireRequerido, $porcentajeExcesoAire, $masaAireHumedo, $pesoTotal, $TemperaturaChimenea, $this->conf->temperaturaReferencia);

			//*********** Correción del calor sensible del medio atomizador ************
			// Hm = Cpmedio * (Tf - Tref) * (mmedio/mf)
			// Cpmedio * (Tf - Tref) = Hvapor - Href
			$CorreccionCalorSensibleMedioAtomizador = 0;

			$eficienciaRadiacionLiquido = $Equipo[0]["eficienciaradiaciongas"];
			
		}else{
			
			$calorCombustionNeto = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("calorCombustionNeto", "liquido");
			$CO2Formado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("CO2Formado", "liquido");
			$H2OFormado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("H2OFormado", "liquido");        
			$N2Formado = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("N2Formado", "liquido");
			$aireRequerido = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("AireRequerido", "liquido");

			$pesoMolecularCombustion = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("pesoMolecularCombustion", "liquido");

			$molesHidrogenoCombustion = $this->ComponentePersistenciaPCombustible->obtenerDataCombustible("molesHidrogenoCombustion", "liquido");

			$masaCombustible = $this->conversionDensidad->ConvertirLiquidoPieCubico_Libra($VolumenCombustible, $this->conf->densidadAgua, $API);

			//Mmedio = 0.5 * Mrf ' [lb/h]
			$FraccionMolarCombustion = $this->masa->ConvertirFraccionMasicaMolar($masaCombustible, $FraccionMasicaCombustion, $pesoMolecularCombustion);
	
			//*********** Poder calorifico inferior del combustible ************
			//LHV = calorCombustion / flujoMasicoCombustible
			$PoderCalorificoInferiorCombustible = $this->energia->CalcularPoderCalorificoInferiorCombustible_2($FraccionMasicaCombustion, $calorCombustionNeto);
			//[Btu/lb combustible]

			//*********** Correcciin del calor sensible del combustible ************
			//Hf = Cpf * (Tf - Tref)
			$CorreccionCalorSensibleCombustible = $this->energia->CalcularHornoSensibleCombustibleLiquido($TemperaturaCombustible, $this->conf->temperaturaReferencia, $API);

			//*********** Perdida de calor por la chimenea teorico ************
			// QsTeorico = QCO2 + QH2Ov + QN2 + QAire
	  
			$PerdidaCalorChimeneaTeorico = $this->energia->CalcularHornoPerdidaChimeneaTeorico_14($masaCombustible, $FraccionMolarCombustion, $pesoMolecularCombustion, $TemperaturaChimenea, $TemperaturaAmbiente, $this->conf->temperaturaReferencia, $MasaMedioAtomizador, $HumedadRelativa, $PorcentajeOxigeno, $CO2Formado, $H2OFormado, $N2Formado, $aireRequerido, $BaseSecaOxigeno);

			//*********** Perdida de calor por la chimenea real ************
			// Q = m * Cp * (Tchi - Tref)
			// QsReal = QO2 + QCO + QCO2 + QNO + QSO2 + QN2 + QH2O
			$PerdidaCalorChimeneaReal = $this->energia->CalcularHornoPerdidaChimeneaReal_14($masaCombustible, $MasaAire, $MasaMedioAtomizador, $FraccionMolarCombustion, $pesoMolecularCombustion, $molesHidrogenoCombustion, $FraccionMolarChimenea, $pesoMolecularChimenea, $TemperaturaChimenea, $TemperaturaAmbiente, $this->conf->temperaturaReferencia, $HumedadRelativa, $this->tipo["Liquido"], $BaseSecaChimenea);

			//*********** Correcion del calor sensible del medio atomizador ************
			// Hm = Cpmedio * (Tf - Tref) * (mmedio/mf)
			// Cpmedio * (Tf - Tref) = Hvapor - Href
			$CorreccionCalorSensibleMedioAtomizador = $this->energia->CalcularHornoSensibleMedioAtomizador($MasaMedioAtomizador, $masaCombustible, $TemperaturaVapor, $PresionVapor, $this->conf->temperaturaReferencia, 1);
			
			$eficienciaRadiacionLiquido = $Equipo[0]["eficienciaradiacionliquido"];
		}

		//*********** Correcion de calor sensible del aire ************

		//Ha = Cpaire * (Ta - Td) * (maire / mf) [Btu/lb combustible]
		$CorreccionCalorSensibleAire = $this->energia->CalcularHornoSensibleAire($masaCombustible, $MasaAire, $this->conf->Cpaire, $TemperaturaAmbiente, $this->conf->temperaturaReferencia, $masaAireHumedo, $masaAireExcesoAire, $masaAireReal);

		//*********** Perdida de calor por radiacion ************
		// Qr = eficiencia * LHV
		// Liquido = 1.5%
		// Gas = 2.5%
		if($eficienciaRadiacionLiquido == 0)
			$eficienciaRadiacionLiquido = $this->conf->eficienciaRadiacionLiquido;

		$PerdidaCalorRadiacion = $this->energia->CalcularHornoPerdidaRadiacion($eficienciaRadiacionLiquido, $PoderCalorificoInferiorCombustible); // Gas

		$eficienciaTermicaTeorico = $this->CalcularHorno_6($PoderCalorificoInferiorCombustible, $CorreccionCalorSensibleAire, $CorreccionCalorSensibleCombustible, $CorreccionCalorSensibleMedioAtomizador, $PerdidaCalorRadiacion, $PerdidaCalorChimeneaTeorico);
		
		if($composicionGases == "Real"){
			$eficienciaTermicaReal = $this->CalcularHorno_6($PoderCalorificoInferiorCombustible, $CorreccionCalorSensibleAire, $CorreccionCalorSensibleCombustible, $CorreccionCalorSensibleMedioAtomizador, $PerdidaCalorRadiacion, $PerdidaCalorChimeneaReal);
			
			$eficienciaTermica[2] = $eficienciaTermicaReal;
		}

		$eficienciaTermica[1] = $eficienciaTermicaTeorico;
		$eficienciaTermica[3] = $PerdidaCalorChimeneaTeorico;
		$eficienciaTermica[4] = $PerdidaCalorChimeneaReal;

		return $eficienciaTermica;
	}

	public function EficienciaTermica(){
		$this->energia = new Energia();
		$this->masa = new Masa();
		$this->conversionDensidad = new Densidad();
		$this->conversionPrefijo = new Prefijos();
		$this->conf = new config();
		$this->ComponentePersistenciaPCombustible = new clad();
	}
}
//FIN eficienciaTermica

class Energia{
	// Attributes
	public $entalpia;   //termodinamica
	public $calor;  //termodinamica
	public $pesoMolecular;  //termodinamica
	public $flujo;   //termodinamica
	public $capacidadCalorifica;  //termodinamica
	public $presion;   //termodinamica
	public $masa;  
	public $eficiencia;
	
	public $combustionGas = array("CO" => 1,
								  "CO2" => 2,
								  "etano" => 3,
								  "eteno" => 4,
								  "H2S" => 5,	
								  "H2" => 6,
								  "isobutano" => 7,
								  "C6" => 8,
								  "isopentano" => 9,
								  "metano" => 10,
								  "butano" => 11,
								  "N2" => 12,
								  "pentano" => 13,
								  "olefinas" => 14,
								  "O2" => 15,
								  "propano" => 16,
								  "propileno" => 17,
								  "buteno" => 18
								); 

	public $chimenea = array("O2" => 1,
							 "CO" => 2,
							 "CO2" => 3,
							 "NO" => 4,                                          
							 "SO2" => 5,					 
							 "N2" => 6,					
							 "H2O" => 7
							 );
							 
	//Properties
	public $porcentajeAPI = array();
	public $azufrePorcentaje0 = array();
	public $azufrePorcentaje1 = array();
	public $azufrePorcentaje2 = array();
	public $azufrePorcentaje3 = array();
	public $azufrePorcentaje4 = array();

	public function CalcularQSHornoLiquido($temperatura, $temperaturaReferencia, $CO2FormadoTotal, $masaExcesoAireMasaComb, $N2FormadoTotal, $masaAguaMasaComb){
		$entalpiaCO2 = $this->entalpia->CalcularCO2($temperatura, $temperaturaReferencia);
		$entalpiaH2O = $this->entalpia->CalcularAguaVapor($temperatura, $temperaturaReferencia);
		$entalpiaN2 = $this->entalpia->CalcularN2($temperatura, $temperaturaReferencia);
		$entalpiaAire = $this->entalpia->CalcularAire($temperatura);

		$QS = ($entalpiaCO2 * $CO2FormadoTotal) + ($entalpiaAire * $masaExcesoAireMasaComb) +
			  ($entalpiaN2 * $N2FormadoTotal) + ($entalpiaH2O * $masaAguaMasaComb);

		return $QS;
	}

	public function CalcularPresionDeVapor($temperatura){
		$presion = $this->presion->CalcularAguaSaturacion($temperatura);

		return $presion;
	}
	
	public function CalcularCalderaEnergiaCombustible($FlujoMasicoCombustible,$PoderCalorificoSuperiorCombustible){
		$energiaCombustible = $this->calor->Calcular_3(1, $FlujoMasicoCombustible, $PoderCalorificoSuperiorCombustible);

		return $energiaCombustible;
	}

	public function CalcularCalderaEnergiaAbsorbida($FlujoMasicoVapor,$FlujoMasicoAgua,$TemperaturaVapor,$PresionVapor,$TemperaturaAgua){
		$entalpiaFluidoSaliendo = $this->entalpia->CalcularAguaVaporTabla($TemperaturaVapor, $PresionVapor); //hvapor
		
		$entalpiaFluidoLiquido = $this->entalpia->CalcularAguaSaturacion($TemperaturaAgua, 0, $this->entalpia->ParametrosTemperatura);
		
		$energiaAbsorbida = $FlujoMasicoVapor * ($entalpiaFluidoSaliendo - $entalpiaFluidoLiquido);
		
		return $energiaAbsorbida;
	}

	public function CalcularLHV($masaCombustible, $pesoMolecularCombustion, $FraccionMolarCombustion, $calorCombustionNeto, &$pesoTotal = array()){
		$n = count($pesoMolecularCombustion);
		$PM = 0;
		$LB = array();
		$LBTotal = 0;
		$pesoTotalTotal = 0;
		$calorCombustionNetoTotal = 0;
		$calorCombustionTotal = 0;
		
		for($i=1;$i<=$n;$i++)
			$PM += $FraccionMolarCombustion[$i] * $pesoMolecularCombustion[$i-1]["pesoMolecularCombustion"];
		
		$flujoMolarMezcla = $masaCombustible / $PM;
		
		for($i=1;$i<=$n;$i++){
			$LB []= ($flujoMolarMezcla * $FraccionMolarCombustion[$i]);
			$LBTotal += $LB[$i-1];
			
			$pesoTotal []= ($LB[$i-1] * $pesoMolecularCombustion[$i-1]["pesoMolecularCombustion"]);
			
			$calorCombustionTotal += $pesoTotal[$i-1] * $calorCombustionNeto[$i-1]["calorCombustionNeto"];
			$calorCombustionNetoTotal += $FraccionMolarCombustion[$i] * $calorCombustionNeto[$i-1]["calorCombustionNeto"];
			$pesoTotalTotal += $pesoTotal[$i-1];
		}
		
		$LHV = $calorCombustionTotal / $pesoTotalTotal;
		
		return $LHV;
	}

	public function CalcularPoderCalorificoInferiorCombustible_4($LHV, $gravedadEspecifica){
		$densidadFluido = $gravedadEspecifica * 0.081156;
		$poderCalorificoInferiorCombustible = $LHV / $densidadFluido;
		
		return $poderCalorificoInferiorCombustible;
	}

	public function CalcularPoderCalorificoInferiorCombustible_2($FraccionMasicaCombustion,$CalorCombustionNeto){
		$poderCalorificoInferiorCombustible = 0; // Poder calorifico inferior del combustible [BTU/lb]

		$n = count($FraccionMasicaCombustion); //-1

		for ($i=1;$i<=$n;$i++){
			$poderCalorificoInferiorCombustible = $poderCalorificoInferiorCombustible + ($FraccionMasicaCombustion[$i] * $CalorCombustionNeto[$i-1]["calorCombustionNeto"]);
		}

		return $poderCalorificoInferiorCombustible;
	}

	public function CalcularPoderCalorificoSuperiorCombustible($PoderCalorificoInferiorCombustible, $PorcentajeHidrogeno){
		$poderCalorificoSuperiorCombustible = 0; // Poder calorifico superior del combustible [BTU/lb]

		$poderCalorificoSuperiorCombustible = $PoderCalorificoInferiorCombustible + (9537.3 * $PorcentajeHidrogeno);

		return $poderCalorificoSuperiorCombustible;
	}

	public function CalcularHornoSensibleAire($FlujoMasicoCombustible,$FlujoMasicoAire,$CalorEspecificoAire,$TemperaturaAmbiente,$TemperaturaReferencia,$masaAireHumedo,$masaAireExcesoAire,$masaAireReal){
		// Correcion de calor sensible del aire
		// Ha = Cpaire * (Ta - Td) * (maire / mf)
		//Ha = Cpaire * (Ta - Td) * (maire / mf)
		$calorSensibleAire = (($TemperaturaAmbiente - $TemperaturaReferencia) * $CalorEspecificoAire);
		
		if($masaAireReal){
			$calorSensibleAire = $calorSensibleAire  * ($FlujoMasicoAire / $FlujoMasicoCombustible);
		}else{
			$calorSensibleAire = $calorSensibleAire * ($masaAireHumedo + $masaAireExcesoAire);
		}

		return $calorSensibleAire;
	}

	public function CalcularHornoSensibleCombustibleGas($TemperaturaPromedio,$TemperaturaReferencia,$FraccionMolarCombustion, $PesoMolecularCombustion,$Coeficiente1,$Coeficiente2,$Coeficiente3,$Coeficiente4,$Coeficiente5){
		$pesoMolecularMezcla = 0;
		$capacidadCalorificaMezcla = 0;
		// Hf = Cpf * (Tf - Tref)

		$n = count($Coeficiente1);
		//ReDim Preserve capacidadCalorificaComponente(n)

		// Conversion las temperaturas de °F a K
		$TemperaturaPromedio = ($TemperaturaPromedio - 32) * 5 / 9 + 273.15;
		$TemperaturaReferencia = ($TemperaturaReferencia - 32) * 5 / 9 + 273.15;

		// Cálculo del peso molecular de mezcla
		$pesoMolecularMezcla = $this->pesoMolecular->CalcularMezcla($PesoMolecularCombustion, $FraccionMolarCombustion);

		for ($i=1;$i<=$n;$i++){
			// Cálculo de la capacidad calorífica de la mezcla
			// [J/gK]
			$capacidadCalorificaComponente[$i] = $this->capacidadCalorifica->CalcularGas($TemperaturaPromedio, $Coeficiente1[$i-1], $Coeficiente2[$i-1], $Coeficiente3[$i-1], $Coeficiente4[$i-1], $Coeficiente5[$i-1]) / 1000;
			// Cálculo de capacidad calorífica de la mezcla
			$capacidadCalorificaMezcla = $capacidadCalorificaMezcla + (($FraccionMolarCombustion[$i] * $capacidadCalorificaComponente[$i]) / $PesoMolecularCombustion[$i-1]["pesoMolecularCombustion"]);
			$capacidadCalorificaKJouleGK[] = (($FraccionMolarCombustion[$i] * $capacidadCalorificaComponente[$i]) / $PesoMolecularCombustion[$i-1]["pesoMolecularCombustion"]);
		}

		//  calorSensibleCombustible [J/g]
		$calorSensibleCombustible = $capacidadCalorificaMezcla * ($TemperaturaPromedio - $TemperaturaReferencia);
		//  calorSensibleCombustible [Btu/lb]
		$calorSensibleCombustible = $calorSensibleCombustible * 0.430215;

		return $calorSensibleCombustible;
	}

	public function CalcularHornoSensibleCombustibleLiquido($TemperaturaPromedio,$TemperaturaReferencia,$API){
		// Hf = Cpf * (Tf - Tref)
		// Cálculo de capacidad calorífica de la mezcla

		$capacidadCalorificaMezcla = $this->capacidadCalorifica->CalcularLiquido($TemperaturaPromedio, $API);

		$calorSensibleCombustible = $capacidadCalorificaMezcla * ($TemperaturaPromedio - $TemperaturaReferencia);

		return $calorSensibleCombustible;
	}

	public function CalcularHornoSensibleMedioAtomizador($FlujoMasicoMedioAtomizador,$FlujoMasicoCombustible,$TemperaturaVapor,$PresionVapor,$TemperaturaReferencia,$ComposicionSaturacion){
		$entalpiaVaporAgua = $this->entalpia->CalcularAguaVapor($TemperaturaVapor, $TemperaturaReferencia);

		$calorSensibleMedioAtomizador = $this->calor->Calcular_3(1, $FlujoMasicoMedioAtomizador, $entalpiaVaporAgua) / $FlujoMasicoCombustible;

		return $calorSensibleMedioAtomizador;
	}

	public function CalcularHornoPerdidaRadiacion($Porcentaje,$PoderCalorificoInferiorCombustible){
		$Porcentaje = $Porcentaje / 100;

		$perdidaCalorRadiacion = $Porcentaje * $PoderCalorificoInferiorCombustible;

		return $perdidaCalorRadiacion;
	}

	public function CalcularHornoPerdidaChimeneaTeorico_14($FlujoMasicoCombustible,$FraccionMolarCombustion,$PesoMolecularCombustion,
														   $TemperaturaChimenea,$TemperaturaAire,$TemperaturaReferencia,
														   $MasaAguaVaporAtomizador,$HumedadRelativa,$PorcentajeO2,
														   $CO2Formado,$H2OFormado,$N2Formado,$AireRequerido,$BaseSeca,
														   &$masaAireHumedo = 0, &$masaAireExcesoAire = 0, &$masaAireRequerido=0, &$porcentajeExcesoAire=0){
		
		// Cálculo de las masas de los componentes formados
		// [lb/lb combustible]
		$masaCO2 = $this->masa->CalcularComponenteChimenaTeorico_4_CO2Formado($FlujoMasicoCombustible, $FraccionMolarCombustion, $PesoMolecularCombustion, $CO2Formado);
		$masaAguaFormada = $this->masa->CalcularComponenteChimenaTeorico_4_H2OFormado($FlujoMasicoCombustible, $FraccionMolarCombustion, $PesoMolecularCombustion, $H2OFormado);
		$masaN2 = $this->masa->CalcularComponenteChimenaTeorico_4_N2Formado($FlujoMasicoCombustible, $FraccionMolarCombustion, $PesoMolecularCombustion, $N2Formado);
		$masaAireRequerido = $this->masa->CalcularComponenteChimenaTeorico_4_AireRequerido($FlujoMasicoCombustible, $FraccionMolarCombustion, $PesoMolecularCombustion, $AireRequerido);

		// Cálculo de las masa del Aire tomando en cuenta el Exceso de Aire
		$masaAireHumedo = $this->masa->CalcularAireHumedo_3($masaAireRequerido, $TemperaturaAire, $HumedadRelativa);
		$masaHumedadMasaCombustible = $masaAireHumedo - $masaAireRequerido;
		$masaAguaMasaCombustible = $masaHumedadMasaCombustible + $masaAguaFormada + $MasaAguaVaporAtomizador;
		
		$masaAireExcesoAire = $this->masa->CalcularAireExceso($masaN2, $masaCO2, $masaAguaMasaCombustible, $masaAireRequerido, $PorcentajeO2, $masaAireHumedo, $BaseSeca);
		$porcentajeExcesoAire = ($masaAireExcesoAire / $masaAireRequerido) * 100;

		// Cálculo de las masa del Agua tomando en cuenta el Exceso de Aire
		//$masaAgua = $this->masa->CalcularAgua($masaAguaFormada, $masaHumedadMasaCombustible, $MasaAguaVaporAtomizador / $FlujoMasicoCombustible);
		$masaAguaTotal = $this->masa->CalcularAguaTotal($porcentajeExcesoAire, $masaHumedadMasaCombustible, $masaAguaMasaCombustible);

		// Cálculo de las entalpías de los componentes formados
		$entalpiaCO2 = $this->entalpia->CalcularCO2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaH2O = $this->entalpia->CalcularAguaVapor($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaN2 = $this->entalpia->CalcularN2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaAire = $this->entalpia->CalcularAire($TemperaturaChimenea);

		// Cálculo de los calores de los componentes formados
		$calorCO2 = $this->calor->Calcular_3(1, $masaCO2, $entalpiaCO2);
		$calorH2O = $this->calor->Calcular_3(1, $masaAguaTotal, $entalpiaH2O);
		$calorN2 = $this->calor->Calcular_3(1, $masaN2, $entalpiaN2);
		$calorAire = $this->calor->Calcular_3(1, $masaAireExcesoAire, $entalpiaAire);

		$perdidaCalorChimenea = $calorCO2 + $calorH2O + $calorN2 + $calorAire;

		return $perdidaCalorChimenea;
	}

	public function CalcularHornoPerdidaChimeneaTeorico_6($MasaCO2,$MasaH2O,$MasaN2,$MasaAire,$TemperaturaChimenea,$TemperaturaReferencia){
		// Cálculo de las entalp�as de los componentes formados
		$entalpiaCO2 = $this->entalpia->CalcularCO2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaH2O = $this->entalpia->CalcularAguaVapor($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaN2 = $this->entalpia->CalcularN2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaAire = $this->entalpia->CalcularAire($TemperaturaChimenea);

		// Cálculo de los calores de los componentes formados
		$calorCO2 = $this->calor->Calcular_3(1, $MasaCO2, $entalpiaCO2);
		$calorH2O = $this->calor->Calcular_3(1, $MasaH2O, $entalpiaH2O);
		$calorN2 = $this->calor->Calcular_3(1, $MasaN2, $entalpiaN2);
		$calorAire = $this->calor->Calcular_3(1, $MasaAire, $entalpiaAire);

		$perdidaCalorChimenea = $calorCO2 + $calorH2O + $calorN2 + $calorAire;

		return $perdidaCalorChimenea;
	}

	public function CalcularHornoPerdidaChimeneaTeorico($FlujoMasicoCombustible,$FraccionMasicaCombustion,$TemperaturaChimenea,
														$TemperaturaAire,$TemperaturaReferencia,$MasaAguaVaporAtomizador,
														$HumedadRelativa, $PorcentajeO2,$CO2Formado,$H2OFormado,$N2Formado,
														$AireRequerido){

		// Cálculo de las masas de los componentes formados
		$masaCO2 = $this->masa->CalcularComponenteChimenaTeorico_2_CO2Formado($FraccionMasicaCombustion, $CO2Formado);

		$masaAguaFormada = $this->masa->CalcularComponenteChimenaTeorico_2_H2OFormado($FraccionMasicaCombustion, $H2OFormado);

		$masaN2 = $this->masa->CalcularComponenteChimenaTeorico_2_N2Formado($FraccionMasicaCombustion, $N2Formado);
		$masaAireRequerido = $this->masa->CalcularComponenteChimenaTeorico_2_AireRequerido($FraccionMasicaCombustion, $AireRequerido);

		// Cálculo de las masa del Aire tomando en cuenta el Exceso de Aire
		$masaAireHumedo = $this->masa->CalcularAireHumedo_3($masaAireRequerido, $TemperaturaAire, $HumedadRelativa);
		$masaAireExcesoAire = $this->masa->CalcularAireExceso($masaN2, $masaCO2, $masaAguaFormada, $masaAireRequerido, $PorcentajeO2, $masaAireHumedo, $this->BaseSeca);
		$porcentajeExcesoAire = ($masaAireExcesoAire / $masaAireRequerido) * 100;

		// Cálculo de las masa del Agua tomando en cuenta el Exceso de Aire
		$masaAgua = $this->masa->CalcularAgua($masaAguaFormada, $masaAireHumedo, $masaAireRequerido, $MasaAguaVaporAtomizador / $FlujoMasicoCombustible);
		$masaAguaExcesoAire = $this->masa->CalcularAguaExcesoAire($porcentajeExcesoAire, $masaAireHumedo, $masaAireRequerido, $masaAgua);

		// Cálculo de las entalp�as de los componentes formados
		$entalpiaCO2 = $this->entalpia->CalcularCO2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaH2O = $this->entalpia->CalcularAguaVapor($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaN2 = $this->entalpia->CalcularN2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaAire = $this->entalpia->CalcularAire($TemperaturaChimenea);

		// Cálculo de los calores de los componentes formados
		$calorCO2 = $this->calor->Calcular_3(1, $masaCO2, $entalpiaCO2);
		$calorH2O = $this->calor->Calcular_3(1, $masaAguaExcesoAire, $entalpiaH2O);
		$calorN2 = $this->calor->Calcular_3(1, $masaN2, $entalpiaN2);
		$calorAire = $this->calor->Calcular_3(1, $masaAireExcesoAire, $entalpiaAire);

		$perdidaCalorChimenea = $calorCO2 + $calorH2O + $calorN2 + $calorAire;

		return $perdidaCalorChimenea;
	}

	public function CalcularHornoPerdidaChimeneaReal_14($FlujoMasicoCombustible,$FraccionMolarChimenea,$BaseSeca,$masaAireRequerido,$porcentajeExcesoAire, 
														$masaAireHumedo,$pesoTotal,$TemperaturaChimenea,$TemperaturaReferencia){
															
		$FlujoMasicoAireSeco = ($masaAireHumedo * $FlujoMasicoCombustible) * (1 + ($porcentajeExcesoAire/100));
		
		$masaHumedaMasaCombustible = $masaAireHumedo - $masaAireRequerido;
		
		$flujoAireHumedo = ($masaAireHumedo + $masaHumedaMasaCombustible) * (1 + ($porcentajeExcesoAire / 100)) * $FlujoMasicoCombustible;

		$flujoMasicoGasesSecos = $FlujoMasicoCombustible + $FlujoMasicoAireSeco;
		
		$porcentajeN2Secos = (100 - ($FraccionMolarChimenea[$this->chimenea["CO2"]] * 100) - 
							($FraccionMolarChimenea[$this->chimenea["O2"]] * 100) - 
							$FraccionMolarChimenea[$this->chimenea["CO"]] - 
							$FraccionMolarChimenea[$this->chimenea["NO"]] - 
							$FraccionMolarChimenea[$this->chimenea["SO2"]])/100;
		
		$pesoMolecularGasesSecos = (($FraccionMolarChimenea[$this->chimenea["CO"]] * 28) + 
								    ($FraccionMolarChimenea[$this->chimenea["NO"]] * 32) + 
								    ($FraccionMolarChimenea[$this->chimenea["SO2"]] * 64) + 
								    ($FraccionMolarChimenea[$this->chimenea["O2"]] * 3200) +
								    ($FraccionMolarChimenea[$this->chimenea["CO2"]] * 4800) +
								    ($porcentajeN2Secos * 2800))/100;
		
		$flujoMolarGasesSecos = $flujoMasicoGasesSecos / $pesoMolecularGasesSecos;
		
		$co = $FraccionMolarChimenea[$this->chimenea["CO"]];
		
		$molesCO = ($FraccionMolarChimenea[$this->chimenea["CO"]] * $flujoMolarGasesSecos) / 100; 
		$molesCO2 = ($FraccionMolarChimenea[$this->chimenea["CO2"]] * 100 * $flujoMolarGasesSecos) / 100; 
		$molesO2 = ($FraccionMolarChimenea[$this->chimenea["O2"]] * 100 * $flujoMolarGasesSecos) / 100;
		$molesSO2 = ($FraccionMolarChimenea[$this->chimenea["SO2"]] * $flujoMolarGasesSecos) / 100;
		$molesNO = ($FraccionMolarChimenea[$this->chimenea["NO"]] * $flujoMolarGasesSecos) / 100;
		$molesN2 =  $flujoMolarGasesSecos * $porcentajeN2Secos;

		$lbTotal = (((6 * 1.01) / ((6 * 1.01 + 2 * 12))) * $pesoTotal[$this->combustionGas["etano"]-1]) + 
				   (((4 * 1.01) / ((4 * 1.01 + 2 * 12))) * $pesoTotal[$this->combustionGas["eteno"]-1]) +
				   (((2 * 1.01) / ((2 * 1.01 + 32))) * $pesoTotal[$this->combustionGas["H2S"]-1]) +
				   (((2 * 1.01) / ((2 * 1.01))) * $pesoTotal[$this->combustionGas["H2"]-1]) +
				   (((10 * 1.01) / (10 * 1.01 + 4 * 12)) * $pesoTotal[$this->combustionGas["isobutano"]-1]) +
				   (((14 * 1.01) / ((14 * 1.01 + 6 * 12))) * $pesoTotal[$this->combustionGas["C6"]-1]) +
				   (((12 * 1.01) / (12 * 1.01 + 5 * 12)) * $pesoTotal[$this->combustionGas["isopentano"]-1]) +
				   (((4 * 1.01) / ((4 * 1.01 + 12))) * $pesoTotal[$this->combustionGas["metano"]-1]) +
				   (((10 * 1.01) / (10 * 1.01 + 4 * 12)) * $pesoTotal[$this->combustionGas["butano"]-1]) +
				   (((12 * 1.01) / (12 * 1.01 + 5 * 12)) * $pesoTotal[$this->combustionGas["pentano"]-1]) +
				   (((10 * 1.01) / (10 * 1.01 + 5 * 12)) * $pesoTotal[$this->combustionGas["olefinas"]-1]) +
				   (((8 * 1.01) / (8 * 1.01 + 3 * 12)) * $pesoTotal[$this->combustionGas["propano"]-1]) +
				   (((6 * 1.01) / (6 * 1.01 + 3 * 12)) * $pesoTotal[$this->combustionGas["propileno"]-1]) +
				   (((8 * 1.01) / (8 * 1.01 + 4 * 12)) * $pesoTotal[$this->combustionGas["buteno"]-1]);
		
		$NHcombustible = $lbTotal / 1.01;
		$MH2OHumedadAire = $masaHumedaMasaCombustible * $FlujoMasicoCombustible * (1 + $porcentajeExcesoAire / 100);
		$NH2HumedadAire = (2.02 / (2.02 + 16)) * $MH2OHumedadAire / 2.02;
		$NH2TotalEntrante = $NHcombustible + (2 * $NH2HumedadAire);
		
		$NH2OSaliente = $NH2TotalEntrante / 2.02;
		
		$totalGasesHumedos = $molesCO + $molesCO2 + $molesO2 + $molesSO2 + $molesNO + $molesN2 + $NH2OSaliente;
		 
		$porcentajeCO = $molesCO / $totalGasesHumedos;
		$porcentajeCO2 = $molesCO2 / $totalGasesHumedos;
		$porcentajeO2 = $molesO2 / $totalGasesHumedos;
		$porcentajeSO2 = $molesSO2 / $totalGasesHumedos;
		$porcentajeNO = $molesNO / $totalGasesHumedos;
		$porcentajeN2 = $molesN2 / $totalGasesHumedos;
		$porcentajeH2O = $NH2OSaliente / $totalGasesHumedos;
		
		$PMGasesTotal = ($porcentajeCO * 28) + ($porcentajeCO2 * 48) + ($porcentajeO2 * 32) + ($porcentajeSO2 * 64) + 
						($porcentajeNO * 30) + ($porcentajeN2 * 28) + ($porcentajeH2O * 18);
		
		$flujoMasicoGasesHumedos = $flujoAireHumedo + $FlujoMasicoCombustible;
		
		$flujoMasicoCO = ($porcentajeCO * $flujoMasicoGasesHumedos * 28) / $PMGasesTotal;
		$flujoMasicoCO2 = ($porcentajeCO2 * $flujoMasicoGasesHumedos * 44) / $PMGasesTotal;
		$flujoMasicoO2 = ($porcentajeO2 * $flujoMasicoGasesHumedos * 32) / $PMGasesTotal;
		$flujoMasicoSO2 = ($porcentajeSO2 * $flujoMasicoGasesHumedos * 64) / $PMGasesTotal;
		$flujoMasicoNO = ($porcentajeNO * $flujoMasicoGasesHumedos * 30) / $PMGasesTotal;
		$flujoMasicoN2 = ($porcentajeN2 * $flujoMasicoGasesHumedos * 28) / $PMGasesTotal;
		$flujoMasicoH2O = ($porcentajeH2O * $flujoMasicoGasesHumedos * 18) / $PMGasesTotal;
		
		$QS = ($this->entalpia->CalcularCO($TemperaturaChimenea, $TemperaturaReferencia) * $flujoMasicoCO) + 
			  ($this->entalpia->CalcularCO2($TemperaturaChimenea, $TemperaturaReferencia) * $flujoMasicoCO2) + 
			  ($this->entalpia->CalcularO2($TemperaturaChimenea, $TemperaturaReferencia) * $flujoMasicoO2) + 
			  ($this->entalpia->CalcularSO2($TemperaturaChimenea, $TemperaturaReferencia) * $flujoMasicoSO2) + 
			  ($this->entalpia->CalcularNO($TemperaturaChimenea, $TemperaturaReferencia) * $flujoMasicoNO) + 
			  ($this->entalpia->CalcularN2($TemperaturaChimenea, $TemperaturaReferencia) * $flujoMasicoN2) +
			  ($this->entalpia->CalcularAguaVapor($TemperaturaChimenea, $TemperaturaReferencia) * $flujoMasicoH2O);
		
		$QS = $QS / $FlujoMasicoCombustible;
		
		return $QS;
	}

	public function CalcularHornoPerdidaChimeneaReal_9($MasaO2,$MasaCO,$MasaCO2,$MasaNO,$MasaH2O,$MasaSO2,$MasaN2,$TemperaturaChimenea,$TemperaturaReferencia){
		// Cálculo de las entalpias de los componentes formados
		$entalpiaO2 = $this->entalpia->CalcularO2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaCO = $this->entalpia->CalcularCO($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaCO2 = $this->entalpia->CalcularCO2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaNO = $this->entalpia->CalcularNO($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaSO2 = $this->entalpia->CalcularSO2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaN2 = $this->entalpia->CalcularN2($TemperaturaChimenea, $TemperaturaReferencia);
		$entalpiaH2O = $this->entalpia->CalcularAguaVapor($TemperaturaChimenea, $TemperaturaReferencia);

		// Cálculo de los calores de los componentes formados
		$calorO2 = $this->calor->Calcular(1, $MasaO2, $entalpiaO2);
		$calorCO = $this->calor->Calcular(1, $MasaCO, $entalpiaCO);
		$calorCO2 = $this->calor->Calcular(1, $MasaCO2, $entalpiaCO2);
		$calorNO = $this->calor->Calcular(1, $MasaNO, $entalpiaNO);
		$calorSO2 = $this->calor->Calcular(1, $MasaSO2, $entalpiaSO2);
		$calorN2 = $this->calor->Calcular(1, $MasaN2, $entalpiaN2);
		$calorH2O = $this->calor->Calcular(1, $MasaH2O, $entalpiaH2O);

		$perdidaCalorChimenea = $calorO2 + $calorCO + $calorCO2 + $calorNO + $calorSO2 + $calorN2 + $calorH2O;

		return $perdidaCalorChimenea;
	}

	public function Energia(){
		$this->entalpia = new Entalpia();  //termodinamica
		$this->calor = new Calor();  //termodinamica
		$this->pesoMolecular = new PesoMolecular();  //termodinamica
		$this->flujo = new Flujo();   //termodinamica
		$this->capacidadCalorifica = new CapacidadCalorifica();  //termodinamica
		$this->presion = new Presion();   //termodinamica
		$this->masa = new masa();

		// Porcentaje de la Densidad API del combustible líquido
		$this->porcentajeAPI[1] = 0 ; $this->porcentajeAPI[2] = 10 ; $this->porcentajeAPI[3] = 20 ; $this->porcentajeAPI[4] = 30 ; $this->porcentajeAPI[5] = 40 ; $this->porcentajeAPI[6] = 50;
		$this->porcentajeAPI[7] = 60 ; $this->porcentajeAPI[8] = 70 ; $this->porcentajeAPI[9] = 80 ; $this->porcentajeAPI[10] = 90 ; $this->porcentajeAPI[11] = 100;

		// Poder Calorifico Infererior a 0% de Azufre
		$this->azufrePorcentaje0[1] = 16926 ; $this->azufrePorcentaje0[2] = 17495 ; $this->azufrePorcentaje0[3] = 17961 ; $this->azufrePorcentaje0[4] = 18189 ; $this->azufrePorcentaje0[5] = 18444 ; $this->azufrePorcentaje0[6] = 18675;
		$this->azufrePorcentaje0[7] = 18811 ; $this->azufrePorcentaje0[8] = 19026 ; $this->azufrePorcentaje0[9] = 19164 ; $this->azufrePorcentaje0[10] = 19179 ; $this->azufrePorcentaje0[11] = 19291;

		// Poder Calorifico Infererior a 1% de Azufre
		$this->azufrePorcentaje1[1] = 16815 ; $this->azufrePorcentaje1[2] = 17375 ; $this->azufrePorcentaje1[3] = 17769 ; $this->azufrePorcentaje1[4] = 18053 ; $this->azufrePorcentaje1[5] = 18299 ; $this->azufrePorcentaje1[6] = 18598;
		$this->azufrePorcentaje1[7] = 18730 ; $this->azufrePorcentaje1[8] = 18855 ; $this->azufrePorcentaje1[9] = 18985 ; $this->azufrePorcentaje1[10] = 19086 ; $this->azufrePorcentaje1[11] = 19212;

		// Poder Calorifico Infererior a 2% de Azufre
		$this->azufrePorcentaje2[1] = 16704 ; $this->azufrePorcentaje2[2] = 17135 ; $this->azufrePorcentaje2[3] = 17576 ; $this->azufrePorcentaje2[4] = 17916 ; $this->azufrePorcentaje2[5] = 18154 ; $this->azufrePorcentaje2[6] = 18290;
		$this->azufrePorcentaje2[7] = 18568 ; $this->azufrePorcentaje2[8] = 18599 ; $this->azufrePorcentaje2[9] = 18806 ; $this->azufrePorcentaje2[10] = 18945 ; $this->azufrePorcentaje2[11] = 19016;

		// Poder Calorifico Infererior a 3% de Azufre
		$this->azufrePorcentaje3[1] = 16537 ; $this->azufrePorcentaje3[2] = 17015 ; $this->azufrePorcentaje3[3] = 17448 ; $this->azufrePorcentaje3[4] = 17779 ; $this->azufrePorcentaje3[5] = 18009 ; $this->azufrePorcentaje3[6] = 18137;
		$this->azufrePorcentaje3[7] = 18406 ; $this->azufrePorcentaje3[8] = 18471 ; $this->azufrePorcentaje3[9] = 18627 ; $this->azufrePorcentaje3[10] = 18757 ; $this->azufrePorcentaje3[11] = 18820;

		// Poder Calorifico Infererior a 4% de Azufre
		$this->azufrePorcentaje4[1] = 16453 ; $this->azufrePorcentaje4[2] = 16896 ; $this->azufrePorcentaje4[3] = 17320 ; $this->azufrePorcentaje4[4] = 17642 ; $this->azufrePorcentaje4[5] = 17863 ; $this->azufrePorcentaje4[6] = 17983;
		$this->azufrePorcentaje4[7] = 18325 ; $this->azufrePorcentaje4[8] = 18429 ; $this->azufrePorcentaje4[9] = 18448 ; $this->azufrePorcentaje4[10] = 18570 ; $this->azufrePorcentaje4[11] = 18624;
	}
} // Energia


class Masa{
	// Attributes
	public $pesoMolecular; //termodinamica
	public $flujo;  //termodinamica
	public $humedad; //termodinamica

	public $chimenea = array("O2" => 1,
							 "CO" => 2,
							 "CO2" => 3,
							 "NO" => 4,                                          
							 "SO2" => 5,					 
							 "N2" => 6,					
							 "H2O" => 7
							 );					

	public function CalcularComponenteChimenaTeorico_4_CO2Formado($FlujoMasicoCombustible,$FraccionMolarCombustion,$PesoMolecularCombustion,$ComponenteFormado){
		$masaComponente = 0;
		$pesoMolecularMezcla = $this->pesoMolecular->CalcularMezcla($PesoMolecularCombustion, $FraccionMolarCombustion);
		$flujoMolarMezcla = $FlujoMasicoCombustible / $pesoMolecularMezcla;

		$n = count($FraccionMolarCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Flujo molar por componente [lbmol/h]
			$flujoMolarComponente[$i] = $this->flujo->CalcularMolarComponente($flujoMolarMezcla, $FraccionMolarCombustion[$i]);
			// Flujo másico por componente [lb/h]
			$flujoMasicoComponente[$i] = $this->flujo->CalcularMasicoComponente($flujoMolarComponente[$i], $PesoMolecularCombustion[$i-1]["pesoMolecularCombustion"]);
			// Masa de CO2 formado
			$masaComponente = $masaComponente + $flujoMasicoComponente[$i] * $ComponenteFormado[$i-1]["CO2Formado"];
		}
		$masaComponente = $masaComponente / $FlujoMasicoCombustible;

		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_4_H2OFormado($FlujoMasicoCombustible,$FraccionMolarCombustion,$PesoMolecularCombustion,$ComponenteFormado){
		$masaComponente = 0;
		$pesoMolecularMezcla = $this->pesoMolecular->CalcularMezcla($PesoMolecularCombustion, $FraccionMolarCombustion);
		$flujoMolarMezcla = $FlujoMasicoCombustible / $pesoMolecularMezcla;

		$n = count($FraccionMolarCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Flujo molar por componente [lbmol/h]
			$flujoMolarComponente[$i] = $this->flujo->CalcularMolarComponente($flujoMolarMezcla, $FraccionMolarCombustion[$i]);
			// Flujo másico por componente [lb/h]
			$flujoMasicoComponente[$i] = $this->flujo->CalcularMasicoComponente($flujoMolarComponente[$i], $PesoMolecularCombustion[$i-1]["pesoMolecularCombustion"]);
			// Masa de CO2 formado
			$masaComponente = $masaComponente + $flujoMasicoComponente[$i] * $ComponenteFormado[$i-1]["H2OFormado"];
		}
		$masaComponente = $masaComponente / $FlujoMasicoCombustible;
		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_4_N2Formado($FlujoMasicoCombustible,$FraccionMolarCombustion,$PesoMolecularCombustion,$ComponenteFormado){
		$masaComponente = 0;
		$pesoMolecularMezcla = $this->pesoMolecular->CalcularMezcla($PesoMolecularCombustion, $FraccionMolarCombustion);

		$flujoMolarMezcla = $FlujoMasicoCombustible / $pesoMolecularMezcla;

		$n = count($FraccionMolarCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Flujo molar por componente [lbmol/h]
			$flujoMolarComponente[$i] = $this->flujo->CalcularMolarComponente($flujoMolarMezcla, $FraccionMolarCombustion[$i]);
			// Flujo másico por componente [lb/h]
			$flujoMasicoComponente[$i] = $this->flujo->CalcularMasicoComponente($flujoMolarComponente[$i], $PesoMolecularCombustion[$i-1]["pesoMolecularCombustion"]);
			// Masa de CO2 formado
			$masaComponente = $masaComponente + $flujoMasicoComponente[$i] * $ComponenteFormado[$i-1]["N2Formado"];
		}

		$masaComponente = $masaComponente / $FlujoMasicoCombustible;

		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_4_AireRequerido($FlujoMasicoCombustible,$FraccionMolarCombustion,$PesoMolecularCombustion,$ComponenteFormado){
		$masaComponente = 0;
		$pesoMolecularMezcla = $this->pesoMolecular->CalcularMezcla($PesoMolecularCombustion, $FraccionMolarCombustion);
		$flujoMolarMezcla = $FlujoMasicoCombustible / $pesoMolecularMezcla;

		$n = count($FraccionMolarCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Flujo molar por componente [lbmol/h]
			$flujoMolarComponente[$i] = $this->flujo->CalcularMolarComponente($flujoMolarMezcla, $FraccionMolarCombustion[$i]);
			// Flujo másico por componente [lb/h]
			$flujoMasicoComponente[$i] = $this->flujo->CalcularMasicoComponente($flujoMolarComponente[$i], $PesoMolecularCombustion[$i-1]["pesoMolecularCombustion"]);
			// Masa de CO2 formado
			$masaComponente = $masaComponente + $flujoMasicoComponente[$i] * $ComponenteFormado[$i-1]["AireRequerido"];
		}

		$masaComponente = $masaComponente / $FlujoMasicoCombustible;

		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_2($FraccionMasicaCombustion,$ComponenteFormado){
		$masaComponente  = 0;

		$n = count($FraccionMasicaCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Masa del componente formado
			$masaComponente = $masaComponente + $FraccionMasicaCombustion[$i] * $ComponenteFormado[$i]["CO2Formado"] ;
		}

		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_2_CO2Formado($FraccionMasicaCombustion,$ComponenteFormado){
		$masaComponente  = 0;

		$n = count($FraccionMasicaCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Masa del componente formado
			$masaComponente = $masaComponente + $FraccionMasicaCombustion[$i] * $ComponenteFormado[$i]["CO2Formado"] ;
		}

		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_2_H2OFormado($FraccionMasicaCombustion,$ComponenteFormado){
		$masaComponente  = 0;

		$n = count($FraccionMasicaCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Masa del componente formado
			$masaComponente = $masaComponente + $FraccionMasicaCombustion[$i] * $ComponenteFormado[$i]["H2OFormado"] ;
		}

		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_2_N2Formado($FraccionMasicaCombustion,$ComponenteFormado){
		$masaComponente  = 0;
		//Dim masaComponente As Double

		$n = count($FraccionMasicaCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Masa del componente formado
			$masaComponente = $masaComponente + $FraccionMasicaCombustion[$i] * $ComponenteFormado[$i]["N2Formado"] ;
		}

		return $masaComponente;
	}

	public function CalcularComponenteChimenaTeorico_2_AireRequerido($FraccionMasicaCombustion,$ComponenteFormado){
		$masaComponente  = 0;

		$n = count($FraccionMasicaCombustion);

		// Cálculo de las masas de los componentes formados
		for ($i=1;$i<=$n;$i++){
			// Masa del componente formado
			$masaComponente = $masaComponente + $FraccionMasicaCombustion[$i] * $ComponenteFormado[$i]["AireRequerido"] ;
		}

		return $masaComponente;
	}

	public function CalcularComponenteChimenaReal($flujoMasicoCombustible,$MasaAireEntrada,$MasaMedioAtomizador,$FraccionMolarComponenteChimenea,$PesoMolecularComponenteChimenea,$posicionComponente){
		// [lb/h]
		$flujoMasicoGases = $flujoMasicoCombustible + $MasaAireEntrada + $MasaMedioAtomizador;

		$pesoMolecularGases = $this->pesoMolecular->CalcularMezcla($PesoMolecularComponenteChimenea, $FraccionMolarComponenteChimenea);

		// [lb/lb combustible]
		$masaComponente = $FraccionMolarComponenteChimenea[$posicionComponente] * ($flujoMasicoGases / $flujoMasicoCombustible) * ($PesoMolecularComponenteChimenea[$posicionComponente-1]["pesoMolecularCombustion"] / $pesoMolecularGases);

		return $masaComponente;
	}

	public function CalcularAireHumedo_6($flujoMasicoCombustible,$FraccionMolarComponente,$PesoMolecularComponente,$AireRequerido,$Temperatura,$HumedadRelativa){
		$masaAireRequerido = $this->CalcularComponenteChimenaTeorico_4_CO2Formado($flujoMasicoCombustible, $FraccionMolarComponente, $PesoMolecularComponente, $AireRequerido);

		$humedadAire = $this->humedad->CalcularAire($Temperatura, $HumedadRelativa);

		$masaAire = $masaAireRequerido / (1 - $humedadAire);

		return $masaAire;
	}

	public function CalcularAireHumedo_3($MasaAireRequerido,$Temperatura,$HumedadRelativa){
		$humedadAire = $this->humedad->CalcularAire($Temperatura, $HumedadRelativa);

		$masaAire = $MasaAireRequerido / (1 - $humedadAire);

		return $masaAire;
	}

	public function CalcularAireHumedo($MasaAireRequerido,$HumedadAire){
		$masaAire = $MasaAireRequerido / (1 - $HumedadAire);

		return $masaAire;
	}

	public function CalcularAireExceso_2($MasaAireRequerido,$PorcentajeExcesoAire){
		$masaAireExceso = $MasaAireRequerido * $PorcentajeExcesoAire / 100;

		return $masaAireExceso;
	}

	public function CalcularAireExceso_5($flujoMasicoCombustible,$FraccionMolarComponente,$PesoMolecularComponente,$AireRequerido,$PorcentajeExcesoAire){
		$masaAireRequerido = CalcularComponenteChimenaTeorico($flujoMasicoCombustible, $FraccionMolarComponente, $PesoMolecularComponente, $AireRequerido);

		$masaAireExceso = $masaAireRequerido * $PorcentajeExcesoAire / 100;

		return $masaAireExceso;
	}

	public function CalcularAireExceso($MasaN2Formado,$MasaCO2Formado,$MasaAguaMasaCombustible,$MasaAireRequerido,$porcentajeO2,$MasaAireHumedo,$BaseSeca){
		$masaHumedadMasaCombustible = 0;

		if ($BaseSeca==true){
			$MasaAguaMasaCombustible = 0;
			$masaHumedadMasaCombustible = 0;
		}else{
			$masaHumedadMasaCombustible = $MasaAireHumedo - $MasaAireRequerido;
		}

		$A = 28.85 * $porcentajeO2;

		$B = ($MasaN2Formado / 28) + ($MasaCO2Formado / 44) + ($MasaAguaMasaCombustible / 18);

		$C = 20.95 - $porcentajeO2;

		$D = (1.6028 * $masaHumedadMasaCombustible / $MasaAireRequerido) + 1;

		$masaAireExceso = ($A * $B) / ($C * $D);

		return $masaAireExceso;
	}

	public function CalcularAguaTotal($PorcentajeAireExceso,$masaHumedadMasaCombustible,$MasaAguaMasaCombustible){
			$masaAguaTotal = ($PorcentajeAireExceso * ($masaHumedadMasaCombustible) / 100) + $MasaAguaMasaCombustible;

			return $masaAguaTotal;
	}

	public function CalcularN2Entrada_5($flujoMasicoCombustible,$MasaAireEntrada,$FraccionMolarComponente,$PesoMolecularComponente,$posicionN2){
			$pesoMolecularN2 = $PesoMolecularComponente[$posicionN2];
			$fraccionMolarN2 = $FraccionMolarComponente[$posicionN2];
			$pesoMolecularMezcla = $this->pesoMolecular->CalcularMezcla($PesoMolecularComponente, $FraccionMolarComponente);

			$masaN2Entrada = $MasaAireEntrada * 0.767 + $flujoMasicoCombustible * ($pesoMolecularN2["pesoMolecularCombustion"] / $pesoMolecularMezcla) * $fraccionMolarN2;

			return $masaN2Entrada;
	}

	public function CalcularN2Entrada_3($flujoMasicoCombustible,$MasaAireEntrada,$FraccionMasicaComponente){
		$masaN2Entrada = $MasaAireEntrada * 0.767 + $flujoMasicoCombustible * $FraccionMasicaComponente;

		return $masaN2Entrada;
	}

	public function CalcularFraccionN2Chimenea($flujoMasicoCombustible,$MasaAireEntrada,$MasaAguaAtomizador,$MasaN2Entrada, 
											   $FraccionMolarComponenteChimena,$PesoMolecularComponenteChimenea){

		$flujoMasicoGases = $flujoMasicoCombustible + $MasaAireEntrada + $MasaAguaAtomizador;
		$terminoA = $PesoMolecularComponenteChimenea[($this->chimenea["N2"]-1)]["pesoMolecularCombustion"] - $PesoMolecularComponenteChimenea[($this->chimenea["H2O"]-1)]["pesoMolecularCombustion"];
		$terminoB = $FraccionMolarComponenteChimena[$this->chimenea["O2"]] * $PesoMolecularComponenteChimenea[$this->chimenea["O2"]-1]["pesoMolecularCombustion"] + $FraccionMolarComponenteChimena[$this->chimenea["CO"]]["pesoMolecularCombustion"] * $PesoMolecularComponenteChimenea[$this->chimenea["CO"]]["pesoMolecularCombustion"] + $FraccionMolarComponenteChimena[$this->chimenea["CO2"]]["pesoMolecularCombustion"] * $PesoMolecularComponenteChimenea[$this->chimenea["CO2"]]["pesoMolecularCombustion"] + $FraccionMolarComponenteChimena[$this->chimenea["NO"]]["pesoMolecularCombustion"] * $PesoMolecularComponenteChimenea[$this->chimenea["NO"]]["pesoMolecularCombustion"] + $FraccionMolarComponenteChimena[$this->chimenea["SO2"]]["pesoMolecularCombustion"] * $PesoMolecularComponenteChimenea[$this->chimenea["SO2"]]["pesoMolecularCombustion"] + $PesoMolecularComponenteChimenea[$this->chimenea["H2O"]-1]["pesoMolecularCombustion"] * (1 - $FraccionMolarComponenteChimena[$this->chimenea["O2"]] - $FraccionMolarComponenteChimena[$this->chimenea["CO"]] - $FraccionMolarComponenteChimena[$this->chimenea["CO2"]] - $FraccionMolarComponenteChimena[$this->chimenea["NO"]] - $FraccionMolarComponenteChimena[$this->chimenea["SO2"]]);
		$terminoC = 0.5 * $FraccionMolarComponenteChimena[$this->chimenea["NO"]]["pesoMolecularCombustion"] - ($MasaN2Entrada * $terminoB / ($flujoMasicoGases * $PesoMolecularComponenteChimenea[$this->chimenea["N2"]]["pesoMolecularCombustion"]));
		$terminoD = ($MasaN2Entrada * $terminoA / ($flujoMasicoGases * $PesoMolecularComponenteChimenea[$this->chimenea["N2"]]["pesoMolecularCombustion"])) - 1;

		$composicionN2Chimenea = $terminoC / $terminoD;

		return $composicionN2Chimenea;
	}
	
	public function CalcularFraccionComponenteChimenea($FraccionMolarComponenteChimena,$posicionComponente){
		$composicionComponenteChimenea = 0;

		$n = count($FraccionMolarComponenteChimena);

		for ($i=1;$i<=$n;$i++){
			if ($i != $posicionComponente){
				$composicionComponenteChimenea = $composicionComponenteChimenea + $FraccionMolarComponenteChimena[$i];
			}
		}

		$composicionComponenteChimenea = 1 - $composicionComponenteChimenea;

		return $composicionComponenteChimenea;
	}

	public function ConvertirFraccionMasicaMolar($FlujoMasico,$FraccionMasica,$PesoMolecular){
		$flujoMolar = 0;

		$n = count($FraccionMasica);

		for ($i=1;$i<=$n;$i++){

			$flujoMasicoComponente[$i] = $FraccionMasica[$i] * $FlujoMasico;

			$flujoMolarComponente[$i] = $flujoMasicoComponente[$i] / $PesoMolecular[$i-1]["pesoMolecularCombustion"];
			$flujoMolar = $flujoMolar + $flujoMolarComponente[$i];
		}

		for ($i=1;$i<=$n;$i++){
			$fraccionMolar[$i] = $flujoMolarComponente[$i] / $flujoMolar;
		}

		return $fraccionMolar;
	}
	
	public function Masa(){
		$this->pesoMolecular = new PesoMolecular(); //termodinamica
		$this->flujo = new Flujo();  //termodinamica
		$this->humedad = new Humedad(); //termodinamica
	}
}
?>