<html>
    <head>
    	<script language="JavaScript" type="text/javascript" src="../js/grafico.js"></script>
        <?php
            include "../../jpgraph-2.1.1/src/jpgraph.php";
            include "../../jpgraph-2.1.1/src/jpgraph_line.php";
            include "../../efiterm/clases/cladWS.php";
            include_once "../clases/presentacion.php";

            $pres = new presentacion();
            $len = strlen($_GET["datos"]);
            $targets = array();
            $alts = array();
            $archivo = "grafico" . time() .  ".jpg";
            $ancho = 685;
            $alto = 240;
            
            if(isset($_GET["ancho"])) $ancho = $_GET["ancho"];
            if(isset($_GET["alto"])) $alto = $_GET["alto"];
            
            if($_GET["datos"]==0){
                echo "";
                exit;
            }

            if(isset($_GET["datos"]) && $len > 0){
                $datos = split(";", $_GET["datos"]);
            
                foreach($datos as $llave => $valor)
                    $datos[$llave] = split(":", $valor);
                
                $c = count($datos);

                if(isset($_GET["efi"])) $_GET["efi"] = str_replace(",", ".", $_GET["efi"]);

                for($i=0; $i<$c; $i++){
                    if(isset($_GET["efi"]))
                        $eficiencia[] = $_GET["efi"];
                        
                    $datax[] = $datos[$i][0];
                    $datay[] = $datos[$i][1];
                    $targets[] = "javascript:ver('lnkPuntoGrafico$i');";
                    $alts[] = $datos[$i][0];
                }

                if($c==1){
                    if(isset($_GET["efi"]))
                        $eficiencia[] = $_GET["efi"];

                    $datax[] = $datos[0][0];
                    $datay[] = $datos[0][1];
                }
                
                $c = count($datax);

                for($i=0; $i< $c;$i++)
                    $dataxNombres[] = $pres->obtenerFechaGrafico($datax[$i]);

                // Setup the graph.
                $graph = new Graph($ancho,$alto,"auto");
                $graph->img->SetMargin(50, 20, 10, 38);
                $graph->img->SetAntiAliasing();
                $graph->SetScale("textlin");
                $graph->SetShadow('darkgray@0.8');
                $graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
                $graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
                $graph->yscale->SetGrace(5);
                $graph->xaxis->SetLabelAngle(40);
                $graph->xaxis->SetTickLabels($dataxNombres);
                $graph->SetMarginColor('white');
                $graph->SetFrame(false);
                
                //l�nea de datos
                $linea1= new LinePlot($datay);
                $linea1->mark->SetType(MARK_FILLEDCIRCLE);
                $linea1->mark->SetFillColor("red");
                $linea1->mark->SetWidth(4);
                $linea1->SetColor("darkseagreen4");
                $linea1->value->SetFormatCallback("mostrarValor");
                $linea1->value->Show();
                $linea1->SetCenter();
                $linea1->SetCSIMTargets($targets, $alts);

                //l�nea de eficiencia de dise�o
                if(isset($eficiencia)){
                    $linea2= new LinePlot($eficiencia);
                    $linea2->SetColor("dodgerblue3");
                    $graph->Add($linea2);

                    $linea3= new LinePlot(array($_GET["efi"] - 0.5, $_GET["efi"] - 0.5));
                    $linea3->SetColor("white");
                    $graph->Add($linea3);
                }

                $graph->Add($linea1);
                
                // Finally send the graph to the browser
                $graph->Stroke($archivo);
            }

            function mostrarValor($aVal){
                $pres = new presentacion();
                return sprintf($pres->formatearMoneda($aVal));
            }
            
            if(!isset($_GET["link"])) $_GET["link"] = "";

            if($_GET["link"]!="no") echo $graph->GetHTMLImageMap("imapGrafico");
        ?>
    </head>
    <body>
        <img src="<?php echo $archivo; ?>" ismap usemap="#imapGrafico" border="0">
    </body>
</html>