<?php
include_once "../clases/presentacion.php";
include "efiterm.php";

class calcularEficiencia{
	public $pres;
	public $conversionPrefijos;
	public $eficiencia;
	public $energia;
	public $conversionPorcentajes;
	
	function calcularEficiencia(){
		$this->pres = new presentacion();
		$this->conversionPrefijos = new Prefijos();
		$this->eficiencia = new EficienciaTermica();
		$this->conversionPorcentajes = new Porcentaje();
		$this->energia = new Energia();
	}
	
	function calcular($form){
		foreach($form as $nombre => $valor)
			$form[$nombre] = str_replace(",",".",$valor);

		$perdidaCalorChimeneaTeorico = 0;
		$perdidaCalorChimeneaReal = 0;
		$fraccionMolarCombustion = array();
		
		$resultado = array("eficienciaTermica" => "", "calorPerdidoTeorico" => "", "txtCalorPerdidoReal" => "");
		
        $eficienciaDiseno = $form["txtEficienciaTarget"];
        $gravedadEspecifica = $form["txtGE"]; //Gravedad especifica gas SQL*LIMS
        $API = $form["txtGradoAPI"];

        if($form["cmbTipoEquipo"] == "Caldera"){
        	
            $HHV = $form["txtHHV2"];
            $masaCombustibleCaldera = $this->conversionPrefijos->ConvertirKilo_Unidad($form["txtMfCaldera"]);
            $masaVapor = $this->conversionPrefijos->ConvertirKilo_Unidad($form["txtMrSt"]);
            $presionVapor = $form["txtPresionVapor"];
            $temperaturaVapor = $form["txtTemperaturaVapor"];
            $temperaturaAgua = $form["txtTemperaturaAgua"];
            $masaAgua = $this->conversionPrefijos->ConvertirKilo_Unidad($form["txtMagua"]);

			$resultado["eficienciaTermica"] = $this->eficiencia->CalcularCalderaEntradaSalida_9($masaCombustibleCaldera, $masaVapor, $masaAgua, $presionVapor, $temperaturaVapor, $temperaturaAgua, $this->eficiencia->tipo["Gas"], $HHV);
            
        }else{ //Horno
        
        	if($form["rblComposicionGases"] == "BaseSeca") $form["rblComposicionGases"] = true;
        	else $form["rblComposicionGases"] = false;
        	           
        	if($form["rblBaseOxigeno"] == "BaseSeca") $form["rblBaseOxigeno"] = true;
        	else $form["rblBaseOxigeno"] = false;
        		
        	if($form["cmbComposicionGases"] == "Real") $form["cmbComposicionGases"] = true;
        	else $form["cmbComposicionGases"] = false;

        	if($form["cmbMa"] == "Real") $form["cmbMa"] = true;
        	else $form["cmbMa"] = false;

            $volumenCombustibleHorno = $form["txtMfHorno"]; //Flujo gas combustible [pie^3/h] 
            $presionVapor = $form["txtPresion"]; //Temperatura vapor salida [°F]
            $temperaturaVapor = $form["txtTemperaturaVaporAtomizacion"]; //Presion vapor alta [psi]
            $temperaturaAmbiente = $form["txtTemperaturaAmbiente"]; //Temperatura ambiente [°F]
            $masaAire = $this->conversionPrefijos->ConvertirKilo_Unidad($form["txtMa"]); //Flujo masico del aire [lb/h]
            $temperaturaCombustible = $form["txtTCombustible"]; //Temperatura promedio del combustible [°F]
            $masaMedioAtomizador = $form["txtMvatom"]; //Flujo Masico del medio Atomizador
            $temperaturaChimenea = $form["txtTChimenea"];  //Temperatura de la chimenea [°F]
            $humedadRelativa = $form["txtHumedadRelativa"]; //Entrada [%]
            $porcentajeOxigeno = $form["txtExcesoAire"];  // Entrada [%]
			
            $fraccionMolarChimenea[$this->energia->chimenea["CO"]] = $this->conversionPorcentajes->ConvertirPPM_Fraccion($form["txtRealCO"]);
            $fraccionMolarChimenea[$this->energia->chimenea["CO2"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtRealCO2"]);
            if(isset($form["txtRealH2O"])) $fraccionMolarChimenea[$this->energia->chimenea["H2O"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtRealH2O"]);
            if(isset($form["txtRealN2"])) $fraccionMolarChimenea[$this->energia->chimenea["N2"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtRealN2"]);
            $fraccionMolarChimenea[$this->energia->chimenea["NO"]] = $this->conversionPorcentajes->ConvertirPPM_Fraccion($form["txtRealNO"]);
            $fraccionMolarChimenea[$this->energia->chimenea["O2"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtRealO2"]);
            $fraccionMolarChimenea[$this->energia->chimenea["SO2"]] = $this->conversionPorcentajes->ConvertirPPM_Fraccion($form["txtRealSO2"]);
            $txtLHV = $form["txtLHV"];

            if($form["cmbTipoCombustible"] == "Gas"){
                $fraccionMolarCombustion[$this->energia->combustionGas["CO"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtCO"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["CO2"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtCO2"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["butano"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtNButano"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["buteno"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtTotalButeno"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["C6"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtC6"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["etano"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtEtano"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["eteno"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtEteno"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["H2"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtHidrogeno"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["H2S"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtH2S"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["isobutano"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtIsoButano"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["isopentano"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtIsoPentano"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["metano"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtMetano"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["N2"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtNitrogeno"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["O2"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtOxigeno"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["olefinas"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtOlefinasC5"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["pentano"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtNPentano"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["propano"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtPropano"]);
                $fraccionMolarCombustion[$this->energia->combustionGas["propileno"]] = $this->conversionPorcentajes->ConvertirPorcentaje_Fraccion($form["txtPropileno"]);
			
                $eficienciaTermmica = $this->eficiencia->CalcularHorno_20($form["cmbEquipo"], $masaAire, $masaMedioAtomizador, $fraccionMolarChimenea, $temperaturaCombustible, $temperaturaChimenea, $temperaturaAmbiente, $temperaturaVapor, $presionVapor, $humedadRelativa, $porcentajeOxigeno, $this->eficiencia->tipo["Gas"],     $form["rblComposicionGases"], $form["rblBaseOxigeno"], $gravedadEspecifica, $API, $volumenCombustibleHorno, $txtLHV, $gravedadEspecifica, $form["cmbComposicionGases"], $form["cmbMa"], $fraccionMolarCombustion, $perdidaCalorChimeneaTeorico, $perdidaCalorChimeneaReal);

            }else{

            	$HHV = $form["txtHHVHornoHL"];
            	$relacionCH = $form["txtRelacionCH"];
            	$cenizas = $form["txtCenizas"];
            	$azufre = $form["txtAzufreHL"];
            	$sodio = $form["txtSodio"];
            	$otros = $form["txtOtros"];
            	$Mvatom = $form["txtMvatomHL"];
            	$Mcomb = $form["txtMcomb"];
            	$tempComb = $form["txtTCombustibleHL"];

				$eficienciaTermmica = $this->eficiencia->CalcularHornoLiquido($form["cmbEquipo"], $masaMedioAtomizador, $tempComb, $temperaturaChimenea, $temperaturaAmbiente, 
																			$humedadRelativa, $porcentajeOxigeno, $form["rblComposicionGases"], $form["rblBaseOxigeno"], $API, 
																			$perdidaCalorChimeneaTeorico, $perdidaCalorChimeneaReal,
																			$Mcomb, $HHV, $Mvatom, $relacionCH, $cenizas, $azufre, $sodio, $otros);
            }

            if($form["cmbComposicionGases"] == "Real"){
                $resultado["eficienciaTermica"] = $eficienciaTermmica[2];
                $resultado["calorPerdidoReal"] = $eficienciaTermmica[4];
            }else{
                $resultado["eficienciaTermica"] = $eficienciaTermmica[1];
            }
            $resultado["calorPerdidoTeorico"] = $eficienciaTermmica[3];
        }

        return $resultado;
	}
}
?>