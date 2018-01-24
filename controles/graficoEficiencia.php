<?php
    include "../../jpgraph-2.1.1/src/jpgraph.php";
    include "../../jpgraph-2.1.1/src/jpgraph_line.php";
    include_once "../clases/presentacion.php";

    $pres = new presentacion();
    $len = strlen($_GET["datos"]);
    $targets = array();
    $alts = array();
    $archivo = "grafico" . time() .  ".jpg";
    $ancho = 585;
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

            $dataxNombres[] = $pres->obtenerFechaGrafico($datos[$i][0]);
        }

        if($c==1){
            if(isset($_GET["efi"]))
                $eficiencia[] = $_GET["efi"];

            $datax[] = $datos[0][0];
            $datay[] = $datos[0][1];
        }

        
        // Setup the graph.
        $graph = new Graph($ancho,$alto,"auto");
        $graph->img->SetMargin(45, 0, 16, 35);
        $graph->img->SetAntiAliasing();
        $graph->SetScale("textlin");
        $graph->SetShadow();
        //$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,7);
        //$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
        $graph->yscale->SetGrace(5);
        //$graph->xaxis->SetLabelAngle(30);
        $graph->xaxis->SetTickLabels($dataxNombres);
        $graph->SetMarginColor('white');
        $graph->SetFrame(false);
        $graph->yaxis->SetLabelFormatCallback("formatoNumero");
        $graph->xaxis->SetColor('darkgray');
        $graph->yaxis->SetColor('darkgray');

        //línea de datos
        $linea1= new LinePlot($datay);
        $linea1->mark->SetFillColor("blue");
        $linea1->mark->SetType(MARK_FILLEDCIRCLE);
        $linea1->SetFillColor("#ffbe00");
        $linea1->mark->SetWidth(1);
        $linea1->SetColor("gray");
        //$linea1->value->SetFont(FS_VERDANA,FS_NORMAL,7);
        $linea1->value->SetColor("darkgray");
        //$linea1->value->SetAngle(50);
        
        if(!isset($_GET["valorlinea"]) || $_GET["valorlinea"]=="si"){
            $linea1->value->SetFormatCallback("mostrarValor");
            $linea1->value->Show();
        }

        $linea1->SetCenter();
        //$linea1->SetCSIMTargets($targets, $alts);

        $graph->Add($linea1);
        
        //línea de eficiencia de diseño
        if(isset($eficiencia)){
            $linea2= new LinePlot($eficiencia);
            $linea2->SetColor("#cc0000");
            $graph->Add($linea2);
            
            /*
            $linea3= new LinePlot(array($_GET["efi"] - 0.5, $_GET["efi"] - 0.5));
            $linea3->SetColor("white");
            $graph->Add($linea3);
            */
        }

        $graph->Stroke();
    }

    function mostrarValor($aVal){
        $pres = new presentacion();
        return sprintf($pres->formatearSeparadorDecimales($aVal));
    }

    function formatoNumero($aVal){
        $pres = new presentacion();
        return sprintf($pres->formatearDecimalesGrafico($aVal, 1));
    }
    
    //if(!isset($_GET["link"])) $_GET["link"] = "";

    //if($_GET["link"]!="no") echo $graph->GetHTMLImageMap("imapGrafico");
?>
