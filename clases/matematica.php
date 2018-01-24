<?php
class clsInterpolacion{
	public function Spline($x,$y,$xx){
        $n = count($x)-1;
        $i = 0;  //integer
		$k = 0;   //integer
		$yy= 0;

        // Validacion de los vectores de entrada
        $validacionVectores = $this->ValidarVectores($x, $y);

		$ordenamiento = new clsOrdenamiento();

        if ($validacionVectores == true){
            // Ordenacion de los vectores de entrada
            $ordenamiento->InsercionDirecta($x, $y);

            // Calculo de la segunda derivada
            $yp1 = 1.0E30;
            $ypn = 1.0E30;
			
            // Si yp1 e ypn son muy grandes se realiza la Spline natural
            if ($yp1 > 9.9E29){
                $y2[1] = 0;
                $u[1] = 0;
            }else{
                $y2[1] = -0.5;
                $u[1] = (3 / ($x[2] - $x[1])) * (($y[2] - $y[1]) / ($x[2] - $x[1]) - $yp1);
            }
            
            for ($i=2;$i<$n;$i++){
                $sig = ($x[$i] - $x[$i - 1]) / ($x[$i + 1] - $x[$i - 1]);
                $p = $sig * $y2[$i - 1] + 2;
                $y2[$i] = ($sig - 1) / $p;
                $u[$i] = (6 * (($y[$i + 1] - $y[$i]) / ($x[$i + 1] - $x[$i]) - ($y[$i] - $y[$i - 1]) / ($x[$i] - $x[$i - 1])) / ($x[$i + 1] - $x[$i - 1]) - $sig * $u[$i - 1]) / $p;
			}

            if ($ypn > 9.9E+29){
                $qn = 0;
                $un = 0;
            }else{
                $qn = 0.5;
                $un = (3 / ($x[$n] - $x[$n - 1])) * ($ypn - ($y[$n] - $y[$n - 1]) / ($x[$n] - $x[$n - 1]));
            }
            
            $y2[$n] = ($un - $qn * $u[$n - 1]) / ($qn * $y2[$n - 1] + 1);

            for ($k=$n-1;$k>=1;$k--){                       
                $y2[$k] = $y2[$k] * $y2[$k + 1] + $u[$k];
            }

            $klo = 1;
            $khi = $n;

            while (($khi - $klo) > 1){
                $k = intval(($khi + $klo) / 2);
                if ($x[$k] > $xx){
                    $khi = $k;
                }else{
                    $klo = $k;
                }
            }

            $h = $x[$khi] - $x[$klo];

            if ($h == 0){
                $yy= 0;
            }else{
                $a = ($x[$khi] - $xx) / $h;
                $b = ($xx - $x[$klo]) / $h;
                $yy = $a * $y[$klo] + $b * $y[$khi] + ((pow($a,3) - $a) * $y2[$klo] + (pow($b,3) - $b) * $y2[$khi]) * pow($h,2) / 6;
            }

        }

        return $yy;
    }

	function ValidarVectores($x ,&$y){ //As Boolean
        $nx = count($x)-1;
        $ny = count($y)-1;
        $validado = true;

        if ($nx != $ny){
            $validado = false;
		}elseif ($nx == 1){
            $validado = false;
		}
        
        return $validado;
    }
}

class clsOrdenamiento{
    public function InsercionDirecta(&$x,&$y){
        $n = count($x)-1;

        for ($j = 2; $j<=$n; $j++){
            $control = true;
            $xx = $x[$j];
            $yy = $y[$j];
            for ($i=$j-1;$i>=1;$i--){
                if ($x[$i] <= $xx){
                    $control = false;
                    break;
				}else{
                    $x[$i + 1] = $x[$i];
                    $y[$i + 1] = $y[$i];
                }
            }
            if ($control==true){
                $i = 0;
            }
            $x[$i + 1] = $xx;
            $y[$i + 1] = $yy;
        }
    }
}
?>