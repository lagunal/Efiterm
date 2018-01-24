<?php
class Densidad{
    public function ConvertirLiquidoPieCubico_Libra($PieCubico,$DensidadAgua,$API){
        $gravedadEspecifica = 141.5 / (131.5 + $API);
        $libra = ($PieCubico * $gravedadEspecifica * $DensidadAgua);

        return $libra;
    }      
	
	public function ConvertirLiquidoLibra_PieCubico($Libra, $DensidadAgua,$API){
        $gravedadEspecifica = 141.5 / (131.5 + $API);
        $pieCubico = $Libra / ($gravedadEspecifica * $DensidadAgua);

        return $pieCubico;
    }
	
	public function ConvertirGasPieCubico_Libra($PieCubico,$DensidadAire,$GravedadEspecifica){
        $libra = $PieCubico * $GravedadEspecifica * $DensidadAire;

        return $libra;
	}
}

class Prefijos{
    public function ConvertirKilo_Unidad($Kilo){
        $unidad = $Kilo * 1000;

        return $unidad;
    }

    public function ConvertirUnidad_Kilo($Unidad){
        $kilo = $Unidad / 1000;

        return $kilo;
    }

    public function ConvertirUnidad_Giga($Unidad){
        $giga = $Unidad / 1000000;

        return $giga;
    }

    public function ConvertirGiga_Unidad($Giga){
        $unidad = $Giga * 1000000;

        return $unidad;
    }
}

class Porcentaje{
    public function ConvertirPorcentaje_Fraccion($Porcentaje){
        $fraccion = $Porcentaje / 100;

        return $fraccion;
    }

    public function ConvertirFraccion_Porcentaje($Fraccion){
        $porcentaje = $Fraccion * 100;

        return $porcentaje;
    }

    public function ConvertirPPM_Fraccion($PPM){
        $fraccion = $PPM / 10000;

        return $fraccion;
    }
}

class Moneda{
    public function ConvertirBolivar_Dolar($Bolivar,$Cambio){
        $dolar = $Bolivar / $Cambio;

        return $dolar;
    }
}

class Tiempo{
    public function ConvertirAno_Dia($Ano){
        $dia = $Ano * 365;

        return $dia;
    }

    public function ConvertirInversoAno_Dia($Ano){
        $dia = $Ano / 365;

        return $dia;
    }
}
?>