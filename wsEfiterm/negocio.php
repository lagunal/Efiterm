<?php
include "../clases/presentacion.php";
include_once "../clases/clad.php";
include "../clases/presentacionAjax.php";
include_once "../config.php";

class negocio{

    public function obtenerEquipos(){
        $conf = new config();
        $clad = new clad($conf->queryString);
        
        $datos = $clad->obtenerEquiposEfiterm();

        return $datos;
    }

    public function obtenerEquipo($codigo){
        $conf = new config();
        $clad = new clad($conf->queryString);
        
        $datos = $clad->obtenerEquipoEfiterm($codigo);

        return $datos;
    }

    public function obtenerEficienciaEquipo($codigo, $dias = 7){
        $pres = new presentacion();
        $presAjax = new presentacionAjax();
        
        $fechaFin = $pres->obtenerFechaActual();
        $fechaInicio = $pres->dateSum($fechaFin, -$dias);
        
        $res = calcularEficienciaRangoIndicadores($codigo, $fechaInicio, $fechaFin, true);
        
        return $res;
        /*
        $pres = new presentacion();
        $conf = new config();
        $clad = new clad($conf->queryString);
        $presAjax = new presentacionAjax();
        
        $fechaActual = $pres->obtenerFechaActual();
    
        $datos = $clad->obtenerDetallesEquipo($codigo);

        $form["cmbEquipo"] = $datos[0]["codigo_equipo"];
        $form["cmbTipoEquipo"] = $datos[0]["equipo"];
        $form["cmbTipoCombustible"] = $datos[0]["combustible"];
        $form["txtEficienciaTermica"] = "";
        $form["txtCalorPerdidoTeorico"] = "";
        $form["txtCalorPerdidoReal"] = "";
        $form["txtFecha"] = "";
        $form["txtFechaInicio"] = $pres->dateSum($fechaActual, -$dias);
        $form["txtFechaFin"] = $fechaActual;
        
        $form["cmbComposicionGases"] = "Teorica";
        $form["txtMetano"] = "";
        $form["txtC6"] = "";
        $form["txtHidrogeno"] = "";
        $form["txtEtano"] = "";
        $form["txtEteno"] = "";
        $form["txtOxigeno"] = "";
        $form["txtPropano"] = "";
        $form["txtPropileno"] = "";
        $form["txtNitrogeno"] = "";
        $form["txtNButano"] = "";
        $form["txtCO2"] = "";
        $form["txtNPentano"] = "";
        $form["txtIsoButano"] = "";
        $form["txtCO"] = "";
        $form["txtIsoPentano"] = "";
        $form["txtOlefinasC5"] = "";
        $form["txtTotalButeno"] = "";
        $form["txtH2S"] = "";
        $form["txtGE"] = "";
        $form["txtHHV1"] = "900";
        $form["txtLHV"] = "";
        $form["txtCarbono"] = "";
        $form["txtHidrogenoLiquido"] = "";
        $form["txtGradoAPI"] = "";
        $form["txtAzufre"] = "";
        $form["txtMfCaldera"] = "";
        $form["txtHHV2"] = "900";
        $form["txtPresionVapor"] = "";
        $form["txtTemperaturaVapor"] = "";
        $form["txtTemperaturaAgua"] = "";
        $form["txtMrSt"] = "";
        $form["txtMagua"] = "";
        $form["txtMfHorno"] = "";
        $form["cmbMa"] = "Teorica";
        $form["txtMa"] = "";
        $form["txtTCombustible"] = "85";
        $form["txtTChimenea"] = "";
        $form["txtExcesoAire"] = "";
        $form["rblBaseOxigeno"] = "BaseSeca";
        $form["txtCostoCombustible"] = "";
        $form["txtParidad"] = "";
        $form["txtEficienciaTarget"] = $datos[0]["eficiencia"];;
        $form["txtTemperaturaAmbiente"] = $conf->temperaturaAmbiente;
        $form["txtHumedadRelativa"] = $conf->humedadRelativa;
        $form["txtMvatom"] = "";
        $form["txtPresion"] = "";
        $form["txtTemperaturaVaporAtomizacion"] = "";
        $form["rblComposicionGases"] = "BaseSeca";
        $form["txtRealCO2"] = "";
        $form["txtRealCO"] = "";
        $form["txtRealSO2"] = "";
        $form["txtRealO2"] = "";
        $form["txtRealNO"] = "";
    
        $eficiencia = calcularEficienciaRangoIntegrador($form);

        $ret = array();
        $ret = array("codigo_equipo"=>$codigo,"eficiencia"=>$eficiencia);   
        
        return $ret;
        */
    }
}
?>
