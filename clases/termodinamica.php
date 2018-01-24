<?php
include "matematica.php";

define("pesoMolecularO2",32);
define("pesoMolecularCO",28.01);
define("pesoMolecularSO2",64.065);
define("pesoMolecularNO",30.01);

class Calor{
    public function Calcular_3($Constante,$FlujoMasico,$DiferencialEntalpia){
		$Calor = $Constante * $FlujoMasico * $DiferencialEntalpia;

        return $Calor;
    }

    public function Calcular_4($Constante,$FlujMasico,$Cp,$DiferencialTemperatura){
        $Calor = $Constante * $FlujMasico * $Cp * $DiferencialTemperatura;
        return $Calor;
    }

    public function CalcularElectrico($Constante,$ConsumoElectrico,$Eficiencia){
        $Calor = $Constante * $ConsumoElectrico * $Eficiencia / 100;
        return $Calor;
    }

    public function CalcularRadiacionConveccion($Constante,$CoeficienteTransferenciaCalor,$Area,$DiferenciaTemperatura){
        $Calor = $Constante * $CoeficienteTransferenciaCalor * $Area * $DiferenciaTemperatura;
        return $Calor;
    }
}
// FIN Calor

class Entalpia{
    public $temperaturaAguaSaturacion = array();
	public $presionAguaSaturacion = array();
	public $entalpiaVaporAguaSaturacion = array();
	public $entalpiaLiquidoAguaSaturacion = array(); //As Double
	public $interpolacion;  //matematica.php
	
	public $tpEntalpia; 	
	public $TablaEntalpia;
    public $n = 333;

	public $ParametrosPresion = 1;
	public $ParametrosTemperatura = 2;
    
	public function Entalpia(){
            $this->interpolacion = new clsInterpolacion();  //matematica.php
            
            $this->temperaturaAguaSaturacion = $this->LlenarTemperaturaAguaSaturacion();
            
            // Presión agua saturación
            $this->presionAguaSaturacion = $this->LlenarPresionAguaSaturacion();
            // Entalpia del liquido agua saturacion
            $this->entalpiaLiquidoAguaSaturacion = $this->LlenarEntalpiaLiquidoAguaSaturacion();
            //' Entalpia del vapor agua saturacion
            $this->entalpiaVaporAguaSaturacion = $this->LlenarEntalpiaVaporAguaSaturacion();
            //' Carga de la tabla de Vapor de Agua (temperatura y entalp�as)
            $this->llenarEntalpia_tablas();
    }
    
	public function CalcularAguaSaturacion($Parametro,$Composicion,$TipoParametro){
        if ($TipoParametro == $this->ParametrosTemperatura){
            $entalpiaVapor = $this->interpolacion->Spline($this->temperaturaAguaSaturacion, $this->entalpiaVaporAguaSaturacion, $Parametro);
            $entalpiaLiquido = $this->interpolacion->Spline($this->temperaturaAguaSaturacion, $this->entalpiaLiquidoAguaSaturacion, $Parametro);
        }else{
            $entalpiaVapor = $this->interpolacion->Spline($this->presionAguaSaturacion, $this->entalpiaVaporAguaSaturacion, $Parametro);
            $entalpiaLiquido = $this->interpolacion->Spline($this->presionAguaSaturacion, $this->entalpiaLiquidoAguaSaturacion, $Parametro);
        }
        $entalpiaAguaSaturacion = (1 - $Composicion) * $entalpiaLiquido + $Composicion * $entalpiaVapor;

        return $entalpiaAguaSaturacion;
    }

    public function CalcularAguaVaporTabla($Temperatura,$Presion){
        $contador = 1; 
        
		$entalpiaVapor[] = 0;
		
        foreach($this->TablaEntalpia as $value){
            $entalpiaVapor[] = $this->interpolacion->Spline($value["Temperatura"],$value["Entalpia"], $Temperatura);
        }

        $presionAguaVapor[] = 0;
        for($contador=150;$contador<=500;$contador=$contador+5){
            $presionAguaVapor[] = $contador;
        }

        for ($contador = 510;$contador<=750;$contador=$contador+10){
            $presionAguaVapor[] = $contador;
        }

        $presionAguaVapor[] = 775;
        $presionAguaVapor[] = 800;

        $entalpiaAguaVapor = $this->interpolacion->Spline($presionAguaVapor, $entalpiaVapor, $Presion);

        return $entalpiaAguaVapor;
    }

    public function CalcularAguaVapor($Temperatura,$TemperaturaReferencia){
        $pesoMolecularAgua=18.015;

        $Temperatura = ($Temperatura - 32) * 5 / 9 + 273.15;
        $TemperaturaReferencia = ($TemperaturaReferencia - 32) * 5 / 9 + 273.15;

        $entalpiaAguaVapor = (8.22 * ($Temperatura - $TemperaturaReferencia) + 0.000075 * (pow($Temperatura,2) - pow($TemperaturaReferencia,2)) + 0.000000446666666666667 * (pow($Temperatura,3) - pow($TemperaturaReferencia,3))) * 0.00396832 / ($pesoMolecularAgua * 0.0022046226);

        return $entalpiaAguaVapor;
    }

    public function CalcularAireSeco($Temperatura){
        $entalpiaAireSeco = 0.0024 * $Temperatura;

        return $entalpiaAireSeco;
    }

    public function CalcularAire($Temperatura){
        $entalpiaAire = 0.2613 * $Temperatura - 22;
        return $entalpiaAire;
    }

    public function CalcularO2($Temperatura,$TemperaturaReferencia){
        $Temperatura = ($Temperatura - 32) * 5 / 9 + 273.15;
        $TemperaturaReferencia = ($TemperaturaReferencia - 32) * 5 / 9 + 273.15;

        $entalpiaO2 = (8.27 * ($Temperatura - $TemperaturaReferencia) + 0.000233 / 2 * (pow($Temperatura,2) - pow($TemperaturaReferencia,2)) + 187700 * ((1 / $Temperatura) - (1 / $TemperaturaReferencia))) * 0.00396832 / (pesoMolecularO2 * 0.0022046226);

        return $entalpiaO2;
    }

    public function CalcularCO($Temperatura,$TemperaturaReferencia){
        $Temperatura = ($Temperatura - 32) * 5 / 9 + 273.15;
        $TemperaturaReferencia = ($TemperaturaReferencia - 32) * 5 / 9 + 273.15;
        $entalpiaCO = (6.6 * ($Temperatura - $TemperaturaReferencia) + 0.0006 * (pow($Temperatura,2) - pow($TemperaturaReferencia,2))) * 0.00396832 / (pesoMolecularCO * 0.0022046226);

        return $entalpiaCO;
    }

    public function CalcularCO2($Temperatura,$TemperaturaReferencia){
        $pesoMolecularCO2=44.01;
        $Temperatura = ($Temperatura - 32) * 5 / 9 + 273.15;
        $TemperaturaReferencia = ($TemperaturaReferencia - 32) * 5 / 9 + 273.15;

        $entalpiaCO2 = (10.34 * ($Temperatura - $TemperaturaReferencia) + 0.00137 * (pow($Temperatura,2) - pow($TemperaturaReferencia,2)) + 199500 * ((1 / $Temperatura) - (1 / $TemperaturaReferencia))) * 0.00396832 / ($pesoMolecularCO2 * 0.0022046226);

        return $entalpiaCO2;
    }

    public function CalcularSO2($Temperatura,$TemperaturaReferencia){
        $Temperatura = ($Temperatura - 32) * 5 / 9 + 273.15;
        $TemperaturaReferencia = ($TemperaturaReferencia - 32) * 5 / 9 + 273.15;

        $entalpiaSO2 = (7.7 * ($Temperatura - $TemperaturaReferencia) + 0.0053 / 2 * (pow($Temperatura,2) - pow($TemperaturaReferencia,2)) - 0.00000083 / 3 * (pow($Temperatura,3) - pow($TemperaturaReferencia,3))) * 0.00396832 / (pesoMolecularSO2 * 0.0022046226);

        return $entalpiaSO2;
    }

    public function CalcularN2($Temperatura,$TemperaturaReferencia){
		$pesoMolecularN2 = 28.014;

		$Temperatura = ((($Temperatura - 32) * 5) / 9) + 273.15;

		$TemperaturaReferencia = ((($TemperaturaReferencia - 32) * 5) / 9) + 273.15;

		$entalpiaN2 = ((6.5 * ($Temperatura - $TemperaturaReferencia) + 0.0005 * (pow($Temperatura,2) - pow($TemperaturaReferencia,2))) * 0.00396832) / ($pesoMolecularN2 * 0.0022046226);

		return $entalpiaN2;
    }

    public function CalcularNO($Temperatura,$TemperaturaReferencia){
        $Temperatura = ($Temperatura - 32) * 5 / 9 + 273.15;
        $TemperaturaReferencia = ($TemperaturaReferencia - 32) * 5 / 9 + 273.15;

        $entalpiaNO = (8.05 * ($Temperatura - $TemperaturaReferencia) + 0.000233 / 2 * (pow($Temperatura,2) - pow($TemperaturaReferencia,2)) + 156300 * ((1 / $Temperatura) - (1 / $TemperaturaReferencia))) * 0.00396832 / (pesoMolecularNO * 0.0022046226);

        return $entalpiaNO;
    }

    //Llena los valores de las temperaturas de la tabla de saturaci�n del Agua
    private function LlenarTemperaturaAguaSaturacion(){
		$i = 0;
        $temperatura[1] = 32.018;
        
        for ($i=2;$i<=169;$i++){
            $temperatura[$i] = $i + 31;
        }
        for ($i=170;$i<=269;$i++){
            $temperatura[$i] = 200 + 2 * ($i - 169);
        }

        for ($i=270;$i<=329;$i++){
            $temperatura[$i] = 400 + 5 * ($i - 269);
        }

        for ($i=330;$i<=331;$i++){
            $temperatura[$i] = 700 + 2 * ($i - 329);
        }
        
        $temperatura[$this->n - 1] = 705;
        $temperatura[$this->n] = 705.44;
        
        return $temperatura;
    }

    // Llena los valores de las presiones de agua de la tabla de saturaci�n del Agua
    private function LlenarPresionAguaSaturacion(){ //As Array
        $presion[1] = 0.0886 ; $presion[2] = 0.09223 ; $presion[3] = 0.09601;
        $presion[4] = 0.09992 ; $presion[5] = 0.10397 ; $presion[6] = 0.10816 ; $presion[7] = 0.1125 ; $presion[8] = 0.117;
        $presion[9] = 0.12166 ; $presion[10] = 0.12648 ; $presion[11] = 0.13146 ; $presion[12] = 0.13662 ; $presion[13] = 0.14196;
        $presion[14] = 0.14748 ; $presion[15] = 0.15319 ; $presion[16] = 0.15909 ; $presion[17] = 0.1652 ; $presion[18] = 0.17151;
        $presion[19] = 0.17803 ; $presion[20] = 0.18477 ; $presion[21] = 0.19173 ; $presion[22] = 0.19892 ; $presion[23] = 0.20635;
        $presion[24] = 0.214 ; $presion[25] = 0.2219 ; $presion[26] = 0.2301 ; $presion[27] = 0.2386 ; $presion[28] = 0.2473;
        $presion[29] = 0.2563 ; $presion[30] = 0.2655 ; $presion[31] = 0.2751 ; $presion[32] = 0.285 ; $presion[33] = 0.2952;
        $presion[34] = 0.3057 ; $presion[35] = 0.3165 ; $presion[36] = 0.3276 ; $presion[37] = 0.3391 ; $presion[38] = 0.351;
        $presion[39] = 0.3632 ; $presion[40] = 0.3758 ; $presion[41] = 0.3887 ; $presion[42] = 0.4021 ; $presion[43] = 0.4158;
        $presion[44] = 0.43 ; $presion[45] = 0.4446 ; $presion[46] = 0.4596 ; $presion[47] = 0.475 ; $presion[48] = 0.4909;
        $presion[49] = 0.5073 ; $presion[50] = 0.5241 ; $presion[51] = 0.5414 ; $presion[52] = 0.5593 ; $presion[53] = 0.5776;
        $presion[54] = 0.5964 ; $presion[55] = 0.6158 ; $presion[56] = 0.6357 ; $presion[57] = 0.6562 ; $presion[58] = 0.6772;

        $presion[59] = 0.6988 ; $presion[60] = 0.7211 ; $presion[61] = 0.7439 ; $presion[62] = 0.7674 ; $presion[63] = 0.7914;
        $presion[64] = 0.8162 ; $presion[65] = 0.8416 ; $presion[66] = 0.8677 ; $presion[67] = 0.8945 ; $presion[68] = 0.922;
        $presion[69] = 0.9503 ; $presion[70] = 0.9792 ; $presion[71] = 1.009 ; $presion[72] = 1.0395 ; $presion[73] = 1.0708;
        $presion[74] = 1.1029 ; $presion[75] = 1.1359 ; $presion[76] = 1.1697 ; $presion[77] = 1.2044 ; $presion[78] = 1.2399;
        $presion[79] = 1.2763 ; $presion[80] = 1.3137 ; $presion[81] = 1.352 ; $presion[82] = 1.3913 ; $presion[83] = 1.4315;
        $presion[84] = 1.4727 ; $presion[85] = 1.515 ; $presion[86] = 1.5583 ; $presion[87] = 1.6026 ; $presion[88] = 1.648;
        $presion[89] = 1.6945 ; $presion[90] = 1.7422 ; $presion[91] = 1.791 ; $presion[92] = 1.8409 ; $presion[93] = 1.8921;
        $presion[94] = 1.9444 ; $presion[95] = 1.998 ; $presion[96] = 2.0529 ; $presion[97] = 2.109 ; $presion[98] = 2.1664;
        $presion[99] = 2.225 ; $presion[100] = 2.285 ; $presion[101] = 2.347 ; $presion[102] = 2.41 ; $presion[103] = 2.474;
        $presion[104] = 2.54 ; $presion[105] = 2.607 ; $presion[106] = 2.676 ; $presion[107] = 2.746 ; $presion[108] = 2.818;
        $presion[109] = 2.892 ; $presion[110] = 2.967 ; $presion[111] = 3.044 ; $presion[112] = 3.122 ; $presion[113] = 3.203;

        $presion[114] = 3.285 ; $presion[115] = 3.368 ; $presion[116] = 3.454 ; $presion[117] = 3.541 ; $presion[118] = 3.63;
        $presion[119] = 3.722 ; $presion[120] = 3.815 ; $presion[121] = 3.91 ; $presion[122] = 4.007 ; $presion[123] = 4.106;
        $presion[124] = 4.207 ; $presion[125] = 4.31 ; $presion[126] = 4.416 ; $presion[127] = 4.523 ; $presion[128] = 4.633;
        $presion[129] = 4.745 ; $presion[130] = 4.859 ; $presion[131] = 4.976 ; $presion[132] = 5.095 ; $presion[133] = 5.216;
        $presion[134] = 5.34 ; $presion[135] = 5.466 ; $presion[136] = 5.595 ; $presion[137] = 5.726 ; $presion[138] = 5.86;
        $presion[139] = 5.996 ; $presion[140] = 6.136 ; $presion[141] = 6.277 ; $presion[142] = 6.422 ; $presion[143] = 6.569;
        $presion[144] = 6.72 ; $presion[145] = 6.873 ; $presion[146] = 7.029 ; $presion[147] = 7.188 ; $presion[148] = 7.35;
        $presion[149] = 7.515 ; $presion[150] = 7.683 ; $presion[151] = 7.854 ; $presion[152] = 8.029 ; $presion[153] = 8.206;
        $presion[154] = 8.387 ; $presion[155] = 8.572 ; $presion[156] = 8.759 ; $presion[157] = 8.951 ; $presion[158] = 9.145;
        $presion[159] = 9.343 ; $presion[160] = 9.545 ; $presion[161] = 9.75 ; $presion[162] = 9.959 ; $presion[163] = 10.172;
        $presion[164] = 10.388 ; $presion[165] = 10.609 ; $presion[166] = 10.833 ; $presion[167] = 11.061 ; $presion[168] = 11.293;

        $presion[169] = 11.529 ; $presion[170] = 12.014 ; $presion[171] = 12.515 ; $presion[172] = 13.034 ; $presion[173] = 13.57;
        $presion[174] = 14.125 ; $presion[175] = 14.698 ; $presion[176] = 15.291 ; $presion[177] = 15.903 ; $presion[178] = 16.535;
        $presion[179] = 17.188 ; $presion[180] = 17.861 ; $presion[181] = 18.557 ; $presion[182] = 19.275 ; $presion[183] = 20.015;
        $presion[184] = 20.78 ; $presion[185] = 21.57 ; $presion[186] = 22.38 ; $presion[187] = 23.22 ; $presion[188] = 24.08;
        $presion[189] = 24.97 ; $presion[190] = 25.88 ; $presion[191] = 26.82 ; $presion[192] = 27.79 ; $presion[193] = 28.79;
        $presion[194] = 29.82 ; $presion[195] = 30.88 ; $presion[196] = 31.97 ; $presion[197] = 33.09 ; $presion[198] = 34.24;
        $presion[199] = 35.42 ; $presion[200] = 36.64 ; $presion[201] = 37.89 ; $presion[202] = 39.17 ; $presion[203] = 40.49;
        $presion[204] = 41.85 ; $presion[205] = 43.24 ; $presion[206] = 44.67 ; $presion[207] = 46.13 ; $presion[208] = 47.64;
        $presion[209] = 49.18 ; $presion[210] = 50.77 ; $presion[211] = 52.4 ; $presion[212] = 54.07 ; $presion[213] = 55.78;
        $presion[214] = 57.53 ; $presion[215] = 59.33 ; $presion[216] = 61.17 ; $presion[217] = 63.06 ; $presion[218] = 65.0;
        $presion[219] = 66.98 ; $presion[220] = 69.01 ; $presion[221] = 71.09 ; $presion[222] = 73.22 ; $presion[223] = 75.4;

        $presion[224] = 77.64 ; $presion[225] = 79.92 ; $presion[226] = 82.26 ; $presion[227] = 84.65 ; $presion[228] = 87.1;
        $presion[229] = 89.6 ; $presion[230] = 92.16 ; $presion[231] = 94.78 ; $presion[232] = 97.46 ; $presion[233] = 100.2;
        $presion[234] = 103.0 ; $presion[235] = 105.86 ; $presion[236] = 108.78 ; $presion[237] = 111.76 ; $presion[238] = 114.82;
        $presion[239] = 117.93 ; $presion[240] = 121.11 ; $presion[241] = 124.36 ; $presion[242] = 127.68 ; $presion[243] = 131.07;
        $presion[244] = 134.53 ; $presion[245] = 138.06 ; $presion[246] = 141.66 ; $presion[247] = 145.34 ; $presion[248] = 149.09;
        $presion[249] = 152.92 ; $presion[250] = 156.82 ; $presion[251] = 160.8 ; $presion[252] = 164.87 ; $presion[253] = 169.01;
        $presion[254] = 173.23 ; $presion[255] = 177.53 ; $presion[256] = 181.92 ; $presion[257] = 186.39 ; $presion[258] = 190.95;
        $presion[259] = 195.6 ; $presion[260] = 200.33 ; $presion[261] = 205.15 ; $presion[262] = 210.06 ; $presion[263] = 215.06;
        $presion[264] = 220.2 ; $presion[265] = 225.3 ; $presion[266] = 230.6 ; $presion[267] = 236.0 ; $presion[268] = 241.5;
        $presion[269] = 247.1 ; $presion[270] = 261.4 ; $presion[271] = 276.5 ; $presion[272] = 292.1 ; $presion[273] = 308.5;
        $presion[274] = 325.6 ; $presion[275] = 343.3 ; $presion[276] = 361.9 ; $presion[277] = 381.2 ; $presion[278] = 401.2;

        $presion[279] = 422.1 ; $presion[280] = 443.8 ; $presion[281] = 466.3 ; $presion[282] = 489.8 ; $presion[283] = 514.1;
        $presion[284] = 539.3 ; $presion[285] = 565.5 ; $presion[286] = 592.6 ; $presion[287] = 620.7 ; $presion[288] = 649.8;
        $presion[289] = 680.0 ; $presion[290] = 711.2 ; $presion[291] = 743.5 ; $presion[292] = 776.9 ; $presion[293] = 811.4;
        $presion[294] = 847.1 ; $presion[295] = 884.0 ; $presion[296] = 922.1 ; $presion[297] = 961.5 ; $presion[298] = 1002.1;
        $presion[299] = 1044.0 ; $presion[300] = 1087.2 ; $presion[301] = 1131.8 ; $presion[302] = 1177.8 ; $presion[303] = 1225.1;
        $presion[304] = 1274.0 ; $presion[305] = 1324.3 ; $presion[306] = 1376.1 ; $presion[307] = 1429.5 ; $presion[308] = 1484.5;
        $presion[309] = 1541.0 ; $presion[310] = 1599.3 ; $presion[311] = 1659.2 ; $presion[312] = 1720.9 ; $presion[313] = 1784.4;
        $presion[314] = 1849.7 ; $presion[315] = 1916.9 ; $presion[316] = 1986.0 ; $presion[317] = 2057.1 ; $presion[318] = 2130.2;
        $presion[319] = 2205.0 ; $presion[320] = 2283.0 ; $presion[321] = 2362.0 ; $presion[322] = 2444.0 ; $presion[323] = 2529.0;
        $presion[324] = 2616.0 ; $presion[325] = 2705.0 ; $presion[326] = 2797.0 ; $presion[327] = 2892.0 ; $presion[328] = 2990.0;
        $presion[329] = 3090.0 ; $presion[330] = 3131.0 ; $presion[331] = 3173.0 ; $presion[332] = 3194.0;
        $presion[333] = 3204.0;

        return $presion;
    }

    // Llena los valores de las entalp�as de l�quido de la tabla de saturaci�n del Agua
    private function LlenarEntalpiaLiquidoAguaSaturacion(){
        $entalpia[1] = 0.01 ; $entalpia[2] = 0.99 ; $entalpia[3] = 1.99;
        $entalpia[4] = 3 ; $entalpia[5] = 4.0 ; $entalpia[6] = 5.0 ; $entalpia[7] = 6.01 ; $entalpia[8] = 7.01;
        $entalpia[9] = 8.02 ; $entalpia[10] = 9.02 ; $entalpia[11] = 10.03 ; $entalpia[12] = 11.03 ; $entalpia[13] = 12.03;
        $entalpia[14] = 13.04 ; $entalpia[15] = 14.04 ; $entalpia[16] = 15.05 ; $entalpia[17] = 16.05 ; $entalpia[18] = 17.05;
        $entalpia[19] = 18.06 ; $entalpia[20] = 19.06 ; $entalpia[21] = 20.07 ; $entalpia[22] = 21.07 ; $entalpia[23] = 22.07;
        $entalpia[24] = 23.07 ; $entalpia[25] = 24.08 ; $entalpia[26] = 25.08 ; $entalpia[27] = 26.08 ; $entalpia[28] = 27.08;
        $entalpia[29] = 28.08 ; $entalpia[30] = 29.09 ; $entalpia[31] = 30.09 ; $entalpia[32] = 31.09 ; $entalpia[33] = 32.09;
        $entalpia[34] = 33.09 ; $entalpia[35] = 34.09 ; $entalpia[36] = 35.09 ; $entalpia[37] = 36.09 ; $entalpia[38] = 37.09;
        $entalpia[39] = 38.09 ; $entalpia[40] = 39.09 ; $entalpia[41] = 40.09 ; $entalpia[42] = 41.09 ; $entalpia[43] = 42.09;
        $entalpia[44] = 43.09 ; $entalpia[45] = 44.09 ; $entalpia[46] = 45.09 ; $entalpia[47] = 46.09 ; $entalpia[48] = 47.09;
        $entalpia[49] = 48.09 ; $entalpia[50] = 49.09 ; $entalpia[51] = 50.08 ; $entalpia[52] = 51.08 ; $entalpia[53] = 52.08;
        $entalpia[54] = 53.08 ; $entalpia[55] = 54.08 ; $entalpia[56] = 55.08 ; $entalpia[57] = 56.07 ; $entalpia[58] = 57.07;
        $entalpia[59] = 58.07 ; $entalpia[60] = 59.06 ; $entalpia[61] = 60.06 ; $entalpia[62] = 61.06 ; $entalpia[63] = 62.06;
        $entalpia[64] = 63.06 ; $entalpia[65] = 64.05 ; $entalpia[66] = 65.05 ; $entalpia[67] = 66.05 ; $entalpia[68] = 67.05;
        $entalpia[69] = 68.05 ; $entalpia[70] = 69.04 ; $entalpia[71] = 70.04 ; $entalpia[72] = 71.04 ; $entalpia[73] = 72.04;
        $entalpia[74] = 73.03 ; $entalpia[75] = 74.03 ; $entalpia[76] = 75.03 ; $entalpia[77] = 76.02 ; $entalpia[78] = 77.02;
        $entalpia[79] = 78.02 ; $entalpia[80] = 79.01 ; $entalpia[81] = 80.01 ; $entalpia[82] = 81.01 ; $entalpia[83] = 82.01;
        $entalpia[84] = 83.01 ; $entalpia[85] = 84.01 ; $entalpia[86] = 85.01 ; $entalpia[87] = 86.0 ; $entalpia[88] = 87.0;
        $entalpia[89] = 88.0 ; $entalpia[90] = 89.0 ; $entalpia[91] = 89.99 ; $entalpia[92] = 90.99 ; $entalpia[93] = 91.99;
        $entalpia[94] = 92.99 ; $entalpia[95] = 93.99 ; $entalpia[96] = 94.98 ; $entalpia[97] = 95.98 ; $entalpia[98] = 96.98;
        $entalpia[99] = 97.98 ; $entalpia[100] = 98.98 ; $entalpia[101] = 99.97 ; $entalpia[102] = 100.97 ; $entalpia[103] = 101.97;
        $entalpia[104] = 102.97 ; $entalpia[105] = 103.97 ; $entalpia[106] = 104.97 ; $entalpia[107] = 105.97 ; $entalpia[108] = 106.97;
        $entalpia[109] = 107.96 ; $entalpia[110] = 108.96 ; $entalpia[111] = 109.96 ; $entalpia[112] = 110.96 ; $entalpia[113] = 111.96;
        $entalpia[114] = 112.96 ; $entalpia[115] = 113.96 ; $entalpia[116] = 114.96 ; $entalpia[117] = 115.96 ; $entalpia[118] = 116.96;
        $entalpia[119] = 117.96 ; $entalpia[120] = 118.96 ; $entalpia[121] = 119.96 ; $entalpia[122] = 120.96 ; $entalpia[123] = 121.96;
        $entalpia[124] = 122.96 ; $entalpia[125] = 123.96 ; $entalpia[126] = 124.96 ; $entalpia[127] = 125.96 ; $entalpia[128] = 126.96;
        $entalpia[129] = 127.96 ; $entalpia[130] = 128.96 ; $entalpia[131] = 129.96 ; $entalpia[132] = 130.96 ; $entalpia[133] = 131.96;
        $entalpia[134] = 132.96 ; $entalpia[135] = 133.96 ; $entalpia[136] = 134.97 ; $entalpia[137] = 135.97 ; $entalpia[138] = 136.97;
        $entalpia[139] = 137.97 ; $entalpia[140] = 138.97 ; $entalpia[141] = 139.97 ; $entalpia[142] = 140.97 ; $entalpia[143] = 141.98;
        $entalpia[144] = 142.98 ; $entalpia[145] = 143.98 ; $entalpia[146] = 144.98 ; $entalpia[147] = 145.99 ; $entalpia[148] = 146.99;
        $entalpia[149] = 147.99 ; $entalpia[150] = 149.0 ; $entalpia[151] = 150.0 ; $entalpia[152] = 151.0 ; $entalpia[153] = 152.01;
        $entalpia[154] = 153.01 ; $entalpia[155] = 154.01 ; $entalpia[156] = 155.02 ; $entalpia[157] = 156.02 ; $entalpia[158] = 157.02;
        $entalpia[159] = 158.03 ; $entalpia[160] = 159.03 ; $entalpia[161] = 160.04 ; $entalpia[162] = 161.04 ; $entalpia[163] = 162.04;
        $entalpia[164] = 163.05 ; $entalpia[165] = 164.05 ; $entalpia[166] = 165.06 ; $entalpia[167] = 166.06 ; $entalpia[168] = 167.07;

        $entalpia[169] = 168.07 ; $entalpia[170] = 170.09 ; $entalpia[171] = 172.1 ; $entalpia[172] = 174.11 ; $entalpia[173] = 176.13;
        $entalpia[174] = 178.14 ; $entalpia[175] = 180.16 ; $entalpia[176] = 182.17 ; $entalpia[177] = 184.18 ; $entalpia[178] = 186.2;
        $entalpia[179] = 188.22 ; $entalpia[180] = 190.24 ; $entalpia[181] = 192.26 ; $entalpia[182] = 194.28 ; $entalpia[183] = 196.3;
        $entalpia[184] = 198.32 ; $entalpia[185] = 200.34 ; $entalpia[186] = 202.37 ; $entalpia[187] = 204.39 ; $entalpia[188] = 206.42;
        $entalpia[189] = 208.44 ; $entalpia[190] = 210.47 ; $entalpia[191] = 212.49 ; $entalpia[192] = 214.52 ; $entalpia[193] = 216.55;
        $entalpia[194] = 218.59 ; $entalpia[195] = 220.62 ; $entalpia[196] = 222.65 ; $entalpia[197] = 224.68 ; $entalpia[198] = 226.72;
        $entalpia[199] = 228.76 ; $entalpia[200] = 230.79 ; $entalpia[201] = 232.83 ; $entalpia[202] = 234.87 ; $entalpia[203] = 236.91;
        $entalpia[204] = 238.95 ; $entalpia[205] = 241.0 ; $entalpia[206] = 243.04 ; $entalpia[207] = 245.08 ; $entalpia[208] = 247.13;
        $entalpia[209] = 249.18 ; $entalpia[210] = 251.23 ; $entalpia[211] = 253.28 ; $entalpia[212] = 255.33 ; $entalpia[213] = 257.38;
        $entalpia[214] = 259.44 ; $entalpia[215] = 261.5 ; $entalpia[216] = 263.55 ; $entalpia[217] = 265.61 ; $entalpia[218] = 267.67;
        $entalpia[219] = 269.73 ; $entalpia[220] = 271.79 ; $entalpia[221] = 273.86 ; $entalpia[222] = 275.93 ; $entalpia[223] = 278.0;
        $entalpia[224] = 280.06 ; $entalpia[225] = 282.13 ; $entalpia[226] = 284.21 ; $entalpia[227] = 286.28 ; $entalpia[228] = 288.36;
        $entalpia[229] = 290.43 ; $entalpia[230] = 292.51 ; $entalpia[231] = 294.59 ; $entalpia[232] = 296.67 ; $entalpia[233] = 298.76;
        $entalpia[234] = 300.84 ; $entalpia[235] = 302.93 ; $entalpia[236] = 305.02 ; $entalpia[237] = 307.11 ; $entalpia[238] = 309.21;
        $entalpia[239] = 311.3 ; $entalpia[240] = 313.39 ; $entalpia[241] = 315.49 ; $entalpia[242] = 317.59 ; $entalpia[243] = 319.7;
        $entalpia[244] = 321.8 ; $entalpia[245] = 323.91 ; $entalpia[246] = 326.02 ; $entalpia[247] = 328.13 ; $entalpia[248] = 330.24;
        $entalpia[249] = 332.35 ; $entalpia[250] = 334.47 ; $entalpia[251] = 336.59 ; $entalpia[252] = 338.71 ; $entalpia[253] = 340.83;
        $entalpia[254] = 342.96 ; $entalpia[255] = 345.08 ; $entalpia[256] = 347.21 ; $entalpia[257] = 349.35 ; $entalpia[258] = 351.48;
        $entalpia[259] = 353.62 ; $entalpia[260] = 355.76 ; $entalpia[261] = 357.9 ; $entalpia[262] = 360.04 ; $entalpia[263] = 362.19;
        $entalpia[264] = 364.34 ; $entalpia[265] = 366.49 ; $entalpia[266] = 368.64 ; $entalpia[267] = 370.8 ; $entalpia[268] = 372.96;

        $entalpia[269] = 375.12 ; $entalpia[270] = 380.53 ; $entalpia[271] = 385.97 ; $entalpia[272] = 391.42 ; $entalpia[273] = 396.89;
        $entalpia[274] = 402.38 ; $entalpia[275] = 407.89 ; $entalpia[276] = 413.42 ; $entalpia[277] = 418.98 ; $entalpia[278] = 424.55;
        $entalpia[279] = 430.2 ; $entalpia[280] = 435.8 ; $entalpia[281] = 441.4 ; $entalpia[282] = 447.1 ; $entalpia[283] = 452.8;
        $entalpia[284] = 458.5 ; $entalpia[285] = 464.3 ; $entalpia[286] = 470.1 ; $entalpia[287] = 475.9 ; $entalpia[288] = 481.8;
        $entalpia[289] = 487.7 ; $entalpia[290] = 493.6 ; $entalpia[291] = 499.6 ; $entalpia[292] = 505.6 ; $entalpia[293] = 511.7;
        $entalpia[294] = 517.8 ; $entalpia[295] = 523.9 ; $entalpia[296] = 530.1 ; $entalpia[297] = 536.4 ; $entalpia[298] = 542.7;
        $entalpia[299] = 549.1 ; $entalpia[300] = 555.5 ; $entalpia[301] = 562.0 ; $entalpia[302] = 568.5 ; $entalpia[303] = 575.2;
        $entalpia[304] = 581.9 ; $entalpia[305] = 588.6 ; $entalpia[306] = 595.5 ; $entalpia[307] = 602.5 ; $entalpia[308] = 609.5;
        $entalpia[309] = 616.7 ; $entalpia[310] = 623.9 ; $entalpia[311] = 631.3 ; $entalpia[312] = 638.8 ; $entalpia[313] = 646.4;
        $entalpia[314] = 654.2 ; $entalpia[315] = 662.1 ; $entalpia[316] = 670.3 ; $entalpia[317] = 678.6 ; $entalpia[318] = 687.1;
        $entalpia[319] = 695.9 ; $entalpia[320] = 705.0 ; $entalpia[321] = 714.4 ; $entalpia[322] = 724.1 ; $entalpia[323] = 734.4;
        $entalpia[324] = 745.3 ; $entalpia[325] = 756.9 ; $entalpia[326] = 769.6 ; $entalpia[327] = 783.8 ; $entalpia[328] = 800.5;

        $entalpia[329] = 822.7 ; $entalpia[330] = 835.4 ; $entalpia[331] = 854.9 ; $entalpia[332] = 875.5;

        $entalpia[333] = 902.5;

        return $entalpia;
    }

    //Llena los valores de las entalp�as de vapor de la tabla de saturaci�n del Agua
    private function LlenarEntalpiaVaporAguaSaturacion(){
        $entalpia[1] = 1075.4 ; $entalpia[2] = 1075.8 ; $entalpia[3] = 1076.3;
        $entalpia[4] = 1076.7 ; $entalpia[5] = 1077.1 ; $entalpia[6] = 1077.6 ; $entalpia[7] = 1078.0 ; $entalpia[8] = 1078.5;
        $entalpia[9] = 1078.9 ; $entalpia[10] = 1079.3 ; $entalpia[11] = 1079.8 ; $entalpia[12] = 1080.2 ; $entalpia[13] = 1080.7;
        $entalpia[14] = 1081.1 ; $entalpia[15] = 1081.5 ; $entalpia[16] = 1082.0 ; $entalpia[17] = 1082.4 ; $entalpia[18] = 1082.9;
        $entalpia[19] = 1083.3 ; $entalpia[20] = 1083.7 ; $entalpia[21] = 1084.2 ; $entalpia[22] = 1084.6 ; $entalpia[23] = 1085.1;
        $entalpia[24] = 1085.5 ; $entalpia[25] = 1085.9 ; $entalpia[26] = 1086.4 ; $entalpia[27] = 1086.8 ; $entalpia[28] = 1087.2;
        $entalpia[29] = 1087.7 ; $entalpia[30] = 1088.1 ; $entalpia[31] = 1088.6 ; $entalpia[32] = 1089.0 ; $entalpia[33] = 1089.4;
        $entalpia[34] = 1089.9 ; $entalpia[35] = 1090.3 ; $entalpia[36] = 1090.7 ; $entalpia[37] = 1091.2 ; $entalpia[38] = 1091.6;
        $entalpia[39] = 1092.0 ; $entalpia[40] = 1092.5 ; $entalpia[41] = 1092.9 ; $entalpia[42] = 1093.4 ; $entalpia[43] = 1093.8;
        $entalpia[44] = 1094.2 ; $entalpia[45] = 1094.7 ; $entalpia[46] = 1095.1 ; $entalpia[47] = 1095.5 ; $entalpia[48] = 1096.0;
        $entalpia[49] = 1096.4 ; $entalpia[50] = 1096.8 ; $entalpia[51] = 1097.3 ; $entalpia[52] = 1097.7 ; $entalpia[53] = 1098.1;
        $entalpia[54] = 1098.6 ; $entalpia[55] = 1099.0 ; $entalpia[56] = 1099.4 ; $entalpia[57] = 1099.9 ; $entalpia[58] = 1100.3;
        $entalpia[59] = 1100.7 ; $entalpia[60] = 1101.2 ; $entalpia[61] = 1101.6 ; $entalpia[62] = 1102.0 ; $entalpia[63] = 1102.4;
        $entalpia[64] = 1102.9 ; $entalpia[65] = 1103.3 ; $entalpia[66] = 1103.7 ; $entalpia[67] = 1104.2 ; $entalpia[68] = 1104.6;
        $entalpia[69] = 1105.0 ; $entalpia[70] = 1105.5 ; $entalpia[71] = 1105.9 ; $entalpia[72] = 1106.3 ; $entalpia[73] = 1106.7;
        $entalpia[74] = 1107.2 ; $entalpia[75] = 1107.6 ; $entalpia[76] = 1108.0 ; $entalpia[77] = 1108.4 ; $entalpia[78] = 1108.9;
        $entalpia[79] = 1109.3 ; $entalpia[80] = 1109.7 ; $entalpia[81] = 1110.2 ; $entalpia[82] = 1110.6 ; $entalpia[83] = 1111.0;
        $entalpia[84] = 1111.4 ; $entalpia[85] = 1111.9 ; $entalpia[86] = 1112.3 ; $entalpia[87] = 1112.7 ; $entalpia[88] = 1113.1;
        $entalpia[89] = 1113.5 ; $entalpia[90] = 1114.0 ; $entalpia[91] = 1114.4 ; $entalpia[92] = 1114.8 ; $entalpia[93] = 1115.2;
        $entalpia[94] = 1115.7 ; $entalpia[95] = 1116.1 ; $entalpia[96] = 1116.5 ; $entalpia[97] = 1116.9 ; $entalpia[98] = 1117.3;
        $entalpia[99] = 1117.8 ; $entalpia[100] = 1118.2 ; $entalpia[101] = 1118.6 ; $entalpia[102] = 1119.0 ; $entalpia[103] = 1119.4;
        $entalpia[104] = 1119.8 ; $entalpia[105] = 1120.3 ; $entalpia[106] = 1120.7 ; $entalpia[107] = 1121.1 ; $entalpia[108] = 1121.5;
        $entalpia[109] = 1121.9 ; $entalpia[110] = 1122.3 ; $entalpia[111] = 1122.8 ; $entalpia[112] = 1123.2 ; $entalpia[113] = 1123.6;
        $entalpia[114] = 1124.0 ; $entalpia[115] = 1124.4 ; $entalpia[116] = 1124.8 ; $entalpia[117] = 1125.2 ; $entalpia[118] = 1125.6;
        $entalpia[119] = 1126.1 ; $entalpia[120] = 1126.5 ; $entalpia[121] = 1126.9 ; $entalpia[122] = 1127.3 ; $entalpia[123] = 1127.7;
        $entalpia[124] = 1128.1 ; $entalpia[125] = 1128.5 ; $entalpia[126] = 1128.9 ; $entalpia[127] = 1129.3 ; $entalpia[128] = 1129.7;
        $entalpia[129] = 1130.1 ; $entalpia[130] = 1130.5 ; $entalpia[131] = 1131.0 ; $entalpia[132] = 1131.4 ; $entalpia[133] = 1131.8;
        $entalpia[134] = 1132.2 ; $entalpia[135] = 1132.6 ; $entalpia[136] = 1133.0 ; $entalpia[137] = 1133.4 ; $entalpia[138] = 1133.8;
        $entalpia[139] = 1134.2 ; $entalpia[140] = 1134.6 ; $entalpia[141] = 1135.0 ; $entalpia[142] = 1135.4 ; $entalpia[143] = 1135.8;
        $entalpia[144] = 1136.2 ; $entalpia[145] = 1136.6 ; $entalpia[146] = 1137.0 ; $entalpia[147] = 1137.4 ; $entalpia[148] = 1137.8;
        $entalpia[149] = 1138.2 ; $entalpia[150] = 1138.6 ; $entalpia[151] = 1139.0 ; $entalpia[152] = 1139.3 ; $entalpia[153] = 1139.7;
        $entalpia[154] = 1140.1 ; $entalpia[155] = 1140.5 ; $entalpia[156] = 1140.9 ; $entalpia[157] = 1141.3 ; $entalpia[158] = 1141.7;
        $entalpia[159] = 1142.1 ; $entalpia[160] = 1142.5 ; $entalpia[161] = 1142.9 ; $entalpia[162] = 1143.3 ; $entalpia[163] = 1143.6;
        $entalpia[164] = 1144.0 ; $entalpia[165] = 1144.4 ; $entalpia[166] = 1144.8 ; $entalpia[167] = 1145.2 ; $entalpia[168] = 1145.6;

        $entalpia[169] = 1145.9 ; $entalpia[170] = 1146.7 ; $entalpia[171] = 1147.5 ; $entalpia[172] = 1148.2 ; $entalpia[173] = 1149.0;
        $entalpia[174] = 1149.7 ; $entalpia[175] = 1150.5 ; $entalpia[176] = 1151.2 ; $entalpia[177] = 1152.0 ; $entalpia[178] = 1152.7;
        $entalpia[179] = 1153.5 ; $entalpia[180] = 1154.2 ; $entalpia[181] = 1154.9 ; $entalpia[182] = 1155.7 ; $entalpia[183] = 1156.4;
        $entalpia[184] = 1157.1 ; $entalpia[185] = 1157.9 ; $entalpia[186] = 1158.6 ; $entalpia[187] = 1159.3 ; $entalpia[188] = 1160.0;
        $entalpia[189] = 1160.7 ; $entalpia[190] = 1161.4 ; $entalpia[191] = 1162.1 ; $entalpia[192] = 1162.8 ; $entalpia[193] = 1163.5;
        $entalpia[194] = 1164.2 ; $entalpia[195] = 1164.9 ; $entalpia[196] = 1165.6 ; $entalpia[197] = 1166.2 ; $entalpia[198] = 1166.9;
        $entalpia[199] = 1167.6 ; $entalpia[200] = 1168.3 ; $entalpia[201] = 1168.9 ; $entalpia[202] = 1169.6 ; $entalpia[203] = 1170.2;
        $entalpia[204] = 1170.9 ; $entalpia[205] = 1171.6 ; $entalpia[206] = 1172.2 ; $entalpia[207] = 1172.8 ; $entalpia[208] = 1173.5;
        $entalpia[209] = 1174.1 ; $entalpia[210] = 1174.7 ; $entalpia[211] = 1175.4 ; $entalpia[212] = 1176.0 ; $entalpia[213] = 1176.6;
        $entalpia[214] = 1177.2 ; $entalpia[215] = 1177.8 ; $entalpia[216] = 1178.4 ; $entalpia[217] = 1179.0 ; $entalpia[218] = 1179.6;
        $entalpia[219] = 1180.2 ; $entalpia[220] = 1180.8 ; $entalpia[221] = 1181.3 ; $entalpia[222] = 1181.9 ; $entalpia[223] = 1182.5;
        $entalpia[224] = 1183.0 ; $entalpia[225] = 1183.6 ; $entalpia[226] = 1184.1 ; $entalpia[227] = 1184.7 ; $entalpia[228] = 1185.2;
        $entalpia[229] = 1185.8 ; $entalpia[230] = 1186.3 ; $entalpia[231] = 1186.8 ; $entalpia[232] = 1187.3 ; $entalpia[233] = 1187.9;
        $entalpia[234] = 1188.4 ; $entalpia[235] = 1188.9 ; $entalpia[236] = 1189.4 ; $entalpia[237] = 1189.9 ; $entalpia[238] = 1190.3;
        $entalpia[239] = 1190.8 ; $entalpia[240] = 1191.3 ; $entalpia[241] = 1191.7 ; $entalpia[242] = 1192.2 ; $entalpia[243] = 1192.7;
        $entalpia[244] = 1193.1 ; $entalpia[245] = 1193.5 ; $entalpia[246] = 1194.0 ; $entalpia[247] = 1194.4 ; $entalpia[248] = 1194.8;
        $entalpia[249] = 1195.2 ; $entalpia[250] = 1195.6 ; $entalpia[251] = 1196.0 ; $entalpia[252] = 1196.4 ; $entalpia[253] = 1196.8;
        $entalpia[254] = 1197.2 ; $entalpia[255] = 1197.6 ; $entalpia[256] = 1197.9 ; $entalpia[257] = 1198.3 ; $entalpia[258] = 1198.6;
        $entalpia[259] = 1199.0 ; $entalpia[260] = 1199.3 ; $entalpia[261] = 1199.6 ; $entalpia[262] = 1200.0 ; $entalpia[263] = 1200.3;
        $entalpia[264] = 1200.6 ; $entalpia[265] = 1200.9 ; $entalpia[266] = 1201.1 ; $entalpia[267] = 1201.4 ; $entalpia[268] = 1201.7;

        $entalpia[269] = 1202.0 ; $entalpia[270] = 1202.6 ; $entalpia[271] = 1203.1 ; $entalpia[272] = 1203.6 ; $entalpia[273] = 1204.1;
        $entalpia[274] = 1204.5 ; $entalpia[275] = 1204.8 ; $entalpia[276] = 1205.1 ; $entalpia[277] = 1205.3 ; $entalpia[278] = 1205.5;
        $entalpia[279] = 1205.6 ; $entalpia[280] = 1205.6 ; $entalpia[281] = 1205.5 ; $entalpia[282] = 1205.4 ; $entalpia[283] = 1205.2;
        $entalpia[284] = 1204.9 ; $entalpia[285] = 1204.6 ; $entalpia[286] = 1204.2 ; $entalpia[287] = 1203.7 ; $entalpia[288] = 1203.1;
        $entalpia[289] = 1202.5 ; $entalpia[290] = 1201.7 ; $entalpia[291] = 1200.9 ; $entalpia[292] = 1200.0 ; $entalpia[293] = 1198.9;
        $entalpia[294] = 1197.8 ; $entalpia[295] = 1196.6 ; $entalpia[296] = 1195.3 ; $entalpia[297] = 1193.8 ; $entalpia[298] = 1192.3;
        $entalpia[299] = 1190.6 ; $entalpia[300] = 1188.9 ; $entalpia[301] = 1187.0 ; $entalpia[302] = 1184.9 ; $entalpia[303] = 1182.8;
        $entalpia[304] = 1180.4 ; $entalpia[305] = 1178.0 ; $entalpia[306] = 1175.3 ; $entalpia[307] = 1172.5 ; $entalpia[308] = 1169.5;
        $entalpia[309] = 1166.4 ; $entalpia[310] = 1163.0 ; $entalpia[311] = 1159.4 ; $entalpia[312] = 1155.5 ; $entalpia[313] = 1151.4;
        $entalpia[314] = 1147.0 ; $entalpia[315] = 1142.4 ; $entalpia[316] = 1137.3 ; $entalpia[317] = 1131.9 ; $entalpia[318] = 1126.1;
        $entalpia[319] = 1119.8 ; $entalpia[320] = 1113.0 ; $entalpia[321] = 1105.5 ; $entalpia[322] = 1097.3 ; $entalpia[323] = 1088.3;
        $entalpia[324] = 1078.2 ; $entalpia[325] = 1066.7 ; $entalpia[326] = 1053.4 ; $entalpia[327] = 1037.7 ; $entalpia[328] = 1017.9;

        $entalpia[329] = 990.2 ; $entalpia[330] = 974.3 ; $entalpia[331] = 950.4 ; $entalpia[332] = 928.6;

        $entalpia[333] = 902.5;

        return $entalpia;
    }

    private function llenarEntalpia_tablas(){
        $this->llenarEntalpia(0, 150, array(0, 1274.1, 1284.5, 1294.9, 1305.2, 1315.5, 1325.7, 1335.9, 1346.1, 1356.2, 1366.4, 1376.6, 1386.7, 1396.9, 1407.1, 1417.2, 1427.5));
        $this->llenarEntalpia(0, 155, array(0, 1273.5, 1284.0, 1294.5, 1304.8, 1315.1, 1325.3, 1335.6, 1345.8, 1355.9, 1366.1, 1376.3, 1386.5, 1396.6, 1406.8, 1417.0, 1427.2));
        $this->llenarEntalpia(0, 160, array(0, 1273.0, 1283.6, 1294.0, 1304.4, 1314.7, 1325.0, 1335.2, 1345.4, 1355.6, 1365.8, 1376.0, 1386.2, 1396.4, 1406.6, 1416.8, 1427.0));
        $this->llenarEntalpia(0, 165, array(0, 1275.5, 1283.1, 1293.6, 1304.0, 1314.3, 1324.6, 1334.9, 1345.1, 1355.3, 1365.5, 1375.7, 1385.9, 1396.1, 1406.4, 1416.6, 1426.8));
        $this->llenarEntalpia(1, 170, array(0, 1272.0, 1277.3, 1282.6, 1287.9, 1293.1, 1298.3, 1303.6, 1308.8, 1313.9, 1319.1, 1324.3, 1334.5, 1344.8, 1355.0, 1365.3, 1375.5, 1385.7, 1395.9, 1406.1, 1416.4, 1426.6));
        $this->llenarEntalpia(1, 175, array(0, 1271.4, 1276.8, 1282.1, 1287.4, 1292.7, 1297.9, 1303.1, 1308.3, 1315.3, 1318.7, 1323.9, 1334.2, 1344.5, 1354.7, 1365.0, 1375.2, 1385.4, 1395.7, 1405.9, 1416.1, 1426.4));
        $this->llenarEntalpia(1, 180, array(0, 1270.9, 1276.3, 1281.6, 1286.9, 1292.2, 1297.5, 1302.7, 1307.9, 1313.1, 1318.3, 1323.5, 1333.9, 1344.1, 1354.4, 1364.7, 1374.9, 1385.2, 1395.4, 1405.7, 1415.9, 1426.2));
        $this->llenarEntalpia(1, 185, array(0, 1270.4, 1275.8, 1281.1, 1286.5, 1291.8, 1297.0, 1302.3, 1307.5, 1312.8, 1318.0, 1323.2, 1333.5, 1343.8, 1354.1, 1364.4, 1374.6, 1384.9, 1395.2, 1405.4, 1415.7, 1426.0));
        $this->llenarEntalpia(1, 190, array(0, 1269.9, 1275.3, 1280.6, 1286.0, 1291.3, 1296.6, 1301.9, 1307.1, 1312.4, 1317.6, 1322.8, 1333.2, 1343.5, 1353.8, 1364.1, 1374.4, 1384.6, 1394.9, 1405.2, 1415.5, 1425.7));
        $this->llenarEntalpia(1, 195, array(0, 1269.3, 1274.8, 1280.2, 1285.5, 1290.9, 1296.2, 1301.5, 1306.7, 1312.0, 1317.2, 1322.4, 1332.8, 1343.2, 1353.5, 1363.8, 1374.1, 1384.4, 1394.7, 1404.9, 1415.2, 1425.5));
        $this->llenarEntalpia(1, 200, array(0, 1268.8, 1274.2, 1279.7, 1285.0, 1290.4, 1295.7, 1301.0, 1306.3, 1311.6, 1316.8, 1322.1, 1332.5, 1342.9, 1353.2, 1363.5, 1373.8, 1384.1, 1394.4, 1404.7, 1415.0, 1425.3));
        $this->llenarEntalpia(1, 205, array(0, 1268.2, 1273.7, 1279.2, 1284.6, 1289.9, 1295.3, 1300.6, 1305.9, 1311.2, 1316.4, 1321.7, 1332.1, 1342.5, 1352.9, 1363.2, 1373.6, 1383.9, 1394.2, 1404.5, 1414.8, 1425.1));
        $this->llenarEntalpia(1, 210, array(0, 1267.7, 1273.2, 1278.7, 1284.1, 1289.5, 1294.8, 1300.2, 1305.5, 1310.8, 1316.1, 1321.3, 1331.8, 1342.2, 1352.6, 1362.9, 1373.3, 1383.6, 1393.9, 1404.2, 1414.6, 1424.9));
        $this->llenarEntalpia(1, 215, array(0, 1267.2, 1272.7, 1278.2, 1283.6, 1289.0, 1294.4, 1299.8, 1305.1, 1310.4, 1315.7, 1320.9, 1331.4, 1341.9, 1352.3, 1362.6, 1373.0, 1383.3, 1393.7, 1404.0, 1414.3, 1424.7));
        $this->llenarEntalpia(1, 220, array(0, 1266.6, 1272.2, 1277.7, 1283.1, 1288.6, 1294.0, 1299.3, 1304.7, 1310.0, 1315.3, 1320.6, 1331.1, 1341.5, 1352.0, 1362.4, 1372.7, 1383.1, 1393.4, 1403.8, 1414.1, 1424.5));
        $this->llenarEntalpia(1, 225, array(0, 1266.1, 1271.6, 1277.2, 1282.6, 1288.1, 1293.5, 1298.9, 1304.3, 1309.6, 1314.9, 1320.2, 1330.7, 1341.2, 1351.7, 1362.1, 1372.4, 1382.8, 1393.2, 1403.5, 1413.9, 1424.2));
        $this->llenarEntalpia(1, 230, array(0, 1265.5, 1271.1, 1276.7, 1282.2, 1287.6, 1293.1, 1298.5, 1303.8, 1309.2, 1314.5, 1319.8, 1330.4, 1340.9, 1351.3, 1361.8, 1372.2, 1382.6, 1392.9, 1403.3, 1413.7, 1424.0));
        $this->llenarEntalpia(1, 235, array(0, 0, 1265.0, 1270.6, 1276.2, 1281.7, 1287.2, 1292.6, 1298.0, 1303.4, 1308.8, 1314.1, 1319.5, 1330.0, 1340.6, 1351.0, 1361.5, 1371.9, 1382.3, 1392.7, 1403.1, 1413.4, 1423.8));
        $this->llenarEntalpia(1, 240, array(0, 1264.4, 1270.0, 1275.6, 1281.2, 1286.7, 1292.2, 1297.6, 1303.0, 1308.4, 1313.7, 1319.1, 1329.7, 1340.2, 1350.7, 1361.2, 1371.6, 1382.0, 1392.4, 1402.8, 1413.2, 1413.2));
        $this->llenarEntalpia(1, 245, array(0, 1263.8, 1269.5, 1275.1, 1280.7, 1286.2, 1291.7, 1297.2, 1302.6, 1308.0, 1313.4, 1318.7, 1329.3, 1339.9, 1350.4, 1360.9, 1371.3, 1381.8, 1392.2, 1402.6, 1413.0, 1423.4));
        $this->llenarEntalpia(1, 250, array(0, 1263.3, 1269.0, 1274.6, 1280.2, 1285.8, 1291.3, 1296.7, 1302.2, 1307.6, 1313.0, 1318.3, 1329.0, 1339.6, 1350.1, 1360.6, 1371.1, 1381.5, 1391.9, 1402.3, 1412.8, 1423.2));
        $this->llenarEntalpia(1, 255, array(0, 1262.7, 1268.4, 1274.1, 1279.7, 1285.3, 1290.8, 1296.3, 1301.8, 1307.2, 1312.6, 1317.9, 1328.6, 1339.2, 1349.8, 1360.3, 1370.8, 1381.2, 1391.7, 1402.1, 1412.5, 1422.9));
        $this->llenarEntalpia(1, 260, array(0, 1262.1, 1267.9, 1273.6, 1279.2, 1284.8, 1290.4, 1295.9, 1301.3, 1306.8, 1312.2, 1317.6, 1328.3, 1338.9, 1349.5, 1360.0, 1370.5, 1381.0, 1391.4, 1401.9, 1412.3, 1422.7));
        $this->llenarEntalpia(1, 265, array(0, 1261.6, 1267.4, 1273.1, 1278.7, 1284.3, 1289.9, 1295.4, 1300.9, 1306.4, 1311.8, 1317.2, 1327.9, 1338.6, 1349.2, 1359.7, 1370.2, 1380.7, 1391.2, 1401.6, 1412.1, 1422.5));
        $this->llenarEntalpia(1, 270, array(0, 1261.0, 1266.8, 1272.5, 1278.2, 1283.9, 1289.4, 1295.0, 1300.5, 1306.0, 1311.4, 1316.8, 1327.6, 1338.2, 1348.9, 1359.4, 1369.9, 1380.4, 1390.9, 1401.4, 1411.8, 1422.3));
        $this->llenarEntalpia(1, 275, array(0, 1260.4, 1266.3, 1272.0, 1277.7, 1283.4, 1289.0, 1294.5, 1300.1, 1305.5, 1311.0, 1316.4, 1327.2, 1337.9, 1348.5, 1359.1, 1369.7, 1380.2, 1390.7, 1401.2, 1411.6, 1422.1));
        $this->llenarEntalpia(1, 280, array(0, 1259.9, 1265.7, 1271.5, 1277.2, 1282.9, 1288.5, 1294.1, 1299.6, 1305.1, 1310.6, 1316.0, 1326.8, 1337.6, 1348.2, 1358.8, 1369.4, 1379.9, 1390.4, 1400.9, 1411.4, 1421.9));
        $this->llenarEntalpia(1, 285, array(0, 1259.3, 1265.2, 1271.0, 1276.7, 1282.4, 1288.1, 1293.6, 1299.2, 1304.7, 1310.2, 1315.7, 1326.5, 1337.2, 1347.9, 1358.5, 1369.1, 1379.7, 1390.2, 1400.7, 1411.2, 1421.6));
        $this->llenarEntalpia(1, 290, array(0, 1258.7, 1264.6, 1270.4, 1276.2, 1281.9, 1287.6, 1293.2, 1298.8, 1304.3, 1309.8, 1315.3, 1326.1, 1336.9, 1347.6, 1358.2, 1368.8, 1379.4, 1389.9, 1400.4, 1410.9, 1421.4));
        $this->llenarEntalpia(1, 295, array(0, 1258.1, 1264.0, 1269.9, 1275.7, 1281.4, 1287.1, 1292.8, 1298.3, 1303.9, 1309.4, 1314.9, 1325.8, 1336.6, 1347.3, 1357.9, 1368.5, 1379.1, 1389.7, 1400.2, 1410.7, 1421.2));
        $this->llenarEntalpia(1, 300, array(0, 1257.5, 1263.5, 1269.4, 1275.2, 1281.0, 1286.7, 1292.3, 1297.9, 1303.5, 1309.0, 1314.5, 1325.4, 1336.2, 1347.0, 1357.6, 1368.3, 1378.9, 1389.4, 1400.0, 1410.5, 1421.0));
        $this->llenarEntalpia(1, 305, array(0, 1256.9, 1262.9, 1268.8, 1274.7, 1280.5, 1286.2, 1291.9, 1297.5, 1303.1, 1308.6, 1314.1, 1325.1, 1335.9, 1346.6, 1357.3, 1368.0, 1378.6, 1389.2, 1399.7, 1410.2, 1420.8));
        $this->llenarEntalpia(1, 310, array(0, 1256.3, 1262.4, 1268.3, 1274.2, 1280.0, 1285.7, 1291.4, 1297.0, 1302.6, 1308.2, 1313.7, 1324.7, 1335.5, 1346.3, 1357.0, 1367.7, 1378.3, 1388.9, 1399.5, 1410.0, 1420.6));
        $this->llenarEntalpia(1, 315, array(0, 1255.7, 1261.8, 1267.8, 1273.7, 1279.5, 1285.2, 1290.9, 1296.6, 1302.2, 1307.8, 1313.3, 1324.3, 1335.2, 1346.0, 1356.7, 1367.4, 1378.0, 1388.7, 1399.2, 1409.8, 1420.3));
        $this->llenarEntalpia(1, 320, array(0, 1255.1, 1261.2, 1267.2, 1273.1, 1279.0, 1284.8, 1290.5, 1296.2, 1301.8, 1307.4, 1313.0, 1324.0, 1334.9, 1345.7, 1356.4, 1367.1, 1377.8, 1388.4, 1399.0, 1409.6, 1420.1));
        $this->llenarEntalpia(1, 325, array(0, 1254.5, 1260.7, 1266.7, 1272.6, 1278.5, 1284.3, 1290.0, 1295.7, 1301.4, 1307.0, 1312.6, 1323.6, 1334.5, 1345.4, 1356.1, 1366.8, 1377.5, 1388.1, 1398.7, 1409.3, 1419.9));
        $this->llenarEntalpia(1, 330, array(0, 1253.9, 1260.1, 1266.1, 1272.1, 1278.0, 1283.8, 1289.6, 1295.3, 1301.0, 1306.6, 1312.2, 1323.2, 1334.2, 1345.0, 1355.8, 1366.6, 1377.2, 1387.9, 1398.5, 1409.1, 1419.7));
        $this->llenarEntalpia(1, 335, array(0, 1253.3, 1259.5, 1265.6, 1271.6, 1277.5, 1283.3, 1289.1, 1294.9, 1300.5, 1306.2, 1311.8, 1322.9, 1333.8, 1344.7, 1355.5, 1366.3, 1377.0, 1387.6, 1398.3, 1408.9, 1419.5));
        $this->llenarEntalpia(1, 340, array(0, 1252.7, 1258.9, 1265.0, 1271.0, 1277.0, 1282.9, 1288.7, 1294.4, 1300.1, 1305.8, 1311.4, 1322.5, 1333.5, 1344.4, 1355.2, 1366.0, 1376.7, 1387.4, 1398.0, 1408.6, 1419.2));
        $this->llenarEntalpia(1, 345, array(0, 1252.1, 1258.3, 1264.5, 1270.5, 1276.5, 1282.4, 1288.2, 1294.0, 1299.7, 1305.4, 1311.0, 1322.1, 1333.2, 1344.1, 1354.9, 1365.7, 1376.4, 1387.1, 1397.8, 1408.4, 1419.0));
        $this->llenarEntalpia(1, 350, array(0, 1251.5, 1257.8, 1263.9, 1270.0, 1276.0, 1281.9, 1287.7, 1293.5, 1299.3, 1304.9, 1310.6, 1321.8, 1332.8, 1343.8, 1354.6, 1365.4, 1376.2, 1386.9, 1397.5, 1408.2, 1418.8));
        $this->llenarEntalpia(1, 355, array(0, 1250.9, 1257.2, 1263.4, 1269.5, 1275.5, 1281.4, 1287.3, 1293.1, 1298.8, 1304.5, 1310.2, 1321.4, 1332.5, 1343.4, 1354.3, 1365.1, 1375.9, 1386.6, 1397.3, 1407.9, 1418.6));
        $this->llenarEntalpia(1, 360, array(0, 1250.3, 1256.6, 1262.8, 1268.9, 1275.0, 1280.9, 1286.8, 1292.6, 1298.4, 1304.1, 1309.8, 1321.0, 1332.1, 1343.1, 1354.0, 1364.9, 1375.6, 1386.4, 1397.0, 1407.7, 1418.4));
        $this->llenarEntalpia(1, 365, array(0, 1249.6, 1256.0, 1262.2, 1268.4, 1274.4, 1280.4, 1286.3, 1292.2, 1298.0, 1303.7, 1309.4, 1320.7, 1331.8, 1342.8, 1353.7, 1364.6, 1375.4, 1386.1, 1396.8, 1407.5, 1418.1));
        $this->llenarEntalpia(1, 370, array(0, 1249.0, 1255.4, 1261.7, 1267.8, 1273.9, 1279.9, 1285.9, 1291.7, 1297.5, 1303.3, 1309.0, 1320.3, 1331.4, 1342.5, 1353.4, 1364.3, 1375.1, 1385.8, 1396.6, 1407.3, 1417.9));
        $this->llenarEntalpia(1, 375, array(0, 1248.4, 1254.8, 1261.1, 1267.3, 1273.4, 1279.4, 1285.4, 1291.3, 1297.1, 1302.9, 1308.6, 1319.9, 1331.1, 1342.1, 1353.1, 1364.0, 1374.8, 1385.6, 1396.3, 1407.0, 1417.7));
        $this->llenarEntalpia(1, 380, array(0, 1247.7, 1254.2, 1260.5, 1266.8, 1272.9, 1279.0, 1284.9, 1290.8, 1296.7, 1302.5, 1308.2, 1319.5, 1330.7, 1341.8, 1352.8, 1363.7, 1374.5, 1385.3, 1396.1, 1406.8, 1417.5));
        $this->llenarEntalpia(1, 385, array(0, 1247.1, 1253.6, 1260.0, 1266.2, 1272.4, 1278.5, 1284.5, 1290.4, 1296.2, 1302.0, 1307.8, 1319.2, 1330.4, 1341.5, 1352.5, 1363.4, 1374.3, 1385.1, 1395.8, 1406.6, 1417.3));
        $this->llenarEntalpia(1, 390, array(0, 1246.5, 1253.0, 1259.4, 1265.7, 1271.9, 1278.0, 1284.0, 1289.9, 1295.8, 1301.6, 1307.4, 1318.8, 1330.0, 1341.2, 1352.2, 1363.1, 1374.0, 1384.8, 1395.6, 1406.3, 1417.0));
        $this->llenarEntalpia(1, 395, array(0, 1245.8, 1252.4, 1258.8, 1265.1, 1271.3, 1277.5, 1283.5, 1289.5, 1295.4, 1301.2, 1307.0, 1318.4, 1329.7, 1340.8, 1351.9, 1362.8, 1373.7, 1384.6, 1395.3, 1406.1, 1416.8));
        $this->llenarEntalpia(1, 400, array(0, 1245.2, 1251.8, 1258.2, 1264.6, 1270.8, 1277.0, 1283.0, 1289.0, 1294.9, 1300.8, 1306.6, 1318.0, 1329.3, 1340.5, 1351.6, 1362.5, 1373.4, 1384.3, 1395.1, 1405.9, 1416.6));
        $this->llenarEntalpia(1, 405, array(0, 1244.5, 1251.2, 1257.6, 1264.0, 1270.3, 1276.5, 1282.5, 1288.5, 1294.5, 1300.4, 1306.2, 1317.7, 1329.0, 1340.2, 1351.3, 1362.3, 1373.2, 1384.0, 1394.8, 1405.6, 1416.4));
        $this->llenarEntalpia(1, 410, array(0, 1243.9, 1250.5, 1257.1, 1263.5, 1269.8, 1276.0, 1282.1, 1288.1, 1294.0, 1299.9, 1305.8, 1317.3, 1328.6, 1339.9, 1351.0, 1362.0, 1372.9, 1383.8, 1394.6, 1405.4, 1416.1));
        $this->llenarEntalpia(1, 415, array(0, 1243.2, 1249.9, 1256.5, 1262.9, 1262.2, 1275.4, 1281.6, 1287.6, 1293.6, 1299.5, 1305.4, 1316.9, 1328.3, 1339.5, 1350.6, 1361.7, 1372.6, 1383.5, 1394.4, 1405.2, 1415.9));
        $this->llenarEntalpia(1, 420, array(0, 1242.6, 1249.3, 1255.9, 1262.3, 1268.7, 1274.9, 1281.1, 1287.2, 1293.2, 1299.1, 1305.0, 1316.5, 1327.9, 1339.2, 1350.3, 1361.4, 1372.4, 1383.3, 1394.1, 1404.9, 1415.7));
        $this->llenarEntalpia(1, 425, array(0, 1241.9, 1248.7, 1255.3, 1261.8, 1268.2, 1274.4, 1280.6, 1286.7, 1292.7, 1298.7, 1304.5, 1316.2, 1327.6, 1338.9, 1350.0, 1361.1, 1372.1, 1383.0, 1393.9, 1404.7, 1415.5));
        $this->llenarEntalpia(1, 430, array(0, 1241.2, 1248.0, 1254.7, 1261.2, 1267.6, 1273.9, 1280.1, 1286.2, 1292.3, 1298.2, 1304.1, 1315.8, 1327.2, 1338.5, 1349.7, 1360.8, 1371.8, 1382.7, 1393.6, 1404.5, 1415.3));
        $this->llenarEntalpia(1, 435, array(0, 1240.6, 1247.4, 1254.1, 1260.7, 1267.1, 1273.4, 1279.6, 1285.8, 1291.8, 1297.8, 1303.7, 1315.4, 1326.9, 1338.2, 1349.4, 1360.5, 1371.5, 1382.5, 1393.4, 1404.2, 1415.0));
        $this->llenarEntalpia(2, 440, array(0, 1239.9, 1246.8, 1253.5, 1260.1, 1266.5, 1272.9, 1279.1, 1285.3, 1291.4, 1297.4, 1303.3, 1309.2, 1315.0, 1320.8, 1326.5, 1332.2, 1337.9, 1343.5, 1349.1, 1354.7, 1360.2, 1371.2, 1382.2, 1393.1, 1404.0, 1414.8));
        $this->llenarEntalpia(2, 445, array(0, 1239.2, 1246.1, 1252.9, 1259.5, 1266.0, 1272.4, 1278.6, 1284.8, 1290.9, 1296.9, 1302.9, 1308.8, 1314.6, 1320.4, 1326.2, 1331.9, 1337.5, 1343.2, 1348.8, 1354.4, 1359.9, 1371.0, 1382.0, 1392.9, 1403.7, 1414.6));
        $this->llenarEntalpia(2, 450, array(0, 1238.5, 1245.5, 1252.3, 1258.9, 1265.5, 1271.9, 1278.2, 1284.4, 1290.5, 1296.5, 1302.5, 1308.4, 1314.2, 1320.0, 1325.8, 1331.5, 1337.2, 1342.9, 1348.5, 1354.1, 1359.6, 1370.7, 1381.7, 1392.6, 1403.5, 1414.4));
        $this->llenarEntalpia(2, 455, array(0, 1237.8, 1244.9, 1251.7, 1258.4, 1264.9, 1271.3, 1277.7, 1283.9, 1290.0, 1296.1, 1302.1, 1308.0, 1313.9, 1319.7, 1325.4, 1331.2, 1336.9, 1342.5, 1348.2, 1353.8, 1359.3, 1370.4, 1381.4, 1392.4, 1403.3, 1414.1));
        $this->llenarEntalpia(2, 460, array(0, 1237.2, 1244.2, 1251.1, 1257.8, 1264.4, 1270.8, 1277.2, 1283.4, 1289.6, 1295.6, 1301.6, 1307.6, 1313.5, 1319.3, 1325.1, 1330.8, 1336.5, 1342.2, 1347.8, 1353.5, 1359.0, 1370.1, 1381.2, 1392.1, 1403.0, 1413.9));
        $this->llenarEntalpia(2, 465, array(0, 1236.5, 1243.6, 1250.5, 1257.2, 1263.8, 1270.3, 1276.7, 1282.9, 1289.1, 1295.2, 1301.2, 1307.2, 1313.1, 1318.9, 1324.7, 1330.5, 1336.2, 1341.9, 1347.5, 1353.2, 1358.7, 1369.9, 1380.9, 1391.9, 1402.8, 1413.7));
        $this->llenarEntalpia(2, 470, array(0, 1235.8, 1242.9, 1249.8, 1256.6, 1263.3, 1269.8, 1276.2, 1282.4, 1288.6, 1294.8, 1300.8, 1306.8, 1312.7, 1318.6, 1324.4, 1330.1, 1335.9, 1341.6, 1347.2, 1352.8, 1358.4, 1369.6, 1380.6, 1391.6, 1402.6, 1413.5));
        $this->llenarEntalpia(2, 475, array(0, 1235.1, 1242.2, 1249.2, 1256.0, 1262.7, 1269.2, 1275.7, 1282.0, 1288.2, 1294.3, 1300.4, 1306.4, 1312.3, 1318.2, 1324.0, 1329.8, 1335.5, 1341.2, 1346.9, 1352.5, 1358.2, 1369.3, 1380.4, 1391.4, 1402.3, 1413.2));
        $this->llenarEntalpia(2, 480, array(0, 1234.4, 1241.6, 1248.6, 1255.4, 1262.1, 1268.7, 1275.2, 1281.5, 1287.7, 1293.9, 1300.0, 1306.0, 1311.9, 1317.8, 1323.6, 1329.4, 1335.2, 1340.9, 1346.6, 1352.2, 1357.9, 1369.0, 1380.1, 1391.1, 1402.1, 1413.0));
        $this->llenarEntalpia(2, 485, array(0, 1233.7, 1240.9, 1248.0, 1254.9, 1261.6, 1268.2, 1274.6, 1281.0, 1287.3, 1293.4, 1299.5, 1305.6, 1311.5, 1317.4, 1323.3, 1329.1, 1334.9, 1340.6, 1346.3, 1351.9, 1357.6, 1368.7, 1379.8, 1390.9, 1401.9, 1412.8));
        $this->llenarEntalpia(2, 490, array(0, 1233.0, 1240.3, 1247.3, 1254.3, 1261.0, 1267.6, 1274.1, 1280.5, 1286.8, 1293.0, 1299.1, 1305.2, 1311.1, 1317.1, 1322.9, 1328.7, 1334.5, 1340.3, 1346.0, 1351.6, 1357.3, 1368.5, 1379.6, 1390.6, 1401.6, 1412.6));
        $this->llenarEntalpia(2, 495, array(0, 1232.2, 1239.6, 1246.7, 1253.7, 1260.5, 1267.1, 1273.6, 1280.0, 1286.3, 1292.6, 1298.7, 1304.8, 1310.7, 1316.7, 1322.6, 1328.4, 1334.2, 1339.9, 1345.6, 1351.3, 1357.0, 1368.2, 1379.3, 1390.4, 1401.4, 1412.3));
        $this->llenarEntalpia(2, 500, array(0, 1231.5, 1238.9, 1246.1, 1253.1, 1259.9, 1266.6, 1273.1, 1279.5, 1285.9, 1292.1, 1298.3, 1304.3, 1310.4, 1316.3, 1322.2, 1328.0, 1333.8, 1339.6, 1345.3, 1351.0, 1356.7, 1367.9, 1379.1, 1390.1, 1401.1, 1412.1));
        $this->llenarEntalpia(2, 510, array(0, 1230.1, 1237.5, 1244.8, 1251.9, 1258.7, 1265.5, 1272.1, 1278.6, 1284.9, 1291.2, 1297.4, 1303.5, 1309.6, 1315.5, 1321.5, 1327.3, 1333.2, 1338.9, 1344.7, 1350.4, 1356.1, 1367.3, 1378.5, 1389.6, 1400.7, 1411.7));
        $this->llenarEntalpia(2, 520, array(0, 1228.6, 1236.2, 1243.5, 1250.6, 1257.6, 1264.4, 1271.1, 1277.6, 1284.0, 1290.3, 1296.5, 1302.7, 1308.8, 1314.8, 1320.7, 1326.6, 1332.5, 1338.3, 1344.0, 1349.8, 1355.5, 1366.8, 1378.0, 1389.1, 1400.2, 1411.2));
        $this->llenarEntalpia(2, 530, array(0, 1227.1, 1234.8, 1242.2, 1249.4, 1256.4, 1263.3, 1270.0, 1276.6, 1283.1, 1289.4, 1295.7, 1301.9, 1308.0, 1314.0, 1320.0, 1325.9, 1331.8, 1337.6, 1343.4, 1349.2, 1354.9, 1366.2, 1377.5, 1388.6, 1399.7, 1410.8));
        $this->llenarEntalpia(2, 540, array(0, 1225.6, 1233.4, 1240.9, 1248.2, 1255.3, 1262.2, 1269.0, 1275.6, 1282.1, 1288.5, 1294.8, 1301.0, 1307.2, 1313.2, 1319.3, 1325.2, 1331.1, 1337.0, 1342.8, 1348.5, 1354.3, 1365.7, 1376.9, 1388.1, 1399.2, 1410.3));
        $this->llenarEntalpia(2, 550, array(0, 1224.1, 1232.0, 1239.6, 1246.9, 1254.1, 1261.1, 1267.9, 1274.6, 1281.1, 1287.6, 1293.9, 1300.2, 1306.4, 1312.5, 1318.5, 1324.5, 1330.4, 1336.3, 1342.1, 1347.9, 1353.7, 1365.1, 1376.4, 1387.6, 1398.8, 1409.8));
        $this->llenarEntalpia(2, 560, array(0, 1222.6, 1230.5, 1238.2, 1245.7, 1252.9, 1260.0, 1266.8, 1273.6, 1280.2, 1286.7, 1293.1, 1299.4, 1305.6, 1311.7, 1317.8, 1323.8, 1329.7, 1335.6, 1341.5, 1347.3, 1353.1, 1364.5, 1375.9, 1387.1, 1398.3, 1409.4));
        $this->llenarEntalpia(2, 570, array(0, 1221.0, 1229.1, 1236.9, 1244.4, 1251.7, 1258.8, 1265.8, 1272.6, 1279.2, 1285.8, 1292.2, 1298.5, 1304.8, 1310.9, 1317.0, 1323.1, 1329.0, 1335.0, 1340.8, 1346.7, 1352.5, 1363.9, 1375.3, 1386.6, 1397.8, 1408.9));
        $this->llenarEntalpia(2, 580, array(0, 1219.4, 1227.6, 1235.5, 1243.1, 1250.5, 1257.7, 1264.7, 1271.5, 1278.2, 1284.8, 1291.3, 1297.7, 1303.9, 1310.1, 1316.3, 1322.3, 1328.3, 1334.3, 1340.2, 1346.0, 1351.8, 1363.4, 1374.8, 1386.1, 1397.3, 1408.5));
        $this->llenarEntalpia(2, 590, array(0, 1217.8, 1226.1, 1234.1, 1241.8, 1249.3, 1256.5, 1263.6, 1270.5, 1277.3, 1283.9, 1290.4, 1296.8, 1303.1, 1309.4, 1315.5, 1321.6, 1327.6, 1333.6, 1339.5, 1345.4, 1351.2, 1362.8, 1374.2, 1385.6, 1396.8, 1408.0));
        $this->llenarEntalpia(2, 600, array(0, 1216.2, 1224.6, 1232.7, 1240.5, 1248.0, 1255.4, 1262.5, 1269.5, 1276.3, 1282.9, 1289.5, 1295.9, 1302.3, 1308.6, 1314.8, 1320.9, 1326.9, 1332.9, 1338.9, 1344.8, 1350.6, 1362.2, 1373.7, 1385.1, 1396.3, 1407.6));
        $this->llenarEntalpia(2, 610, array(0, 1214.6, 1223.1, 1231.3, 1239.2, 1246.8, 1254.2, 1261.4, 1268.4, 1275.3, 1282.0, 1288.6, 1295.1, 1301.5, 1307.8, 1314.0, 1320.1, 1326.2, 1332.2, 1338.2, 1344.1, 1350.0, 1361.6, 1373.2, 1384.6, 1395.9, 1407.1));
        $this->llenarEntalpia(2, 620, array(0, 1212.9, 1221.6, 1229.9, 1237.8, 1245.6, 1253.0, 1260.3, 1267.4, 1274.3, 1281.0, 1287.7, 1294.2, 1300.6, 1307.0, 1313.2, 1319.4, 1325.5, 1331.6, 1337.6, 1343.5, 1349.4, 1361.1, 1372.6, 1384.0, 1395.4, 1406.6));
        $this->llenarEntalpia(2, 630, array(0, 1211.2, 1220.0, 1228.4, 1236.5, 1244.3, 1251.8, 1259.2, 1266.3, 1273.3, 1280.1, 1286.8, 1293.3, 1299.8, 1306.2, 1312.5, 1318.7, 1324.8, 1330.9, 1336.9, 1342.9, 1348.8, 1360.5, 1372.1, 1383.5, 1394.9, 1406.2));
        $this->llenarEntalpia(2, 640, array(0, 1209.5, 1218.4, 1227.0, 1235.1, 1243.0, 1250.6, 1258.0, 1265.2, 1272.3, 1279.1, 1285.9, 1292.5, 1299.0, 1305.4, 1311.7, 1317.9, 1324.1, 1330.2, 1336.2, 1342.2, 1348.2, 1359.9, 1371.5, 1383.0, 1394.4, 1405.7));
        $this->llenarEntalpia(2, 650, array(0, 1207.8, 1216.9, 1225.5, 1233.8, 1241.7, 1249.4, 1256.9, 1264.2, 1271.2, 1278.2, 1284.9, 1291.6, 1298.1, 1304.6, 1310.9, 1317.2, 1323.4, 1329.5, 1335.6, 1341.6, 1347.5, 1359.3, 1371.0, 1382.5, 1393.9, 1405.3));
        $this->llenarEntalpia(2, 660, array(0, 1206.0, 1215.2, 1224.0, 1232.4, 1240.4, 1248.2, 1255.7, 1263.1, 1270.2, 1277.2, 1284.0, 1290.7, 1297.3, 1303.8, 1310.1, 1316.4, 1322.7, 1328.8, 1334.9, 1340.9, 1346.9, 1358.7, 1370.4, 1382.0, 1393.4, 1404.8));
        $this->llenarEntalpia(2, 670, array(0, 1204.3, 1213.6, 1222.5, 1231.0, 1239.1, 1247.0, 1254.6, 1262.0, 1269.2, 1276.2, 1283.1, 1289.8, 1296.4, 1302.9, 1309.4, 1315.7, 1321.9, 1328.1, 1334.2, 1340.3, 1346.3, 1358.2, 1369.9, 1381.5, 1392.9, 1404.3));
        $this->llenarEntalpia(2, 680, array(0, 1202.5, 1211.9, 1221.0, 1229.5, 1237.8, 1245.7, 1253.4, 1260.9, 1268.1, 1275.2, 1282.1, 1288.9, 1295.6, 1302.1, 1308.6, 1314.9, 1321.2, 1327.4, 1333.6, 1339.6, 1345.7, 1357.6, 1369.3, 1380.9, 1392.4, 1403.9));
        $this->llenarEntalpia(2, 690, array(0, 1200.6, 1210.3, 1219.4, 1228.1, 1236.5, 1244.5, 1252.2, 1259.8, 1267.1, 1274.2, 1281.2, 1288.0, 1294.7, 1301.3, 1307.8, 1314.2, 1320.5, 1326.7, 1332.9, 1339.0, 1345.0, 1357.0, 1368.8, 1380.4, 1392.0, 1403.4));
        $this->llenarEntalpia(2, 700, array(0, 1198.8, 1208.6, 1217.8, 1226.7, 1235.1, 1243.2, 1251.1, 1258.7, 1266.0, 1273.2, 1280.2, 1287.1, 1293.9, 1300.5, 1307.0, 1313.4, 1319.8, 1326.0, 1332.2, 1338.3, 1344.4, 1356.4, 1368.2, 1379.9, 1391.5, 1402.9));
        $this->llenarEntalpia(2, 710, array(0, 1196.9, 1206.8, 1216.3, 1225.2, 1233.7, 1241.9, 1249.9, 1257.5, 1265.0, 1272.2, 1279.3, 1286.2, 1293.0, 1299.6, 1306.2, 1312.7, 1319.0, 1325.3, 1331.5, 1337.7, 1343.8, 1355.8, 1367.7, 1379.4, 1391.0, 1402.5));
        $this->llenarEntalpia(2, 720, array(0, 1194.9, 1205.1, 1214.6, 1223.7, 1232.4, 1240.7, 1248.7, 1256.4, 1263.9, 1271.2, 1278.3, 1285.3, 1292.1, 1298.8, 1305.4, 1311.9, 1318.3, 1324.6, 1330.8, 1337.0, 1343.1, 1355.2, 1367.1, 1378.8, 1390.5, 1402.0));
        $this->llenarEntalpia(2, 730, array(0, 1193.0, 1203.3, 1213.0, 1222.2, 1231.0, 1239.4, 1247.4, 1255.3, 1262.8, 1270.2, 1277.4, 1284.4, 1291.2, 1298.0, 1304.6, 1311.1, 1317.5, 1323.9, 1330.2, 1336.4, 1342.5, 1354.6, 1366.5, 1378.3, 1390.0, 1401.5));
        $this->llenarEntalpia(2, 740, array(0, 1191.0, 1201.5, 1211.4, 1220.7, 1229.6, 1238.1, 1246.2, 1254.1, 1261.7, 1269.2, 1276.4, 1283.4, 1290.4, 1297.1, 1303.8, 1310.3, 1316.8, 1323.2, 1329.5, 1335.7, 1341.9, 1354.0, 1366.0, 1377.8, 1389.5, 1401.0));
        $this->llenarEntalpia(2, 750, array(0, 1189.0, 1199.7, 1209.7, 1219.2, 1228.1, 1236.7, 1245.0, 1252.9, 1260.6, 1268.1, 1275.4, 1282.5, 1289.5, 1296.3, 1303.0, 1309.6, 1316.1, 1322.5, 1328.8, 1335.0, 1341.2, 1353.4, 1365.4, 1377.3, 1389.0, 1400.6));
        $this->llenarEntalpia(3, 775, array(0, 1195.0, 1205.4, 1215.2, 1224.5, 1233.4, 1241.8, 1250.0, 1257.9, 1265.5, 1272.9, 1280.2, 1287.2, 1294.2, 1300.9, 1307.6, 1314.2, 1320.7, 1327.1, 1333.4, 1339.6, 1351.9, 1364.0, 1375.9, 1387.8, 1399.4));
        $this->llenarEntalpia(3, 800, array(0, 1190.1, 1201.0, 1211.2, 1220.8, 1229.9, 1238.6, 1247.0, 1255.1, 1262.9, 1270.4, 1277.8, 1285.0, 1292.0, 1298.9, 1305.6, 1312.3, 1318.8, 1325.3, 1331.7, 1338.0, 1350.4, 1362.6, 1374.6, 1386.5, 1398.2));
    }

    public function llenarEntalpia($rangoTemperatura,$presion,$entalpia){
		$this->tpEntalpia = array("Temperatura" => array(),"Entalpia" => array(), "Presion" => 0);	

        $this->tpEntalpia["Temperatura"] = $this->LlenarTemperaturaAguaVapor($rangoTemperatura, $this->tpEntalpia["Temperatura"]);          
        $this->tpEntalpia["Entalpia"] = $entalpia;
        $this->tpEntalpia["Presion"] = $presion;

        $this->TablaEntalpia[] = $this->tpEntalpia;
    }

    public function LlenarTemperaturaAguaVapor($rangoTemperatura,$Temperatura){
        $k = 1;
		$Temperatura[] = 0;
		
        switch ($rangoTemperatura){
            case 0:
                for($i=500;$i<=800;$i=$i+20){
                    $Temperatura[] = $i;
                }
                break;
            case 1:
                for ($i=500;$i<=590;$i=$i+10){
                    $Temperatura[] = $i;
                }
                for ($i=600;$i<=800;$i=$i+20){
                    $Temperatura[] = $i;
                }
                break;
            case 2:
                for ($i=500;$i<=690;$i=$i+10){
                    $Temperatura[] = $i;
                }
                for ($i=700;$i<=800;$i=$i+20){
                    $Temperatura[] = $i;
                }
                break;
            case 3:
                for ($i=510;$i<=690;$i=$i+10){
                    $Temperatura[] = $i;
                }
                for ($i=700;$i<=800;$i=$i+20){
                    $Temperatura[] = $i;
                }
                break;
        }
        return $Temperatura;

    }


}
//FIN Entalpia


class Flujo{
    public function CalcularMasicoAireSeco($FlujoMasicoAireHumedo,$Humedad){
        $flujoMasicoAireSeco = $FlujoMasicoAireHumedo * (1 - $Humedad);

        return $flujoMasicoAireSeco;
    }

    public function CalcularMasicoComponente($FlujoMolarComponente,$PesoMolecular){
        $flujoMasicoComponente = $FlujoMolarComponente * $PesoMolecular;

        return $flujoMasicoComponente;
    }

    public function CalcularMolarComponente($FlujoMolarMezcla,$FraccionMolar){
        $flujoMolarComponente = $FlujoMolarMezcla * $FraccionMolar;

        return $flujoMolarComponente;
    }

}
//FIN Flujo


class PesoMolecular{
    public function CalcularMezcla($PesoMolecularComponente, $FraccionMolar){
        $pesoMolecularMezcla = 0;
        $n = count($PesoMolecularComponente);

        for ($i=1;$i<=$n;$i++){
            $pesoMolecularMezcla = $pesoMolecularMezcla + $PesoMolecularComponente[$i-1]["pesoMolecularCombustion"] * $FraccionMolar[$i];
        }

        return $pesoMolecularMezcla;

    }

}
//FIN PesoMolecular


class CapacidadCalorifica{
    public function CalcularGas($Temperatura,$Coeficiente1,$Coeficiente2,$Coeficiente3,$Coeficiente4,$Coeficiente5){
        $capacidadCalorifica = $Coeficiente1["coeficiente1"] + ($Coeficiente2["coeficiente2"] * pow(($Coeficiente3["coeficiente3"] / ($Temperatura * sinh($Coeficiente3["coeficiente3"] / $Temperatura))),2)) + ($Coeficiente4["coeficiente4"] * pow(($Coeficiente5["coeficiente5"] / ($Temperatura * cosh($Coeficiente5["coeficiente5"] / $Temperatura))),2));

        return $capacidadCalorifica;
    }

    public function CalcularLiquido($Temperatura,$API){
        $gravedadEspecifica = (141.5) / ($API + 131);

        $capacidadCalorifica = (0.388 + (0.00045 * $Temperatura)) / sqrt($gravedadEspecifica);

        return $capacidadCalorifica;
    }

}
//FIN CapacidadCalorifica


class Presion{
    public $temperaturaAguaSaturacion;
    public $presionAguaSaturacion;
    public $interpolacion;  //matematica.php
     
    public $n = 333;

    public function CalcularAguaSaturacion($Temperatura){
        $presion = 0;
        
        $presion = $this->interpolacion->Spline($this->temperaturaAguaSaturacion_p, $this->presionAguaSaturacion, $Temperatura);

        return $presion;
    }

    public function Presion(){
        $this->interpolacion = new clsInterpolacion();  //matematica.php
        
        //Carga de la tabla de sarutacion del Agua (temperatura y presion)

        // Temperatura agua saturacion
        $this->temperaturaAguaSaturacion_p = $this->LlenarTemperaturaAguaSaturacion();
        // Entalpia del liquido agua saturacion
        $this->presionAguaSaturacion = $this->LlenarPresionAguaSaturacion();
    }

    // Llena los valores de las temperaturas de la tabla de saturacion del Agua
    private function LlenarTemperaturaAguaSaturacion(){
		$n = 333;
		
        $temperatura[1] = 32.018;

        for ($i=2;$i<=169;$i++){
            $temperatura[$i] = $i + 31;
        }

        for ($i=170;$i<=269;$i++){
            $temperatura[$i] = 200 + 2 * ($i - 169);
        }

        for ($i=270;$i<=329;$i++){
            $temperatura[$i] = 400 + 5 * ($i - 269);
        }

        for ($i=330;$i<=331;$i++){
            $temperatura[$i] = 700 + 2 * ($i - 329);
        }

        $temperatura[$n - 1] = 705;
        $temperatura[$n] = 705.44;

        return $temperatura;
    }

    // Llena los valores de las temperaturas de la tabla de saturación del Agua
    private function LlenarPresionAguaSaturacion(){
        $presion[1] = 0.08866 ; $presion[2] = 0.09223 ; $presion[3] = 0.09601;
        $presion[4] = 0.09992 ; $presion[5] = 0.10397 ; $presion[6] = 0.10816 ; $presion[7] = 0.1125 ; $presion[8] = 0.117;
        $presion[9] = 0.12166 ; $presion[10] = 0.12648 ; $presion[11] = 0.13146 ; $presion[12] = 0.13662 ; $presion[13] = 0.14196;
        $presion[14] = 0.14748 ; $presion[15] = 0.15319 ; $presion[16] = 0.15909 ; $presion[17] = 0.1652 ; $presion[18] = 0.17151;
        $presion[19] = 0.17803 ; $presion[20] = 0.18477 ; $presion[21] = 0.19173 ; $presion[22] = 0.19892 ; $presion[23] = 0.20635;
        $presion[24] = 0.214 ; $presion[25] = 0.2219 ; $presion[26] = 0.2301 ; $presion[27] = 0.2386 ; $presion[28] = 0.2473;
        $presion[29] = 0.2563 ; $presion[30] = 0.2655 ; $presion[31] = 0.2751 ; $presion[32] = 0.285 ; $presion[33] = 0.2952;
        $presion[34] = 0.3057 ; $presion[35] = 0.3165 ; $presion[36] = 0.3391 ; $presion[37] = 0.3391 ; $presion[38] = 0.351;
        $presion[39] = 0.3632 ; $presion[40] = 0.3758 ; $presion[41] = 0.3887 ; $presion[42] = 0.4021 ; $presion[43] = 0.4158;
        $presion[44] = 0.43 ; $presion[45] = 0.4446 ; $presion[46] = 0.4596 ; $presion[47] = 0.475 ; $presion[48] = 0.4909;
        $presion[49] = 0.5073 ; $presion[50] = 0.5241 ; $presion[51] = 0.5414 ; $presion[52] = 0.5593 ; $presion[53] = 0.5776;
        $presion[54] = 0.5964 ; $presion[55] = 0.6158 ; $presion[56] = 0.6357 ; $presion[57] = 0.6562 ; $presion[58] = 0.6772;
        $presion[59] = 0.6988 ; $presion[60] = 0.7211 ; $presion[61] = 0.7439 ; $presion[62] = 0.7674 ; $presion[63] = 0.7914;
        $presion[64] = 0.8162 ; $presion[65] = 0.8416 ; $presion[66] = 0.8677 ; $presion[67] = 0.8945 ; $presion[68] = 0.922;
        $presion[69] = 0.9503 ; $presion[70] = 0.9792 ; $presion[71] = 1.009 ; $presion[72] = 1.0395 ; $presion[73] = 1.0708;
        $presion[74] = 1.1029 ; $presion[75] = 1.1359 ; $presion[76] = 1.1697 ; $presion[77] = 1.2044 ; $presion[78] = 1.2399;
        $presion[79] = 1.2763 ; $presion[80] = 1.3137 ; $presion[81] = 1.352 ; $presion[82] = 1.3913 ; $presion[83] = 1.4315;
        $presion[84] = 1.4727 ; $presion[85] = 1.515 ; $presion[86] = 1.5583 ; $presion[87] = 1.6026 ; $presion[88] = 1.648;
        $presion[89] = 1.6945 ; $presion[90] = 1.7422 ; $presion[91] = 1.791 ; $presion[92] = 1.8409 ; $presion[93] = 1.8921;
        $presion[94] = 1.9444 ; $presion[95] = 1.998 ; $presion[96] = 2.0529 ; $presion[97] = 2.109 ; $presion[98] = 2.1664;
        $presion[99] = 2.225 ; $presion[100] = 2.285 ; $presion[101] = 2.347 ; $presion[102] = 2.41 ; $presion[103] = 2.474;
        $presion[104] = 2.54 ; $presion[105] = 2.607 ; $presion[106] = 2.676 ; $presion[107] = 2.746 ; $presion[108] = 2.818;
        $presion[109] = 2.892 ; $presion[110] = 2.967 ; $presion[111] = 3.044 ; $presion[112] = 3.122 ; $presion[113] = 3.203;
        $presion[114] = 3.285 ; $presion[115] = 3.368 ; $presion[116] = 3.454 ; $presion[117] = 3.541 ; $presion[118] = 3.63;
        $presion[119] = 3.722 ; $presion[120] = 3.815 ; $presion[121] = 3.91 ; $presion[122] = 4.007 ; $presion[123] = 4.106;
        $presion[124] = 4.207 ; $presion[125] = 4.31 ; $presion[126] = 4.416 ; $presion[127] = 4.523 ; $presion[128] = 4.633;
        $presion[129] = 4.745 ; $presion[130] = 4.859 ; $presion[131] = 4.976 ; $presion[132] = 5.095 ; $presion[133] = 5.216;
        $presion[134] = 5.34 ; $presion[135] = 5.466 ; $presion[136] = 5.595 ; $presion[137] = 5.726 ; $presion[138] = 5.86;
        $presion[139] = 5.996 ; $presion[140] = 6.136 ; $presion[141] = 6.277 ; $presion[142] = 6.422 ; $presion[143] = 6.569;
        $presion[144] = 6.72 ; $presion[145] = 6.873 ; $presion[146] = 7.029 ; $presion[147] = 7.188 ; $presion[148] = 7.35;
        $presion[149] = 7.515 ; $presion[150] = 7.683 ; $presion[151] = 7.854 ; $presion[152] = 8.029 ; $presion[153] = 8.206;
        $presion[154] = 8.387 ; $presion[155] = 8.572 ; $presion[156] = 8.759 ; $presion[157] = 8.951 ; $presion[158] = 9.145;
        $presion[159] = 9.343 ; $presion[160] = 9.545 ; $presion[161] = 9.75 ; $presion[162] = 9.959 ; $presion[163] = 10.172;
        $presion[164] = 10.388 ; $presion[165] = 10.609 ; $presion[166] = 10.833 ; $presion[167] = 11.061 ; $presion[168] = 11.293;

        $presion[169] = 11.529 ; $presion[170] = 12.014 ; $presion[171] = 12.515 ; $presion[172] = 13.034 ; $presion[173] = 13.57;
        $presion[174] = 14.125 ; $presion[175] = 14.698 ; $presion[176] = 15.291 ; $presion[177] = 15.903 ; $presion[178] = 16.535;
        $presion[179] = 17.188 ; $presion[180] = 17.861 ; $presion[181] = 18.557 ; $presion[182] = 19.275 ; $presion[183] = 20.015;
        $presion[184] = 20.78 ; $presion[185] = 21.57 ; $presion[186] = 22.38 ; $presion[187] = 23.22 ; $presion[188] = 24.08;
        $presion[189] = 24.97 ; $presion[190] = 25.88 ; $presion[191] = 26.82 ; $presion[192] = 27.79 ; $presion[193] = 28.79;
        $presion[194] = 29.82 ; $presion[195] = 30.88 ; $presion[196] = 31.97 ; $presion[197] = 33.09 ; $presion[198] = 34.24;
        $presion[199] = 35.42 ; $presion[200] = 36.64 ; $presion[201] = 37.89 ; $presion[202] = 39.17 ; $presion[203] = 40.49;
        $presion[204] = 41.85 ; $presion[205] = 43.24 ; $presion[206] = 44.67 ; $presion[207] = 46.13 ; $presion[208] = 47.64;
        $presion[209] = 49.18 ; $presion[210] = 50.77 ; $presion[211] = 52.4 ; $presion[212] = 54.07 ; $presion[213] = 55.78;
        $presion[214] = 57.53 ; $presion[215] = 59.33 ; $presion[216] = 61.17 ; $presion[217] = 63.06 ; $presion[218] = 65.0;
        $presion[219] = 66.98 ; $presion[220] = 69.01 ; $presion[221] = 71.09 ; $presion[222] = 73.22 ; $presion[223] = 75.4;
        $presion[224] = 77.64 ; $presion[225] = 79.92 ; $presion[226] = 82.26 ; $presion[227] = 84.65 ; $presion[228] = 87.1;
        $presion[229] = 89.6 ; $presion[230] = 92.16 ; $presion[231] = 94.78 ; $presion[232] = 97.46 ; $presion[233] = 100.2;
        $presion[234] = 103.0 ; $presion[235] = 105.86 ; $presion[236] = 111.76 ; $presion[237] = 1189.9 ; $presion[238] = 114.82;
        $presion[239] = 117.93 ; $presion[240] = 121.11 ; $presion[241] = 124.36 ; $presion[242] = 127.68 ; $presion[243] = 131.07;
        $presion[244] = 134.53 ; $presion[245] = 138.06 ; $presion[246] = 141.66 ; $presion[247] = 145.34 ; $presion[248] = 149.09;
        $presion[249] = 152.92 ; $presion[250] = 156.82 ; $presion[251] = 160.8 ; $presion[252] = 164.87 ; $presion[253] = 169.01;
        $presion[254] = 173.23 ; $presion[255] = 177.53 ; $presion[256] = 181.92 ; $presion[257] = 186.39 ; $presion[258] = 190.95;
        $presion[259] = 195.6 ; $presion[260] = 200.33 ; $presion[261] = 205.15 ; $presion[262] = 210.06 ; $presion[263] = 215.06;
        $presion[264] = 220.2 ; $presion[265] = 225.3 ; $presion[266] = 230.6 ; $presion[267] = 236.0 ; $presion[268] = 241.5;

        $presion[269] = 247.1 ; $presion[270] = 261.4 ; $presion[271] = 276.5 ; $presion[272] = 292.1 ; $presion[273] = 308.5;
        $presion[274] = 325.6 ; $presion[275] = 343.3 ; $presion[276] = 361.9 ; $presion[277] = 381.2 ; $presion[278] = 401.2;
        $presion[279] = 422.1 ; $presion[280] = 443.8 ; $presion[281] = 466.3 ; $presion[282] = 489.8 ; $presion[283] = 514.1;
        $presion[284] = 539.3 ; $presion[285] = 565.5 ; $presion[286] = 592.6 ; $presion[287] = 620.7 ; $presion[288] = 649.8;
        $presion[289] = 680.0 ; $presion[290] = 711.2 ; $presion[291] = 743.5 ; $presion[292] = 776.9 ; $presion[293] = 811.4;
        $presion[294] = 847.1 ; $presion[295] = 884.0 ; $presion[296] = 922.1 ; $presion[297] = 961.5 ; $presion[298] = 1002.1;
        $presion[299] = 1044.0 ; $presion[300] = 1087.2 ; $presion[301] = 1131.8 ; $presion[302] = 1177.8 ; $presion[303] = 1225.1;
        $presion[304] = 1274.0 ; $presion[305] = 1324.3 ; $presion[306] = 1376.1 ; $presion[307] = 1429.5 ; $presion[308] = 1484.5;
        $presion[309] = 1541.0 ; $presion[310] = 1599.3 ; $presion[311] = 1659.2 ; $presion[312] = 1720.9 ; $presion[313] = 1784.4;
        $presion[314] = 1849.7 ; $presion[315] = 1916.9 ; $presion[316] = 1986.0 ; $presion[317] = 2057.1 ; $presion[318] = 2130.2;
        $presion[319] = 2205.0 ; $presion[320] = 2283.0 ; $presion[321] = 2362.0 ; $presion[322] = 2444.0 ; $presion[323] = 2529.0;
        $presion[324] = 2616.0 ; $presion[325] = 2705.0 ; $presion[326] = 2797.0 ; $presion[327] = 2892.0 ; $presion[328] = 2990.0;

        $presion[329] = 3090.0 ; $presion[330] = 3131.0 ; $presion[331] = 3173.0 ; $presion[332] = 3194.0;

        $presion[333] = 3204.0;

        return $presion;
    }
}
//FIN presion


class Humedad{
	public function CalcularAire($Temperatura,$HumedadRelativa){
	    $presionAtmosferica = 14.696; //[psi]
	    $pmH2O = 18;
	    $pmAire = 28.85;
	    $presion = new Presion();
	     
	    $presionAguaVapor = $presion->CalcularAguaSaturacion($Temperatura);
	
	    $humedadAire = ($presionAguaVapor / $presionAtmosferica) * ($HumedadRelativa / 100) * ($pmH2O / $pmAire);
	
	    return $humedadAire;
	}
}
?>